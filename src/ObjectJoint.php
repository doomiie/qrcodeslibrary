<?php

/**
 * Objects for qrcode project
 * ObjectJoint
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

use Database\DBObject2;


class jointFields extends DBObject2
{
    use TraitAncestor;
    protected $tableName = "joint";
    public $elementId1 = -1;
    public $elementId2 = -1;
    public $typSpoiny = 1;
    public $numerSpoiny = -1;
    public $WPS = -1;
    public $przetopL = -1;
    public $przetopR = -1;
    public $wypelnienieL = -1;
    public $wypelnienieR = -1;
    public $licoL = -1;
    public $licoR = -1;
    public $numerGrupy = -1;
}
class jointFunctions extends jointFields
{
    /**
     * Proces dodawania kodu QR do spoiny
     * 1. Sprawdź, czy kod istnieje w bazie danych, jeśli nie, error
     * 2. Sprawdź, czy kod jest w innej spoinie, jeśli tak, error
     * 3. Sprawdź, czy kod bliźniaczy jest w innej spoinie, jeśli tak, error
     *
     * @param string $qrCodeString kod QR
     * 
     * @return [int] rezultat update
     * 
     * Created at: 2/6/2023, 10:20:16 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function addQr(string $qrCodeString, int $position = 1)
    {
        // jeśli już jest podpięty kod
        $stringPostion = "elementId" . $position;
        $otherPosition = $position == 1 ? "elementId2" : "elementId1";

        if ($this->$stringPostion != "-1") return DBObject2::QR_ALREADY_CONNECTED;

        // nowy qrKod, załaduj z stringu qrCodeString
        $qrCode = new ObjectQrcode();
        $qrCode->loadFromDBFieldValue("qrCode", $qrCodeString);
        // jeśli QRkod nie istnieje
        if ($qrCode->id == -1) return DBObject2::QR_NOT_FOUND;
        // jeśli qrCode nie jest podpięty do rury (!)
        if ($qrCode->elementId == -1) return DBObject2::QR_NOT_IN_ELEMENT;
        // jeśli już jest podpięty do spoiny
        $row = $this->findTableFieldValue("joint", "elementId1", $qrCode->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        $row = $this->findTableFieldValue("joint", "elementId2", $qrCode->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        // załaduj siblinga!
        $sibling = $qrCode->getSibling();
        // jeśli SIBLING już jest podpięty do spoiny        
        $row = $this->findTableFieldValue("joint", "elementId1", $sibling->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        $row = $this->findTableFieldValue("joint", "elementId2", $sibling->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        // kody elementu z drugiej strony są w spawie (!)
        if ($this->$otherPosition != -1) {
            // załaduj drugi koniec!
            $otherQrCode1 = $qrCode->getOtherEnd();
            // jeśli otherEnd już jest podpięty do spoiny        
            $row = $this->findTableFieldValue("joint", $otherPosition, $otherQrCode1->id);
            if (count($row) > 0) return DBObject2::QR_CIRCULLAR_CONNECTION;
            // załaduj siblinga z drugiego końca
            $otherQrCode1Sibling = $otherQrCode1->getSibling();
            // jeśli jest podpięty do spoiny...
            $row = $this->findTableFieldValue("joint", $otherPosition, $otherQrCode1Sibling->id);
            if (count($row) > 0) return DBObject2::QR_CIRCULLAR_CONNECTION;
        }
        // chyba o niczym nie zapomniałem, można dodawać ?
        $this->$stringPostion = $qrCode->id;
        return $this->updateToDB();
    }

    public function testQR($qrCodeString, $position)
    {
        $stringPostion = "elementId" . $position;
        $otherPosition = $position == 1 ? "elementId2" : "elementId1";

        if ($this->$stringPostion != "-1") return DBObject2::QR_ALREADY_CONNECTED;


        // nowy qrKod, załaduj z stringu qrCodeString
        $qrCode = new ObjectQrcode();
        $qrCode->loadFromDBFieldValue("qrCode", $qrCodeString);
        // jeśli QRkod nie istnieje
        if ($qrCode->id == -1) return DBObject2::QR_NOT_FOUND;
        // jeśli qrCode nie jest podpięty do rury (!)
        if ($qrCode->elementId == -1) return DBObject2::QR_NOT_IN_ELEMENT;
        // jeśli qrCode JEST podpięty do tego spawu
        if ($this->$stringPostion == $qrCode->id) return DBObject2::QR_ALREADY_CONNECTED;
        if ($this->$otherPosition == $qrCode->id) return DBObject2::QR_ALREADY_CONNECTED;
        // czy obiekt jest w porządku!
        $element = new ObjectElement($qrCode->elementId);
        if ($element->typeID != 1) return DBObject2::OBJECT_WRONG_TYPE;
        if ($element->getQRCount() < 4) return DBObject2::OBJECT_WITH_ERRORS;


        // jeśli już jest podpięty do spoiny



        $row = $this->findTableFieldValue("joint", "elementId1", $qrCode->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        $row = $this->findTableFieldValue("joint", "elementId2", $qrCode->id);
        if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        // załaduj siblinga!
        $sibling = $qrCode->getSibling();

        if (!is_int($sibling)) {
            //print("O, jest sibling\r\n");
            // jeśli SIBLING już jest podpięty do spoiny        
            $row = $this->findTableFieldValue("joint", "elementId1", $sibling->id);
            if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
            $row = $this->findTableFieldValue("joint", "elementId2", $sibling->id);
            if (count($row) > 0) return DBObject2::QR_ALREADY_CONNECTED;
        } else {
            print(DBObject2::getErrorDescription($sibling));
        }
        // kody elementu z drugiej strony są w spawie (!)
        if ($this->$otherPosition != -1) {
            // załaduj drugi koniec!
            $otherQrCode1 = $qrCode->getOtherEnd();
            // jeśli otherEnd już jest podpięty do spoiny        
            $row = $this->findTableFieldValue("joint", $otherPosition, $otherQrCode1->id);
            if (count($row) > 0) return DBObject2::QR_CIRCULLAR_CONNECTION;
            // załaduj siblinga z drugiego końca
            $otherQrCode1Sibling = $otherQrCode1->getSibling();
            // jeśli jest podpięty do spoiny...
            $row = $this->findTableFieldValue("joint", $otherPosition, $otherQrCode1Sibling->id);
            if (count($row) > 0) return DBObject2::QR_CIRCULLAR_CONNECTION;
        }
        // chyba o niczym nie zapomniałem, można dodawać ?
        $this->$stringPostion = $qrCode->id;
        return $qrCode->id;
    }

    protected function findQrInJoints($qrCodeId)
    {
    }

    public function returnTableArray()
    {
        $elementId1  = $this->elementId1;
        $elementId2  = $this->elementId2;

        // jestem spoiną z ancestorem!
        if ($this->elementId1 == -1 and $this->elementId2 == -1 and $this->ancestorID != 0) {
            $ancestor = new ObjectJoint($this->ancestorID);
            $elementId1  = $ancestor->elementId1;
            $elementId2  = $ancestor->elementId2;
        }


        $qrcode1 = $this->findRelative("qrcode", $elementId1);
        $qrcode2 = $this->findRelative("qrcode", $elementId2);
        $paramString['qrCode name']['1'] = $qrcode1['name'];
        $paramString['qrCode name']['2'] = $qrcode2['name'];
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);
        $paramString['Pipe name']['1'] = $element1->wytop . "/" . $element1->numerRury;
        $paramString['Pipe name']['2'] = $element2->wytop . "/" . $element2->numerRury;

        $paramString['Data stworzenia'] = date("Y-m-d",strtotime($this->time_added));
        //$paramString["elementId2"]['name']  = $qrcode2['name'];
        //        error_log(json_encode("PIPE1".json_encode($qrcode1)));
        $paramString["Typ spoiny"] = $this->getTypSpoinyOpis();
        $gps = new ObjectGps();
        $gps->findMe($this);
        $paramString["Mapa google"] = $gps->returnGoogleMapPictureHref($gps->id);

        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    protected function getTypSpoinyOpis()
    {
        switch ($this->typSpoiny) {
            case DBObject2::JOINT_TYP_LINIA:
                return "LINIA";
            case DBObject2::JOINT_TYP_MONTAZ:
                return "MONTAŻ";
            case DBObject2::JOINT_TYP_HDD:
                return "HDD";
            case DBObject2::JOINT_TYP_DP:
                return  "DP";
            case DBObject2::JOINT_TYP_OSLONA:
                return "RURA OSŁONOWA";
            case DBObject2::JOINT_TYP_NAPRAWA:
                return "NAPRAWA";
            case DBObject2::JOINT_TYP_CUT_BADANIA:
                return "CIĘTA, Badania DT";
            case DBObject2::JOINT_TYP_CUT_NIEZGODNOSC: return "CIĘTA, Niezgodność";
            case DBObject2::JOINT_TYP_CUT_TECHNOLOGICZNE: return "CIĘTA, Cięcie technologiczne";

            default:
                return "NIEZNANE!";
        }
    }
    public function printStructure()
    {

        return "<div class='text-primary'>STRUKTURA elementy ObjectJoint</div>";
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

        $qrcode1 = $this->findRelative("qrcode", $this->elementId1);
        $qrcode2 = $this->findRelative("qrcode", $this->elementId2);
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);
        $gps = new ObjectGps();
        $gps->findMe($this);

        $paramString = "<div class='text-break  justify-content-end align-items-stretch col-12 text-cyan'>";
        $paramString .= PageElements::lineForElement("Numer spoiny ", $this->numerSpoiny);
        $paramString .= PageElements::lineForElement("WPS ", $this->WPS);
        $paramString .= PageElements::lineForElement("Typ spoiny ",  $this->getTypSpoinyOpis());
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("Data stworzenia ",  strtotime("Y-m-s",$this->time_added));
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("LEWY QR ",  $qrcode1['name']);
        $paramString .= PageElements::lineForElement("PRAWY QR ",  $qrcode2['name']);
        $paramString .= PageElements::lineForElement("LEWA RURA ", $element1->numerRury . "/" . $element1->wytop);
        $paramString .= PageElements::lineForElement("PRAWA RURA ", $element2->numerRury . "/" . $element2->wytop);
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("Mapa google ", $gps->returnGoogleMapPictureHref($gps->id, "50%", "50%"));
        $paramString .= "<hr>";
        $paramString .= "</div>";
        return $paramString;


        $paramString .= "</div>";
        return $paramString;
    }

    public function generateAncestor(array $params = null)
    {

        $id = parent::generateAncestor($params);
        $ancestor = new ObjectJoint($id);
        $ancestor->elementId1 = -1;
        $ancestor->elementId2 = -1;
        //$ancestor->typSpoiny = DBObject2::JOINT_TYP_NAPRAWA;  // to już powinno być w generateAncestor!
        $ancestor->updateToDB();
    }

    public function getObjectButton($color = "text-white", $title = null)
    {
        switch ($this->typSpoiny) {
            case DBObject2::JOINT_TYP_NAPRAWA:
                return parent::getObjectButton("text-gray-50", sprintf("[%'.6d] %s %s", $this->id, $this->numerSpoiny, $this->getTypSpoinyOpis()));
            default:
                return parent::getObjectButton("text-white", sprintf("[%'.6d] %s %s", $this->id, $this->numerSpoiny, $this->getTypSpoinyOpis()));
        }
    }
}
class ObjectJoint extends jointFunctions
{
}
