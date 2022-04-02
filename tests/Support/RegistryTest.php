<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace Support;

use FluffyDollop\Support\Registry;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{

    final public function testSet(): void
    {
        $instance = Registry::set('ttt', 'qwerty');
        self::assertEquals('qwerty', $instance);
        Registry::set('ttt', 'word');
        Registry::set('1', 123);
        Registry::set('2', ['fff' => 12]);
        self::assertTrue(true);
    }

    final public function testGet(): void
    {
        $instance = Registry::get('ttt');
        self::assertEquals('word', $instance);
        $instance = Registry::get('fail');
        self::assertEquals(null, $instance);
    }

    final public function testExists(): void
    {
        $instance = Registry::get('ttt');
        self::assertEquals('ttt', $instance);
        $instance = Registry::get('fail');
        self::assertEquals(null, $instance);
    }
}
