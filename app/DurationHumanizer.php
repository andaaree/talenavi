<?php
namespace App;

use Carbon\CarbonInterval;

trait DurationHumanizer
{

    public function convert($totalSeconds){
        $duration = CarbonInterval::seconds($totalSeconds)->cascade()->format('%H:%I:%S');
        return $duration;
    }
}
