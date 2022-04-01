<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testInput()
    {
        $instance_1 = textFilter('qwerty');

        $instance_2 = textFilter('<div>qwerty' . PHP_EOL . 't`tt<div>');

        $instance_3 = textFilter('<div>t`tt<div>', 2500, true);

        self::assertTrue(true);
    }

    public function testInt()
    {
        $instance = intFilter('qwerty');
        self::assertEquals(0, $instance);
    }

    public function testRequestFilter()
    {
        $instance = requestFilter('qwerty');
        self::assertEquals('', $instance);

    }
}
