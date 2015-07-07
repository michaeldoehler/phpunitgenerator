<?php

namespace PhpUnitTestGenerator\Indexer;

/**
 * Implementation of an indexer, which index files from filesystem
 *
 * @author Michael Doehler
 */
class IndexerFilesystem implements IndexerInterface
{

    /**
     * index files from given source path
     *
     * @param string $source
     * @return string[]
     */
    private function indexFilesFromSource($source)
    {
        $fileList = array();
        if (is_file($source)) {
            $fileList[] = $source;
        } elseif (is_dir($source)) {
            $fileList = array_merge($fileList, $this->indexDirectory($source));
        }

        return $fileList;
    }

    /**
     * index a directory recursivly
     *
     * @param string $source
     * @return string[]
     */
    private function indexDirectory($source)
    {
        $fileList = array();
        foreach (scandir($source) as $directory) {
            if ($directory != "." && $directory != ".." && is_dir($source . DIRECTORY_SEPARATOR . $directory)) {
                $fileList = array_merge($fileList, $this->indexDirectory($source . DIRECTORY_SEPARATOR . $directory));
            } elseif (is_file($source . DIRECTORY_SEPARATOR . $directory) && substr($directory, -1 * strlen('.php')) === '.php') {
                $fileList[] = $source . DIRECTORY_SEPARATOR . $directory;
            }
        }

        return $fileList;
    }

    /**
     * index files for given configuration from file system and create a testable collection of files
     *
     * @param \PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration
     * @return \PhpUnitTestGenerator\Testable\Collection
     */
    public function indexFilesFromConfiguration(\PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration)
    {
        return new \PhpUnitTestGenerator\Testable\Collection($configuration, $this->indexFilesFromSource($configuration->getSourceDirectory()));
    }

}
