<?php

/**
 * Objects for qrcode project
 * ObjectZlecenie dla 1015
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

class zlecenieFields extends DBObject2
{
    protected $tableName = "1015zlecenie";
    public $elementId;
    public $numerSpoiny;
    public $srednica;
    public $gruboscScianki;
    public $gatunekMaterialu;
    public $metodaSpawaniaNrWps;
    public $VT;
    public $RT;
    public $PT;
    public $MT;
    public $UT;
    public $UTA;
    public $UTPA;
    public $uwagi;
    public $numerFaktury;
    public $status;
    public $numerZlecenia;
    public $lokalizacjaAdministracyjna;
    public $lokalizacjaWgTrasy;
    public $zlecajacy;
    public $dataZlecenia;
    const badaniaArray = array(
        'VT' => array('name' => 'VT'),
        'RT' => array('name' => 'RT'),
        'PT' => array('name' => 'PT'),
        'MT' => array('name' => 'MT'),
        'UT' => array('name' => 'UT'),
        'UTA' => array('name' => 'UTA'),
        'UTPA' => array('name' => 'UTPA')
    );
}
class zlecenieFunctions extends zlecenieFields
{
    public function returnTableArray()
    {
        
        $reflect = new \ReflectionClass('qrcodeslibrary\ObjectZlecenie');
        $props   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($props as $prop) {
            $propname = $prop->getName();
            $paramString[$propname] = $this->$propname;
        }
        // przyciski statusów
        foreach (ObjectZlecenie::badaniaArray as $key => $value) {
            # code...
            $paramString[$key] = $this->getDescription($this->$key,'',$key);
        }
        $results = $this->getResults();

        $stringAllFields = '';

        foreach ($results as $key => $value) {
            # code...
            // TODO Opracować sposób na zatwierdzanie wyników (zmianę statusu zlecenia)
            // NOTE wstawiamy button, jeśli status  == 202 !
            $typek = $value['typ'];
            
            //error_log(json_encode($value));
            //error_log(json_encode($typek));
            if ($this->$typek == DBObject2::ZLECENIE_STATUS_REVIEW) {
                $paramString[$value['typ']] = $this->getDescription(DBObject2::ZLECENIE_STATUS_REVIEW, $value['wynik'], $value['typ'], $value['id'],$value['typWielkoscNiezgodnosci']);
                $stringAllFields .= ",".$value['typ'];
            }
        }
        $stringAllFields .= '';
        $stringAllFields = sprintf("'%s','%s',localFunctionRefreshTable", $this->id, $stringAllFields);
        
        $paramString['Akcje'] = PageElements::addButtonViewItemInTable($this->getTableName(), $this->id) . "<br>" . 
        PageElements::addButtonActionParams('functionAcceptResults1015ALL',$stringAllFields, "buttonAcceptAll" . $this->id,"fa-close");
        return $paramString;
    }
    function getResults()
    {
        $result = $this->findTableFieldValue('1015wynik', 'elementId', $this->id);
        return $result;
    }
    function getDescription($value, $wynik = '', $field='VT', $resultID=-1, $typWielkoscNiezgodnosci='')
    {
        switch ($value) {
            case 0:
                # code...                
                return "<span data-status=0></span>";
                break;
            case DBObject2::ZLECENIE_STATUS_OPEN:
                # code...                
                return "<span data-status=201>Otwarte". PageModals::modal1015ZlecenieWynikButton($this, $field)."</span>";
                break;
            case DBObject2::ZLECENIE_STATUS_REVIEW:
                # code...       
                $dataSpan = ($wynik == 'A') ? "<span data-status=202>" : "<span data-status=204>";
                //if($wynik == 'A') $dataSpan = "<span data-status=202>"; else $dataSpan = "<span data-status=204>";
                return $dataSpan.$wynik . "<br>" . (($typWielkoscNiezgodnosci=='Brak')?'':$typWielkoscNiezgodnosci). "</span>" ;// . PageElements::addButtonActionParams('functionAcceptResults1015', sprintf("'%s','%s',%s", $this->id,$field, 'localFunctionRefreshTable' ),$this->id."_button",'fa-check');
                break;
            case DBObject2::ZLECENIE_STATUS_CLOSED:
                # code...                
                return "<span data-status=203>OK</span>";
                break;
            default:
                # code...
                return "<span data-status=0>BROKEN!</span>";
                break;
        }
    }
    public function printDIV()
    {
        $tempArray = '';
        foreach (ObjectZlecenie::badaniaArray as $key => $value) {
            # code...
            $tempArray .= sprintf("<div>%s: <span class='text-white'>%s</span></div>", $key, $this->$key);
            $tempArray .= sprintf("<div><span class='text-white muted'>%s</span></div>", DBObject2::getStatusDescription((int)$this->$key));
        }
        return sprintf(
            "
        <div class='text-break text-cyan'>
        <div>Numer zlecenia: <span class='text-white'>%s</span></div>
        <div>Data zlecenia: <span class='text-white'>%s</span></div>
        <div>Numer spoiny: <span class='text-white'>%s</span></div>
        <div>ID elementu: <span class='text-white'>%s</span></div>
        <div>Status zlecenia: </div>       
        %s
        </div>
        <hr>
        ",
            $this->numerZlecenia,
            $this->dataZlecenia,
            $this->numerSpoiny,
            $this->elementId,
            $tempArray
        ) . parent::printDIV();
        return  parent::printDIV();
    }
    public function printStructure()
    {
        return "<div class='text-primary'>STRUCTURE</div>";
    }
}
class ObjectZlecenie extends zlecenieFunctions
{
}
