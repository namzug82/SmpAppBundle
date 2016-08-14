<?php 
namespace Tests\Service;

use AppBundle\Service\UsersMaterialsLinker;
use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UsersMaterialsLinkerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(0));
        
        $material = $this->createMock(Material::class);
        $material->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(0));

        $linker = new UsersMaterialsLinker($this->em);
        $linker->link($user, $material);
    }

    public function testLink()
    {
    }
}