<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

/**
 * @param string $source
 * @param int $substr_num
 * @param bool $strip_tags
 * @return string
 */
function textFilter(string $source, int $substr_num = 25000, bool $strip_tags = false): string
{
    $source = trim($source);
    $source = stripslashes($source);
    if (empty($source)) {
        return '';
    } else {
        return htmlspecialchars($source, ENT_QUOTES, 'UTF-8');
    }

}

/**
 * @param string $source
 * @param int $default
 * @return int
 */
function intFilter(string $source, int $default = 0): int
{
    if (isset($_POST[$source])) {
        $source = $_POST[$source];
    } elseif (isset($_GET[$source])) {
        $source = $_GET[$source];
    } else {
        return $default;
    }
    return (int)$source;
}

/**
 * @param string $source
 * @param int $substr_num
 * @param bool $strip_tags
 * @return string|array
 */
function requestFilter(string $source, int $substr_num = 25000, bool $strip_tags = false): string|array
{
    if (empty($source)) {
        return '';
    }
    if (!empty($_POST[$source])) {
        if (is_array($_POST[$source])) {
            return $_POST[$source];
        }
        $source = $_POST[$source];
    } elseif (!empty($_GET[$source])) {
        if (is_array($_GET[$source])) {
            return $_POST[$source];
        }
        $source = $_GET[$source];
    } else {
        return '';
    }
    return textFilter($source, $substr_num, $strip_tags);
}