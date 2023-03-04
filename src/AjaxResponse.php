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

class AjaxResponse
{

    //NOTE -  przykład wykorzystania
    // qrcodeslibrary\AjaxResponse::success($message, $desc, __FILE__,$refId, $object, array('ileZmienionych'=> $ileZmienionych));// id
    public static function fail($message, $error, $filename = __FILE__, $id = -1,$object = null, array $additionalArgs = null)
    {
        return self::response(false, $message, $error, $filename, $id, $object, $additionalArgs  );
    }
    public static function success($message, $error, $filename = __FILE__, $id = -1,$object = null, array $additionalArgs = null)
    {
        return self::response(true, $message, $error, $filename, $id, $object, $additionalArgs);
    }
    public static function response($result, $message, $error, $filename = __FILE__, $id = -1,$object = null, array $additionalArgs = null)
    {
        global $sql;
        $structure = "";
        $nextFreePosition = "";
        $historyTable = "";
        if(null != $object AND is_object($object))
        {
            $structure = method_exists($object, "printStructure") ?  $object->printStructure() :  "";
            $nextFreePosition = method_exists($object, "getNextFreePosition") ?  $object->getNextFreePosition() :  "";
            $historyTable = method_exists($object, "returnHistoryTable") ?  $object->returnHistoryTable($object->returnHistoryArrayForElement()) :  null;

        }


        $result = array(
            'result' => $result, 
            'input' => json_encode($_POST),
            'message' => $message,
            'error' => $error,
            'sql' => isset($sql) ? $sql : "",
            'history' => $historyTable,
            'structure' => $structure,
            'nextQR' => $nextFreePosition,
            'object' => $object,
            'id' => $id,
            'filename' => $filename
        );
        if(null != $additionalArgs)
        foreach ($additionalArgs as $key => $value) {
            $result[$key] = $value;
        }
        echo json_encode($result);
        exit;
    }
}
