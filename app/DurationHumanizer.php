<?php
namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait DurationHumanizer
{

    public function convert($totalSeconds){
        return CarbonInterval::seconds($totalSeconds)->cascade()->format('%H:%I:%S');
    }

    public function diff($a,$b){
        $timeA = Carbon::parse($a);
        $timeB = Carbon::parse($b);
        return $timeA->diffInSeconds($timeB);
    }
}
