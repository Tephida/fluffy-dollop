<?php
/*
 * Copyright (c) 2022. Semen Alekseev
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Support;

use FluffyDollop\Support\Mysql;

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
     * @return string|bool|array|\FluffyDollop\Support\Mysql|null
     */
    public static function get(mixed $name): string|bool|null|array|Mysql
    {
        return (isset(self::$store[$name])) ? self::$store[$name] : null;
    }

    /**
     * Сохраняет данные по ключу в статическом хранилище
     *
     * @param string $name
     * @param mixed $obj
     * @return string
     */
    public static function set(mixed $name, mixed $obj): mixed
    {
        return self::$store[$name] = $obj;
    }
}