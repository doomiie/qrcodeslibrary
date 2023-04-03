<?php

/**
 * Objects for qrcode project
 * ObjectWynikdla 1015
 * 
 * @see       https://github.com/doomiie/gps/
 *
 *
 * @author    Jerzy Zientkowski <jerzy@zientkowski.pl>
 * @copyright 2020 - 2023 Jerzy Zientkowski
 * @license   FIXME need to have a licence
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace qrcodeslibrary;

use Database\DBObject2;

class wynikFields extends DBObject2
{
    protected $tableName = "1015wynik";
    public $elementId;
    public $wynik;
    public $numerProtokolu;
    public $typWielkoscNiezgodnosci;
    public $typ;

}
class wynikFunctions extends wynikFields
{
    public function returnTableArray()
    {
        $paramString = "";
        return $paramString;
    }
  
   
    public function printDIV()
    {
        return  parent::printDIV();
    }
    public function printStructure()
    {
        return "<div class='text-primary'>STRUCTURE</div>";
    }
}
class ObjectWynik extends wynikFunctions
{
}
