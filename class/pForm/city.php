<?php // vi: set fenc=utf-8 ts=4 sw=4 et:
/*
 * Copyright (C) 2012 Nicolas Grekas - p@tchwork.com
 *
 * This library is free software; you can redistribute it and/or modify it
 * under the terms of the (at your option):
 * Apache License v2.0 (http://apache.org/licenses/LICENSE-2.0.txt), or
 * GNU General Public License v2.0 (http://gnu.org/licenses/gpl-2.0.txt).
 */


class pForm_city extends pForm_QSelect
{
    protected $src = 'QSelect/city';

    protected function init(&$param)
    {
        if (isset($param['default']))
        {
            $a = strpos($param['default'], ':');

            if (false !== $a) $param['default'] = substr($param['default'], $a + 1);
        }

        parent::init($param);

        if (!$this->value)
        {
            $a = strpos($this->value, ':');

            if (false !== $a) $this->value = str_replace($this->value, ':', '_');
        }
    }

    function getDbValue()
    {
        if ($this->value)
        {
            $value = geodb::getCityId($this->value) . ':' . $this->value;
        }
        else $value = '0:';

        return $value;
    }
}
