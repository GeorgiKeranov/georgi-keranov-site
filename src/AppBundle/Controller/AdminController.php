<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectView;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private function getFirstDayOfTheMonth() {
        $now = new \DateTime();
        $year = $now->format('Y');
        $month = $now->format('m');

        return new \DateTime($year . '-' . $month . '-' . '01');
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


        return $this->render('admin/dashboard.html.twig', [
            'registered_users_this_month' => $registeredUsersThisMonth,
            'project_views_this_month' => $projectsViewsThisMonth
        ]);
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
        return $this->render('admin/users.html.twig');
    }

    /**
     * @Route("/admin/comments", name="admin_comments")
     */
    public function commentsAction()
    {
        return $this->render('admin/comments.html.twig');
    }

}
