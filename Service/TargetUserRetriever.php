<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager as EntityManager;

final class TargetUserRetriever 
{
	const ZERO_RESULTS = 0;
	private $container;
	private $em;
	private $recipients = array();

	public function __construct(Container $container, EntityManager $entityManager)
	{
		$this->container = $container;
		$this->em = $entityManager;
	}

    public function retrieveUserEmailsInterestedByMaterial($term)
    {
    	$termQueryResult = $this->getMaterialsByTerm($term);
        $users = [];

    	if (self::ZERO_RESULTS != $termQueryResult["total_found"]) {
            $arrayOfMaterialsIds = $this->getMaterialsIds($termQueryResult);
            $users = $this->getUsersByMaterialIds($arrayOfMaterialsIds);	   		
    	}

        if (null != $users) {
            return $this->getUserEmails($users);
        }

        return $this->recipients = [""];	
    }

    private function getMaterialsByTerm($term)
    {
    	return $this->container->get('material_sphinx_repo')->retrieve($term);    	
    }

    private function getMaterialsIds($materialList)
    {
        foreach ($materialList['matches'] as $materialId => $value) {
            $arrayOfMaterialsIds[] = $materialId;
        }

        return $arrayOfMaterialsIds;
    }

    private function getUsersByMaterialIds($arrayOfMaterialsIds)
    {
        $users = $this->em
            ->getRepository('AppBundle:UsersMaterials')
            ->getUsersByMaterialIds($arrayOfMaterialsIds);

        return $users;
    }

    private function getUserEmails($users)
    {
        foreach ($users as $user) {
            $this->recipients[] = $user->getEmail();
        }
        
        return $this->recipients;
    }
}
