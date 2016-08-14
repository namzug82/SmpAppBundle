<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use AppBundle\Entity\UsersMaterials;

class UsersMaterialsLinker implements Linker
{
	private $em;
	private $usersMaterials;

	public function __construct(EntityManager $entityManager)
	{
    	$this->em = $entityManager;
    	$this->usersMaterials = new UsersMaterials;
	}

    public function link(User $user, Material $material)
    {
    	$this->usersMaterials->setUserId($user->getId());
    	$this->usersMaterials->setMaterialId($material->getId());
		$this->em->persist($this->usersMaterials);

		try {
		   $this->em->flush();
		}
		catch (UniqueConstraintViolationException $e){
		}
    }
}
