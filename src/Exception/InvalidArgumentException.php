<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Exception;

class InvalidArgumentException extends AbstractException
{
    public function __construct(string|false $message = false, $code = 500)
    {
        if (!$message) {
            $message = "We encountered an internal error. Please try again.";
        }
        parent::__construct($message, $code);
    }

}