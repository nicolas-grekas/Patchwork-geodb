<?php // vi: set fenc=utf-8 ts=4 sw=4 et:
/*
 * Copyright (C) 2012 Nicolas Grekas - p@tchwork.com
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the (at your option):
 * Apache License v2.0 (http://apache.org/licenses/LICENSE-2.0.txt), or
 * GNU General Public License v2.0 (http://gnu.org/licenses/gpl-2.0.txt).
 */


class agent_live_city extends agent
{
    public $get = 'q';

    protected $maxage = -1;

    function control() {}

    function compose($o)
    {
        $db = patchworkPath('data/geodb.sqlite3');
        $db = new PDO("sqlite:{$db}");

        $sql = $this->get->q;
        $sql = ('*' == $sql ? '' : lingua::getKeywords($sql));
        $sql = substr($db->quote($sql), 1, -1);

        switch ($a = substr($sql, 0, 3))
        {
        case 'agi':
        case 'ayi':
            if ('os ' == substr($sql, 3, 3))
            {
                $sql = substr($sql, 5);
                $sql = "search GLOB 'agios{$sql}*' OR search GLOB 'ayios{$sql}*'";
                break;
            }

        case 'st ': $sql = 'saint ' . substr($sql, 3);
        default: $sql = '' === $sql ? 1 : "search GLOB '{$sql}*'";
        }

        $sql = "SELECT city_id, city FROM city WHERE {$sql} ORDER BY rowid";

        $o->cities = new loop_city_($db, $sql, 15);

        return $o;
    }
}

class loop_city_ extends loop
{
    protected $db;
    protected $sql;
    protected $limit;

    protected $prevId;
    protected $count;
    protected $result;

    function __construct($db, $sql, $limit)
    {
        $this->db = $db;
        $this->sql = $sql;
        $this->limit = $limit + 1;
    }

    protected function prepare()
    {
        $this->prevId = 0;
        $this->count = $this->limit;
        $this->result = $this->db->query($this->sql);

        return -1;
    }

    protected function next()
    {
        if (--$this->count) for (;;)
        {
            if ($data = $this->result->fetch(PDO::FETCH_ASSOC))
            {
                if ($data['city_id'] != $this->prevId)
                {
                    $this->prevId = $data['city_id'];
                    return (object) array('city' => $data['city']);
                }
            }
            else break;
        }

        $this->result->closeCursor();

        unset($this->result);
    }
}
