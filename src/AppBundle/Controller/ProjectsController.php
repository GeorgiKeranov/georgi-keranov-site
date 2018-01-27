<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction()
    {
        return $this->render('projects/projects.html.twig');
    }
}
