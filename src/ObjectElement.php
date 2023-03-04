<?php

/**
 * Objects for qrcode project
 * ObjectElement
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
use qrcodeFields;

class elementFields extends DBObject2
{
    protected $tableName = "element";
    public $wytop;
    public $numerRury;
    public $numerObiektu;
    public $dlugoscFabryczna;
    public $dlugoscZabudowy;
    public $gruboscScianki;
    public $gatunekMaterialu;
    public $dlugoscLuku;
    public $srednica;
    public $rodzajIzolacji;
    public $numerIzolacji;
    public $kilometraz;
    public $gpsLink;
    public $producent;
    public $nrProby;
    public $swiadectwoNaRure;
    public $swiadectwoNaIzolacje;
    public $rm;
    public $re;
    public $typeID;
}
/**
 * Klasa odpowiedzialna za funkcje specyficzne dla elementów (rur)
 */
class elementFunctions extends elementFields
{
    //NOTE -  make it protected (?)
    public function getNextFreePosition()
    {
        $qrCodes = $this->findRelativesElementId("qrcode");
        //error_log("WJAZD OBIEKTU -------------------" . uniqid());
        //error_log(json_encode($this->id));
        //error_log(json_encode($qrCodes));
        //error_log(json_encode(count($qrCodes)));
        if (count($qrCodes) == 4) return DBObject2::QR_POSITION_TAKEN;
        // wszystkie wolne?
        if (count($qrCodes) == 0) return "A";
        foreach ($qrCodes as $key => $value) {
            $tRow[] = $value['pozycja'];
        }
        if (!isset($tRow)) {
            return DBObject2::OBJECT_NOT_FOUND;
        }
        $stRow = implode("", $tRow);
        if (strpos($stRow, "A") === false) return "A";
        if (strpos($stRow, "B") === false) return "B";
        if (strpos($stRow, "C") === false) return "C";
        if (strpos($stRow, "D") === false) return "D";
        return DBObject2::OBJECT_NOT_FOUND;
    }

    public function getFirstAvailableQr()
    {
        $qrCodes = $this->findRelativesElementId("qrcode");
        if (count($qrCodes) == 0) return null;
        foreach ($qrCodes as $key => $value) {
            return (new ObjectQrcode($value['id']));
        }
    }
    /**
     * Wyszukaj dla danej rury kod QR na danej pozycji
     *
     * @param mixed $position pozycja, ABCD
     * 
     * @return [mixed] obiekt typu ObjectQrcode albo null, jeśli nie znalazł
     * 
     * Created at: 2.02.2023, 11:19:35 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function getQR($position)
    {
        if (null == $position) return null;
        $qrCodes = $this->findRelatives("qrcode");
        //print_r($qrCodes);
        foreach ($qrCodes as $key => $value) {
            # code...
            if ($position == $value['pozycja']) {
                $qrCode = new ObjectQrcode();
                $qrCode->loadFromArray($value);
                return $qrCode;
            }
        }
        //print_r(array_keys($qrCodes));
        return null;
    }
    public function getQRCodeATPosition($position)
    {
        if (strlen($position) != 1) return DBObject2::QR_POSITION_NOT_ABCD;
        $qrCodes = $this->findRelativesElementId("qrcode");
        //print_r($qrCodes);
        foreach ($qrCodes as $key => $value) {
            # code...
            if ($position == $value['pozycja']) {
                $qrCode = new ObjectQrcode();
                $qrCode->loadFromArray($value);
                return $qrCode;
            }
        }
        //print_r(array_keys($qrCodes));
        return DBObject2::QR_NOT_FOUND;
    }
    /**
     * [Description for getQRCodePosition]
     * Funkcja znajduje w elemencie kod QR na podstawie kodu (String!)
     *
     * @param string $qrCodeString QR KOD
     * 
     * @return [ObjectQrcode] jeśli ok, kod błędu jeśli nie ok
     * 
     * Created at: 2/5/2023, 6:01:44 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function getQRCodePosition(string $qrCodeString)
    {
        $qrCode = new ObjectQrcode();
        $qrCode->loadFromDBFieldValue("qrCode", $qrCodeString);
        error_log("Looking for QR Code " . $this->id);
        if ($qrCode->elementId != $this->id) return DBObject2::QR_NOT_IN_ELEMENT;
        return $qrCode;
    }
    /**
     * Dodawanie kodu QR do elementu
     *
     * @param mixed $qrCodeID ID kodu
     * @param mixed $position pozycja ABCD
     * 
     * @return [mixed] null, jeśli nieudane dodawanie, qrCodeID w przeciwnym wypadku
     * 
     * Created at: 2.02.2023, 11:29:42 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function addQR($qrCodeID, $position = null)
    {
        //error_log("DODAJĘ QR " . $qrCodeID);
        // jeśli jest już jakiś kod na pozycji, fail
        if (null != $this->getQR($position))
            return DBObject2::QR_POSITION_TAKEN;
        if (null == $position)
            $position = $this->getNextFreePosition();
        if (is_int($position))   // error code?
        {
            error_log("DODAJĘ QR na $position");
            return $position;
        }
        $qrCode = new ObjectQrcode($qrCodeID);
        // jeśli $qrCode jest już przypisany do 
        if ($qrCode->elementId != -1)
            return DBObject2::QR_ALREADY_CONNECTED;
        if (1 != $qrCode->active)
            return DBObject2::QR_NOT_ACTIVE;
        // jeśli nic z tych rzeczy, podłącz
        $qrCode->elementId = $this->id;
        $qrCode->pozycja = $position;
        $qrCode->updateToDB();
        \Database\DBLog::UserLog($this, "ADD QR", basename(__FILE__) . "[" . __LINE__ . "]", "", $qrCodeID);

        // sukces!
        return $qrCodeID;
    }
    /**
     * Funkcja usuwa kod QR z połączenia BEZ usuwania kodu z bazy danych!
     *
     * @param mixed $qrCodeID
     * 
     * @return [type]
     * 
     * Created at: 2.02.2023, 11:38:42 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function removeQR($qrCodeID = "", $position = "")
    {
        if ($position == "" and $qrCodeID == "")
            return DBObject2::QR_REMOVE_NO_PARAMS;
        if ("" != $position) {
            $qrCode = $this->getQR($position);
            if (null == $qrCode)
                return DBObject2::QR_NOT_IN_ELEMENT;    // qr istnieje, ale nie jest w elemencie
            // ustaw podłączenie na -1
            $qrCode->elementId = -1;
            // zapisz do bazy danych
            $qrCode->updateToDB();
            // zwróć znaleziony ID
            \Database\DBLog::LogMe($this, __FUNCTION__ . " " . $qrCode->id, basename(__FILE__) . "[" . __LINE__ . "]");
            return $qrCode->id;
        }
        if ("" != $qrCodeID) {
            // znajdź tego jednego QR
            $qrCode = new ObjectQrcode($qrCodeID);
            if (null == $qrCode)
                return DBObject2::QR_NOT_FOUND;
            if ($qrCode->elementId != $this->id) return DBObject2::QR_NOT_IN_ELEMENT;
            // ustaw podłączenie na -1
            $qrCode->elementId = -1;
            $qrCode->pozycja = "";
            // zapisz do bazy danych
            $qrCode->updateToDB();
            // zwróć znaleziony ID
            \Database\DBLog::LogMe($this, __FUNCTION__ . " " . $qrCode->id, basename(__FILE__) . "[" . __LINE__ . "]");
            return $qrCode->id;
        }
    }
  

    /**
     * Funkcja pokazuje najważniejsze elementy rury do wyświetlania
     *
     * @return [type]
     * 
     * Created at: 2/15/2023, 10:54:34 AM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function printDIV()
    {
        $paramString = "<div class='text-break  justify-content-end align-items-stretch col-12 text-cyan'>";
        $paramString .= PageElements::lineForElement("Nr rury / wytop", $this->numerRury . " / " . $this->wytop);
        $paramString .= PageElements::lineForElement("ID w BAZIE", $this->id);
        $paramString .= PageElements::lineForElement("Średnica", $this->srednica);
        $paramString .= PageElements::lineForElement("Długość fabryczna", $this->dlugoscFabryczna);
        $paramString .= PageElements::lineForElement("Długość zabudowy", $this->dlugoscZabudowy);
        $paramString .= PageElements::lineForElement("Średnica * do dyskusji", $this->srednica);
        $paramString .= PageElements::lineForElement("Grubość ścianki", $this->gruboscScianki);
        $paramString .= PageElements::lineForElement("Kąt łuku", "N/A do dyskusji*");
        $paramString .= PageElements::lineForElement("Gatunek materiału", $this->gatunekMaterialu);
        $paramString .= PageElements::lineForElement("Rodzaj izolacji", $this->rodzajIzolacji);
        $typElementu = new ObjectType($this->typeID);
        //$typElementu->findTableFieldValue("type","type",$this->typeID);
        $paramString .= PageElements::lineForElement("TYP ELEMENTU", $typElementu->typeName);
        $paramString .= "<hr>";
        // cięta?
        $mileage = $this->findRelativesElementId("mileage");
        $mileageCount = count($mileage);
        if ($mileageCount > 0) {
            $paramString .= PageElements::lineForElement("Ostatni Kilometraż ", $mileage[0]['kilometraz']);
            $paramString .= PageElements::lineForElement("Data kilometrażu *", $mileage[0]['time_added']);
        } else
            $paramString .= PageElements::lineForElement("Kilometraż? ", "BRAK");

        $paramString .= "<hr>";
        $cut = $this->findRelativesElementId("cut");
        $cutCount = count($cut);
        if ($cutCount > 0) {
            $paramString .= PageElements::lineForElement("ID Cięcia *", $cut[0]['id']);
            $paramString .= PageElements::lineForElement("Data cięcia *", $cut[0]['time_added']);
        } else
            $paramString .= PageElements::lineForElement("Element cięty? *", "NIE");

        $paramString .= "<hr>";
        $bend = $this->findRelativesElementId("bend");
        $bendCount = count($bend);
        if ($bendCount > 0) {
            $paramString .= PageElements::lineForElement("ID gięcia", $bend[0]['id']);
            $paramString .= PageElements::lineForElement("Data gięcia", $bend[0]['time_added']);
        } else
            $paramString .= PageElements::lineForElement("Element gięty? *", "NIE");

        $paramString .= "<hr>";
        $qrCodes = $this->findRelativesElementId("qrcode");
        $paramString .= PageElements::lineForElement("Poprawność QR? *", count($qrCodes) . " / 4");




        $paramString .= "</div>";
        return $paramString;
    }

    public function getType()
    {
        $row = $this->dbHandler->getRowSQL("SELECT typeName from type where id = $this->typeID;");
        return $row[0]['typeName'];
    }
    public function getQRCount()
    {
        $qrCodes = $this->findRelativesElementId("qrcode");
        return count($qrCodes);
    }

    public function returnTableArray()
    {
        //$paramString = parent::returnTableArray();
        $paramString["Przyciski działań"] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id);
        $mileage = $this->findRelativesElementId("mileage");
        $mileageCount = count($mileage);
        if ($mileageCount > 0) {
            $paramString["Ostatni Kilometraż"]  = $mileage[0]['kilometraz'];
            $paramString["Data kilometrażu"]  =  $mileage[0]['time_added'];
        } else {
            $paramString["Kilometraż"]  =  "BRAK";
            $paramString["Data kilometrażu"]  =  "";
        }

        $type = new ObjectType($this->typeID);
        $paramString["Typ elementu"]  =  $type->typeName;



        $cut = $this->findRelativesElementId("cut");
        $cutCount = count($cut);
        if ($cutCount > 0) {
            $paramString["ID Cięcia"]  =  $cut[0]['id'];
            $paramString["Data cięcia *"]  =  $cut[0]['time_added'];
        } else {
            $paramString["ID Cięcia"]  =  "BRAK CIĘCIA";
            $paramString["Data cięcia *"]  =  "";
        }


        $bend = $this->findRelativesElementId("bend");
        $bendCount = count($bend);
        if ($bendCount > 0) {
            $paramString["ID gięcia"]  =  $bend[0]['id'];
            $paramString["Data gięcia"]  =  $bend[0]['time_added'];
        } else {
            $paramString["ID gięcia"]  =  "BRAK GIĘCIA";
            $paramString["Data gięcia"]  =  "";
        }


        $qrCodes = $this->findRelativesElementId("qrcode");
        $paramString["Poprawność QR"]['count']  =  count($qrCodes) . " / 4";
        $paramString["Poprawność QR"]['error']  =  count($qrCodes) == 4;


        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    public function printStructure()
    {
        $qrCodeList = $this->findRelativesElementId("qrcode");
        $arrayIn['A'] = "<div>qrA: BRAK</div>";
        $arrayIn['B'] = "<div>qrB: BRAK</div>";
        $arrayIn['C'] = "<div>qrC: BRAK</div>";
        $arrayIn['D'] = "<div>qrD: BRAK</div>";
        //$arrayIn['E'] = json_encode($qrCodeList);
        foreach ($qrCodeList as $key => $value) {
            # code...
            $arrayIn[$value['pozycja']] = sprintf("<div>qr%s: %s</div>", $value['pozycja'], $value['name']);
        }

        return "<div class='text-primary'>" . implode("", $arrayIn) . "</div>";
    }

    public function getObjectButton($color = "text-white", $title = null)
    {
        
                return parent::getObjectButton("text-white", sprintf("[%'.6d] %s %s", $this->id, $this->wytop . " / ".$this->numerRury, $this->getType()));
    }
}
class ObjectElement extends elementFunctions
{
}
