<?php

namespace Visca\Bundle\CoreBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\RunSqlDoctrineCommand;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Class DoctrineCommand.
 */
class DoctrineCommand extends RunSqlDoctrineCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('doctrine:query:file')
            ->setDescription('Executes arbitrary SQL script file.')
            ->setDefinition(
                [
                    new InputArgument(
                        'file',
                        InputArgument::REQUIRED,
                        'The script file to execute.'
                    ),
                    new InputOption(
                        'connection',
                        null,
                        InputOption::VALUE_OPTIONAL,
                        'The connection to use for this command.',
                        'default'
                    ),
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
         * Add definition for legacy
         */
        $this->addOption(
            'depth',
            null,
            InputOption::VALUE_REQUIRED,
            'Dumping depth of result set.',
            7
        );
        $this->addArgument('sql');

        /*
         * Process the command
         */
        DoctrineCommandHelper::setApplicationConnection(
            $this->getApplication(),
            $input->getOption('connection')
        );

        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException();
        }

        $input->setArgument('sql', file_get_contents($filePath));

        $stopwatch = new Stopwatch();
        $stopwatch->start('import');

        $bufferedOutput = new BufferedOutput();

        parent::execute($input, $bufferedOutput);

        $event = $stopwatch->stop('import');

        $result = trim($bufferedOutput->fetch());

        if ($result == 'int(0)' || $result == 'int 0') {
            $output->writeln(
                sprintf('Importation done in %d ms', $event->getDuration())
            );
        } else {
            throw new Exception(sprintf('Importation failed with error code : %s', $result));
        }
    }
}
