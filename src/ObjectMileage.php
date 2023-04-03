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
use Database\ObjectUser;

class mileageFields extends DBObject2
{
    protected $tableName = "mileage";
    public $elementId = -1;
    public $refType;    // typ referencji, jako nazwa tabeli
    public $kilometraz;
    //TODO - wzbogacić mileage o automatyczny algorytm wyliczania GPS/kilometrażu
    //public $kilometrazGPS;
}
class mileageFunctions extends mileageFields
{
    

    public function printDIV()
    {
        return sprintf("
        <div class='text-break text-cyan'>
        <div>Kilometraż: <span class='text-white'>%s</span></div>
        <div>ID elementu: <span class='text-white'>%s</span></div>
        </div>
        ", 
        $this->kilometraz,
        $this->elementId
        ) . parent::printDIV();
    }
    
    public function returnTableArray()
    {
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
        $paramString["Akcje"]  = PageElements::addButtonViewItemInTable($elementOriginal->getTableName(), $elementOriginal->id);
        $paramString["Kilometraż"]  = $this->kilometraz;
        $paramString["Typ referencji"]  = $this->refType;
        //$paramString["Akcje"]  = PageElements::addButtonViewItemInTable("ansurd", $elementOriginal->id);
        
        $gps = new ObjectGps();
        $gps->findMe($this);
        //$paramString["GPS"]  = $gps->returnGoogleMapsHref($gps->id);
        if($gps->id != -1)
        {
        $paramString["Pozycja"]  = $gps->returnGoogleMapsHref('Mapa google');
        $paramString["Mapa google"]  = $gps->returnGoogleMapPictureHref($gps->id);
        }
        else
        {
            $paramString["Pozycja"]  = "Brak wpisu w DB";
            $paramString["Mapa google"]  = "Brak wpisu w DB";
                
        }
        $paramString = array_merge($paramString, parent::returnTableArray());
        
        
        $paramString["Użytkownik"] = $gps->name != ""?(new ObjectUser($gps->name))->username:"";
        
        return $paramString;
    }

    public function returnTableArray931()
    {
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Akcje"]  = PageElements::addButtonViewItemInTable($elementOriginal->getTableName(), $elementOriginal->id);
        $paramString["ID"]  = $this->id;
        $paramString["Data"]  = date("Y-m-d", strtotime($this->time_added));

        $paramString["Wytop/nr rury  (narastająco wraz z kilometrażem)"]  = $elementOriginal->wytop . "/" . $elementOriginal->numerRury;
        $paramString['Długość rury (zabudowa)'] = $elementOriginal->dlugoscZabudowy;
        $paramString['Lokalizacja w km trasy (projekt)'] = "Skąd to wziąć?";
        $paramString['Lokalizacja w km trasy (faktyczna)'] = $this->kilometraz;

        
        //$gps = new ObjectGps();
        //$gps->findMe($this);
        //$paramString["GPS"]  = $gps->returnGoogleMapsHref($gps->id);
        //$paramString["Mapa google"]  = $gps->returnGoogleMapPictureHref($gps->id);
        //$paramString = array_merge($paramString, parent::returnTableArray());
        //$paramString['Data stworzenia'] = date("Y-m-d",strtotime($this->time_added));
        //$paramString['Ostatnia modyfikacja'] = date("Y-m-d",strtotime($this->time_updated));
        return $paramString;
    }
}
class ObjectMileage extends mileageFunctions
{
}
