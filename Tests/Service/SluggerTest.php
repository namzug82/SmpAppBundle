<?php
namespace AppBundle\Tests\Service;

use AppBundle\Service\Slugger;

final class SluggerTest extends \PHPUnit_Framework_TestCase
{
	public function testGetSlug()
    {
        $slugger = new Slugger();
        
        $result = $slugger->getSlug('Hola Mundo');
        $this->assertEquals('hola-mundo', $result);

        $result = $slugger->getSlug('Áááá');
        $this->assertEquals('aaaa', $result);

        $result = $slugger->getSlug('Ánodos de Titanio');
        $this->assertEquals('anodos-de-titanio', $result);

        $result = $slugger->getSlug('Piraña 27');
        $this->assertEquals('pirana-27', $result);
    }
}
