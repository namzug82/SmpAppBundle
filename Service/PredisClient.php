<?php
namespace SmpBundle\Service;

use Snc\RedisBundle\Doctrine\Cache\RedisCache;
use Predis\Client;
use SmpBundle\Service\Cache;

final class PredisClient implements Cache
{
	public function init()
	{
		$predis = new RedisCache();
		$predis->setRedis(new Client());
		return $predis;
	}
}
