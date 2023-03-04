<?php
/**
 * QR Traits for QR classess
 * TraitFromArray loads data from array into the class object 
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

trait TraitFromArray
{
    public static function fromArray(array $data = [])
    {
        foreach (get_object_vars($obj = new self) as $property => $default) {
            if (!array_key_exists($property, $data)) continue;
            $obj->{$property} = $data[$property];
        }
        return $obj;
    }

    public function fromArrayObject(array $data = [])
    {
        foreach (get_object_vars($this) as $property => $default) {
            if (!array_key_exists($property, $data)) continue;
            $this->{$property} = $data[$property];
            unset($data[$property]);
        }
        if(0==count($data))
        return $this;
        return "Excessive data in DB: " . json_encode($data);
    }
    
}

?>