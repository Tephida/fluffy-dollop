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

/**
 * @param string $value
 * @param bool $lower
 * @param bool $part
 * @return array|string|null
 */
function to_translit(string $value, bool $lower = true, bool $part = true): array|string|null
{
    $lang_translit = [
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => '', 'ы' => 'y', 'ъ' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        "ї" => "yi", "є" => "ye",

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        "Ї" => "yi", "Є" => "ye",
    ];
    $value = str_replace(".php", "", $value);
    $value = trim(strip_tags($value));
    $value = preg_replace("/\s+/ms", "-", $value);
    $value = strtr($value, $lang_translit);
    if ($part) {
        $value = preg_replace("/[^a-z0-9\_\-.]+/mi", "", $value);
    } else {
        $value = preg_replace("/[^a-z0-9\_\-]+/mi", "", $value);
    }
    $value = preg_replace('#[\-]+#i', '-', $value);
    if ($lower) {
        $value = strtolower($value);
    }
    if (strlen($value) > 200) {
        $value = substr($value, 0, 200);
        if (($temp_max = strrpos($value, '-'))) {
            $value = substr($value, 0, $temp_max);
        }
    }
    return $value;
}

/**
 * @param $format
 * @param $stamp
 * @return string
 */
function langdate(string $format, $stamp): string
{
    $langdate = [
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
    return strtr(date($format, (int)$stamp), $langdate);
}

/**
 * @param $text
 * @return array|string
 */
function strip_data($text): array|string
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
 * @param $id
 * @param $options
 * @return array|string
 */
function installationSelected($id, $options): array|string
{
    return str_replace('value="' . $id . '"', 'value="' . $id . '" selected', $options);
}

/**
 * @param $id
 * @return array
 */
function xfieldsdataload(string $id): array
{
    $x_fields_data = explode("||", $id);
    $end = array_key_last($x_fields_data);
    if (!$x_fields_data[$end]) {
        unset($x_fields_data[$end]);
    }

    $data = [];
    foreach ($x_fields_data as $x_field_data) {
        list ($x_field_data_name, $x_field_data_value) = explode("|", $x_field_data);
        $x_field_data_name = str_replace(["&#124;", "__NEWL__"], ["|", "\r\n"], $x_field_data_name);
        $x_field_data_value = str_replace(["&#124;", "__NEWL__"], ["|", "\r\n"], $x_field_data_value);
        $data[$x_field_data_name] = trim($x_field_data_value);
    }
    return $data;
}

/**
 * @return array|false|void
 */
function profileload()
{
    $path = ENGINE_DIR . '/data/xfields.txt';
    $filecontents = file($path);
    if (!is_array($filecontents)) {
        exit('Невозможно загрузить файл');
    }
    foreach ($filecontents as $name => $value) {
        $filecontents[$name] = explode("|", trim($value));
        foreach ($filecontents[$name] as $name2 => $value2) {
            $value2 = str_replace(["&#124;", "__NEWL__"], ["|", "\r\n"], $value2);
            $filecontents[$name][$name2] = $value2;
        }
    }
    return $filecontents;
}

function checkAjax(): bool
{
    return !empty($_POST['ajax']) && $_POST['ajax'] == 'yes';
}


/**
 * @param int|null $date
 * @param bool $func
 * @param bool $full
 * @return string
 */
function megaDate(?int $date, bool $func = false, bool $full = false): string
{
    $server_time = Registry::get('server_time');
    if (date('Y-m-d', $date) === date('Y-m-d', $server_time)) {
        return langdate('сегодня в H:i', $date);
    } elseif (date('Y-m-d', $date) === date('Y-m-d', ($server_time - 84600))) {
        return langdate('вчера в H:i', $date);
    } elseif ($func) {
        //no_year
        return langdate('j M в H:i', $date);
    } elseif ($full) {
        return langdate('j F Y в H:i', $date);
    } else {
        return langdate('j M Y в H:i', $date);
    }
}

/**
 * @param int $number
 * @param array $titles
 * @return mixed
 */
function declOfNum(int $number, array $titles): string
{
    $cases = [2, 0, 1, 1, 1, 2];
    return $titles[($number % 100 > 4 and $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

/**
 * @param $num
 * @param $a
 * @param $b
 * @param $c
 * @param bool $t
 * @return mixed
 */
function newGram($num, $a, $b, $c, bool $t = false): string
{
    if ($t) {
        return declOfNum($num, [sprintf($a, $num), sprintf($b, $num), sprintf($c, $num)]);
    } else {
        return declOfNum($num, [sprintf("%d {$a}", $num), sprintf("%d {$b}", $num), sprintf("%d {$c}", $num)]);
    }
}

/**
 * @throws JsonException
 */
function _e_json(array $value): void
{
    header('Content-Type: application/json');
    echo json_encode($value, JSON_THROW_ON_ERROR);
}
