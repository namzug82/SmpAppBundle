<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Validation;
use AppBundle\Entity\User;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $user;
    private $validator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->validator = $this->getValidator();
        $this->em = $this->getManager();
        $this->setUser();
    }

    public function testSuccessfullyFindByName()
    {
        $user = $this->em
            ->getRepository('AppBundle:User')
            ->findByName('Test User')
        ;

        $this->assertCount(1, $user);
    }

    public function testUnsuccessfullyFindByName()
    {
        $user = $this->em
            ->getRepository('AppBundle:User')
            ->findByName('Nonexistent User')
        ;

        $this->assertEmpty($user);
    }

    public function testWhenEmailIsNotUnique()
    {
        $user = new User();
        $user->setRole('ROLE_TEST');
        $user->setName('Test User');
        $user->setSurname('Test');
        $user->setEmail('test@test.com');
        $user->setSalt('test');
        $user->setPassword('test');
        $user->setAllowsEmail(1);

        $errors = $this->validator->validate($user);

        $this->assertEquals(1, count($errors));
    }

    public function testBadEmailSetted()
    {
        $this->user->setEmail('bad-email');
        $errors = $this->validator->validate($this->user);

        $this->assertEquals(1, count($errors));
    }

    public function testRightEmailSetted()
    {
        $this->user->setEmail('test@test.com');
        $errors = $this->validator->validate($this->user);

        $this->assertEquals(0, count($errors));
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    private function getValidator()
    {
        return static::$kernel->getContainer()->get('validator');
    }

    private function getManager()
    {
        return static::$kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
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
