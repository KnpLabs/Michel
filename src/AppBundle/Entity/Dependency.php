<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dependency
 *
 * @ORM\Table(name="dependency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DependencyRepository")
 */
class Dependency
{
    /**
     * @ORM\ManyToMany(targetEntity="Package", mappedBy="dependencies", cascade={"persist"})
     */
    private $packages;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255)
     */
    private $version;

    /**
     * @var boolean $isDev
     *
     * @ORM\Column(name="isDev", type="boolean", options={"default":false})
     */
    private $isDev;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->packages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
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
     * @return Dependency
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
     * Set version
     *
     * @param string $version
     *
     * @return Dependency
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set isDev
     *
     * @param boolean $isDev
     *
     * @return Dependency
     */
    public function setIsDev($isDev)
    {
        $this->isDev = $isDev;

        return $this;
    }

    /**
     * Get isDev
     *
     * @return boolean
     */
    public function isDev()
    {
        return $this->isDev;
    }

    /**
     * Add package
     *
     * @param \AppBundle\Entity\Package $package
     *
     * @return Dependency
     */
    public function addPackage(\AppBundle\Entity\Package $package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * Remove package
     *
     * @param \AppBundle\Entity\Package $package
     */
    public function removePackage(\AppBundle\Entity\Package $package)
    {
        $this->packages->removeElement($package);
    }

    /**
     * Get packages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPackages()
    {
        return $this->packages;
    }
}
