<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class BanglaCalendar
{
    protected const MONTHS = [
        ['name' => 'বৈশাখ', 'start' => ['month' => 4, 'day' => 14]],
        ['name' => 'জ্যৈষ্ঠ', 'start' => ['month' => 5, 'day' => 15]],
        ['name' => 'আষাঢ়', 'start' => ['month' => 6, 'day' => 15]],
        ['name' => 'শ্রাবণ', 'start' => ['month' => 7, 'day' => 16]],
        ['name' => 'ভাদ্র', 'start' => ['month' => 8, 'day' => 17]],
        ['name' => 'আশ্বিন', 'start' => ['month' => 9, 'day' => 17]],
        ['name' => 'কার্তিক', 'start' => ['month' => 10, 'day' => 18]],
        ['name' => 'অগ্রহায়ণ', 'start' => ['month' => 11, 'day' => 17]],
        ['name' => 'পৌষ', 'start' => ['month' => 12, 'day' => 17]],
        ['name' => 'মাঘ', 'start' => ['month' => 1, 'day' => 15]],
        ['name' => 'ফাল্গুন', 'start' => ['month' => 2, 'day' => 14]],
        ['name' => 'চৈত্র', 'start' => ['month' => 3, 'day' => 15]],
    ];

    protected const BN_DIGITS = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    public static function format(CarbonInterface $dateTime): string
    {
        [$day, $monthName, $year] = self::convertToBanglaDate($dateTime);

        return sprintf('%s %s %s', self::convertNumber($day), $monthName, self::convertNumber($year));
    }

    protected static function convertToBanglaDate(CarbonInterface $dateTime): array
    {
        $date = Carbon::parse($dateTime)->setTimezone(setting('timezone', config('app.timezone')));

        $banglaYearStart = Carbon::create($date->year, 4, 14, 0, 0, 0, $date->timezone);
        if ($date->lt($banglaYearStart)) {
            $banglaYearStart->subYear();
        }

        $banglaYear = $banglaYearStart->year - 593;

        $monthBoundaries = self::buildMonthBoundaries($banglaYearStart);
        $monthIndex = 0;

        foreach ($monthBoundaries as $index => $boundary) {
            if ($date->gte($boundary['start'])) {
                $monthIndex = $index;
            }
        }

        $startDate = $monthBoundaries[$monthIndex]['start'];
        $nextIndex = ($monthIndex + 1) % count($monthBoundaries);
        $nextStart = $monthBoundaries[$nextIndex]['start'];

        $day = $startDate->diffInDays($date) + 1;

        if ($monthIndex === 10 && $date->isLeapYear()) {
            $day = min($day, $nextStart->diffInDays($startDate) + 1);
        }

        return [$day, $monthBoundaries[$monthIndex]['name'], $banglaYear];
    }

    protected static function buildMonthBoundaries(CarbonInterface $yearStart): array
    {
        $boundaries = [];

        foreach (self::MONTHS as $month) {
            $monthYear = $month['start']['month'] >= 4 ? $yearStart->year : $yearStart->year + 1;

            $start = Carbon::create(
                $monthYear,
                $month['start']['month'],
                $month['start']['day'],
                0,
                0,
                0,
                $yearStart->timezone
            );

            $boundaries[] = [
                'name' => $month['name'],
                'start' => $start,
            ];
        }

        return $boundaries;
    }

    protected static function convertNumber(int $number): string
    {
        return str_replace(range(0, 9), self::BN_DIGITS, (string) $number);
    }
}
