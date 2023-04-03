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
    /**
     * 2023-03-14 pole uwag, nie wiem, czy potrzebne
     *
     * @var varchar 256
     */
    public $uwagi;
    /**
     * 
     * 2023-03-159 pole statusSpoiny
     * Używane do izolacji
     * A - oznacza poprawność i możliwość izolowania
     */
    public $statusSpoiny;
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
    /**
     * Dla obu kodów QR, sprawdzanie, czy nie ma tej samej rury etc
     *
     * @param mixed $qrCodeString1
     * @param mixed $qrCodeString2
     * 
     * @return [int] Error code
     * 
     * Created at: 3/27/2023, 1:08:26 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function testQRBoth($qrCodeString1, $qrCodeString2)
    {
        $qrCode1 = new ObjectQrcode();
        $qrCode1->loadFromDBFieldValue("qrCode", $qrCodeString1);
        $element1 = new ObjectElement($qrCode1->elementId);
        $qrCode2 = new ObjectQrcode();
        $qrCode2->loadFromDBFieldValue("qrCode", $qrCodeString2);
        $element2 = new ObjectElement($qrCode2->elementId);

        if ($element1->id == $element2->id) return DBObject2::QR_CIRCULLAR_CONNECTION;
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
        // SAME PIPE?
        $qrCode2 = new ObjectQrcode($this->$otherPosition);
        $elementOther = new ObjectElement($qrCode2->elementId);

        if ($element->id == $elementOther->id) return DBObject2::QR_FROM_SAME_PIPE;

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

    /**
     * [Description for getOtherPipeWithDirection]
     *
     * @param ObjectElement $pipe
     * 
     * @return id QRCODE!
     * 
     * Created at: 3/16/2023, 2:22:58 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function getOtherPipeWithDirection(ObjectElement $pipe)
    {
        // najpierw sprawdzenie, czy ten obiekt jest w joincie :)
        // $this->print();
        $qrCodes = $pipe->findRelativesElementId("qrcode");
        //print_r($qrCodes);
        foreach ($qrCodes as $key => $value) {
            //print_r($value);
            $qrNew = new ObjectQrcode($value['id']);
            if ($qrNew->id == $this->elementId1) {
                return $this->elementId2;
            } else
            if ($qrNew->id == $this->elementId2) {
                return $this->elementId1;
            }
        }
        return -1;
    }

    public function returnTableArray1015()
    {

        $elementId1  = $this->elementId1;
        $elementId2  = $this->elementId2;

        // jestem spoiną z ancestorem!
        if ($this->elementId1 == -1 and $this->elementId2 == -1 and $this->ancestorID != 0) {
            $ancestor = new ObjectJoint($this->ancestorID);
            $elementId1  = $ancestor->elementId1;
            $elementId2  = $ancestor->elementId2;
        }

        $paramString["CHECK"] = null;
        $paramString["Numer spoiny"] = $this->numerSpoiny;

        $qrcode1 = $this->findRelative("qrcode", $elementId1);
        $qrcode2 = $this->findRelative("qrcode", $elementId2);
        $paramString['qrCode name']['1'] = $qrcode1['name'];
        $paramString['qrCode name']['2'] = $qrcode2['name'];
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);


        $paramString["ID"] = $this->id;
        $paramString["Średnica"] = $element1->srednica;
        $paramString["Grubość ścianki"] = $element1->gruboscScianki;
        $paramString["Gatunek materiału"] = $element1->gatunekMaterialu;
        $paramString["Metoda spawania / nr WPS"] = $this->getTypSpoinyOpis() . " / " . $this->WPS;
        $paramString["Typ spoiny"] = $this->typSpoiny;
        $paramString["Opis typu spoiny"] = $this->getTypSpoinyOpis();
        
        $paramString["_VT"] =  $this->id;
        $paramString["_RT"] =  $this->id;
        $paramString["_PT"] =  $this->id;
        $paramString["_UT"] =  $this->id;
        $paramString["_MT"] =  $this->id;
        $paramString["_UTA"] =  $this->id;
        $paramString["_UTPA"] =  $this->id;


        $paramString["Uwagi"] = $this->uwagi;
        $paramString['Data stworzenia'] = date("Y-m-d", strtotime($this->time_added));
        $ancestor = $this->getAncestor();
        $paramString['Spoina oryginalna'] = $ancestor->id > 0 ? sprintf("%s [%s]", $ancestor->numerSpoiny, $ancestor->id) : "";
        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }
    public function returnTableArray1012()
    {

        $elementId1  = $this->elementId1;
        $elementId2  = $this->elementId2;

        // jestem spoiną z ancestorem!
        if ($this->elementId1 == -1 and $this->elementId2 == -1 and $this->ancestorID != 0) {
            return null;    // nic nie zwracamy do 1012!            
        }

        $qrcode1 = $this->findRelative("qrcode", $elementId1);
        $qrcode2 = $this->findRelative("qrcode", $elementId2);
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);


        $paramString["ID"] = $this->id;
        $paramString["Nr spoiny"] = $this->numerSpoiny;
        $paramString["elementId1"] = $element1->id;
        $paramString["elementId2"] = $element2->id;
        $paramString["Nazwa elementu 1"] = $element1->getType();
        $paramString["Wytop/nr rury 1"] = $element1->wytop . "/" . $element1->numerRury;
        $paramString["Długość fabryczna rury 1"] = $element1->dlugoscFabryczna;
        $paramString["Średnica 1"] = $element1->srednica;
        $paramString["Długość do zabudowy 1"] = $element1->dlugoscZabudowy;
        $paramString["Nazwa elementu 2"] = $element2->getType();
        $paramString["Wytop/nr rury 2"] = $element2->wytop . "/" . $element2->numerRury;
        $paramString["Długość fabryczna rury 2"] = $element2->dlugoscFabryczna;
        $paramString["Średnica 2"] = $element2->srednica;
        $paramString["Długość do zabudowy 2"] = $element2->dlugoscZabudowy;
        $paramString["Nr WPS"] = $this->WPS;
        $paramString["Przetop L"] = $this->przetopL;
        $paramString["Przetop P"] = $this->przetopR;
        $paramString["Wypełnienie L"] = $this->wypelnienieL;
        $paramString["Wypełnienie P"] = $this->wypelnienieR;
        $paramString["Lico L"] = $this->licoL;
        $paramString["Lico P"] = $this->licoR;
        $paramString["Data spawania"] = $this->time_added;



        $qrcode1 = $this->findRelative("qrcode", $elementId1);
        $qrcode2 = $this->findRelative("qrcode", $elementId2);
        $paramString['qrCode name']['1'] = $qrcode1['name'];
        $paramString['qrCode name']['2'] = $qrcode2['name'];
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);



        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
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
        $paramString["Numer spoiny"] = $this->numerSpoiny;

        $qrcode1 = $this->findRelative("qrcode", $elementId1);
        $qrcode2 = $this->findRelative("qrcode", $elementId2);
        $paramString['qrCode name']['1'] = $qrcode1['name'];
        $paramString['qrCode name']['2'] = $qrcode2['name'];
        $element1 = new ObjectElement($qrcode1['elementId']);
        $element2 = new ObjectElement($qrcode2['elementId']);
        $paramString["Akcje"] = $this->getLinkToSingleElement("Element");;
        $paramString['Lewy element'] = $element1->getLinkToSingleElement($element1->wytop . "/" . $element1->numerRury);
        $paramString['Prawy element'] = $element2->getLinkToSingleElement($element2->wytop . "/" . $element2->numerRury);
        $paramString['Pipe name']['1'] = $element1->wytop . "/" . $element1->numerRury;
        $paramString['Pipe name']['2'] = $element2->wytop . "/" . $element2->numerRury;

        $paramString['Data stworzenia'] = date("Y-m-d", strtotime($this->time_added));
        //$paramString["elementId2"]['name']  = $qrcode2['name'];
        //        error_log(json_encode("PIPE1".json_encode($qrcode1)));
        $paramString["Typ spoiny"] = $this->getTypSpoinyOpis();
        $gps = new ObjectGps();
        $gps->findMe($this);
        $paramString["Mapa google"] = $gps->returnGoogleMapPictureHref($gps->id);

        $paramString["Średnica"] = $element1->srednica;
        $paramString["Grubość ścianki"] = $element1->gruboscScianki;
        $paramString["Gatunek materiału"] = $element1->gatunekMaterialu;
        $paramString["Metoda spawania / nr WPS"] = $this->getTypSpoinyOpis() . " / " . $this->WPS;
        $paramString["Typ spoiny"] = $this->typSpoiny;
        $paramString["Opis typu spoiny"] = $this->getTypSpoinyOpis();
        $paramString["Uwagi"] = $this->uwagi;
        $paramString['Data stworzenia'] = date("Y-m-d", strtotime($this->time_added));
        $ancestor = $this->getAncestor();
        $paramString['Spoina oryginalna'] = $ancestor->id > 0 ? sprintf("%s [%s]", $ancestor->numerSpoiny, $ancestor->id) : "";

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
                return "Badania DT";
            case DBObject2::JOINT_TYP_CUT_NIEZGODNOSC:
                return "Niezgodność";
            case DBObject2::JOINT_TYP_CUT_TECHNOLOGICZNE:
                return "Cięcie techn.";

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

        //$iso = new ObjectIsolation();
        $isoList = $this->findRelativesElementId("isolation", $this->id);
        $iso = new ObjectIsolation($isoList[0]['id']);

        $gps = new ObjectGps();
        $gps->findMe($this);

        $paramString = "<div class='text-break  justify-content-end align-items-stretch col-12 text-cyan'>";
        $paramString .= PageElements::lineForElement("Numer spoiny", $this->numerSpoiny);
        $paramString .= PageElements::lineForElement("WPS ", $this->WPS);
        $paramString .= PageElements::lineForElement("Typ spoiny ",  $this->getTypSpoinyOpis());
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("Data stworzenia ",  strtotime("Y-m-s", $this->time_added));
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("LEWY QR ",  $qrcode1['name']);
        $paramString .= PageElements::lineForElement("PRAWY QR ",  $qrcode2['name']);
        $paramString .= PageElements::lineForElement("LEWA RURA ", $element1->numerRury . "/" . $element1->wytop);
        $paramString .= PageElements::lineForElement("PRAWA RURA ", $element2->numerRury . "/" . $element2->wytop);
        $paramString .= "<hr>";
        $paramString .= PageElements::lineForElement("Wynik spoiny ", $this->statusSpoiny);
        $paramString .= PageElements::lineForElement("Izolacja, ID", $iso->id . " | " . $iso->time_added);
        $paramString .= "<hr class='mb-2'>";
        $paramString .= PageElements::lineForElement("Mapa google ", $gps->returnGoogleMapPictureHref($gps->id, "50%", "50%"));
        $paramString .= "<hr>";
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


    public function ajaxObjectStatus()
    {
        $tableName = $this->getTableName();
        $params = array(
            'className' => 'ObjectJoint',
            'class' => 'ObjectJoint',
            'index' => $this->id
        );
        $arrayHtml = "<div class='position-relative text-white'>";
        $arrayHtml .= "<button class='btn btn-lg btn-warning m-1 input-block-level form-control'>" . PageElements::jsLinkString(sprintf('gotoLinkWithPost(\'joint-single-fix.php\',\'%s\');', htmlentities(json_encode($params))), "NAPRAWA", "opis testu") . "</button>";
        $arrayHtml .= "</div>";

        //$arrayHtml = PageElements::addButtonString("NAPRAWA", "var key = { elementId: '$this->id'}; qs_addObject('qrcodeslibrary+ObjectIsolation', key, funkcjaPokazStatusIzolacji)", "btn-warning");
        if ($this->statusSpoiny == 'A') {
            //NOTE - W tym miejscu ustawiam defaultowe parametry izolacji
            //REVIEW - to nie jest dobra praktyka...
            $arrayHtml .= PageElements::addButtonString("IZOLUJ", "var key = { elementId: '$this->id', rodzajIzolacji: 'biuro', numerPartii: 'biuro', temperaturaOtoczenia: 'biuro' }; qs_addObject('qrcodeslibrary+ObjectIsolation', key, funkcjaPokazStatusIzolacji)", "btn-warning");

        }
        //$arrayHtml = PageElements::addButtonString("IZOLUJ", "gotoElementSingle($tableName, $this->id)", "btn-warning");        
        return $arrayHtml;

        //qs_addObject(className, params, callbackfunction
    }
}
class ObjectJoint extends jointFunctions
{
}
