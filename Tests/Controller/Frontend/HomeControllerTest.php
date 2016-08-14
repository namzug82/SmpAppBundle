<?php
namespace AppBundle\Tests\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class HomeControllerTest extends WebTestCase
{
	private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context (defaults to the firewall name)
        $firewall = 'secured_area';

        $token = new UsernamePasswordToken('pepe@pepe.com', 1234, $firewall, array('ROLE_USER'));
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

	public function testIndexAction()
	{
		$client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Bienvenido/a a SMP', $crawler->filter('h1')->text());
	}

	public function testPanelAction()
	{
		$this->logIn();
		$client = static::createClient();

        $crawler = $client->request('GET', '/panel/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Panel Usuario', $crawler->filter('h1')->text());
	}
}
