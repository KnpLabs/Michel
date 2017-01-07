<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Entity
 */
class Package
{
    /**
     * @ORM\ManyToMany(targetEntity="Package", mappedBy="myRequirements", cascade={"persist"})
     */
    private $requiresMe;

    //@TODO: Add a naming_strategy
    /**
     * @ORM\ManyToMany(targetEntity="Package", inversedBy="requiresMe", cascade={"persist"})
     * @ORM\JoinTable(name="requirements")
     */
    private $myRequirements;

    /**
     * @ORM\ManyToMany(targetEntity="Package", mappedBy="myDevRequirements", cascade={"persist"})
     */
    private $devRequiresMe;

    /**
     * @ORM\ManyToMany(targetEntity="Package", inversedBy="devRequiresMe", cascade={"persist"})
     * @ORM\JoinTable(name="requirements_dev")
     */
    private $myDevRequirements;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * Package constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->id = $name;
        $this->myRequirements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->myDevRequirements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyRequirements()
    {
        return $this->myRequirements;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyDevRequirements()
    {
        return $this->myDevRequirements;
    }
}