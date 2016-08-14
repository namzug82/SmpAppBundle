<?php
namespace AppBundle\Service;

use Snc\RedisBundle\Doctrine\Cache\RedisCache;
use Predis\Client;
use AppBundle\Service\Cache;

final class PredisClient implements Cache
{
	public function init()
	{
		$predis = new RedisCache();
		$predis->setRedis(new Client());
		return $predis;
	}
}
