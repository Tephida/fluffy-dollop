<?php

/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Contracts\Filesystem;

interface FilesystemInterface
{
    public static function createDir(string $dir, int $mode = 0777): bool;
    public static function delete(string $file): bool;
    public static function check(string $file): bool;
    public static function copy(string $from, string $to): bool;
    public static function dirSize(string $directory): bool|int;
    public static function humanFileSize(int $bytes, int $decimals = 1): string;
}
