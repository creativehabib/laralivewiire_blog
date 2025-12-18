<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class BanglaCalendar
{
    protected const BN_MONTHS = [
        'বৈশাখ', 'জ্যৈষ্ঠ', 'আষাঢ়', 'শ্রাবণ', 'ভাদ্র', 'আশ্বিন',
        'কার্তিক', 'অগ্রহায়ণ', 'পৌষ', 'মাঘ', 'ফাল্গুন', 'চৈত্র'
    ];

    protected const BN_DIGITS = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

    public static function format(CarbonInterface $dateTime): string
    {
        [$day, $monthName, $year] = self::convertToBanglaDate($dateTime);
        return sprintf('%s %s %s', self::convertNumber($day), $monthName, self::convertNumber($year));
    }

    protected static function convertToBanglaDate(CarbonInterface $dateTime): array
    {
        $date = $dateTime->copy()->setTimezone(config('app.timezone'));

        // বর্তমান ইংরেজি তারিখ থেকে বছর, মাস, দিন নেওয়া
        $engDay = (int)$date->format('j');
        $engMonth = (int)$date->format('n');
        $engYear = (int)$date->format('Y');

        // বাংলা বছর বের করা
        $banglaYear = ($engMonth < 4 || ($engMonth == 4 && $engDay < 14)) ? $engYear - 594 : $engYear - 593;

        // মাসের দিনের হিসাব (বাংলাদেশী সংশোধিত নিয়ম)
        $isLeapYear = $date->isLeapYear();
        $monthDays = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, ($isLeapYear ? 31 : 30), 30];

        // ১৪ এপ্রিল থেকে আজকের দিনের ব্যবধান বের করা
        $yearStart = Carbon::create($engMonth < 4 || ($engMonth == 4 && $engDay < 14) ? $engYear - 1 : $engYear, 4, 14);
        $totalDays = (int)$yearStart->diffInDays($date);

        $runningDays = 0;
        $monthIndex = 0;

        foreach ($monthDays as $index => $days) {
            if ($totalDays < ($runningDays + $days)) {
                $monthIndex = $index;
                $day = $totalDays - $runningDays + 1;
                break;
            }
            $runningDays += $days;
        }

        return [$day, self::BN_MONTHS[$monthIndex], $banglaYear];
    }

    protected static function convertNumber(int $number): string
    {
        return str_replace(range(0, 9), self::BN_DIGITS, (string) $number);
    }
}
