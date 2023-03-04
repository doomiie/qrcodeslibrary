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

class cutFields extends DBObject2
{
    protected $tableName = "cut";
    public $elementId = -1;
    public $leftoverElement;
    public $originalLength;
    public $leftoverLength;
    public $cutType;
}
class cutFunctions extends cutFields
{
    public function printDIV()
    {
        return sprintf(
            "
        <div class='text-break text-cyan'>
        <div>Oryginalny element: <span class='text-white'>%s</span></div>
        <div>Oryginalna długość: <span class='text-white'>%s</span></div>
        <div>Pozostały element: <span class='text-white'>%s</span></div>
        <div>Pozostała długość: <span class='text-white'>%s</span></div>
        <div>Typ cięcia:<span class='text-white'>%s</span></div>
        </div>
        ",
            $this->elementId,
            $this->originalLength,
            $this->leftoverElement,
            $this->leftoverLength,
            $this->cutType
        ) . parent::printDIV();
    }
}
class ObjectCut extends cutFunctions
{
    public static function cutElementMain(ObjectElement $object, $newLength, $leftoverLength, ?ObjectQrcode $qrCode, $cutType = DBObject2::CUT_TYPE_MOVE)
    {
        // UWAGA, zmiana, newLength jest odcinane z prawej strony
        $newLength = $object->dlugoscZabudowy - $newLength;
        if ($object->getQRCount() != 4) return DBObject2::QR_NOT_FOUND;
        // jeśli nie ma tyle rury, nie tnij!
        if ($newLength >= $object->dlugoscZabudowy) return DBObject2::OBJECT_LENGTH_TOO_SMALL;
        // średnica jest 700 - czyli 0,7m
        // długość cięcia jest w m. (1.12)
        // czyli trzeba pomnożyć * 1000
        if ($newLength * 1000 <= $object->srednica) return DBObject2::OBJECT_LENGTH_SMALLER_THAN_DIAMETER;
        // jeśli nie ma leftoverLEngth, oblicz
        if ("" == $leftoverLength) {
            $leftoverLength = (float)$object->dlugoscZabudowy - (float)$newLength;
        }
        error_log("Nowy obiekt ma długość " . $leftoverLength * 1000 . " vs srednica " . $object->srednica);
        if ($leftoverLength * 1000 <= $object->srednica) return DBObject2::OBJECT_LENGTH_SMALLER_THAN_DIAMETER;
        //1. Create a copy of original element
        $originalClass = get_class($object);
        $lefoverObject = new $originalClass($object->id);   // <= copy of original
        //1. Cut it down
        $object->dlugoscZabudowy = $newLength;
        //2. delete old qrcodes!
        error_log("---------------------------------------- Działamy z objectcut");
        if (null == $qrCode) {
            $qrCode = $object->getFirstAvailableQr();
            error_log("Szukamy kodu" . json_encode($qrCode));
        }
        if (null == $qrCode) return DBObject2::QR_NOT_FOUND;
        if (DBObject2::CUT_TYPE_MOVE == $cutType or DBObject2::CUT_TYPE_DESTROY == $cutType) {
            $qrCodeSibling = $qrCode->getSibling();
            $qrCode->removeFromElement();
            if (is_object($qrCodeSibling))
                $qrCodeSibling->removeFromElement();
        }
        // zdeaktywuj QRy, jeśli destroy
        if (DBObject2::CUT_TYPE_DESTROY == $cutType) {
            $qrCode->Deactivate();
            $qrCodeSibling->Deactivate();
        }
        //3. Update Object itself
        \Database\DBLog::UserLog($object, "CUT ELEMENT ", basename(__FILE__) . "[" . __LINE__ . "]");
        $object->updateToDB();
        //1. Cut down new object
        $lefoverObject->dlugoscZabudowy = $leftoverLength;
        $lefoverObject->id = null;
        //NOTE - w tym miejscu nazywamy rurę!
        //1. Znajdź wszystkie elementy o wytop = wytop i nr rury zawierający nr rury
        // SELECT * FROM `element` WHERE WYTOP = 220746 and numerRury like '2281_1%' ORDER BY id DESC
        $sql = sprintf("SELECT * FROM `element` WHERE WYTOP = %s and numerRury like '%s__' ORDER BY id DESC", $object->wytop, $object->numerRury);
        $row = $object->dbHandler->getRowSql($sql);
        if(null == $row)
        {
            $nowyNumerRury = 1;
        }
        else
        {
            $tempNumerRury = explode("-", $object->numerRury);
            $nowyNumerRury = array_pop($tempNumerRury) + 1;
        }



        $lefoverObject->numerRury .= "-" . $nowyNumerRury;
        //$lefoverObject->numerRury .= "_" . substr(bin2hex(random_bytes(4)), 0, 4);  // random name to avoid duplicates
        //2. Check for leftover type
        $nL = (float)100 * (float)$leftoverLength;
        if ((float)$lefoverObject->srednica / 10 > $nL) {
            //print("Cut type ? $lefoverObject->srednica > $nL");
            $lefoverObject->typeID = 10;    // ODPAD, jeśli średnica większa niż długość
        }
        if (self::CUT_TYPE_ZERO == $cutType) {
            $lefoverObject->typeID = 10;
        }   // ODPAD!!
        $leftoverNewId =  $lefoverObject->saveToDB();
        \Database\DBLog::UserLog($lefoverObject, "NEW ELEMENT ", basename(__FILE__) . "[" . __LINE__ . "]", "", $object->id);
        if (self::CUT_TYPE_MOVE == $cutType) {
            $lefoverObject->addQR($qrCode->id, $qrCode->pozycja);
            if (is_object($qrCodeSibling))
                $lefoverObject->addQR($qrCodeSibling->id, $qrCodeSibling->pozycja);
        }
        $cut = new ObjectCut();
        $cut->elementId = $object->id;
        $cut->leftoverElement = $leftoverNewId;
        $cut->originalLength = $newLength;
        $cut->leftoverLength = $leftoverLength;
        $cut->cutType = $cutType;
        $cutNewId = $cut->saveToDB();
        \Database\DBLog::UserLog($cut, "NEW CUT " . DBObject2::getErrorDescription($cutType), basename(__FILE__) . "[" . __LINE__ . "]", "", "");
        return $cutNewId;
    }
    public static function cutElementAndDestroyQrCodes(ObjectElement $object, $newLength, $leftoverLength, ObjectQrcode $qrCode)
    {
        return self::cutElementMain($object, $newLength, $leftoverLength,  $qrCode, self::CUT_TYPE_DESTROY);
        // jeśli nie ma tyle rury, nie tnij!
        if ($newLength > $object->dlugoscZabudowy) return DBObject2::OBJECT_LENGTH_TOO_SMALL;
        // jeśli nie ma leftoverLEngth, oblicz
        if ("" == $leftoverLength) {
            $leftoverLength = (float)$object->dlugoscZabudowy - (float)$newLength;
        }
        //1. Create a copy of original element
        $originalClass = get_class($object);
        $lefoverObject = new $originalClass($object->id);   // <= copy of original
        //1. Cut it down
        $object->dlugoscZabudowy = $newLength;
        //2. delete old qrcodes!
        $qrCodeSibling = $qrCode->getSibling();
        $qrCode->removeFromElement();
        if (is_object($qrCodeSibling))
            $qrCodeSibling->removeFromElement();
        //3. Update Object itself
        \Database\DBLog::LogMe($object, __FUNCTION__ . " " . $object->id, basename(__FILE__) . "[" . __LINE__ . "]");
        $object->updateToDB();
        //1. Cut down new object
        $lefoverObject->dlugoscZabudowy = $leftoverLength;
        $lefoverObject->id = null;
        $lefoverObject->numerRury .= "_" . substr(bin2hex(random_bytes(4)), 0, 4);  // random name to avoid duplicates
        //2. Check for leftover type
        $nL = (float)100 * (float)$leftoverLength;
        if ((float)$lefoverObject->srednica / 10 > $nL) {
            //print("Cut type ? $lefoverObject->srednica > $nL");
            $lefoverObject->typeID = 10;    // ODPAD!!
        }
        $leftoverNewId =  $lefoverObject->saveToDB();
        \Database\DBLog::LogMe($lefoverObject, __FUNCTION__ . " " . $leftoverNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        $cut = new ObjectCut();
        $cut->elementId = $object->id;
        $cut->leftoverElement = $leftoverNewId;
        $cut->originalLength = $newLength;
        $cut->leftoverLength = $leftoverLength;
        $cut->cutType = $lefoverObject->typeID;
        $cutNewId = $cut->saveToDB();
        \Database\DBLog::LogMe($cut, __FUNCTION__ . " " . $cutNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        return $cutNewId;
    }
    public static function cutElementAndMoveQrCodes(ObjectElement $object, $newLength, $leftoverLength, ObjectQrcode $qrCode)
    {
        return self::cutElementMain($object, $newLength, $leftoverLength,  $qrCode, self::CUT_TYPE_MOVE);
        // jeśli nie ma tyle rury, nie tnij!
        if ($newLength > $object->dlugoscZabudowy) return DBObject2::OBJECT_LENGTH_TOO_SMALL;
        // jeśli nie ma leftoverLEngth, oblicz
        if ("" == $leftoverLength) {
            $leftoverLength = (float)$object->dlugoscZabudowy - (float)$newLength;
            //print($leftoverLength . "<<<<");
        }
        //1. Create a copy of original element
        $originalClass = get_class($object);
        $lefoverObject = new $originalClass($object->id);   // <= copy of original
        //1. Cut it down
        $object->dlugoscZabudowy = $newLength;
        //2. delete old qrcodes!
        $qrCodeSibling = $qrCode->getSibling();
        $qrCode->removeFromElement();
        if (is_object($qrCodeSibling))
            $qrCodeSibling->removeFromElement();
        //3. Update Object itself
        \Database\DBLog::LogMe($object, __FUNCTION__ . " " . $object->id, basename(__FILE__) . "[" . __LINE__ . "]");
        $object->updateToDB();
        //1. Cut down new object
        $lefoverObject->dlugoscZabudowy = $leftoverLength;
        $lefoverObject->id = null;
        $lefoverObject->numerRury .= "_" . substr(bin2hex(random_bytes(4)), 0, 4);  // random name to avoid duplicates
        //2. Check for leftover type
        $nL = (float)100 * (float)$leftoverLength;
        if ((float)$lefoverObject->srednica / 10 > $nL) {
            //print("Cut type ? $lefoverObject->srednica > $nL");
            $lefoverObject->typeID = 10;    // ODPAD!!
        }
        $leftoverNewId =  $lefoverObject->saveToDB();
        \Database\DBLog::LogMe($lefoverObject, __FUNCTION__ . " " . $leftoverNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        // 1. A ADD qrCodes
        $lefoverObject->addQR($qrCode->id, $qrCode->pozycja);
        if (is_object($qrCodeSibling))
            $lefoverObject->addQR($qrCodeSibling->id, $qrCodeSibling->pozycja);
        $cut = new ObjectCut();
        $cut->elementId = $object->id;
        $cut->leftoverElement = $leftoverNewId;
        $cut->originalLength = $newLength;
        $cut->leftoverLength = $leftoverLength;
        $cut->cutType = $lefoverObject->typeID;
        $cutNewId = $cut->saveToDB();
        \Database\DBLog::LogMe($cut, __FUNCTION__ . " " . $cutNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        return $cutNewId;
    }
    public static function cutElementZeroOption(ObjectElement $object, $newLength, $leftoverLength, ObjectQrcode $qrCode)
    {
        return self::cutElementMain($object, $newLength, $leftoverLength,  $qrCode, self::CUT_TYPE_ZERO);
        // jeśli nie ma tyle rury, nie tnij!
        if ($newLength > $object->dlugoscZabudowy) return DBObject2::OBJECT_LENGTH_TOO_SMALL;
        // jeśli nie ma leftoverLEngth, oblicz
        if ("" == $leftoverLength) {
            $leftoverLength = (float)$object->dlugoscZabudowy - (float)$newLength;
        }
        //1. Create a copy of original element
        $originalClass = get_class($object);
        $lefoverObject = new $originalClass($object->id);   // <= copy of original
        //1. Cut it down
        $object->dlugoscZabudowy = $newLength;
        //2. DO NOT delete old qrcodes!
        //3. Update Object itself
        \Database\DBLog::LogMe($object, __FUNCTION__ . " " . $object->id, basename(__FILE__) . "[" . __LINE__ . "]");
        $object->updateToDB();
        //1. Cut down new object
        $lefoverObject->dlugoscZabudowy = $leftoverLength;
        $lefoverObject->id = null;
        $lefoverObject->numerRury .= "_" . substr(bin2hex(random_bytes(4)), 0, 4);  // random name to avoid duplicates
        //2. Check for leftover type
        $lefoverObject->typeID = 10;    // ODPAD!!
        $leftoverNewId =  $lefoverObject->saveToDB();
        \Database\DBLog::LogMe($lefoverObject, __FUNCTION__ . " " . $leftoverNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        $cut = new ObjectCut();
        $cut->elementId = $object->id;
        $cut->leftoverElement = $leftoverNewId;
        $cut->originalLength = $newLength;
        $cut->leftoverLength = $leftoverLength;
        $cut->cutType = $lefoverObject->typeID;
        $cutNewId = $cut->saveToDB();
        \Database\DBLog::LogMe($cut, __FUNCTION__ . " " . $cutNewId, basename(__FILE__) . "[" . __LINE__ . "]");
        return $cutNewId;
    }

    public function returnTableArray()
    {
        //return parent::returnTableArray();
        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        $paramString["Typ cięcia"] = $this->getTypCieciaOpis();
        $gps = new ObjectGps();
        $gps->findMe($this);
        //$paramString["GPS"]  = $gps->returnGoogleMapsHref($gps->id);
        $paramString["Mapy google"]  = $gps->returnGoogleMapPictureHref($gps->id);
      
     
        $elementOriginal = new ObjectElement($this->elementId);
        $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->numerRury . "/" . $elementOriginal->wytop);
        $elementLeftover = new ObjectElement($this->leftoverElement);
        $paramString["Do zabudowy/odpad element"]  = $elementLeftover->getLinkToSingleElement($elementLeftover->numerRury . "/" . $elementLeftover->wytop);
        $paramString = array_merge($paramString, parent::returnTableArray());

        return $paramString;
    }
    protected function getTypCieciaOpis()
    {
        switch ($this->cutType) {
            case DBObject2::CUT_TYPE_ZERO:
                return "ZEROWE";
            case DBObject2::CUT_TYPE_MOVE:
                return "NA DWIE RURY";
            case DBObject2::CUT_TYPE_DESTROY:
                return "NISZCZĄCE";
            default:
                return "NIEZNANE!";
        }
    }
}
