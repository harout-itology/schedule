<?php

namespace App\Http\Lib;

use Carbon\Carbon;

class Slots
{
    public const SLOT_DURATION = 15;
    public const MAX_BREAKS = 4;

    public function handler(array $request): array
    {
        $totalSlots = [];

        foreach ($request as $schedule) {
            $totalSlots = array_merge($totalSlots, $this->setSchedule($schedule));
        }
        ksort($totalSlots);

        return array_values($totalSlots);
    }

    public function setSchedule(array $schedule): array
    {
        $employeeSlots = [];

        $start = new Carbon($schedule['startDate'] . ' ' . $schedule['startTime']);
        $end = new Carbon($schedule['endDate'] . ' ' . $schedule['endTime']);
        $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);

        $startBreaks = [];
        $endBreaks = [];

        for($break = 1; $break <= self::MAX_BREAKS; $break++) {
            $postfix = $break === 1 ? '' : $break;
            if ($schedule['startBreak' . $postfix] !== '00:00:00' && $schedule['endBreak' . $postfix] !== '00:00:00') {
                $startBreaks[] = new Carbon($schedule['startDate'] . ' ' . $schedule['startBreak' . $postfix]);
                $endBreaks[] = new Carbon($schedule['endDate'] . ' ' . $schedule['endBreak' . $postfix]);
            }
        }

        static $employee = 0;
        $employee++;

        while ($singleSlot <= $end)
        {
            for($break = 0; $break < count($startBreaks); $break++) {
                if($singleSlot > $startBreaks[$break] && $singleSlot <= $endBreaks[$break]) {
                    $start = $endBreaks[$break]->copy();
                    $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
                }
            }

            $time = $start->format('Y-m-d H:i') . ' - ' . $singleSlot->format('H:i');
            $employeeSlots[$time . ' - ' . $employee] = $time . ' ' . $schedule['employeeName'];
            $start = $singleSlot->copy();
            $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
        }

        return $employeeSlots;
    }

}
