<?php
/**
 * @filesource Kotchasan/Date.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan;

/**
 * คลาสจัดการเกี่ยวกับวันที่และเวลา
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Date
{
    /**
     * @var mixed
     */
    private static $lang;

    /**
     * class constructer
     */
    public function __construct()
    {
        self::$lang = Language::getItems(array(
            'DATE_SHORT',
            'DATE_LONG',
            'MONTH_SHORT',
            'MONTH_LONG',
            'YEAR_OFFSET'
        ));
    }

    /**
     * ฟังก์ชั่น คำนวนความแตกต่างของวัน (เช่น อายุ)
     * คืนค่า จำนวนวัน(ติดลบได้) ปี เดือน วัน [days, year, month, day] ที่แตกต่าง
     *
     * @assert (mktime(0, 0, 0, 2, 1, 2016), mktime(0, 0, 0, 3, 1, 2016)) [==]  array('days' => 29, 'year' => 0,'month' => 1, 'day' => 0)
     * @assert ('2016-3-1', '2016-2-1') [==]  array('days' => -29, 'year' => 0,'month' => 1, 'day' => 0)
     *
     * @param string|int  $begin_date วันที่เริ่มต้นหรือวันเกิด (Unix timestamp หรือ วันที่ รูปแบบ YYYY-m-d)
     * @param istring|int $end_date   วันที่สิ้นสุดหรือวันนี้ (Unix timestamp หรือ วันที่ รูปแบบ YYYY-m-d)
     *
     * @return array
     */
    public static function compare($begin_date, $end_date)
    {
        if (is_string($begin_date) && preg_match('/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})(\s([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}))?/', $begin_date, $match)) {
            $begin_date = mktime(0, 0, 0, (int) $match[2], (int) $match[3], (int) $match[1]);
        }
        if (is_string($end_date) && preg_match('/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})(\s([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}))?/', $end_date, $match)) {
            $end_date = mktime(0, 0, 0, (int) $match[2], (int) $match[3], (int) $match[1]);
        }
        if ($end_date == $begin_date) {
            // เท่ากัน
            return array(
                'days' => 0,
                'year' => 0,
                'month' => 0,
                'day' => 0
            );
        } else {
            // จำนวนวันที่แตกต่าง
            $days = floor(($end_date - $begin_date) / 86400);
            if ($end_date < $begin_date) {
                $tmp = $begin_date;
                $begin_date = $end_date;
                $end_date = $tmp;
            }
        }
        $Year1 = (int) date('Y', $begin_date);
        $Month1 = (int) date('m', $begin_date);
        $Day1 = (int) date('d', $begin_date);
        $Year2 = (int) date('Y', $end_date);
        $Month2 = (int) date('m', $end_date);
        $Day2 = (int) date('d', $end_date);
        // วันแต่ละเดือน
        $months = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        // ปีอธิกสุรทิน
        if (($Year2 % 4) == 0) {
            $months[2] = 29;
        }
        // ปีอธิกสุรทิน
        if ((($Year2 % 100) == 0) & (($Year2 % 400) != 0)) {
            $months[2] = 28;
        }
        if (abs($days) < $months[$Month1]) {
            // ไม่เกิน 1 เดือน
            return array(
                'days' => $days,
                'year' => 0,
                'month' => 0,
                'day' => abs($days)
            );
        } else {
            // ห่างกันเกิน 1 เดือน
            $YearDiff = $Year2 - $Year1;
            if ($Month2 >= $Month1) {
                $MonthDiff = $Month2 - $Month1;
            } else {
                --$YearDiff;
                $MonthDiff = 12 + $Month2 - $Month1;
            }
            if ($Day1 > $months[$Month2]) {
                $Day1 = 0;
            } elseif ($Day1 > $Day2) {
                $Month2 = $Month2 == 1 ? 13 : $Month2;
                $Day2 += $months[$Month2 - 1];
                --$MonthDiff;
            }
            return array(
                'days' => $days,
                'year' => $YearDiff,
                'month' => $MonthDiff,
                'day' => $Day2 - $Day1
            );
        }
    }

    /**
     * คืนค่าเวลาที่แตกต่าง หน่วย msec
     *
     * @assert ('08:00', '09:00') [==] 3600
     *
     * @param  $firstTime
     * @param  $lastTime
     *
     * @return int
     */
    public static function timeDiff($firstTime, $lastTime)
    {
        $firstTime = strtotime($firstTime);
        $lastTime = strtotime($lastTime);
        $timeDiff = $lastTime - $firstTime;
        return $timeDiff;
    }

    /**
     * แปลงตัวเลขเป็นชื่อวันตามภาษาที่ใช้งานอยู่
     * คืนค่า อาทิตย์...6 เสาร์
     *
     * @assert (0) [==] 'อา.'
     * @assert (0, false) [==] 'อาทิตย์'
     *
     * @param int  $date       0-6
     * @param bool $short_date true (default) วันที่แบบสั้น เช่น อ., false ชื่อเดือนแบบเต็ม เช่น อาทิตย์
     *
     * @return string
     */
    public static function dateName($date, $short_date = true)
    {
        // create class
        if (!isset(self::$lang)) {
            new static();
        }
        $var = $short_date ? self::$lang['DATE_SHORT'] : self::$lang['DATE_LONG'];
        return isset($var[$date]) ? $var[$date] : '';
    }

    /**
     * ฟังก์ชั่นแปลงเวลาเป็นวันที่ตามรูปแบบที่กำหนด สามารถคืนค่าวันเดือนปี พศ. ได้ ขึ้นกับไฟล์ภาษา
     * คืนค่า วันที่และเวลาตามรูปแบบที่กำหนดโดย $format
     *
     * @assert (0, 'y-m-d H:i:s') [==]  date('y-m-d H:i:s')
     * @assert (null) [==]  ''
     * @assert (1454259600, 'Y-m-d H:i:s') [==] '2559-02-01 00:00:00'
     *
     * @param int|string $time   int เวลารูปแบบ Unix timestamp, string เวลารูปแบบ Y-m-d หรือ Y-m-d H:i:s ถ้าไม่ระบุหรือระบุ หมายถึงวันนี้
     * @param string     $format รูปแบบของวันที่ที่ต้องการ (ถ้าไม่ระบุจะใช้รูปแบบที่มาจากระบบภาษา DATE_FORMAT)
     *
     * @return string
     */
    public static function format($time = 0, $format = null)
    {
        if ($time === 0) {
            $time = time();
        } elseif (is_string($time)) {
            if (preg_match('/^[0-9]+$/', $time)) {
                $time = (int) $time;
            } else {
                $time = strtotime($time);
            }
        } elseif (!is_int($time)) {
            return '';
        }
        // create class
        if (!isset(self::$lang)) {
            new static();
        }
        $format = empty($format) ? 'DATE_FORMAT' : $format;
        $format = Language::get($format);
        if (preg_match_all('/(.)/u', $format, $match)) {
            $ret = '';
            foreach ($match[0] as $item) {
                switch ($item) {
                    case ' ':
                    case ':':
                    case '/':
                    case '-':
                    case '.':
                    case ',':
                        $ret .= $item;
                        break;
                    case 'l':
                        $ret .= self::$lang['DATE_SHORT'][date('w', $time)];
                        break;
                    case 'L':
                        $ret .= self::$lang['DATE_LONG'][date('w', $time)];
                        break;
                    case 'M':
                        $ret .= self::$lang['MONTH_SHORT'][date('n', $time)];
                        break;
                    case 'F':
                        $ret .= self::$lang['MONTH_LONG'][date('n', $time)];
                        break;
                    case 'Y':
                        $ret .= (int) date('Y', $time) + (int) self::$lang['YEAR_OFFSET'];
                        break;
                    default:
                        $ret .= trim($item) == '' ? ' ' : date($item, $time);
                        break;
                }
            }
        } else {
            $ret = date($format, $time);
        }
        return $ret;
    }

    /**
     * แปลงตัวเลขเป็นชื่อเดือนตามภาษาที่ใช้งานอยู่
     * คืนค่า 1 มกราคม...12 ธันวาคม
     *
     * @assert (1) [==] 'ม.ค.'
     * @assert (1, false) [==] 'มกราคม'
     *
     * @param int  $month       1-12
     * @param bool $short_month true (default) ชื่อเดือนแบบสั้น เช่น มค., false ชื่อเดือนแบบเต็ม เช่น มกราคม
     *
     * @return string
     */
    public static function monthName($month, $short_month = true)
    {
        // create class
        if (!isset(self::$lang)) {
            new static();
        }
        $var = $short_month ? self::$lang['MONTH_SHORT'] : self::$lang['MONTH_LONG'];
        return isset($var[$month]) ? $var[$month] : '';
    }

    /**
     * แยกวันที่ออกเป็น array
     * คืนค่า array(y, m, d, h, i, s) หรือ array(y, m, d) หากเป้นวันที่อย่างเดียว หรือ false หากไม่ใช่วันที่
     *
     * @param string $date
     *
     * @return array|bool
     */
    public static function parse($date)
    {
        if (preg_match('/([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2})(\s([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}))?/', $date, $match)) {
            if (isset($match[4])) {
                return array('y' => $match[1], 'm' => $match[2], 'd' => $match[3], 'h' => $match[5], 'i' => $match[6], 's' => $match[7]);
            } else {
                return array('y' => $match[1], 'm' => $match[2], 'd' => $match[3]);
            }
        }
        return false;
    }
}
