<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction()
    {
        return $this->render('projects/projects.html.twig');
    }

    /**
     * @Route("/admin/project/add", name="project_add")
     */
    public function addProjectAction(Request $request) {

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // Saving the main image to local storage and
            // name of it in the database.
            if($project->getImageName() != null) {
                $imageFile = $project->getImageName();
                // Creating unique name for the image.
                $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();

                // Saving the image in local directory.
                $imageFile->move('uploads/images', $imageName);

                // Setting the new name of the image in our project
                $project->setImageName($imageName);
            } else {
                $project->setImageName(null);
            }

            // If there are image files
            if($project->getImageFiles()) {
                // Here we are saving entity objects of type Image.
                $images = [];

                // Saving the image file in the local directory
                // and creating Image object with the new name of the image
                // and adding it to all images.
                foreach($project->getImageFiles() as $imageFile) {
                    // Creating unique name for the image.
                    $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
                    // Saving the image in local directory.
                    $imageFile->move('uploads/images', $imageName);

                    $image = new Image();
                    $image->setName($imageName);
                    $image->setProject($project);
                    $images[] = $image;
                }

                // Setting the images for project.
                $project->setImages($images);
            }

            // Setting the date of creation of the project.
            $project->setDateCreated(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('admin_projects');
        }

        return $this->render('admin/add-project.html.twig', ["form" => $form->createView()] );
    }
}
