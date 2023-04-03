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
class Raport1010 extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray[] =  "ID";
        $inputArray[] =  "Data cięcia";
        $inputArray[] =  "Grubość ścianki";
        $inputArray[] =  "Średnica";
        $inputArray[] =  "Wytop/nr rury";
        $inputArray[] =  "Długość fabryczna rury";
        $inputArray[] =  "Długość do zabudowy";
        $inputArray[] =  "Nr spoiny";
        $inputArray[] =  "Kontrola";
        $inputArray[] =  "Ocena";
        $inputArray[] =  "Odcinek pozostały";
        $inputArray[] =  "Uwagi";
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

    public function returnDataArray($className = "ObjectElement")
    {
        $element = new \qrcodeslibrary\ObjectCut();
        $resultObjectArray = $element->getObjectList("SELECT * FROM " . $element->getTableName());
        $dataArray = null;
        foreach ($resultObjectArray as $key => $value) {
            $dataArray[] = $value->returnTableArray1010($key);
        }
        $result['data'] = $dataArray;
        return $result;
    }
}
