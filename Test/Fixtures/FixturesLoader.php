<?php

namespace Visca\Bundle\CoreBundle\Test\Fixtures;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use InvalidArgumentException;
use Nelmio\Alice\Fixtures;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class FixturesLoader.
 */
class FixturesLoader
{
    /**
     * @var RegistryInterface
     */
    private $managerRegistry;

    /**
     * @param RegistryInterface $managerRegistry
     */
    public function __construct(RegistryInterface $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param string   $entityManagerName The name of the entity manager
     * @param string[] $fixtures          Array of file paths
     *
     * @return array
     */
    public function load($entityManagerName, $fixtures)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this
            ->managerRegistry
            ->getManager($entityManagerName);

        if (!$entityManager instanceof EntityManager) {
            throw new InvalidArgumentException(
                sprintf(
                    'Provided $entityManager was expected to be an "%s", "%s" given',
                    EntityManager::class,
                    get_class($entityManager)
                )
            );
        }

        $this->purge($entityManager);

        $objects = Fixtures::load(
            $fixtures,
            $entityManager
        );

        $entityManager->clear();

        return $objects;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function purge(EntityManager $entityManager)
    {
        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();

        /** @var ClassMetaData[] $metadataArray */
        $metadataArray = $entityManager->getMetadataFactory()->getAllMetadata();

        foreach ($metadataArray as $metadata) {
            if (($metadata->isInheritanceTypeSingleTable()
                    && $metadata->name != $metadata->rootEntityName)
                || $metadata->isMappedSuperclass
            ) {
                continue;
            }

            $tableName = $metadata->table['name'];
            $query = "ALTER TABLE `$tableName` AUTO_INCREMENT = 1";
            $entityManager->getConnection()->executeUpdate($query);
        }
    }
}
