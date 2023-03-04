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

class bendFields extends DBObject2
{
    protected $tableName = "bend";
    public $elementId = -1;
    public $oznaczenieLuku;
    public $kat;
    public $promienGiecia;
    public $P;
    public $S;
    public $K;
    

    
}
class bendFunctions extends bendFields
{
    public function printDIV()
    {
        return  parent::printDIV();
    }

    public function returnTableArray()
    {
        //return parent::returnTableArray();
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
     
        $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->numerRury . "/" . $elementOriginal->wytop);
        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    public function returnTableArray2($globalKey)
    {
        
        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        $paramString["Lp."] = $globalKey;
        $paramString["Data"] = $this->time_added;
        $paramString["Numer punktu załamania Pz / Pp"] = "Brak danych";
        
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Nr wytopu"] = $elementOriginal->wytop;
        $paramString["Nr rury"] = $elementOriginal->numerRury;
        $paramString["Grubość ścianki (mm)"] = $elementOriginal->gruboscScianki;
        $paramString["Rodzaj izolacji"] = $elementOriginal->rodzajIzolacji;
        $paramString["Rodzaj łuku {V,h}"] = "Brak danych";
        $paramString["Promień gięcia"] = $this->promienGiecia;
        $paramString["Długość (m)"] = $this->promienGiecia;
        // TODO trzeba dać kilometraż do gięcia, cz
        $paramString["Km trasy"] = "Tu kilometraż gięcia czy rury?";
        $paramString["Kąt wg PT (⁰)"] = "Nie wiem, co tu dać";
        $paramString["Kąt wygiety (⁰)"] = "Nie wiem, co tu dać?";
        $paramString["Owalizacja % P"] = $this->P;
        $paramString["Owalizacja % S"] = $this->S;
        $paramString["Owalizacja % K"] = $this->K;
        $paramString["Izolacja A/N"] = "Nie wiem, co tu dać";
        $paramString["Ocena końcowa pozytywna/negatywna"] = "P";
        $gps = new ObjectGps();
        $gps->findMe($this);
        //$paramString["GPS"]  = $gps->returnGoogleMapsHref($gps->id);
        $paramString["Mapy google"]  = $gps->returnGoogleMapPictureHref($gps->id);
        return $paramString;



        
    }
}
class ObjectBend extends bendFunctions
{
}
