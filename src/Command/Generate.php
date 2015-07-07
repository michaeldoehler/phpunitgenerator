<?php

namespace PhpUnitTestGenerator\Command;

use PhpUnitTestGenerator\Configuration\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate command that initilizes the generation process
 *
 * @author Michael Doehler
 */
class Generate extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('generate');
        $this->setDescription('Generate the test suite');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $configuration = Configuration::getInstance();

        //launch indexer
        $indexer = new \PhpUnitTestGenerator\Indexer\IndexerFilesystem();

        //launch generator
        $generator = new \PhpUnitTestGenerator\Generator\Generator($configuration);

        //build index
        $index = $indexer->indexFilesFromConfiguration($configuration);

        //generate tests
        $results = $generator->generateTestsFromCollection($index);

        //print output
        $output->writeln("Generated " . $results->count() . " tests for " . $index->count() . " classes: " . $configuration->getSourceDirectory() . " --> " . $configuration->getTargetDirectory() . ".");
    }

}
