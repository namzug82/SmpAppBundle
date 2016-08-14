<?php

namespace AppBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Service\Slugger;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MaterialRepository")
 * @UniqueEntity("name")
 * @UniqueEntity("slug")
 */
class Material
{
    private $slugger;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="UsersMaterials", mappedBy="material_id", cascade={"remove"}, orphanRemoval=true)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entry_date", type="datetime")
     */
    private $entryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="symbol", type="string", length=50, nullable=true)
     */
    private $symbol;

    /**
     * @var string
     *
     * @ORM\Column(name="image_1", type="string", length=255, nullable=true)
     *
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 2000,
     *     minHeight = 200,
     *     maxHeight = 2000
     * )
     *
     */
    private $image1;

    /**
     * @var string
     *
     * @ORM\Column(name="image_2", type="string", length=255, nullable=true)
     *
     * @Assert\Image()
     *
     */
    private $image2;

    /**
     * @var string
     *
     * @ORM\Column(name="image_3", type="string", length=255, nullable=true)
     *
     * @Assert\Image()
     *
     */
    private $image3;

    /**
     * @var string
     *
     * @ORM\Column(name="image_4", type="string", length=255, nullable=true)
     *
     * @Assert\Image()
     *
     */
    private $image4;

    /**
     * @var string
     *
     * @ORM\Column(name="image_5", type="string", length=255, nullable=true)
     *
     * @Assert\Image()
     *
     */
    private $image5;

    /**
     * @var array
     *
     * @ORM\Column(name="alloy", type="array", nullable=true)
     */
    private $alloy;

    /**
     * @var array
     *
     * @ORM\Column(name="format", type="array", nullable=true)
     */
    private $format;

    public function __construct() 
    {
        $this->slugger = new Slugger();
        $this->entryDate = new \DateTime();
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
     * @return Material
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug();

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
     * Set slug
     *
     * @param string $slug
     * @return Material
     */
    public function setSlug($slug = null)
    {
        if (null == $slug) {
            $this->slug = $this->slugger->getSlug($this->name);
            return $this;
        }
        
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Material
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
     * Set symbol
     *
     * @param string $symbol
     * @return Material
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set image1
     *
     * @param string $image1
     * @return Material
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * Get image1
     *
     * @return string 
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * Set image2
     *
     * @param string $image2
     * @return Material
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;

        return $this;
    }

    /**
     * Get image2
     *
     * @return string 
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * Set image3
     *
     * @param string $image3
     * @return Material
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;

        return $this;
    }

    /**
     * Get image3
     *
     * @return string 
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Set image4
     *
     * @param string $image4
     * @return Material
     */
    public function setImage4($image4)
    {
        $this->image4 = $image4;

        return $this;
    }

    /**
     * Get image4
     *
     * @return string 
     */
    public function getImage4()
    {
        return $this->image4;
    }

    /**
     * Set image5
     *
     * @param string $image5
     * @return Material
     */
    public function setImage5($image5)
    {
        $this->image5 = $image5;

        return $this;
    }

    /**
     * Get image5
     *
     * @return string 
     */
    public function getImage5()
    {
        return $this->image5;
    }

    /**
     * Set alloy
     *
     * @param array $alloy
     * @return Material
     */
    public function setAlloy($alloy)
    {
        $this->alloy = $alloy;

        return $this;
    }

    /**
     * Get alloy
     *
     * @return array 
     */
    public function getAlloy()
    {
        return $this->alloy;
    }

    /**
     * Set format
     *
     * @param array $format
     * @return Material
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return array 
     */
    public function getFormat()
    {
        return $this->format;
    }
}
