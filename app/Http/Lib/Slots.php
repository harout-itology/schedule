<?php

namespace App\Http\Lib;

use Carbon\Carbon;

class Slots
{
    public const SLOT_DURATION = 15;

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

        if ($schedule['startBreak'] != '00:00:00' && $schedule['endBreak'] != '00:00:00') {
            $startBreak = new Carbon($schedule['startDate'] . ' ' . $schedule['startBreak']);
            $endBreak = new Carbon($schedule['endDate'] . ' ' . $schedule['endBreak']);
        } else {
            $startBreak = $endBreak = $end;
        }

        if ($schedule['startBreak2'] != '00:00:00' && $schedule['endBreak2'] != '00:00:00') {
            $startBreak2 = new Carbon($schedule['startDate'] . ' ' . $schedule['startBreak2']);
            $endBreak2 = new Carbon($schedule['endDate'] . ' ' . $schedule['endBreak2']);
        } else {
            $startBreak2 = $endBreak2 = $end;
        }

        if ($schedule['startBreak3'] != '00:00:00' && $schedule['endBreak3'] != '00:00:00') {
            $startBreak3 = new Carbon($schedule['startDate'] . ' ' . $schedule['startBreak3']);
            $endBreak3 = new Carbon($schedule['endDate'] . ' ' . $schedule['endBreak3']);
        } else {
            $startBreak3 = $endBreak3 = $end;
        }

        if ($schedule['startBreak4'] != '00:00:00' && $schedule['endBreak4'] != '00:00:00') {
            $startBreak4 = new Carbon($schedule['startDate'] . ' ' . $schedule['startBreak4']);
            $endBreak4 = new Carbon($schedule['endDate'] . ' ' . $schedule['endBreak4']);
        } else {
            $startBreak4 = $endBreak4 = $end;
        }

        $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);

        static $employee = 0;
        $employee++;

        while ($singleSlot <= $end)
        {
            if($singleSlot > $startBreak && $singleSlot <= $endBreak) {
                $start = $endBreak->copy();
                $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
            }

            if($singleSlot > $startBreak2 && $singleSlot <= $endBreak2) {
                $start = $endBreak2->copy();
                $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
            }

            if($singleSlot > $startBreak3 && $singleSlot <= $endBreak3) {
                $start = $endBreak3->copy();
                $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
            }

            if($singleSlot > $startBreak4 && $singleSlot <= $endBreak4) {
                $start = $endBreak4->copy();
                $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
            }

            $time = $start->format('Y-m-d H:i') . ' - ' . $singleSlot->format('H:i');
            $employeeSlots[$time . ' - ' . $employee] = $time . ' ' . $schedule['employeeName'];
            $start = $singleSlot->copy();
            $singleSlot = $start->copy()->addMinutes(self::SLOT_DURATION);
        }

        return $employeeSlots;
    }

}
