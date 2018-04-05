<?php
namespace Core;

class Directory {

    /**
     *
     *
     * @var array
     */
    static protected $_directoryInstances = array();

    /**
     *
     *
     * @param $path
     * @return \DirectoryIterator
     * @throws \Exception
     */
    static protected function getDirectoryInstance($path) {
        if (false === isset(self::$_directoryInstances[$path])) {
            if (false === is_dir($path)) {
                throw new \Exception($path . ' directory not exists');
            }
            self::$_directoryInstances[$path] = new \DirectoryIterator($path);
        }
        return self::$_directoryInstances[$path];
    }

    /**
     *
     *
     * @param $path
     * @return \SplFileInfo[]
     */
    public function getFiles($path) {
        $files = array();
        foreach (self::getDirectoryInstance($path) as $item) {
            if ($item->isDot() || $item->isDir()) {
                continue;
            }
            $files[] = $item;
        }
        return $files;
    }

    /**
     *
     *
     * @param $path
     * @return \SplFileInfo[]
     */
    public function getDirectories($path) {
        $directories = array();
        foreach (self::getDirectoryInstance($path) as $item) {
            if ($item->isDot() || $item->isFile()) {
                continue;
            }
            $directories[] = $item;
        }
        return $directories;
    }

    /**
     *
     *
     * @param $path
     * @return bool
     * @throws \Exception
     */
    public function deleteRecursive($path) {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileInfo) {
            $filename = $fileInfo->getRealPath();
            $func = $fileInfo->isDir() ? rmdir($filename) : unlink($filename);
            if (false === $func) {
                throw new \Exception($filename . ' delete error');
            }
        }
        return rmdir($path);
    }

    /**
     *
     *
     * @param $source
     * @param $destination
     * @param null $owner
     * @return bool
     * @throws \Exception
     */
    public function copyRecursive($source, $destination, $owner = null) {
        $mask = umask(0);
        mkdir($destination, 0777, true);
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST) as $item) {
            if ($item->isDir()) {
                $directory = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                if (mkdir($directory, 0775, true) === false) {
                    throw new \Exception($directory . ' create dir error');
                }
            } else {
                $filename = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
                if (copy($item, $filename) === false) {
                    throw new \Exception($item . ' to ' . $filename . ' copy error');
                }
                if ($owner !== null) {
                    chown($filename, $owner);
                }
            }
        }
        umask($mask);
        return true;
    }


    public function setPathPermissions($path, $writeDirectories = array()) {
        $mask = umask(0);
        foreach ($writeDirectories as $dir) {
            $dirPath = $path . DIRECTORY_SEPARATOR . $dir;
            if (false === is_dir($dirPath)) {
                throw new \Exception($dirPath . ' not exists');
            }
            $iterator = new \RecursiveIteratorIterator(
                new  \RecursiveDirectoryIterator($dirPath), \RecursiveIteratorIterator::SELF_FIRST);
            foreach($iterator as $item) {
                chmod($item, 0777);
            }
        }
        umask($mask);
        return $this;
    }

    /**
     *
     *
     * @param $path
     * @param int $mode
     * @return bool
     * @throws \Exception
     */
    public function chmodRecursive($path, $mode = 0777) {
        if (is_dir($path) === false) {
            throw new \Exception($path . ' directory not found');
        }
        $mask = umask(0);
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::SELF_FIRST);
        foreach($iterator as $item) {
            if (false === chmod($item, $mode)) {
                throw new \Exception($item . " chmod error");
            }
        }
        umask($mask);
        return true;
    }
}