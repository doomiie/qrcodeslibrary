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

class RaportClass extends Raport
{
    public function getHeaders($switchReason = "HTML", $className = "qrcodeslibrary\ObjectElement")
    {
        $db = new DBHandler();
        $resultArray  = "";
        $inputArray = $this->getInputArray($className);

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
                    $resultArray .= sprintf("{label: '%s', name: '%s', },", $value, $value);
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

    protected function getInputArray($className)
    {
        $resultArray  = "";
        $inputArray[] = "Akcje";
        switch ($className) {
            case 'qrcodeslibrary\ObjectElement':
                $inputArray[] = "Nr wytopu / Nr rury";
                $inputArray[] = "Grubość ścianki";
                $inputArray[] = "Średnica";
                $inputArray[] = "Długość fabryczna";
                $inputArray[] = "Długość zabudowy";
                $inputArray[] = "Gatunek materiału";
                $inputArray[] = "Rodzaj izolacji";
                //$inputArray[] = "Nr rury";

                $inputArray[] = "Data dodania";
                $inputArray[] = "Ostatni Kilometraż";
                $inputArray[] = "Data kilometrażu";
                $inputArray[] = "Typ elementu";
                $inputArray[] = "Data";
                $inputArray[] = "ID Cięcia";
                $inputArray[] = "Data cięcia *";
                $inputArray[] = "ID gięcia";
                $inputArray[] = "Data gięcia";
                $inputArray[] = "QR spaw A";
                $inputArray[] = "QR spaw B";
                $inputArray[] = "QR spaw C";
                $inputArray[] = "QR spaw D";
                $inputArray[] = "QR A";
                $inputArray[] = "QR B";
                $inputArray[] = "QR C";
                $inputArray[] = "QR D";
                $inputArray[] = "Kody QR";
                //$inputArray[] = "id";
                //$inputArray[] = "active";
                break;
            case 'qrcodeslibrary\ObjectCut':
                $inputArray[] = "ID";
                $inputArray[] = "Typ cięcia";
                $inputArray[] = "Wytop/nr rury";
                $inputArray[] = "Do zabudowy/odpad element";
                $inputArray[] = "Mapy google";
                $inputArray[] = "Data";
                $inputArray[] = "Numer spoiny";

                break;
                //LINK - tu zbieramy BEND

            case 'qrcodeslibrary\ObjectBend':
                $inputArray[] = "ID";
                //$inputArray[] = "Typ cięcia";
                $inputArray[] = "Oryginalny element";
                $inputArray[] = "Nr wytopu";
                $inputArray[] = "Nr rury";
                $inputArray[] = "dataDodaniaGiecia";
                $inputArray[] = "Data";

                $inputArray[] = "Numer punktu załamania Pz / Pp";
                $inputArray[] = "Mapy google";

                $inputArray[] = "Grubość ścianki (mm)";
                $inputArray[] = "Rodzaj izolacji";
                $inputArray[] = "Rodzaj łuku (V,h)";
                $inputArray[] = "Promień gięcia";
                $inputArray[] = "Długość (m)";
                $inputArray[] = "Km trasy";
                $inputArray[] = "Kąt wg PT (⁰)";
                $inputArray[] = "Kąt wygiety (⁰)";
                $inputArray[] = "Owalizacja % P";
                $inputArray[] = "Owalizacja % S";
                $inputArray[] = "Owalizacja % K";
                $inputArray[] = "Izolacja A/N";
                $inputArray[] = "Ocena końcowa pozytywna/negatywna";

                break;
            case 'qrcodeslibrary\ObjectQrcode':
                $inputArray[] = "id";
                //$inputArray[] = "name";
                //$inputArray[] = "active";
                $inputArray[] = "time_added";
                $inputArray[] = "pozycja";
                $inputArray[] = "elementId";
                $inputArray[] = "Oryginalny element";
                $inputArray[] = "Ostatnia aktualizacja";
                break;
            case 'qrcodeslibrary\ObjectJoint':
                $inputArray[] = "Numer spoiny";
                $inputArray[] = "id";
                //$inputArray[] = "name";
                //$inputArray[] = "active";
                //$inputArray[] = "time_added";
                $inputArray[] = "Lewy element";
                $inputArray[] = "Prawy element";


                $inputArray[] = "Średnica";
                $inputArray[] = "Grubość ścianki";
                $inputArray[] = "Gatunek materiału";
                $inputArray[] = "Metoda spawania / nr WPS";
                $inputArray[] = "Typ spoiny";
                $inputArray[] = "Opis typu spoiny";
                $inputArray[] = "Uwagi";
                $inputArray[] = 'Data stworzenia';

                $inputArray[] = 'Spoina oryginalna';
                unset($inputArray['Akcje']);
                break;
            case 'qrcodeslibrary\ObjectGps':
                //$inputArray[] = "id";
                //$inputArray[] = "name";
                //$inputArray[] = "active";
                $inputArray[] = "time_added";
                $inputArray[] = "Pozycja";
                $inputArray[] = "Wytop/nr rury";
                $inputArray[] = "ID oryginału";
                $inputArray[] = "Typ referencji";
                $inputArray[] = "Użytkownik";
                break;
            case 'qrcodeslibrary\ObjectIsolation':
                //$inputArray[] = "id";
                //$inputArray[] = "name";
                //$inputArray[] = "active";
                $inputArray[] = "time_added";
                $inputArray[] = "Wytop/nr rury";
                $inputArray[] = "Rodzaj izolacji";
                $inputArray[] = "Numer partii";
                $inputArray[] = "Temperatura otoczenia";

                break;

            case 'qrcodeslibrary\ObjectMileage':
                $inputArray[] = "Oryginalny element";
                $inputArray[] = "Kilometraż";
                $inputArray[] = "Pozycja";
                $inputArray[] = "time_added";
                //$inputArray[] = "id";
                //$inputArray[] = "name";
                //$inputArray[] = "active";
                $inputArray[] = "Typ referencji";
                $inputArray[] = "Użytkownik";
                break;
            case 'qrcodeslibrary\ObjectZlecenie':


               
                $inputArray[] = "VT";
               // $inputArray[] = "VT Wynik";
                $inputArray[] = "RT";
               // $inputArray[] = "RT Wynik";
                $inputArray[] = "PT";
               // $inputArray[] = "PT Wynik";
                $inputArray[] = "MT";
               // $inputArray[] = "MT Wynik";
                $inputArray[] = "UT";
              //  $inputArray[] = "UT Wynik";
                $inputArray[] = "UTA";
              //  $inputArray[] = "UTA Wynik";
                $inputArray[] = "UTPA";
              //  $inputArray[] = "UTPA Wynik";
                $inputArray[] = "uwagi";
                $inputArray[] = "numerFaktury";
                $inputArray[] = "status";
                $inputArray[] = "numerZlecenia";
                $inputArray[] = "lokalizacjaAdministracyjna";
                $inputArray[] = "lokalizacjaWgTrasy";
                $inputArray[] = "zlecajacy";
                $inputArray[] = "dataZlecenia";
                $inputArray[] = "elementId";
                $inputArray[] = "numerSpoiny";
                $inputArray[] = "srednica";
                $inputArray[] = "gruboscScianki";
                $inputArray[] = "gatunekMaterialu";
                $inputArray[] = "metodaSpawaniaNrWps";
                break;
            default:
                $inputArray[] = "id";
                $inputArray[] = "name";
                $inputArray[] = "active";
                $inputArray[] = "time_added";
                break;
        }
        return $inputArray;
    }
}
