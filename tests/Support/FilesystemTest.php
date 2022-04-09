<?php

/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\tests\Support;

use FluffyDollop\Support\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testCreateDir()
    {
        $dir = __DIR__;
        $instance = Filesystem::createDir($dir . '/test/');
        self::assertEquals(true, $instance);
        $instance = Filesystem::createDir($dir . '/');
        self::assertEquals(true, $instance);
        $instance = Filesystem::createDir($dir . '/test/');
        self::assertEquals(true, $instance);
    }

    public function testCheck()
    {
        $dir = __DIR__;
        $instance = Filesystem::check($dir . '/test/');
        self::assertEquals(true, $instance);
        $instance = Filesystem::check($dir . '/fail/');
        self::assertEquals(false, $instance);
    }

    public function testFormatsize()
    {
        $instance = Filesystem::formatsize('500000');
        self::assertEquals('488.28 Kb', $instance);
        $instance = Filesystem::formatsize('300000');
        self::assertEquals('292.97 Kb', $instance);
        $instance = Filesystem::formatsize('0');
        self::assertEquals('0 b', $instance);
    }

    public function testCopy()
    {
        $dir = __DIR__;
        file_put_contents($dir . "/test/qwerty.php", 'qwerty');
        if (Filesystem::check($dir . '/test/qwerty2.php')) {
            Filesystem::delete($dir . '/test/qwerty2.php');
        }
        $instance = Filesystem::copy($dir . "/test/qwerty.php", $dir . "/test/qwerty2.php");
        self::assertEquals(true, $instance);
        $instance = Filesystem::copy($dir . "/test/qwerty.php", $dir . "/test/qwerty2.php");
        self::assertEquals(false, $instance);
    }

    public function testDirSize()
    {
        $dir = __DIR__;
        $instance = Filesystem::dirSize($dir . '/test/');
        self::assertEquals(12, $instance);
        $instance = Filesystem::dirSize($dir . '/qwerty/');
        self::assertEquals(-1, $instance);
    }

    public function testDelete()
    {
        $dir = __DIR__;
        $instance = Filesystem::delete($dir . '/test/');
        self::assertEquals(true, $instance);
        $instance = Filesystem::delete($dir . '/test2/');
        self::assertEquals(false, $instance);
    }
}
