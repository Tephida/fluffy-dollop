<?php

/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Exception;

use JetBrains\PhpStorm\Pure;

class InvalidArgumentException extends AbstractException
{
    #[Pure] public function __construct(string|false $message = false, $code = 500)
    {
        if (!$message) {
            $message = "We encountered an internal error. Please try again.";
        }
        parent::__construct($message, $code);
    }
}
