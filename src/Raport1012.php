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

class Raport1012 extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectJoint")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray[] =  "ID";
        $inputArray[] =  "Nr spoiny";
        $inputArray[] =  "Data spawania";
        $inputArray[] =  "Nazwa elementu 1";
        $inputArray[] =  "Wytop/nr rury 1";
        $inputArray[] =  "Długość fabryczna rury 1";
        $inputArray[] =  "Średnica 1";
        $inputArray[] =  "Długość do zabudowy 1";
        $inputArray[] =  "Nazwa elementu 2";
        $inputArray[] =  "Wytop/nr rury 2";
        $inputArray[] =  "Długość fabryczna rury 2";
        $inputArray[] =  "Średnica 2";
        $inputArray[] =  "Długość do zabudowy 2";
        $inputArray[] =  "Nr WPS";
        $inputArray[] =  "Przetop L";
        $inputArray[] =  "Przetop P";
        $inputArray[] =  "Wypełnienie L";
        $inputArray[] =  "Wypełnienie P";
        $inputArray[] =  "Lico L";
        $inputArray[] =  "Lico P";

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

    public function returnDataArray($className = "ObjectJoint", $where = '')
    {

        $element = new \qrcodeslibrary\ObjectJoint();
        $resultObjectArray = $element->getObjectList("SELECT * FROM " . $element->getTableName());
        $dataArray = null;
        foreach ($resultObjectArray as $key => $value) {
            if (($test = $value->returnTableArray1012($key)) != null) {
                $dataArray[] = $test;
            }
            //$test = $value->returnTableArray1012($key);
            //if(null != $test )
            //$dataArray[] = $test;
        }
        $result['data'] = $dataArray;
        return $result;
    }
}
