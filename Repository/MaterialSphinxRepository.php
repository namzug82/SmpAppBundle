<?php
namespace AppBundle\Repository;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

final class MaterialSphinxRepository implements SphinxRepository
{
    const INDEX_MATERIAL = 'IndexMaterial';
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

    public function retrieve($term)
    {
    	$searchd = $this->container->get('iakumai.sphinxsearch.search');
    	$searchd->setLimits(0, 100);
    	return $searchd->searchEx('*'.$term.'*', array(self::INDEX_MATERIAL));
    }
}
