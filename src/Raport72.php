<?php

/**
 * Page Elements for printing
 *
 * @see https://github.com/doomiie/gps/
 *
 *
 * @author Jerzy Zientkowski <jerzy@zientkowski.pl>
 * @copyright 2020 - 2023 Jerzy Zientkowski
 * @license FIXME need to have a licence
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace qrcodeslibrary;

use Database\DBHandler;

class Raport72 extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray[] = "ID";
        $inputArray[] = "Data";
        $inputArray[] = "Numer punktu załamania Pz / Pp";
        $inputArray[] = "Nr wytopu";
        $inputArray[] = "Nr rury";
        $inputArray[] = "Grubość ścianki ";
        $inputArray[] = "Rodzaj izolacji";
        $inputArray[] = "Rodzaj łuku (V,h)";
        $inputArray[] = "Rodzaj łuku (V,h) opis";
        $inputArray[] = "Promień gięcia";
        $inputArray[] = "Długość ";
        $inputArray[] = "Km trasy";
        $inputArray[] = "Kąt wg PT ";
        $inputArray[] = "Kąt wygięty ";
        $inputArray[] = "Owalizacja ";
        $inputArray[] = "Izolacja  A/N";
        $inputArray[] = "P";
        $inputArray[] = "S";
        $inputArray[] = "K";
        $inputArray[] = "Ocena końcowa pozytywna / negatywna";


        switch ($switchReason) {
            case 'HTML':
                foreach ($inputArray as $key => $value) {
                    $resultArray .= sprintf("<td>%s</td>", $value);
                }
                break;
            case 'DATA':
                foreach ($inputArray as $key => $value) {
                    $resultArray .= sprintf("{data: '%s'},", $value);
                }
                break;
            case 'EDIT':
                foreach ($inputArray as $key => $value) {
                    $resultArray .= sprintf("{label: '%s', name: '%s'},", $value, $value);
                }
                //$resultArray .= sprintf("{label: 'CUSTOM', name: 'CUSTOM'},", $value, $value);
                break;
            case 'FIELD':
                //return  $row1[0]['kolumny'];
                break;
            default:
                # code...
                break;
        }
        return rtrim($resultArray, ',');
    }
    public function returnDataArray($className = "ObjectElement", $where = "")
    {
        $t = new TimeMe();
        $element = new \qrcodeslibrary\ObjectBend();
        $resultObjectArray = $element->getObjectList("SELECT * FROM " . $element->getTableName());
        $dataArray = null;

        foreach ($resultObjectArray as $key => $value) {
            $dataArray[] = $value->returnTableArray72($key);
        }
        $result['data'] = $dataArray;
        error_log($t->log(__FILE__));
     
        return $result;
    }
}
