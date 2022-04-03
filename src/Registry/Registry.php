<?php

/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Registry;

use RuntimeException;

class Registry
{
    /**
     * List of all callbacks
     *
     * @var callable[]
     */
    protected $callbacks = [];

    /**
     * List of all fresh resources
     *
     * @var array
     */
    protected $fresh = [];

    /**
     * List of all connections
     *
     * @var array
     */
    protected $registry = [
        'default' => [],
    ];

    /**
     * Current context
     *
     * @var string
     */
    protected $context = 'default';

    /**
     * Set a new connection callback
     *
     * @param string $name
     * @param callable $callback
     * @param bool $fresh
     * @return $this
     * @throws RuntimeException
     */
    public function set(string $name, callable $callback, bool $fresh = false): self
    {
        if (\array_key_exists($name, $this->registry[$this->context])) {
            unset($this->registry[$this->context][$name]);
        }

        $this->fresh[$name] = $fresh;
        $this->callbacks[$name] = $callback;

        return $this;
    }

    /**
     * If connection has been created returns it, otherwise create and than return it
     *
     * @param string $name
     * @param bool $fresh
     * @return mixed
     * @throws RuntimeException
     */
    public function get(string $name, bool $fresh = false)
    {
        if (!\array_key_exists($name, $this->registry[$this->context]) || $fresh || $this->fresh[$name]) {
            if (!\array_key_exists($name, $this->callbacks)) {
                throw new \RuntimeException('No callback named "' . $name . '" found when trying to create connection');
            }

            $this->registry[$this->context][$name] = $this->callbacks[$name]();
        }

        return $this->registry[$this->context][$name];
    }

    /**
     * Set the current context
     */
    public function context(string $name): self
    {
        if (!array_key_exists($name, $this->registry)) {
            $this->registry[$name] = [];
        }

        $this->context = $name;

        return $this;
    }
}