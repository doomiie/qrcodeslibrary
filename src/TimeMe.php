<?php
/**
 * Class to time shit
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

 class TimeMe 
 {
    private $time_start;
    private $time_end;
    private $total_time;

    public function __construct()
    {
        $this->time_start = microtime(true);
    }

    protected function stop()
    {
        $this->time_end = microtime(true);
        $this->total_time = ($this->time_end - $this->time_start);
        return $this->total_time;
    }

    public function log($info = '')
    {
        $total_time = $this->stop();
        return $total_time;
        //return sprintf("%s [%s s]",$info, $total_time);
    }
 }


?>