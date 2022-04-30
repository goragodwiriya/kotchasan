<?php
/**
 * @filesource Kotchasan/Number.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan;

/**
 * ฟังก์ชั่นตัวเลข
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Number
{
    /**
     * ฟังก์ชั่น เติม comma รองรับจุดทศนิยม
     * ถ้าไม่มีทศนิยมคืนค่า จำนวนเต็ม
     * ไม่ปัดเศษ
     *
     * @assert (100) [==] "100"
     * @assert (100.1) [==] "100.1"
     * @assert (1000.12) [==] "1,000.12"
     * @assert (1000.1555) [==] "1,000.1555"
     *
     * @param float  $value
     * @param string $thousands_sep (optional) เครื่องหมายหลักพัน (default ,)
     *
     * @return string
     */
    public static function format($value, $thousands_sep = ',')
    {
        $values = explode('.', $value);
        return number_format((float) $values[0], 0, '', $thousands_sep).(empty($values[1]) ? '' : '.'.$values[1]);
    }

    /**
     * หังก์ชั่นหาร
     * $divisor เท่ากับ 0 คืนค่า 0
     *
     * @param $actual ตัวตั้ง
     * @param $divisor ตัวหาร
     *
     * @return mixed
     */
    public static function division($actual, $divisor)
    {
        return $divisor == 0 ? 0 : $actual / $divisor;
    }

    /**
     * จัดรูปแบบตัวเลข รองรับการเติม วัน เดือน ปี
     *
     * @assert ('G%04d', 1) [==] "G0001"
     * @example G-%Y-%M-%D-%04d    G-64-08-09-0001
     * @example G-%y-%m-%d-%04d    G-21-8-9-0001
     * @example G-%YY-%M-%D-%04d   G-2564-08-09-0001
     * @example G-%yy-%m-%d-%04d   G-2021-8-9-0001
     *
     * @param string $format
     * @param mixed $value
     *
     * @return string
     */
    public static function printf($format, $value)
    {
        $y = date('Y');
        $Y = $y + 543;
        $m = date('m');
        $d = date('d');
        $format = str_replace(
            array('%YY', '%yy', '%Y', '%y', '%M', '%m', '%D', '%d'),
            array($Y, $y, substr($Y, 2, 2), substr($y, 2, 2), $m, (int) $m, $d, (int) $d),
            $format
        );
        return sprintf($format, $value);
    }
}
