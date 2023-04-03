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

class Raport
{

    public function getHeaders($switchReason = "HTML", $className="qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        
        $object = new $className();
        $tableName = $object->getTableName();
        $row1 = $db->getRowSql("SELECT group_concat(COLUMN_NAME) as kolumny FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'qrcodes' AND TABLE_NAME = '" . $tableName . "';");
        $resultArray  = "";

        $buttonString = PageElements::addButtonViewItemInTable($object->getTableName(), $object->id);
        
        switch ($switchReason) {
            case 'HTML':
                //$resultArray .= sprintf("<td>Akcje</td>");
                foreach (explode(",", $row1[0]['kolumny']) as $key => $value) {
                    $resultArray .= sprintf("<td>%s</td>", $value);
                }
                break;
            case 'DATA':
                //$resultArray .= sprintf("{data: 'Akcje'},");
                foreach (explode(",", $row1[0]['kolumny']) as $key => $value) {
                    $resultArray .= sprintf("{data: '%s'},", $value);
                }
                //$resultArray .= sprintf("{data: null, render: function(val, type, row){ return 'to jest ok';}},");
                break;
            case 'EDIT':
                foreach (explode(",", $row1[0]['kolumny']) as $key => $value) {
                    $resultArray .= sprintf("{label: '%s', name: '%s'},", $value, $value);
                }
                //$resultArray .= sprintf("{label: 'CUSTOM', name: 'CUSTOM'},", $value, $value);
                break;
            case 'FIELD':
                //$row1[0]['kolumny']['Akcje'] = $buttonString();
                return  $row1[0]['kolumny'];
                break;

            default:
                # code...
                break;
        }

        return rtrim($resultArray, ',');
    }


    public function returnDataArray($className="ObjectElement", $where = "")
    {
        
        $className = "qrcodeslibrary\\" . $className;
        
        $element = new $className();
        $sql = "SELECT * FROM " . $element->getTableName() ." ". $where;
        //error_log($sql);
        $resultObjectArray = $element->getObjectList($sql);
        $dataArray = null;
        foreach ($resultObjectArray as $key => $value) {
            $dataArray[] = $value->returnTableArray($key);
        }
        $result['data'] = $dataArray;
        //error_log(json_encode($dataArray));
        return $result;


        $className = "qrcodeslibrary\\" . $className;
        $handler = new DBHandler();
        $obj = new $className();


        $sql = "SELECT * from ". $obj->getTableName();
        
        $result['data'] = $handler->getRowSql($sql);        
        return $result;
        

    }


}
