<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{

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
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/messages", name="admin_messages")
     */
    public function messagesAction()
    {
        return $this->render('admin/messages.html.twig');
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
