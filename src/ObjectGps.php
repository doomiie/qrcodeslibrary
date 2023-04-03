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
use ObjectCut as GlobalObjectCut;

class gpsFields extends DBObject2
{
    protected $tableName = "gps";
    public $elementId = -1;
    public $refType;
    public $refId;
    public $lat;
    public $lng;
}
class gpsFunctions extends gpsFields
{
    public function printDIV()
    {
        return  parent::printDIV();
    }

    public function returnTableArray()
    {
        //return parent::returnTableArray();
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Akcje"] = PageElements::addButtonViewItemInTable($elementOriginal->getTableName(), $elementOriginal->id);
        $paramString["Pozycja"] = $this->returnGoogleMapsHref("Pozycja na mapie");
        $paramString["Typ referencji"] = $this->refType;
        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        $paramString["Użytkownik"] = $this->name != "" ? (new ObjectUser($this->name))->username : "";
        switch (substr($this->refType, 0, 3)) {
            case 'ben':
                $object = new ObjectBend($this->elementId);
                $elementOriginal =  new ObjectElement($object->elementId);

                $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
                break;
            case 'cut':
                $object = new ObjectCut($this->elementId);
                $el1 =  new ObjectElement($object->elementId);
                $el2 =  new ObjectElement($object->leftoverElement);
                //$paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
                $paramString["Oryginalny element"]  = sprintf("Cięta rura: %s<br>Do zabudowy: %s", $el1->getLinkToSingleElement($el1->wytop . "/" . $el1->numerRury), $el2->getLinkToSingleElement($el2->wytop . "/" . $el2->numerRury));
                break;
            case 'qrc':
                $object = new ObjectQrcode($this->elementId);
                $elementOriginal =  new ObjectElement($object->elementId);
                $paramString["Oryginalny element"]  = $object->name . "<br>" . $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);;
                break;
            case 'ele':
                $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
                break;
            case 'mil':
                $object = new ObjectMileage($this->elementId);                
                $paramString["Oryginalny element"]  = sprintf("[%s] TYP: %s, ID: %s",$object->kilometraz, $object->refType, $object->elementId);
                break;
            case 'joi':
                $object = new ObjectJoint($this->elementId);                
                $qrCode1 =  new ObjectQrcode($object->elementId1);
                $qrCode2 =  new ObjectQrcode($object->elementId2);
                $el1 = new ObjectElement($qrCode1->elementId);
                $el2 = new ObjectElement($qrCode2->elementId);
                $paramString["Oryginalny element"]  = sprintf("Lewa rura: %s<br>Prawa rura: %s", $el1->getLinkToSingleElement($el1->wytop . "/" . $el1->numerRury), $el2->getLinkToSingleElement($el2->wytop . "/" . $el2->numerRury));
                break;

            default:
                $paramString["Oryginalny element"]  = "Sprawdź ObjectGps " . __LINE__;
                break;
            }
            $paramString["ID oryginału"]  = $this->elementId;

        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    public function returnTableArray2($globalKey)
    {

        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        return $paramString;
    }

    public function returnGoogleMapsHref($title = "")
    {
        return sprintf("<a href='%s' target='_blank'>%s</a>", $this->returnGoogleMapsLink($title), $title);
    }
    public function returnGoogleMapsLink($title = "")
    {
        return self::returnGoogleMapsSimpleLink($this->lat, $this->lng, $title);
    }

    public static function returnGoogleMapsSimpleLink($lat, $lng, $title = "")
    {
        if ($title != "") $title = "(" . $title . ")";
        return "https://maps.google.com/maps?q=" . $lat . "," . $lng . "&ll=" . $lat . "," . $lng . $title;
        //return "https://maps.google.com/maps?q=".$lat.",".$lng."";
    }

    public function returnGoogleMapPictureHref($title = "", $width = "100%", $height = "100%")
    {
        //error_log($width);
        //error_log($height);
        if ($this->id == -1) return null;
        $img = $this->returnGoogleMapPicture($title, $width, $height);
        return sprintf("<a href='%s' target='_blank'>%s</a>", $this->returnGoogleMapsLink($title), $img);
    }
    public function returnGoogleMapPicture($title = "", $width = "100%", $height = "100%")
    {
        $lat = $this->lat;
        $lng = $this->lng;
        return "<img style='height: $height; width: $width; object-fit: contain' src='https://maps.googleapis.com/maps/api/staticmap?zoom=17&size=512x512&maptype=normal&markers=color:red|size:large|label:B|$lat,$lng&key=AIzaSyDxRzfVBGUNOAz21DCwK8_P-fJE1h7dpHc'>";
        return "<img src='https://maps.googleapis.com/maps/api/staticmap?center=$lat,$lng&zoom=12&key=AIzaSyDxRzfVBGUNOAz21DCwK8_P-fJE1h7dpHc&size=600x400&markers=size:tiny%7label:Tutaj'>";
    }

    public function findMe($object)
    {
        $search = $object->getTableName();
        $sql = "SELECT * FROM `gps` WHERE `refType` like '$search%' AND `refId` = $object->id ORDER BY id LIMIT 1";
        $result = $this->dbHandler->getRowSql($sql);
        if (null == $result) return -1;
        $this->loadFromArray($result[0]);
    }
}
class ObjectGps extends gpsFunctions
{
    public static function saveGPS($object, $refType, $refId, $positionInfo)
    {
        if (null === $positionInfo) return -1;
        //error_log("Creating new GPS!");
        if (get_class($object) == "Database\\DBLog") return -1;
        if (get_class($object) == "qrcodeslibrary\\ObjectGps") return -1;

        $gps = new ObjectGps();
        $gps->elementId = $object->id;
        $gps->name = $object->getLoggedUserID();
        $gps->refType = $refType;
        $gps->refId = $refId;
        $positionArray = explode(",", $positionInfo);
        $gps->lat = $positionArray[0];
        $gps->lng = $positionArray[1];
        return $gps->saveToDB();
    }
}
