<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder) {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Encoding plain password with bcrypt
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());

            // Setting the encoded password in our user object.
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();

            // Getting the user role by name from the database.
            $role = $em->getRepository(Role::class)->findOneBy(['name' => 'ROLE_USER']);
            // Adding ROLE_USER to the new user.
            $user->setRoles([$role]);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render("user/register.html.twig", ["form_dump" => $form, "form" => $form->createView()]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        return $this->render("user/login.html.twig");
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request) {
    }

    /**
     * @Route("/account", name="account_settings")
     */
    public function accountSettingsAction() {
        return $this->render("user/account.html.twig");
    }
}

