<?php

namespace VersionPress\Utils;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Traversable;

/**
 * Helper functions to work with filesystem. Currently, the functions use either bare implementation
 * or {@link http://symfony.com/doc/master/components/filesystem/introduction.html Symfony Filesystem}.
 */
class FileSystem
{

    /**
     * Renames (moves) origin to target.
     *
     * @see SymfonyFilesystem::rename()
     *
     * @param string $origin
     * @param string $target
     * @param bool $overwrite
     */
    public static function rename($origin, $target, $overwrite = false)
    {
        $fs = new SymfonyFilesystem();
        $fs->rename($origin, $target, $overwrite);
    }

    /**
     * Removes a file / directory. Works recursively.
     *
     * @see SymfonyFilesystem::remove()
     *
     * @param string|Traversable $path Path to a file or directory.
     */
    public static function remove($path)
    {
        $fs = new SymfonyFilesystem();
        $fs->remove($path);
    }

    /**
     * Removes the content of a directory (not the directory itself). Works recursively.
     *
     * @param string $path Path to a directory.
     */
    public static function removeContent($path)
    {

        if (!is_dir($path)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $fs = new SymfonyFilesystem();
        $fs->remove($iterator);
    }

    /**
     * Copies a file. Uses Symfony's copy but actually honors the third parameter.
     *
     * @param string $origin
     * @param string $target
     * @param bool $override
     */
    public static function copy($origin, $target, $override = false)
    {
        $fs = new SymfonyFilesystem();

        if (!$override && $fs->exists($target)) {
            return;
        }

        $fs->copy($origin, $target, $override);
    }

    /**
     * Copies a directory. Uses Symfony's mirror() under the cover.
     *
     * @see SymfonyFilesystem::mirror()
     *
     * @param string $origin
     * @param string $target
     */
    public static function copyDir($origin, $target)
    {
        $fs = new SymfonyFilesystem();
        $fs->mirror($origin, $target);
    }

    /**
     * Creates a directory with usual permissions.
     *
     * @param string $dir
     * @param int $mode
     */
    public static function mkdir($dir, $mode = 0750)
    {
        $fs = new SymfonyFilesystem();
        $fs->mkdir($dir, $mode);
    }

    /**
     * Compares two files and returns true if their contents is equal.
     *
     * @param $file1
     * @param $file2
     * @return bool
     */
    public static function filesHaveSameContents($file1, $file2)
    {
        return sha1_file($file1) === sha1_file($file2);
    }
}
