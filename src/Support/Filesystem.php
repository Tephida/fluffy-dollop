<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Support;

class Filesystem
{
    /**
     * Create dir
     * @param string $dir
     * @param int $mode
     * @return bool
     */
    public static function createDir(string $dir, int $mode = 0777): bool
    {
        if (!is_dir($dir) && !mkdir($dir, $mode, true) && !is_dir($dir)) {
            throw new InvalidArgumentException("Unable to create directory '$dir' with mode ");
            return false;
        }

        return true;
    }

    /**
     * Delete file OR directory
     * @param string $file
     * @return bool
     */
    public static function delete(string $file): bool
    {
        if (is_dir($file)) {
            if (!str_ends_with($file, '/')) {
                $file .= '/';
            }
            $files = glob($file . '*', GLOB_MARK);
            foreach ($files as $file_) {
                if (is_dir($file_)) {
                    self::delete($file_);
                } else {
                    unlink($file_);
                }
            }
            if (is_dir($file)) {
                rmdir($file);
                return true;
            }
            return false;
        } else if (is_file($file)) {
            unlink($file);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Ceck file or dir
     * @param string $file
     * @return bool
     */
    public static function check(string $file): bool
    {
        if (is_file($file)) {
            return true;
        }
        if (is_dir($file)) {
            return true;
        }
        return false;
    }

    public static function copy(string $from, string $to): bool
    {
        if (is_file($from) && !is_file($to)) {
            return copy($from, $to);
        }
        return false;
    }

    public static function dirSize(string $directory): bool|int
    {
        if (!is_dir($directory)) {
            return -1;
        }
        $size = 0;
        if ($DIR = opendir($directory)) {
            while (($dir_file = readdir($DIR)) !== false) {
                if (is_link($directory . '/' . $dir_file) || $dir_file == '.' || $dir_file == '..') {
                    continue;
                }
                if (is_file($directory . '/' . $dir_file)) {
                    $size += filesize($directory . '/' . $dir_file);
                } else if (is_dir($directory . '/' . $dir_file)) {
                    $dirSize = self::dirSize($directory . '/' . $dir_file);
                    if ($dirSize >= 0) {
                        $size += $dirSize;
                    } else {
                        return -1;
                    }
                }
            }
            closedir($DIR);
        }
        return $size;
    }

    public static function formatsize(int|string $file_size): string
    {
        if ($file_size >= 1073741824) {
            $file_size = round($file_size / 1073741824 * 100) / 100 . " Gb";
        } elseif ($file_size >= 1048576) {
            $file_size = round($file_size / 1048576 * 100) / 100 . " Mb";
        } elseif ($file_size >= 1024) {
            $file_size = round($file_size / 1024 * 100) / 100 . " Kb";
        } else {
            $file_size .= " b";
        }
        return $file_size;
    }
}