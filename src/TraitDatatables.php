<?php

/**
 * QR Traits for QR classess
 * Database functions
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

use Database\DBHandler;

trait TraitDatatables
{
    private $db;
    /**
     * Do używania przy tworzeniu elementu tabeli w HTML
     *
     * @return [type]
     * 
     * Created at: 3/6/2023, 1:02:23 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function getFieldsForDatatableHTML($switchReason = "HTML")
    {
        $row1 = $this->dbHandler->getRowSql("SELECT group_concat(COLUMN_NAME) as kolumny FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'qrcodes' AND TABLE_NAME = '" . $this->getTableName() . "';");
        $resultArray  = "";
        $table = $this->getTableName();
        switch ($switchReason) {
            case 'HTML':
                foreach (explode(",", $row1[0]['kolumny']) as $key => $value) {
                    $resultArray .= sprintf("<td>%s</td>", $value);
                }
                //$resultArray .= sprintf("<td>CUSTOM</td>");
                break;
            case 'DATA':
                foreach (explode(",", $row1[0]['kolumny']) as $key => $value) {
                    $resultArray .= sprintf("{data: '%s.%s'},", $table, $value);
                    //$resultArray .= sprintf("{data: '%s'},", $value);
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
                return  $row1[0]['kolumny'];
                break;

            default:
                # code...
                break;
        }

        return rtrim($resultArray, ',');
    }

    public function getFieldsForDatatableHTMLAddCustomFields($switchReason, $value)
    {
        $resultArray  = "";
        switch ($switchReason) {
            case 'HTML':
                $resultArray .= sprintf("<td>%s</td>", $value);

                break;
            case 'DATA':
                $resultArray .= sprintf("{%s},", $value);
                //$resultArray .= sprintf("{data: null, render: function(val, type, row){ return 'to jest ok';}},");
                break;
            case 'EDIT':
                $resultArray .= sprintf("{label: '%s', name: '%s'},", $value, $value);
                //$resultArray .= sprintf("{label: 'CUSTOM', name: 'CUSTOM'},", $value, $value);
                break;
            case 'FIELD':
                return array($value);
                break;
            case 'LEFT':
                return "";
                break;

            default: return "Niezadeklarowane switchReason, " . __FILE__ . " " . __LINE__; break;
        }
        return $resultArray;
    }
}
