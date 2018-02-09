<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction()
    {
        $allProjects = $this
            ->getDoctrine()
            ->getRepository(Project::class)
            ->findAll();

        return $this->render('projects/projects.html.twig', ['projects' => $allProjects]);
    }

    /**
     * @Route("/project/{name}", name="project_view")
     */
    public function viewProjectAction(Project $project) {

        return $this->render('projects/project.html.twig', ['project' => $project]);
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

        return $this->render('admin/functions/add-project.html.twig', ["form" => $form->createView()] );
    }

    /**
     * @Route("/admin/project/edit/{name}", name="project_edit")
     */
    public function editProjectAction(Project $project) {

        $form = $this->createForm(ProjectType::class)->setData($project);

        return $this->render('admin/functions/edit-project.html.twig', [
            'form' => $form->createView(),
            'project' => $project
        ]);
    }

    /**
     * @Route("/admin/project/remove/{name}", name="project_remove")
     * @Method({"POST"})
     */
    public function deleteProjectAction($name) {

        // Finding the project for deleting by name.
        $project = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findOneBy(['name' => $name]);

        // Deleting images that are saved in the local storage.
        $images = $project->getImages();
        if($images) {
            foreach($images as $image) {
                unlink("../web/uploads/images/" . $image->getName());
            }
        }

        $mainImage = $project->getimageName();
        if($mainImage) {
            unlink("../web/uploads/images/" . $mainImage);
        }

        // Deleting project and related fields.
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('admin_projects');
    }
}
