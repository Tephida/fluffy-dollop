<?php

/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

use FluffyDollop\Support\Registry;

/**
 * @param string $input_text
 * @param int $substr_num
 * @param bool $strip_tags
 * @return string
 */
function textFilter(string $input_text, int $substr_num = 25000, bool $strip_tags = false): string
{
    if (empty($input_text)) {
        return '';
    }

    if ($strip_tags) {
        $input_text = strip_tags($input_text);
    }
    $input_text = trim($input_text);
    $input_text = stripslashes($input_text);
    $input_text = str_replace(PHP_EOL, '<br>', $input_text);
    return htmlspecialchars($input_text, ENT_QUOTES, 'UTF-8');
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
 * @return string
 */
function requestFilter(string $source, int $substr_num = 25000, bool $strip_tags = false): string
{
    if (empty($source)) {
        return '';
    }
    if (!empty($_POST[$source])) {
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

/**
 * todo update
 * @param string $format
 * @param int $stamp
 * @return string
 */
function langDate(string $format, int $stamp): string
{
    $lang_date = [
        'January' => "января",
        'February' => "февраля",
        'March' => "марта",
        'April' => "апреля",
        'May' => "мая",
        'June' => "июня",
        'July' => "июля",
        'August' => "августа",
        'September' => "сентября",
        'October' => "октября",
        'November' => "ноября",
        'December' => "декабря",
        'Jan' => "янв",
        'Feb' => "фев",
        'Mar' => "мар",
        'Apr' => "апр",
        'Jun' => "июн",
        'Jul' => "июл",
        'Aug' => "авг",
        'Sep' => "сен",
        'Oct' => "окт",
        'Nov' => "ноя",
        'Dec' => "дек",

        'Sunday' => "Воскресенье",
        'Monday' => "Понедельник",
        'Tuesday' => "Вторник",
        'Wednesday' => "Среда",
        'Thursday' => "Четверг",
        'Friday' => "Пятница",
        'Saturday' => "Суббота",

        'Sun' => "Вс",
        'Mon' => "Пн",
        'Tue' => "Вт",
        'Wed' => "Ср",
        'Thu' => "Чт",
        'Fri' => "Пт",
        'Sat' => "Сб",
    ];
    return strtr(date($format, $stamp), $lang_date);
}

/**
 * @param string $text
 * @return string
 */
function strip_data(string $text): string
{
    $quotes = [
        "\x27", "\x22", "\x60", "\t", "\n", "\r", "'", ",", "/", ";", ":", "@", "[", "]", "{", "}", "=", ")",
        "(", "*", "&", "^", "%", "$", "<", ">", "?", "!", '"'];
    $good_quotes = ["-", "+", "#"];
    $rep_quotes = ["\-", "\+", "\#"];
    $text = stripslashes($text);
    $text = trim(strip_tags($text));
    return str_replace([...$quotes, ...$good_quotes], ['', ...$rep_quotes], $text);
}


/**
 * @param string $id
 * @param string $options
 * @return string
 */
function installationSelected(string $id, string $options): string
{
    return str_replace('value="' . $id . '"', 'value="' . $id . '" selected', $options);
}

function checkAjax(): bool
{
    return !empty($_POST['ajax']) && $_POST['ajax'] === 'yes';
}


/**
 * @param int $date
 * @param bool $func
 * @param bool $full
 * @return string
 */
function megaDate(int $date, bool $func = false, bool $full = false): string
{
    if (date('Y-m-d', $date) === date('Y-m-d', time())) {
        return langDate('сегодня в H:i', $date);
    } elseif (date('Y-m-d', $date) === date('Y-m-d', (time() - 84600))) {
        return langDate('вчера в H:i', $date);
    } elseif ($func) {
        //no_year
        return langDate('j M в H:i', $date);
    } elseif ($full) {
        return langDate('j F Y в H:i', $date);
    } else {
        return langDate('j M Y в H:i', $date);
    }
}

/**
 * @param int $number
 * @param array<int> $titles
 * @return string
 */
function declOfNum(int $number, array $titles): string
{
    $cases = [2, 0, 1, 1, 1, 2];
    return (string)$titles[($number % 100 > 4 and $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

/**
 * @throws JsonException
 */
function _e_json(mixed $value): void
{
    header('Content-Type: application/json');
    echo json_encode($value, JSON_THROW_ON_ERROR);
}
