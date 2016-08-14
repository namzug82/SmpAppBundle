<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UsersMaterials
 *
 * @ORM\Table(name="users_materials")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersMaterialsRepository")
 */
class UsersMaterials
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
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="id")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="material_id", type="integer")
     * @ORM\ManyToOne(targetEntity="Material", inversedBy="id")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $materialId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    public function __construct() 
    {
        $this->date = new \DateTime();
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
     * Set userId
     *
     * @param integer $userId
     * @return UsersMaterials
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set materialId
     *
     * @param integer $materialId
     * @return UsersMaterials
     */
    public function setMaterialId($materialId)
    {
        $this->materialId = $materialId;

        return $this;
    }

    /**
     * Get materialId
     *
     * @return integer 
     */
    public function getMaterialId()
    {
        return $this->materialId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return UsersMaterials
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
