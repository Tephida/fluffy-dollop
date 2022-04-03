<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Support;

/**
 * Registry
 */
class Registry
{
    /** Статическое хранилище для данных */
    protected static array $store = [];

    /** Защита от создания экземпляров статического класса */
    protected function __construct()
    {
    }

    /**  */
    protected function __clone()
    {
    }

    /**
     * Проверяет существуют ли данные по ключу
     *
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return isset(self::$store[$name]);
    }

    /**
     * Возвращает данные по ключу или null, если не данных нет
     *
     * @param string $name
     * @return string|bool|array|Mysql|null
     */
    public static function get(mixed $name): string|bool|array|Mysql
    {
        return self::$store[$name];
    }

    /**
     * Сохраняет данные по ключу в статическом хранилище
     *
     * @param string $name
     * @param mixed $obj
     * @return string
     */
    public static function set(string $name, mixed $obj): mixed
    {
        return self::$store[$name] = $obj;
    }
}