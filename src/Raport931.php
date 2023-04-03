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

class Raport931 extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray[] =  "Akcje";
        $inputArray[] =  "ID";
        $inputArray[] =  "Data";
        $inputArray[] =  "Wytop/nr rury  (narastająco wraz z kilometrażem)";
        $inputArray[] =  "Długość rury (zabudowa)";
        $inputArray[] =  "Lokalizacja w km trasy (projekt)";
        $inputArray[] =  "Lokalizacja w km trasy (faktyczna)";
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
    public function returnDataArrayDeprecated($className = "ObjectElement")
    {
        $element = new \qrcodeslibrary\ObjectMileage();
        $resultObjectArray = $element->getObjectList("SELECT * FROM " . $element->getTableName() . " ORDER BY kilometraz ASC;");
        $dataArray = null;
        foreach ($resultObjectArray as $key => $value) {
            $dataArray[] = $value->returnTableArray931($key);
        }
        $result['data'] = $dataArray;
        return $result;
    }

    public function returnDataArray(string $className = "ObjectElement"): array
    {
        $mileageObj = new \qrcodeslibrary\ObjectMileage();
        $resultObjectArray = $mileageObj->getObjectList(
            "SELECT * FROM {$mileageObj->getTableName()} ORDER BY kilometraz ASC;"
        );

        $dataArray = array_map(function ($value) {
            return $value->returnTableArray931();
        }, $resultObjectArray);

        return ['data' => $dataArray];
    }
}
