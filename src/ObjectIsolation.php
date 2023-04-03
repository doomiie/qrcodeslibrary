<?php

/**
 * Objects for qrcode project
 * ObjectQrcode
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
use ObjectCut as GlobalObjectCut;

class isolationFields extends DBObject2
{
    protected $tableName = "isolation";
    public $elementId;
    public $rodzajIzolacji = '';
    public $numerPartii = '';
    public $temperaturaOtoczenia = '';


}
class isolationFunctions extends isolationFields
{

    public function returnTableArray()
    {

        /**!SECTION
         *        $inputArray[] = "Rodzaj izolacji";
                $inputArray[] = "Numer partii";
                $inputArray[] = "Temperatura otoczenia";
         */
    //$elementOriginal = new ObjectElement($this->elementId);
    $paramString["Akcje"] = "sooner". PageElements::addButtonViewItemInTable('joint', $this->elementId);
    $paramString["Rodzaj izolacji"] = $this->rodzajIzolacji;
    $paramString["Numer partii"] = $this->numerPartii;
    $paramString["Temperatura otoczenia"] = $this->temperaturaOtoczenia;

    $paramString = array_merge(parent::returnTableArray(),$paramString);
    return $paramString;
    }

    
}
class ObjectIsolation extends isolationFunctions
{
}
