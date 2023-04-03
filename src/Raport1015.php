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

class Raport1015 extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray[] =  "CHECK";
        $inputArray[] =  "ID";
        $inputArray[] =  "Numer spoiny";
        $inputArray[] =  "Średnica";
        $inputArray[] =  "Grubość ścianki";
        $inputArray[] =  "Gatunek materiału";
        $inputArray[] =  "Metoda spawania / nr WPS";
        $inputArray[] =  "Typ spoiny";
        $inputArray[] =  "Opis typu spoiny";
        $inputArray[] =  "Data stworzenia";
        $inputArray[] =  "Spoina oryginalna";
        $inputArray[] =  "_VT";
        $inputArray[] =  "_RT";
        $inputArray[] =  "_PT";
        $inputArray[] =  "_MT";
        $inputArray[] =  "_UT";
        $inputArray[] =  "_UTA";
        $inputArray[] =  "_UTPA";
        $inputArray[] =  "Uwagi";
        switch ($switchReason) {
            case 'HTML':
                foreach ($inputArray as $key => $value) {
                    $resultArray .= sprintf("<td>%s</td>", $value);
                }
                break;
            case 'DATA':
                foreach ($inputArray as $key => $value) {
                    if (strpos($value, "_") === false) {
                        $resultArray .= sprintf("{data: '%s'},", $value);
                    } else {
                        $resultArray .= sprintf("{data: '%s', render: function ( data, type, row ) {
                            if ( type === 'display' ) {
                                return '<input data-type=\'%s\' type=\'checkbox\' class=\'editor-active\' id=\'input%s_'+data+'\'>';
                                
                            }
                            return data;
                        },},", $value,ltrim($value,"_"),  $value);
                    }
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
        $element = new \qrcodeslibrary\ObjectJoint();
        $resultObjectArray = $element->getObjectList("SELECT * FROM " . $element->getTableName());
        $dataArray = null;
        foreach ($resultObjectArray as $key => $value) {
            $dataArray[] = $value->returnTableArray1015($key);
        }
        $result['data'] = $dataArray;
        return $result;
    }
}
