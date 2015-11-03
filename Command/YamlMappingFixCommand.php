<?php

namespace Visca\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerDebugCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class YamlMappingFixCommand.
 */
class YamlMappingFixCommand extends ContainerDebugCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('skipper:mapping:fix')
            ->setDescription('Fix YAML mappings.')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'A bundle name'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Bundle $bundle */
        $bundle = $this
            ->getContainer()
            ->get('kernel')
            ->getBundle($input->getArgument('name'));

        $files = $this->getMappingFiles($bundle);

        foreach ($files as $file) {
            $this->fixMappingFile($file, $output);
        }
    }

    /**
     * @param Bundle $bundle
     *
     * @return array
     */
    protected function getMappingFiles(Bundle $bundle)
    {
        $files = [];

        $finder = new Finder();
        $finder
            ->files()
            ->name('*.orm.yml');

        foreach ($finder->in($bundle->getPath().'/*') as $file) {
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param SplFileInfo     $file
     * @param OutputInterface $output
     */
    protected function fixMappingFile(
        SplFileInfo $file,
        OutputInterface $output
    ) {
        $fileContent = $file->getContents();

        $fixedFileContent = $this->fixExtends($fileContent);

        if ($fileContent !== $fixedFileContent) {
            file_put_contents($file->getPathname(), $fixedFileContent);

            $output->writeln($file->getFilename().' has been fixed');
        }
    }

    /**
     * @param string $fileContent The content of the file to fix
     *
     * @return string
     */
    private function fixExtends($fileContent)
    {
        $pattern = '@extends: [\w\\\]+@';

        $fixedFileContent = preg_replace(
            $pattern,
            '',
            $fileContent
        );

        return $fixedFileContent;
    }
}
