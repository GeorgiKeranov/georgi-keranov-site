<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectView;
use AppBundle\Entity\Role;
use AppBundle\Entity\SiteText;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    private function getFirstDayOfTheMonth() {
        $now = new \DateTime();
        $year = $now->format('Y');
        $month = $now->format('m');

        return new \DateTime($year . '-' . $month . '-' . '01');
    }

    private function addSiteText($name, $content) {

        $siteText = $this->getDoctrine()
            ->getRepository(SiteText::class)
            ->findOneBy(['name' => $name]);

        if($siteText == null) {
            $siteText = new SiteText();
            $siteText->setName($name);
            $siteText->setText($content);
        }
        else {
            $siteText->setText($content);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($siteText);
        $em->flush();
    }

    private function getSiteText($name) {
        $siteText = $this->getDoctrine()
            ->getRepository(SiteText::class)
            ->findOneBy(['name' => $name]);

        if($siteText != null) {
            return $siteText->getText();
        }
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
        return $this->redirectToRoute("admin_dashboard");
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     *
     * @Method({"GET"})
     */
    public function dashboardAction()
    {
        $firstDayOTheMonth = $this->getFirstDayOfTheMonth();

        // Getting the number of registered users this month.
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $registeredUsersThisMonth = $userRepo->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.dateRegistered > :date')
            ->setParameter('date', $firstDayOTheMonth)
            ->getQuery()
            ->getSingleScalarResult();

        // Getting the number of viewed projects this month.
        $projectViewRepo = $this->getDoctrine()->getRepository(ProjectView::class);
        $projectsViewsThisMonth = $projectViewRepo->createQueryBuilder('pv')
            ->select('count(pv.id)')
            ->where('pv.dateViewed > :date')
            ->setParameter('date', $firstDayOTheMonth)
            ->getQuery()
            ->getSingleScalarResult();


        $homeLargeText = $this->getSiteText('home_page_text');
        $homeDescription = $this->getSiteText('home_page_description');

        return $this->render('admin/dashboard.html.twig', [
            'registered_users_this_month' => $registeredUsersThisMonth,
            'project_views_this_month' => $projectsViewsThisMonth,
            'home_large_text' => $homeLargeText,
            'home_description' => $homeDescription
        ]);
    }

    /**
     * @Route("/admin/dashboard", name="admin_dashboard_edit")
     *
     * @Method({"POST"})
     */
    public function dashboardEditAction(Request $request)
    {
        $image = $request->files->get('portfolio_picture');
        $largeText = $request->get('home_text');
        $description = $request->get('home_description');

                if($image != null) {
                    $image->move('img/', 'georgi-keranov.jpg');
                }

                if($largeText) {
                    $this->addSiteText('home_page_text', $largeText);
                }

                if($description) {
                    $this->addSiteText('home_page_description', $description);
                }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/admin/messages", name="admin_messages")
     */
    public function messagesAction()
    {
        $allMessages = $this
            ->getDoctrine()
            ->getRepository(Message::class)
            ->findBy([], ['dateSent' => 'DESC']);

        return $this->render('admin/messages.html.twig', ['messages' => $allMessages]);
    }

    /**
     * @Route("/admin/messages/{id}", name="admin_message")
     */
    public function readMessageAction($id) {

        $message = $this->getDoctrine()
            ->getManager()->find(Message::class, $id);

        if($message == null) {
            $this->redirectToRoute('admin_messages');
        }

        return $this->render('admin/functions/message-read.html.twig', [
            'message' => $message
        ]);
    }

    /**
     * @Route("/admin/messages/{id}/delete", name="admin_message_delete")
     *
     * @Method({"POST"})
     */
    public function deleteMessageAction($id) {

        $responseParams = [ 'error' => false ];

        $em = $this->getDoctrine()->getManager();

        $message = $em->find(Message::class, $id);

        // If message is not existing.
        if(!$message) {
            $responseParams = [
                'error' => true,
                'message' => 'Message with this id is not existing'
            ];
        }

        // If message is existing.
        else {
            // Deleting message from database.
            $em->remove($message);
            $em->flush();
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/admin/projects", name="admin_projects")
     */
    public function projectsAction()
    {
        $allProjects = $this
            ->getDoctrine()
            ->getRepository(Project::class)
            ->findBy([], ['dateCreated' => 'DESC']);

        return $this->render('admin/projects.html.twig', ['projects' => $allProjects]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function usersAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/comments", name="admin_comments")
     */
    public function commentsAction()
    {
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy([], ['created' => 'DESC']);

        return $this->render('admin/comments.html.twig', ['comments' => $comments]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @Method({"POST"})
     */
    public function commentEditAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $comment = $em->find(Comment::class, $id);

        $responseParams = ['error' => false];

        if($comment) {
            $newCommentText = $request->request->get('comment');
            $comment->setComment($newCommentText);

            $em->flush();
        }

        else {
            $responseParams = [
                'error' => true,
                'message' => 'Comment doesn\'t exists.'
            ];
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @Method({"POST"})
     */
    public function commentDeleteAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $comment = $em->find(Comment::class, $id);

        $responseParams = ['error' => false];

        if($comment) {
            $em->remove($comment);
            $em->flush();
        }

        else {
            $responseParams = [
                'error' => true,
                'message' => 'Comment doesn\'t exists.'
            ];
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/admin/role/add", name="admin_add_role")
     *
     * @Method({"POST"})
     */
    public function addUserRole(Request $request)
    {

        $responseParams = [ 'error' => false ];

        $newRoleName = $request->request->get('role_name');
        // Get role by name from database.
        $role = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findOneBy(['name' => $newRoleName]);

        if($role) {
            // Get the user by id from database.
            $userId = $request->request->get('user_id');
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($userId);

            if($user) {
                // Check if user have this role already.
                if($user->checkIfUserHasRole($newRoleName)) {
                    $responseParams = [
                        'error' => true,
                        'message' => 'This user have that role already'
                    ];
                }

                // Adding this role to the user in database.
                else {
                    $user->addRole($role);
                    $this->getDoctrine()->getManager()->flush();
                }
            }

            // If user with this id is not existing
            else {
                $responseParams = [
                    'error' => true,
                    'User with this id doesn\'t exists in database'
                ];
            }
        }
        else {
            $responseParams = [
                'error' => true,
                'This role doesn\'t exists in database'
            ];
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/role/delete", name="admin_delete_role")
     *
     * @Method({"POST"})
     */
    public function deleteUserRole(Request $request)
    {

        $responseParams = [ 'error' => false ];

        $userId = $request->request->get('user_id');
        $roleName = $request->request->get('role_name');

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        if($user) {

            if($role = $user->isRoleExisting($roleName)) {
                $user->deleteRole($role);
                $this->getDoctrine()->getManager()->flush();
            }

            else {
                $responseParams = [
                    'error' => true,
                    'message' => 'This role doesn\'t exists in the user'
                ];
            }

        }
        else {
            $responseParams = [
                'error' => true,
                'message' => 'User with this id doesn\'t exists.'
            ];
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/user/delete/{id}", name="admin_delete_user")
     *
     * @Method({"POST"})
     */
    public function deleteUser($id) {

        $responseParams = ['error' => false];

        $em = $this->getDoctrine()->getManager();
        $user = $em->find(User::class, $id);

        if($user) {

            if($profilePic = $user->getProfilePicture()) {
                // Deleting profile picture file from local storage.
                $imagesPath = $this->container->getParameter('images_save_path');
                unlink($imagesPath . $profilePic);
            }

            $em->remove($user);
            $em->flush();
        }

        else {
            $responseParams = [
                'error' => true,
                'message' => 'User is not existing!'
            ];
        }

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
