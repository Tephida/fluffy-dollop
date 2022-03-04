<?php
/*
 * Copyright (c) 2022 Tephida
 *
 *  For the full copyright and license information, please view the LICENSE
 *   file that was distributed with this source code.
 *
 */

namespace FluffyDollop\Security;

use FluffyDollop\Support\Registry;

class AntiSpam
{
    /** @var int Лимиты новых друзей на день */
    private static int $max_friends = 40;
    /** @var int Максимум сообщений не друзьям */
    private static int $max_msg = 40;
    /** @var int Максимум записей на стену */
    private static int $max_wall = 10;
    /** @var int Максимум одинаковых текстовых данных */
    private static int $max_identical = 10;
    /** @var int Максимум комментариев к записям на стенах людей и сообществ */
    private static int $max_comm = 100;
    /** @var int Максимум сообществ за день */
    private static int $max_groups = 5;

    /** @var int Максимум альбомов за день */
    private static int $max_albums = 5;
    private static int $max_album_photos = 5;
    private static int $max_music = 5;
    private static int $max_doc = 5;
    private static int $max_group_forum = 5;
    private static int $max_group_forum_msg = 5;
    private static int $max_notes = 5;
    private static int $max_videos = 5;
    private static int $max_support = 5;

    private static array $types = array(
        'friends' => 1,
        'messages' => 2,
        'wall' => 3,
        'identical' => 4,
        'comments' => 5,
        'groups' => 6,
        'albums' => 7,
        'music' => 8,
        'doc' => 9,
        'group_forum' => 10,
        'group_forum_msg' => 1,
        'notes' => 12,
        'videos' => 13,
        'support' => 14,
    );

    public static function limit(string $act): int
    {
        if ($act === 'friends') {
            return self::$max_friends;
        }
        if ($act === 'messages') {
            return self::$max_msg;
        }
        if ($act === 'wall') {
            return self::$max_wall;
        }
        if ($act === 'identical') {
            return self::$max_identical;
        }
        if ($act === 'comments') {
            return self::$max_comm;
        }
        if ($act === 'groups') {
            return self::$max_groups;
        }
        if ($act === 'albums') {
            return self::$max_albums;
        }
        if ($act === 'album_photos') {
            return self::$max_album_photos;
        }
        if ($act === 'music') {
            return self::$max_music;
        }
        if ($act === 'doc') {
            return self::$max_doc;
        }
        if ($act === 'group_forum') {
            return self::$max_group_forum;
        }
        if ($act === 'group_forum_msg') {
            return self::$max_group_forum_msg;
        }
        if ($act === 'notes') {
            return self::$max_notes;
        }
        if ($act === 'videos') {
            return self::$max_videos;
        }
        if ($act === 'support') {
            return self::$max_support;
        }
        return 0;
    }

    /**
     * @param string $act
     * @param false|string $text
     * @return void
     */
    public static function check(string $act, false|string $text = false): void
    {
        $user_info = Registry::get('user_info');
        $db = Registry::get('db');
        if ($text) {
            $text = md5($text);
        }

        //спам дата
        $antiDate = date('Y-m-d', time());
        $antiDate = strtotime($antiDate);

        $action = self::$types[$act];
        $limit = self::limit($act);

        //Проверяем в таблице
        $check = $db->super_query("SELECT COUNT(*) AS cnt FROM `antispam` WHERE act = '{$action}' AND user_id = '{$user_info['user_id']}' AND date = '{$antiDate}' AND txt = '{$text}'");
        //Если кол-во, логов больше, то ставим блок
        if ($check['cnt'] >= $limit) {
            die('antispam_err');
        }
    }

    /**
     * @param string $act
     * @return int
     */
    private static function getType(string $act): int
    {
        return self::$types[$act];
    }

    /**
     * @param string $act
     * @param bool $text
     * @return void
     */
    public static function logInsert(string $act, bool|string $text = false): void
    {
        $user_info = Registry::get('user_info');
        $db = Registry::get('db');
        $text = (is_string($text) and !empty($text)) ? md5($text) : '';
        $server_time = date('Y-m-d', time());
        $antiDate = strtotime($server_time);
        $act_num = self::getType($act);
        $db->query("INSERT INTO `antispam` SET act = '{$act_num}', user_id = '{$user_info['user_id']}', date = '{$antiDate}', txt = '{$text}'");
    }
}