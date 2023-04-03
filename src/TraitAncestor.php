<?php

/**
 * QR Traits for QR classess
 * Database functions
 * 
 * @see       https://github.com/doomiie/gps/
 *
 *
 * @author    Jerzy Zientkowski <jerzy@zientkowski.pl>
 * @copyright 2020 - 2022 Jerzy Zientkowski
 * @license   FIXME need to have a licence
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace qrcodeslibrary;

use Database\ObjectUser;

trait TraitAncestor
{

    /**
     * Jeśli obiekt jest kopią poprzedniego, tutaj trzymamy, kto jest naszym przodkiem
     * Dzięki temu możemy 'wędrować' po liście przodków
     *
     * @var int
     */
    public $ancestorID = -1;

    public function generateAncestor(array $params = null)
    {
        $className = get_class($this);
        $ancestor = new $className($this->id);
        $ancestor->id = null;
        $ancestor->ancestorID = $this->id;

        foreach ($params as $key => $value) {
            if (property_exists($ancestor, $key)) {
                $ancestor->$key = $value;
            }
        }

        return $ancestor->saveToDB();
    }

    public function getAncestor()
    {
        $className = get_class($this);
        $ancestor = new $className($this->ancestorID);
        return $ancestor;
    }
}
