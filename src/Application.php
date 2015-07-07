<?php

namespace PhpUnitTestGenerator;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Definition of the Generator Application as a pure console application
 *
 * @author Michael Doehler
 */
class Application extends ConsoleApplication
{

    /**
     * get all default commands
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new Command\Generate();
        $defaultCommands[] = new Command\Test();

        return $defaultCommands;
    }

    /**
     * Gets the InputDefinition related to this Application.
     *
     * @return InputDefinition The InputDefinition instance
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        return $inputDefinition;
    }

}
