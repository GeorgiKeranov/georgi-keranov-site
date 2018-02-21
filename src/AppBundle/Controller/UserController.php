<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;
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
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

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
    public function logoutAction(Request $request)
    {
    }

    /**
     * @Route("/account", name="account_settings")
     */
    public function accountSettingsAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $encoder = $this->container->get('security.password_encoder');

        if($form->isSubmitted() && $form->isValid()) {
            // Checking if confirm password is not the actual password
            if(!$encoder->isPasswordValid($user, $user->getConfirmPassword())) {
                return $this->render('user/account.html.twig', [
                    'form' => $form->createView(),
                    'user' => $user,
                    'error' => 'You have entered wrong your current password'
                ]);
            }

            // Check if deleteProfilePicture is set to true.
            if($user->isDeleteProfilePicture()) {
                // Check if user have profile picture.
                if($user->getProfilePicture()) {
                    // Deleting the profile picture from the local storage.
                    $imagesPath = $this->container->getParameter('images_save_path');
                    unlink($imagesPath . $user->getProfilePicture());
                    // Deleting name of the profile picture from database.
                    $user->setProfilePicture(null);
                }
            }

            // Check if new image file for profile picture is given.
            if(($imageFile = $user->getProfilePictureFile()) != null && $imageFile != '') {
                // Saving the new profile picture
                // Creating unique name for the image.
                $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                // Saving the image in local directory.
                $imageFile->move('uploads/images', $imageName);

                // Setting the new name of the image to the user.
                $user->setProfilePicture($imageName);
            }

            // Check if we have new password given.
            if($newPassword = $user->getNewPassword()) {
                // Encoding the new password
                $newPassword = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($newPassword);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render("user/account.html.twig", [
            'form' => $form->createView()
            ]);
    }
}

