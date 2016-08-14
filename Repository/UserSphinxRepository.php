<?php
namespace AppBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

final class UserSphinxRepository implements SphinxRepository
{
	const INDEX_USER = 'IndexUser';
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

    public function retrieve($term)
    {
    	$searchd = $this->container->get('iakumai.sphinxsearch.search');
    	$searchd->setLimits(0, 100);
    	return $searchd->searchEx('*'.$term.'*', array(self::INDEX_USER));
    }
}
