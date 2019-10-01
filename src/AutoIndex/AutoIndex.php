<?php

namespace AutoIndex;

class AutoIndex
{
    /**
     * @var string
     */
    private $directoryPath;

    /**
    * @var string
    */
    private $sourcePath;

    /**
     * @var array
     */
    public $skipDirectory = [
        '.git',
        '.svn',
        '.github',
        'node_modules',
        'tests',
        'vendor'
    ];

    /**
     * Constructor.
     * 
     * @param string $directoryPath 
     * @param string $sourcePath    
     */
    public function __construct($directoryPath, $sourcePath)
    {
        $this->sourcePath = $this->setPath($sourcePath);
        $this->directoryPath = $this->setPath($directoryPath);

        $this->setSkip();
    }

    /**
     * Set the path name to be used.
     *
     * @param string $path 
     *
     * @throws \InvalidArgumentException When directory isn't set
     */
    private function setPath($path)
    {
        if (file_exists($path) === false) {
            throw new \InvalidArgumentException('setPath only accepts directory. Input was: ' . $path);
        }
        return realpath($path) . DIRECTORY_SEPARATOR;
    }

    /**
     * Set the skip list to be used.
     */
    private function setSkip()
    {
        foreach ($this->skipDirectory as &$value) {
            $value = $this->directoryPath . $value;
        }
        unset($value);
    }

    /**
     * Copies a file recursively.
     *
     * @param  string $path
     */
    private function copyFileRecursively($path)
    {
        $filesList = (array) glob($path . "*", GLOB_ONLYDIR);
        $filterList = array_diff($filesList, $this->skipDirectory);

        if ($this->checkIndexInDirectory($path) === false) {
            $this->copyFile($path . DIRECTORY_SEPARATOR);
            echo 'Added to: ' . $path . PHP_EOL;
        }

        foreach ($filterList as $directory) {
            $this->copyFileRecursively($directory . DIRECTORY_SEPARATOR);
        }
        unset($directory);
    }

    /**
     * Checks the existence of index files.
     *
     * @param  string $directoryPath
     *
     * @return bool
     */
    private function checkIndexInDirectory($directoryPath)
    {
        return file_exists($directoryPath . 'index.php');
    }

    /**
     * Copies a file.
     *
     * @param  string $directoryPath
     *
     * @return bool
     */
    private function copyFile($directoryPath)
    {
        return copy($this->sourcePath . 'index.php', $directoryPath . 'index.php');
    }

    /**
     * Adds index.php files to the configured path.
     */
    public function addIndex()
    {
        $this->copyFileRecursively($this->directoryPath);
    }
}
