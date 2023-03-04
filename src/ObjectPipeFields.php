<?php
/**
 * Objects for qrcode project
 * ObjectPipe
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

class ObjectPipeFields extends DBObject2
{
    protected $tableName = "element";
    public $wytop;
    public $numerRury;
    public $numerObiektu;
    public $dlugoscFabryczna;
    public $dlugoscZabudowy; 
    public $gruboscScianki;
    public $gatunekMaterialu;
    public $dlugoscLuku;
    public $srednica;
    public $rodzajIzolacji;
    public $numerIzolacji;
    public $kilometraz;
    public $gpsLink;
    public $producent;
    public $nrProby;
    public $swiadectwoNaRure;
    public $swiadectwoNaIzolacje;
    public $rm;
    public $re;
}
?>