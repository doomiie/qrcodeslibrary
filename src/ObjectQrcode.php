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
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;



class qrcodeFields extends DBObject2
{
    protected $tableName = "qrcode";
    public $elementId = -1;
    public $pozycja;
    public $qrCode;
}
class qrcodeFunctions extends qrcodeFields
{
    public function removeFromElement()
    {
        if ($this->elementId == -1) return DBObject2::QR_ALREADY_DISCONNECTED;
        \Database\DBLog::UserLog($this, "REMOVE QR FROM ELEMENT", basename(__FILE__) . "[" . __LINE__ . "]", "", null);
        $elementId = $this->elementId;
        $this->elementId = -1;
        // Dodane wyczyszczenie pozycji!
        $this->pozycja = "";
        $this->updateToDB();
        return $elementId; // zwracam ID rury, do której było podpięte
    }

    public function getSibling()
    {
        if ($this->elementId == -1) return DBObject2::OBJECT_NOT_FOUND;
        // w tym miejscu zakładam, że pozycja jest prawidłowa!
        // znajdź wszystkie kody
        $siblingList = $this->findRelativesByField($this->tableName, "elementId");
        $search = "";
        if ($this->pozycja == "A") $search = "B";
        if ($this->pozycja == "B") $search = "A";
        if ($this->pozycja == "C") $search = "D";
        if ($this->pozycja == "D") $search = "C";
        foreach ($siblingList as $key => $value) {
            if ($value['pozycja'] == $search) {
                $temp = new self();
                $temp->loadFromArray($value);
                return $temp;
            }
        }
        return DBObject2::OBJECT_NOT_FOUND;
    }

    public function getOtherEnd()
    {
        if ($this->elementId == -1) return DBObject2::OBJECT_NOT_FOUND;
        // w tym miejscu zakładam, że pozycja jest prawidłowa!
        // znajdź wszystkie kody
        $siblingList = $this->findRelativesByField($this->tableName, "elementId");
        $search = "";
        if ($this->pozycja == "A") $search = "C";
        if ($this->pozycja == "B") $search = "C";
        if ($this->pozycja == "C") $search = "A";
        if ($this->pozycja == "D") $search = "A";
        foreach ($siblingList as $key => $value) {
            if ($value['pozycja'] == $search) {
                $temp = new self();
                $temp->loadFromArray($value);
                return $temp;
            }
        }
        return DBObject2::OBJECT_NOT_FOUND;
    }

    /**
     * Zwraca joint, w którym jest dany qrCode
     *
     * @return ObjectJoint
     * 
     * Created at: 3/16/2023, 1:01:31 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function getJointImQrCode()
    {
        $row = $this->findJoints('joint');
        //print_r($row);
        if (null == $row) return null;
        return new ObjectJoint($row[0]['id']);
    }

    public function printDIV()
    {
        return sprintf(
            "
        <div class='text-break text-cyan'>
        <div>KOD: <span class='text-white'>%s</span></div>
        <div>Pozycja: <span class='text-white'>%s</span></div>
        <div>ID elementu: <span class='text-white'>%s</span></div>
        </div>
        <div>Kod QR: </div>
        <span class='text-white'>%s</span><span class='text-white'>%s</span>
        ",
            $this->qrCode,
            $this->pozycja,
            $this->elementId,
            $this->getQRCodeSVGString(),
            $this->getQRCodeSVGString($this->name)
            
        );
        // . parent::printDIV();
    }

    public function returnTableArray()
    {
        $paramString["Akcje"] = "BRAK";
        $paramString["Oryginalny element"] = "BRAK";
        if ($this->elementId > 0) {
            $elementOriginal = new ObjectElement($this->elementId);
            $paramString["Oryginalny element"]  = $elementOriginal->getLinkToSingleElement($elementOriginal->wytop . "/" . $elementOriginal->numerRury);
        }
        $paramString["Ostatnia aktualizacja"] = $this->time_updated;

        $paramString = array_merge($paramString, parent::returnTableArray());
        return $paramString;
    }

    public function getQRCodeSVGString($string = null, $size=200)
    {

        $renderer = new ImageRenderer(
            new RendererStyle($size,1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        return $writer->writeString($string === null?$this->qrCode:$string);
    }

    public function getQRCodeDiv($string = null)
    {
        return sprintf("<div style='width:100%; height:100%;' >" . $this->getQRCodeSVGString($string) . "</div>");
    }
}
class ObjectQrcode extends qrcodeFunctions
{

   
}
