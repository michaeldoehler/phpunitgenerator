<?php

namespace PhpUnitTestGenerator\Command;

use PhpUnitTestGenerator\Configuration\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Test command that test the generated tests
 *
 * @Todo: Finalize me
 *
 * @author Michael Doehler
 */
class Test extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('test');
        $this->setDescription('Run the test suite');
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
        $phpunit = new \PHPUnit_TextUI_Command();
        $phpunit->run(array('phpunit', "-c", realpath($configuration->getTargetDirectory()) . "/phpunit.xml"), true);
    }

}
