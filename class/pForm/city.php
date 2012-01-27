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
