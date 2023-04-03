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
    public $dataDodaniaGiecia;
}
class bendFunctions extends bendFields
{
    public function printDIV()
    {
        return  parent::printDIV();
    }

    public function returnTableArray()
    {
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Akcje"] = PageElements::addButtonViewItemInTable($elementOriginal->getTableName(), $elementOriginal->id);
        $paramString["ID"] = $this->id;
        //$paramString["Lp."] = $globalKey;
        $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
        $paramString["Data"] = $this->time_added;
        $paramString["dataDodaniaGiecia"] = $this->time_added;
        $paramString["Numer punktu załamania Pz / Pp"] = $this->oznaczenieLuku;

        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Nr wytopu"] = $elementOriginal->wytop;
        $paramString["Nr rury"] = $elementOriginal->numerRury;
        $paramString["Grubość ścianki (mm)"] = $elementOriginal->gruboscScianki;
        $paramString["Rodzaj izolacji"] = $elementOriginal->rodzajIzolacji;
        $paramString["Rodzaj łuku (V,h)"] = $this->getVH();
        $paramString["Rodzaj łuku (V,h) opis"] =  "<span class='small muted text-gray-300'>" . $this->oznaczenieLuku."</span>";
        $paramString["Promień gięcia"] = $this->promienGiecia;
        $paramString["Długość (m)"] = $this->promienGiecia;
        // TODO trzeba dać kilometraż do gięcia, cz
        // no to jechane
        $mileage = new ObjectMileage();
        //$mileage->
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
   
        //return parent::returnTableArray();
        //$elementOriginal = new ObjectElement($this->elementId);
        //$paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);

        //$paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->numerRury . "/" . $elementOriginal->wytop);
        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    public function returnTableArray2($globalKey)
    {

        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        $paramString["ID"] = $this->id;
        $paramString["Lp."] = $globalKey;
        $paramString["Data"] = $this->time_added;
        $paramString["dataDodaniaGiecia"] = $this->time_added;
        $paramString["Numer punktu załamania Pz / Pp"] = $this->oznaczenieLuku;

        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Nr wytopu"] = $elementOriginal->wytop;
        $paramString["Nr rury"] = $elementOriginal->numerRury;
        $paramString["Grubość ścianki (mm)"] = $elementOriginal->gruboscScianki;
        $paramString["Rodzaj izolacji"] = $elementOriginal->rodzajIzolacji;
        $paramString["Rodzaj łuku (V,h)"] = $this->getVH();
        $paramString["Rodzaj łuku (V,h) opis"] =  "<span class='small muted text-gray-300'>" . $this->oznaczenieLuku."</span>";
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

    public function returnTableArray72()
    {
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["ID"]  = $this->id;
        $paramString["Data"]  = date("Y-m-d", strtotime($this->time_added));
        $paramString["Numer punktu załamania Pz / Pp"] = $this->oznaczenieLuku;
        $paramString["Nr wytopu"] = $elementOriginal->wytop;
        $paramString["Nr rury"] = $elementOriginal->numerRury;
        $paramString["Grubość ścianki "] = $elementOriginal->gruboscScianki;
        $paramString["Rodzaj izolacji"] = $elementOriginal->rodzajIzolacji;
        $paramString["Rodzaj łuku (V,h)"] = $this->getVH();
        $paramString["Rodzaj łuku (V,h) opis"] =  "<span class='small muted text-gray-300'>" . $this->oznaczenieLuku."</span>";
        $paramString["Promień gięcia"] = $this->promienGiecia;
        $paramString["Długość "] = $elementOriginal->dlugoscZabudowy;
        $paramString["Km trasy"] = "km łuku czy elementu?";
        $paramString["Kąt wg PT "] = "Skąd ta dana?";
        $paramString["Kąt wygięty "] = "Skąd ta dana?";
        $paramString["Owalizacja "] = "Skąd ta dana?";
        $paramString["Izolacja  A/N"] = "Skąd ta dana?";
        $paramString["P"] = $this->P;
        $paramString["S"] = $this->S;
        $paramString["K"] = $this->K;
        $paramString["Ocena końcowa pozytywna / negatywna"] = "A";





        return $paramString;
    }

    protected function getVH()
    {
        return strpos(strtolower($this->oznaczenieLuku), "pz") === false ? "V" : "H";     
    }
}
class ObjectBend extends bendFunctions
{
    public function getFieldsForDatatableHTML($switchReason = "HTML")
    {
        $resultArray  = "";
        switch ($switchReason) {
            case 'HTML':
                $resultArray = $this->getFieldsForDatatableHTMLAddCustomFields($switchReason, "Wytop, nr rury");
                $resultArray .= parent::getFieldsForDatatableHTML($switchReason);

                break;
            case 'DATA':
                //$resultArray = $this->getFieldsForDatatableHTMLAddCustomFields($switchReason, "data: element.wytop, render: function(val, type, row){ return 'to jest ok';}");
                $resultArray = $this->getFieldsForDatatableHTMLAddCustomFields($switchReason, "data: null, render: function ( data, type, row ) { return data.element.wytop + '/' + data.element.numerRury;}");
                $resultArray .= parent::getFieldsForDatatableHTML($switchReason);
                break;
            case 'EDIT':
                $resultArray = $this->getFieldsForDatatableHTMLAddCustomFields($switchReason, "Nazwa rury");
                $resultArray .= parent::getFieldsForDatatableHTML($switchReason);
                break;
            case 'FIELD':
                $resultArray .= "element.numerRury,";
                $resultArray .= "element.wytop,";
                $resultArray .= parent::getFieldsForDatatableHTML($switchReason);
                break;
            case 'LEFT':
                $Arr =  'element, bend.elementId, =, element.id';
                $resultArray = $Arr;
                break;
        }
        return $resultArray;
    }
}
