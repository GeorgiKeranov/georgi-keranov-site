<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image_name", type="string", length=255, nullable=true, unique=true)
     */
    private $imageName;

    /**
     * @var string
     *
     * @ORM\Column(name="youtube_link", type="string", length=255, nullable=true, unique=true)
     */
    private $youtubeLink;

    /**
     * @var string
     *
     * @ORM\Column(name="github_link", type="string", length=255, nullable=true, unique=true)
     */
    private $githubLink;

    /**
     * @var string
     *
     * @ORM\Column(name="programming_languages", type="string", length=255)
     */
    private $programmingLanguages;

    /**
     * @var string
     *
     * @ORM\Column(name="technologies_used", type="string", length=255)
     */
    private $technologiesUsed;

    /**
     * @var Collection|Image[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="project", cascade={"persist"})
     */
    private $images;

    /**
     * @var Collection|Comment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="project")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    private $comments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var File
     */
    private $mainImage;

    /**
     * @var Collection|File[]
     */
    private $imageFiles;

    /**
     * @var Collection|string[]
     */
    private $deleteImages;

    /**
     * @var bool
     */
    private $deleteMainImage;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Project
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set youtubeLink
     *
     * @param string $youtubeLink
     *
     * @return Project
     */
    public function setYoutubeLink($youtubeLink)
    {
        // Extracting the id of the youtube link
        // Example: https://www.youtube.com/watch?v=keh-V_DP0y8
        // We are getting only the "keh-V_DP0y8" part and setting it
        // to the youtube link.
        $this->youtubeLink = explode("v=", $youtubeLink)[1];

        return $this;
    }

    /**
     * Get youtubeLink
     *
     * @return string
     */
    public function getYoutubeLink()
    {
        // Adding "https://www.youtube.com/watch?v=" to the front of the youtube link.
        return "https://www.youtube.com/watch?v=" . $this->youtubeLink;
    }

    /**
     * Get youtubeLinkEmbed
     *
     * @return string
     */
    public function getYoutubeLinkEmbed() {
        // Adding "https://www.youtube.com/embed/" to the front of the youtube link.
        return "https://www.youtube.com/embed/" . $this->youtubeLink;
    }

    /**
     * Set githubLink
     *
     * @param string $githubLink
     *
     * @return Project
     */
    public function setGithubLink($githubLink)
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    /**
     * Get githubLink
     *
     * @return string
     */
    public function getGithubLink()
    {
        return $this->githubLink;
    }

    /**
     * @return string
     */
    public function getProgrammingLanguages()
    {
        return $this->programmingLanguages;
    }

    /**
     * @param string $programmingLanguages
     */
    public function setProgrammingLanguages($programmingLanguages)
    {
        $this->programmingLanguages = $programmingLanguages;
    }

    /**
     * @return string
     */
    public function getTechnologiesUsed()
    {
        return $this->technologiesUsed;
    }

    /**
     * @param string $technologiesUsed
     */
    public function setTechnologiesUsed($technologiesUsed)
    {
        $this->technologiesUsed = $technologiesUsed;
    }

    /**
     * @return Image[]|Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param Image[]|Collection $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return Comment[]|Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment[]|Collection $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return Collection|File[]
     */
    public function getImageFiles()
    {
        return $this->imageFiles;
    }

    /**
     * @param Collection|File[] $imageFiles
     */
    public function setImageFiles($imageFiles)
    {
        $this->imageFiles = $imageFiles;
    }

    /**
     * @return Collection|string[]
     */
    public function getDeleteImages()
    {
        return $this->deleteImages;
    }

    /**
     * @param Collection|string[] $deleteImages
     */
    public function setDeleteImages($deleteImages)
    {
        $this->deleteImages = $deleteImages;
    }

    /**
     * @return File
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param File $mainImage
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @return bool
     */
    public function isDeleteMainImage()
    {
        return $this->deleteMainImage;
    }

    /**
     * @param bool $deleteMainImage
     */
    public function setDeleteMainImage($deleteMainImage)
    {
        $this->deleteMainImage = $deleteMainImage;
    }

    
}

