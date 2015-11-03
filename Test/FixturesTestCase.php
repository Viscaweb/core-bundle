<?php

namespace Visca\Bundle\CoreBundle\Test;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use Nelmio\Alice\Fixtures;
use Symfony\Component\HttpKernel\KernelInterface;
use Visca\Bundle\DoctrineBundle\Util\EntityManagerPurger;

/**
 * Class FixturesTestCase.
 */
abstract class FixturesTestCase extends BaseWebTestCase
{
    /**
     * Array of objects loaded in the fixtures
     * Keys are the unique ids given in the fixture document for each object.
     *
     * @var array
     */
    protected $objects;

    protected function setUp()
    {
        $fixturesByEntityManager = $this->getFixtures(
            $this->getContainer()->get('kernel'),
            $this->getName(false)
        );

        foreach ($fixturesByEntityManager as $entityManagerName => $fixtures) {
            $entityManager = $this
                ->getContainer()
                ->get('doctrine')
                ->getManager($entityManagerName);

            if (!$entityManager instanceof EntityManager) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Manager "%s" was expected to be of type "%s", "%s" given',
                        $entityManagerName,
                        EntityManager::class,
                        get_class($entityManager)
                    )
                );
            }

            $this->purge($entityManager);

            $this->objects = Fixtures::load(
                $fixtures,
                $entityManager
            );

            static::getContainer()
                ->get('doctrine')
                ->getManager($entityManagerName)
                ->clear();
        }
    }

    /**
     * Return the fixtures necessary for this test.
     *
     * @param KernelInterface $kernel
     * @param string          $testName
     *
     * @return array An array with key=entity manager and values=array of YML file path
     */
    abstract protected function getFixtures(KernelInterface $kernel, $testName);

    /**
     * @param EntityManager $entityManager
     */
    private function purge(EntityManager $entityManager)
    {
        $purger = new EntityManagerPurger();
        $purger->purge($entityManager);
    }
}
