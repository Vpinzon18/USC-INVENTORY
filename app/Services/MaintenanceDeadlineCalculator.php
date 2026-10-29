<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class MaintenanceDeadlineCalculator
{
    /** Adds business weekdays, preserving the assignment time and application timezone. */
    public function addBusinessDays(CarbonInterface $start, int $days): CarbonImmutable
    {
        $date = CarbonImmutable::instance($start);
        $added = 0;

        while ($added < $days) {
            $date = $date->addDay();

            if (!$date->isWeekend()) {
                $added++;
            }
        }

        return $date;
    }
}
