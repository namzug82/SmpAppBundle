<?php
namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Entity\Material;

class MaterialRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $material;
    private $validator;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $this->validator = $this->getValidator();
        $this->em = $this->getManager();
        $this->setMaterial();
    }

    public function testWhenMaterialNameIsNotUnique()
    {
        $material = new Material();
        $material->setName('Test Material');
        $material->setSlug('test');
        $material->setImage1(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-1.jpg')
        );
        $material->setImage2(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-2.jpg')
        );

        $errors = $this->validator->validate($material);

        $this->assertEquals(1, count($errors));
    }

    public function testWhenMaterialSlugIsNotUnique()
    {
        $material = new Material();
        $material->setName('New Test Material');
        $material->setSlug('test-material');
        $material->setImage1(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-1.jpg')
        );
        $material->setImage2(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-2.jpg')
        );

        $errors = $this->validator->validate($material);

        $this->assertEquals(1, count($errors));
    }

    public function testWhenMaterialImage1HasWrongSize()
    {
        $material = new Material();
        $material->setName('New Test Material');
        $material->setSlug('new-test-material');
        $material->setImage1(
            'https://specialmetals.s3.amazonaws.com/wrong-size-test-material-1.jpg'
        );
        $material->setImage2(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-2.jpg')
        );

        $errors = $this->validator->validate($material);

        $this->assertEquals(1, count($errors));
    }

    public function testWhenMaterialImage2HasWrongSize()
    {
        $material = new Material();
        $material->setName('New Test Material');
        $material->setSlug('new-test-material');
        $material->setImage1(
            $this->getMaterialImageFile('https://specialmetals.s3.amazonaws.com/test-material-1.jpg')
        );
        $material->setImage2(
            'https://specialmetals.s3.amazonaws.com/test-material-2.jpg'
        );

        $errors = $this->validator->validate($material);

        $this->assertEquals(1, count($errors));
    }

    public function testSuccessfullyFindOneBySlug()
    {
        $material = $this->em
            ->getRepository('AppBundle:Material')
            ->findBySlug('test-material')
        ;

        $this->assertCount(1, $material);
    }

    public function testUnsuccessfullyFindOneBySlug()
    {
        $material = $this->em
            ->getRepository('AppBundle:Material')
            ->findBySlug('nonexistent-material')
        ;

        $this->assertEmpty($material);
    }

    public function testRetrieveArrayAllMaterials()
    {
        $arrayOfAllMaterials = $this->em
            ->getRepository('AppBundle:Material')
            ->retrieveArrayAllMaterials()
        ;

        $this->assertContains('Test Material', $arrayOfAllMaterials);
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

    private function setMaterial()
    {
        if (null == $this->em
            ->getRepository('AppBundle:Material')
            ->findBySlug('test-material')
        ) {
            $this->material = new Material();
            $this->material->setName('Test Material');
            $this->material->setImage1('https://specialmetals.s3.amazonaws.com/test-material-1.png');
            $this->material->setImage2('https://specialmetals.s3.amazonaws.com/test-material-2.png');
            $this->em->persist($this->material);
            $this->em->flush();
        } else {
            $material = $this->em
                ->getRepository('AppBundle:Material')
                ->findBySlug('test-material')
            ;

            $this->material = $material[0];
        }
    }

    private function getMaterialImageFile($image)
    {
        if (null == $image) {
            return;
        }

        $filePath = realpath(static::$kernel
            ->getContainer()->getParameter('images_directory')) . '_tmp';
        $content = file_get_contents($image);
        file_put_contents($filePath, $content);
               
        return new File($filePath);
    }
}
