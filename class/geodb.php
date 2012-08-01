<?php // vi: set fenc=utf-8 ts=4 sw=4 et:
/*
 * Copyright (C) 2012 Nicolas Grekas - p@tchwork.com
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the (at your option):
 * Apache License v2.0 (http://apache.org/licenses/LICENSE-2.0.txt), or
 * GNU General Public License v2.0 (http://gnu.org/licenses/gpl-2.0.txt).
 */


class geodb
{
    static $db;

    static function __init()
    {
        self::$db = new PDO('sqlite:' . patchworkPath('data/geodb.sqlite3'));
    }

    static function getCityId($city)
    {
        $sql = self::$db->quote(lingua::getKeywords($city));
        $sql = "SELECT city_id
                FROM city
                WHERE search={$sql}
                LIMIT 1";
        $sql = self::$db->query($sql)->fetchAll(PDO::FETCH_NUM);
        return $sql ? $sql[0][0] : 0;
    }

    static function getCityInfo($city_id)
    {
        $sql = "SELECT c.city_id AS city_id,
                        city,
                        latitude,
                        longitude,
                        country,
                        div1,
                        div2,
                        r.zipcode AS divcode
                FROM city c JOIN region r
                    ON r.region_id=c.region_id
                WHERE city_id={$city_id}
                LIMIT 1";
        $sql = self::$db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $sql ? $sql[0] : false;
    }
}
