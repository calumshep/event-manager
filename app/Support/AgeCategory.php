<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Date;

class AgeCategory
{
    private const U8_AGES  = [ 6,  7];
    private const U10_AGES = [ 8,  9];
    private const U12_AGES = [10, 11];
    private const U14_AGES = [12, 13];
    private const U16_AGES = [14, 15];
    private const U18_AGES = [16, 17];
    private const U21_AGES = [18, 20];
    private const SEN_AGES = [21, 29];

    /**
     * Age categories, ordered from youngest to oldest
     */
    public const CATEGORIES = [
        "U8",
        "U10",
        "U12",
        "U14",
        "U16",
        "U18",
        "U21",
        "SEN",
        "MAS"
    ];

    /**
     * Get the category-adjusted years of birth corresponding to the given array of ages.
     * Will return years of birth which would put trainees in new-season categories if the current system date is 1st
     * May or later.
     *
     * @param array $ages
     * @return array
     */
    public static function years(array $ages)
    {
        $years = [];

        foreach ($ages as $age) {
            if (Carbon::now()->isAfter(Carbon::createFromDate(Carbon::now()->year, 5, 1))) {
                // Categories have switched over, calculate using higher age bands
                $years[] = Date::now()->year - $age;
            } else {
                // Categories are still on old season, calculate using lower bands
                $years[] = Date::now()->year - $age - 1;
            }
        }

        return $years;
    }

    /**
     * Get the category corresponding to the given year of birth
     *
     * @param int $year
     * @return string
     */
    public static function category(int $year)
    {
        if        (in_array($year, self::u8()))  {
            return self::CATEGORIES[0];
        } else if (in_array($year, self::u10())) {
            return self::CATEGORIES[1];
        } else if (in_array($year, self::u12())) {
            return self::CATEGORIES[2];
        } else if (in_array($year, self::u14())) {
            return self::CATEGORIES[3];
        } else if (in_array($year, self::u16())) {
            return self::CATEGORIES[4];
        } else if (in_array($year, self::u18())) {
            return self::CATEGORIES[5];
        } else if (in_array($year, self::u21())) {
            return self::CATEGORIES[6];
        } else if (in_array($year, self::sen())) {
            return self::CATEGORIES[7];
        } else {
            return self::CATEGORIES[8];
        }
    }

    /**
     * Get years of birth corresponding to U8 category
     * @return array
     */
    public static function u8()
    {
        return self::years(self::U8_AGES);
    }

    /**
     * Get years of birth corresponding to U10 category
     * @return array
     */
    public static function u10()
    {
        return self::years(self::U10_AGES);
    }

    /**
     * Get years of birth corresponding to U12 category
     * @return array
     */
    public static function u12()
    {
        return self::years(self::U12_AGES);
    }

    /**
     * Get years of birth corresponding to U14 category
     * @return array
     */
    public static function u14()
    {
        return self::years(self::U14_AGES);
    }

    /**
     * Get years of birth corresponding to U16 category
     * @return array
     */
    public static function u16()
    {
        return self::years(self::U16_AGES);
    }

    /**
     * Get years of birth corresponding to U18 category
     * @return array
     */
    public static function u18()
    {
        return self::years(self::U18_AGES);
    }

    /**
     * Get years of birth corresponding to U21 category
     * @return array
     */
    public static function u21()
    {
        return self::years(range(self::U21_AGES[0], self::U21_AGES[1]));
    }

    /**
     * Get years of birth corresponding to SEN category
     * @return array
     */
    public static function sen()
    {
        return self::years(range(self::SEN_AGES[0], self::SEN_AGES[1]));
    }

    /**
     * Get years of birth corresponding to MAS category
     * @return array
     */
    public static function mas()
    {
        return self::years(range(self::SEN_AGES[1]+1, 100));
    }
}
