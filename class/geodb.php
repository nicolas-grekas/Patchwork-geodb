<?php /***************** vi: set fenc=utf-8 ts=4 sw=4 et: ******************
 *
 *   Copyright : (C) 2012 Nicolas Grekas. All rights reserved.
 *   Email     : p@tchwork.org
 *   License   : http://www.gnu.org/licenses/agpl.txt GNU/AGPL
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as
 *   published by the Free Software Foundation, either version 3 of the
 *   License, or (at your option) any later version.
 *
 ***************************************************************************/


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
