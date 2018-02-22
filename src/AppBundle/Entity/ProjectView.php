<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectViews
 *
 * @ORM\Table(name="project_views")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectViewRepository")
 */
class ProjectView
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
     * @ORM\Column(name="ip", type="string", length=255, unique=true)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_viewed", type="datetime")
     */
    private $dateViewed;


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
     * Set ip
     *
     * @param string $ip
     *
     * @return ProjectView
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set dateViewed
     *
     * @param \DateTime $dateViewed
     *
     * @return ProjectView
     */
    public function setDateViewed($dateViewed)
    {
        $this->dateViewed = $dateViewed;

        return $this;
    }

    /**
     * Get dateViewed
     *
     * @return \DateTime
     */
    public function getDateViewed()
    {
        return $this->dateViewed;
    }
}

