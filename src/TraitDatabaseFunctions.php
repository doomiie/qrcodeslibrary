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

use Database\ObjectUser;

trait TraitDatabaseFunctions
{
    /**
     * Funkcja zwraca WSZYSTKIE obiekty z danej tabel, które mają elementID taki, jak ID obiektu
     *
     * @param mixed $tableName
     * 
     * @return [type]
     * 
     * Created at: 2.02.2023, 12:20:01 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function findRelativesByField($tableName, $fieldName = "")
    {
        if ($fieldName == "")
            $fieldName = "id";
        $value = $this->$fieldName;
        $sql = "SELECT * from $tableName where $fieldName = $value;";
        //error_log($sql);
        $row = $this->dbHandler->getRowSql($sql);
        return $row;
    }
    public function  findRelatives($tableName)
    {
        return $this->findRelativesByField($tableName, "id");
    }
    /**
     * Zwraca row danych znalezionych w tabeli - nie obiekty!
     *
     * @param mixed $tableName
     * 
     * @return [array] row z tabeli danych
     * 
     * Created at: 2/11/2023, 3:05:27 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function  findRelativesElementId($tableName, $active = true)
    {
        $activeString = "";
        if ($active) $activeString = " AND active = 1";
        $sql = "SELECT * from $tableName where elementId = $this->id $activeString order by id desc;";
        
        $row = $this->dbHandler->getRowSql($sql);
        return $row;
    }
    /**
     * [Description for findRelative]
     * Funkcja znajduje JEDNEGO relative, o podanym ID, z tabeli
     *
     * @param mixed $tableName tabela wejściowa
     * @param mixed $id podane id
     * 
     * @return [array] zawartość bazy danych lub null, jeśli nie znaleziono
     * 
     * Created at: 2.02.2023, 12:23:07 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function findRelative($tableName, $id)
    {
        $tempID = $this->id;
        $this->id = $id;
        $row = $this->findRelatives($tableName);
        $this->id = $tempID;
        if (null == $row)
            return null;
        foreach ($row as $key => $value) {
            # code...
            if ($id == $value['id'])
                return $value;
        }
        return null;
    }
    /**
     * funkcja dla elementu znajduje wszystkie joints
     * //FIXME - To, oczywiście, zła funkcja - powinna być zamieniona na wyszukiwanie po QRCODE!
     *
     * @param mixed $tableName
     * 
     * @return [type]
     * 
     * Created at: 1.02.2023, 21:49:49 (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function findJoints($tableName)
    {
        $sql = "SELECT * from $tableName where elementId1 = $this->id or elementId2 = $this->id ;";
        //print_r($sql);
        $row = $this->dbHandler->getRowSql($sql);
        //print_r($row);
        return $row;
    }
    /**
     * Znajduje ROW ze spoinami, jeśli są
     *
     * @param mixed $tableName
     * @param string $LR wejściowy, 1 - dla LEWEJ spoiny, 2 dla prawej
     * 
     * @return array ze spoiną lub empty row, jeśli nic nie ma
     * 
     * Created at: 3/16/2023, 11:48:42 AM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    public function findJointsLRIMElement($LR = "1")
    {
        switch ($LR) {
            case '1':
                //printf("1 %s:%s\r\n",__FUNCTION__, $LR);
                $qr1 = $this->getQRCodeATPosition("A");
                $qr2 = $this->getQRCodeATPosition("B");
                break;
                case '2':
                //printf("2 %s:%s\r\n",__FUNCTION__, $LR);
                $qr1 = $this->getQRCodeATPosition("C");
                $qr2 = $this->getQRCodeATPosition("D");
                break;

            default:
            //print("default" . __FUNCTION__);
                return null;
                break;
        }
        //printf("QR1:%s,QR2:%s\r\n", $qr1->pozycja, $qr2->pozycja);
        //printf("QR1:%s,QR2:%s\r\n", is_object($qr1),is_object($qr2));
        $joint1 = null;
        if (is_object($qr1)) {
           // printf("QR1:%s\r\n", $qr1->id);
            $joint1 =  $qr1->getJointImQrCode(); // joint or null
        }
        if (is_object($qr2) AND $joint1 == null) {
            //printf("QR2:%s\r\n", $qr2->id);
            $joint1 = $qr2->getJointImQrCode(); // joint or null
        }
        return $joint1;


        
    }
    public function returnCount($tableName)
    {
        return count($this->findRelatives($tableName));
    }
    public function getJointsCount()
    {
        return count($this->findJoints("joint"));
    }
    public function getLoggedUserID()
    {
        if (isset($_SESSION['qrcode_user_id']))
            return $_SESSION['qrcode_user_id'];
        return -1;
    }
    /**
     * [Funkcja produkuje tablicę historii wszystkich elementów związanych z elementem]
     *
     * @return [type]
     * 
     * Created at: 2/11/2023, 3:20:18 PM (Europe/Warsaw)
     * @author     Jerzy "Doom_" Zientkowski 
     * @see       {@link https://github.com/doomiie} 
     */
    function returnHistoryArrayForElement()
    {
        $totalArray[] = $this->returnHistoryArray();
        foreach ((array)$this->getLogHistory() as $key => $value) {
            $totalArray[] = $value;
        }

        // kody QR
        $qrCodeList = $this->findRelativesElementId('qrcode');
        foreach ($qrCodeList as $key => $value) {
            $qrCode = new ObjectQrcode($value['id']);
            $totalArray[] = $qrCode->returnHistoryArray();
            foreach ((array)$qrCode->getLogHistory() as $key => $value) {
                $totalArray[] = $value;
            }
            // jointsy
            $jointList = $qrCode->findJoints("joint");
            foreach ($jointList as $key1 => $value1) {
                $joint = new ObjectJoint($value['id']);
                $totalArray[] = $joint->returnHistoryArray();
                foreach ((array)$joint->getLogHistory() as $key => $value) {
                    $totalArray[] = $value;
                }
            }
        }
        // mileage
        $mileageList = $this->findRelativesElementId('mileage');
        foreach ($mileageList as $key => $value) {
            $mileage = new ObjectMileage($value['id']);
            $totalArray[] = $mileage->returnHistoryArray();
            foreach ((array)$mileage->getLogHistory() as $key => $value) {
                $totalArray[] = $value;
            }
        }

        $order = array('time_added' => 'desc', 'id' => 'desc');



        usort($totalArray, function ($totalArray, $b) use ($order) {
            $t = array(true => -1, false => 1);
            $r = true;
            $k = 1;
            foreach ($order as $key => $value) {
                $k = ($value === 'asc') ? 1 : -1;
                $r = ($totalArray[$key] < $b[$key]);
                if ($totalArray[$key] !== $b[$key]) {
                    return $t[$r] * $k;
                }
            }
            return $t[$r] * $k;
        });

        //usort($totalArray, function ($a, $b) {  return $a['time_added'] <= $b['time_added'];  });

        return $totalArray;
    }

    public function getLogHistory()
    {
        /**!SECTION
         * SELECT * FROM `log` WHERE (refType = 'element' AND elementId = 2) AND (name != "SYSTEM") ORDER BY id DESC
         * 
         */
        // log
        $myTable = $this->getTableName();
        $sql = "SELECT * FROM `log`  WHERE ((elementId = $this->id) AND ( refType = '$myTable') AND (name != 'SYSTEM'))  ORDER BY ID DESC;";
        /*
        if($myTable == 'element')
        {
            $sql = "SELECT * FROM `log`  WHERE ((elementId = $this->id) AND ( refType = 'element') AND (name != 'SYSTEM')) UNION SELECT * FROM `log` WHERE ((refId = $this->id ) AND(name != 'SYSTEM')) ORDER BY ID DESC;";
        }
        else
        {
            $sql = "SELECT * FROM `log`  WHERE ((refId = $this->id ) AND (name != 'SYSTEM')) ORDER BY id DESC";
        }
        */


        // error_log("HISTORY FOR " . get_class($this) . " mytable is " . $myTable . "SQL is " . $sql);

        $logList = $this->dbHandler->getRowSql($sql);
        if (null == $logList) return null;
        if (count($logList) == 0) return null;
        //error_log("LOG LIST:" .json_encode($logList));
        foreach ($logList as $key => $value) {
            $log = new \Database\DBLog($value['id']);
            $totalArray[] = $log->returnHistoryArray();
            //error_log("HISTORY ARRAY FOR $log->id, $log->message");
            //echo json_encode($mileage->returnHistoryArray()) . "\r\n";
        }
        return $totalArray;
    }

    public function returnHistoryTable($tableArray)
    {
        $returnString = "<table class='table table-bordered' id='dataTable' width='100%' cellspacing='0'>
											<thead>
												<tr>
													<th>ID</th>
													<th>Klasa</th>
													<th>Data dodania</th>
													<th>Data aktualizacji</th>
													<th>Nazwa</th>
													<th>Typ</th>
													<th>Referencja</th>
													<th>Message</th>									
												</tr>
											</thead>
											<tfoot>
												<tr>
                                                <th>ID</th>
													<th>Klasa</th>
													<th>Data dodania</th>
													<th>Data aktualizacji</th>
													<th>Nazwa</th>
													<th>Typ</th>
                                                    <th>Referencja</th>
													<th>Message</th>
												</tr>
											</tfoot>
											<tbody id='historyTable'>";


        foreach ($tableArray as $key => $value) {
            # code...
            if ($value['id'] == -1) continue;

            if ($value['class'] == "Database\DBLog") {
                $user = new ObjectUser($value['name']);
            } else
                $user = new ObjectUser();
            $returnString .= sprintf('<tr>');
            $returnString .= sprintf('<td class="">%s</td>', $value['id']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['class']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['time_added']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['time_updated']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['name'] . ($user->id == -1 ? "" : " " . $user->username));
            $returnString .= sprintf('<td class="text-break">%s</td>', isset($value['refType']) ? $value['refType'] : "OBIEKT");
            $returnString .= sprintf('<td class="text-break">%s</td>', isset($value['refId']) ? $value['refId'] : $value['id']);
            $returnString .= sprintf('<td class="text-break">%s</td>', $value['message']);
            $returnString .= sprintf('</tr>');
        }

        $returnString .= "</tbody></table>";
        return $returnString;
    }
}
