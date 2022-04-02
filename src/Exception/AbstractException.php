<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Exception;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * A Abstration of Excecption to include the __toString function
 *
 */
class AbstractException extends Exception
{
    private string $_soapFault;

    /**
     * @param string|false $message Error description $message
     * @param string|false $code HTTP Error code $code
     */
    #[Pure] public function __construct(string|false $message = false, $code = false)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function getSoapFault(): string
    {
        return $this->_soapFault;
    }

    /**
     * @param string $soapFault
     * @return string
     */
    public function setSoapFault(string $soapFault): string
    {
        $this->_soapFault = $soapFault;
        return $soapFault;
    }

    /**
     * Returns a formatted string of the error code and message
     *
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}