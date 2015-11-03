<?php

namespace Visca\Bundle\CoreBundle\Test;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\HttpFoundation\Response;
use Visca\Bundle\DoctrineBundle\Util\EntityManagerPurger;

/**
 * Class WebTestCase.
 */
abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * Setup databases schema.
     */
    protected function setUpDatabases()
    {
        $managerNames = $this
            ->getContainer()
            ->get('doctrine')
            ->getManagerNames();

        foreach ($managerNames as $managerName) {
            $entityManager = $this
                ->getContainer()
                ->get($managerName);

            if (!$entityManager instanceof EntityManager) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Manager "%s" was expected to be of type "%s", "%s" given',
                        $managerName,
                        EntityManager::class,
                        get_class($entityManager)
                    )
                );
            }

            $purger = new EntityManagerPurger();
            $purger->purge($entityManager);

            $this
                ->getContainer()
                ->get($managerName)
                ->clear();
        }
    }

    /**
     * Load data fixtures with multi entity managers support.
     *
     * @param array  $fixturesPath An array of relative path
     * @param string $omName
     */
    protected function loadFixturesWithCommand(
        array $fixturesPath,
        $omName = 'default'
    ) {
        $rootDir = $this
                ->getContainer()
                ->get('kernel')
                ->getRootDir();

        /*
         * Set paths to full path
         */
        foreach ($fixturesPath as &$nextFixturePath) {
            $nextFixturePath = $rootDir.'/../'.$nextFixturePath;
        }
        unset($nextFixturePath);

        /*
         * Run the command
         */
        $this->runCommandWithException(
            'doctrine:fixtures:load',
            [
                '--fixtures' => $fixturesPath,
                '--append' => true,
                '--em' => $omName,
            ],
            false
        );
    }

    /**
     * Builds up the environment to run the given command.
     *
     * @param string $name
     * @param array  $params
     * @param bool   $reuseKernel
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected function runCommandWithException(
        $name,
        array $params = [],
        $reuseKernel = false
    ) {
        array_unshift($params, $name);

        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        if (!$reuseKernel) {
            if (null !== static::$kernel) {
                static::$kernel->shutdown();
            }

            $kernel = static::$kernel = $this->createKernel(
                ['environment' => $this->environment]
            );
            $kernel->boot();
        } else {
            $kernel = $this->getContainer()->get('kernel');
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput($params);
        $input->setInteractive(false);

        $fp = fopen('php://temp/maxmemory:'.$this->maxMemory, 'r+');
        $output = new StreamOutput($fp);

        $resultStatus = $application->run($input, $output);

        rewind($fp);
        $output = stream_get_contents($fp);

        if ($resultStatus != 0) {
            throw new RuntimeException(
                sprintf(
                    '"%s" command with params "%s" failed with the output : "%s"',
                    $name,
                    json_encode($params),
                    $output
                )
            );
        }
    }

    /**
     * Create a Client with the default user credentials.
     *
     * @return Client
     */
    protected function createDefaultUserClient()
    {
        return static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'John Smith',
                'PHP_AUTH_PW' => 'test2014',
            ]
        );
    }

    /**
     * Create a Client with the alternative user credentials.
     *
     * @return Client
     */
    protected function createAlternativeUserClient()
    {
        return static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'Luc Skywalker',
                'PHP_AUTH_PW' => 'test2014',
            ]
        );
    }

    /**
     * Assert that the given Response is a valid json response.
     *
     * @param Response $response
     * @param int      $statusCode
     */
    protected function assertJsonResponse(Response $response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode,
            $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );

        if ($statusCode >= 200 && $statusCode < 300) {
            $this->assertNotEmpty($response->getContent());

            $this->assertNotNull(json_decode($response->getContent()));
        }
    }

    /**
     * Count how many entities there are in the repository.
     *
     * @param string $entityManagerName
     * @param string $repositoryName
     *
     * @return int
     */
    protected function countEntities($entityManagerName, $repositoryName)
    {
        $repository = static::getContainer()
            ->get('doctrine')
            ->getManager($entityManagerName)
            ->getRepository($repositoryName);

        return count($repository->findAll());
    }
}
