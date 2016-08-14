<?php 
namespace Tests\Repository;

use AppBundle\Service\UsersMaterialsLinker;
use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class UsersMaterialsRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $user;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $this->setUser();

        $material = $this->createMock(Material::class);
        $material->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(0));

        $linker = new UsersMaterialsLinker($this->em);
        $linker->link($this->user, $material);
    }

    public function testFindUsersByMaterialId()
    {
        $testId = $this->user->getId();
        $materialId = array(0);
        $arrayOfUsers = $this->em
            ->getRepository('AppBundle:UsersMaterials')
            ->getUsersByMaterialIds($materialId)
        ;

        $this->assertEquals($testId, $arrayOfUsers[0]->getId());
    }

    private function setUser()
    {
        if (null == $this->em
            ->getRepository('AppBundle:User')
            ->findByName('Test User')
        ) {
            $this->user = new User();
            $this->user->setRole('ROLE_TEST');
            $this->user->setName('Test User');
            $this->user->setSurname('Test');
            $this->user->setEmail('test@test.com');
            $this->user->setSalt('test');
            $this->user->setPassword('test');
            $this->user->setAllowsEmail(1);
            $this->em->persist($this->user);
            $this->em->flush();
        } else {
            $user = $this->em
                ->getRepository('AppBundle:User')
                ->findByName('Test User')
            ;

            $this->user = $user[0];
        }
    }
}
