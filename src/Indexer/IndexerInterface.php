<?php

namespace PhpUnitTestGenerator\Indexer;

/**
 * This interface describes the implementation of an indexer for the source directory
 *
 * @author Michael Doehler
 */
interface IndexerInterface
{

    /**
     * index files for given configuration from file system and create a testable collection of files
     *
     * @param \PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration
     * @return \PhpUnitTestGenerator\Testable\Collection
     */
    public function indexFilesFromConfiguration(\PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration);
}
