<?php

namespace Visca\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PhpcsFixerCommand
 * Apply Code Sniffer rules to php files using .php_cs config file..
 */
class PhpcsFixerCommand extends ContainerAwareCommand
{
    /**
     * Sets information about the command.
     */
    protected function configure()
    {
        $this
            ->setName('phpcs:fixer')
            ->setDescription('Apply Code Sniffer rules to php files using .php_cs config file.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
         * validate input
         */
        $input->validate();

        /*
         * Find where is php-cs-fixer
         */
        exec('which php-cs-fixer', $phpcs);

        if (!is_array($phpcs) or !isset($phpcs[0])) {
            throw new \Exception('Can not find php-cs-fixer. Install it before run this command.');
        }

        $phpcsPath = $phpcs[0];

        /*
         * Execute php-cs-fixer
         */
        $command = "{$phpcsPath} fix --config-file=.php_cs";
        $output->writeln("Executing {$command}");
        passthru($command);

        return true;
    }
}
