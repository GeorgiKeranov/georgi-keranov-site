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

    private function deleteImageByName($name) {
        //$path = $this->container->getParameter('images_save_path');
        unlink('uploads/images/' . $name);
    }

    private function saveImage(File $imageFile) {
        // Creating unique name for the image.
        $imageName = md5(uniqid()) . '.' . $imageFile->guessExtension();
        // Saving the image in local directory.
        $imageFile->move('uploads/images', $imageName);

        // Returning the new name for the image.
        return $imageName;
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction()
    {
        $allProjects = $this
            ->getDoctrine()
            ->getRepository(Project::class)
            ->findBy([], ['dateCreated' => 'DESC']);

        return $this->render('projects/projects.html.twig', ['projects' => $allProjects]);
    }

    /**
     * @Route("/project/{name}", name="project_view")
     */
    public function viewProjectAction(Project $project) {

        return $this->render('projects/project-view.html.twig', ['project' => $project]);
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
            if($project->getMainImage() != null) {
                // Setting the new name of the image in our project
                $project->setImageName($project->getMainImage());
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
                    $image = new Image();
                    $image->setName($this->saveImage($imageFile));
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

        return $this->render('admin/functions/project-add.html.twig', ["form" => $form->createView()] );
    }

    /**
     * @Route("/admin/project/edit/{name}", name="project_edit")
     */
    public function editProjectAction(Request $request, Project $project) {

        $form = $this->createForm(ProjectType::class)->setData($project);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Deleting the old image if deleteMainImage is true
            if($project->isDeleteMainImage()) {
                // Deleting the image from local storage.
                $this->deleteImageByName($project->getImageName());
                $project->setImageName(null);
            }

            // Save the new image.
            if($project->getMainImage()) {
                // Saving the new image in local storage and
                // setting the new name of image in project.
                $project->setImageName($this->saveImage($project->getMainImage()));
            }

            // Deleting old secondary images.
            if($project->getDeleteImages()) {

                $imageRepo = $this->getDoctrine()
                    ->getRepository(Image::class);

                foreach($project->getDeleteImages() as $imageName) {
                    $image = $imageRepo->findOneBy([
                        'name' => $imageName,
                        'project' => $project
                    ]);

                    // If there is image with these params in the database.
                    if($image) {
                        // Delete image from database images.
                        $em->remove($image);
                        // Delete image from local storage.
                        $this->deleteImageByName($image->getName());
                    }
                }
            }

            // If there are image files save them.
            if($project->getImageFiles()) {

                // Saving the image file in the local directory
                // and creating Image object with the new name of the image
                // and adding it to database.
                foreach($project->getImageFiles() as $imageFile) {
                    $image = new Image();
                    $image->setName($this->saveImage($imageFile));
                    $image->setProject($project);
                    $em->persist($image);
                }
            }

            $em->flush();

            return $this->redirectToRoute('admin_projects');
        }

        return $this->render('admin/functions/project-edit.html.twig', [
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
        $pathToImages = $this->container->getParameter('images_save_path');

        if($images) {
            foreach($images as $image) {
                unlink($pathToImages . $image->getName());
            }
        }

        $mainImage = $project->getimageName();
        if($mainImage) {
            unlink($pathToImages . $mainImage);
        }

        // Deleting project and related fields.
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('admin_projects');
    }




}
