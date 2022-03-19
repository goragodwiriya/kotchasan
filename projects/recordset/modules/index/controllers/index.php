<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

use Index\World\Model as World;
use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Orm\Recordset;

/**
 * default Controller.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * แสดงผล.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        // อ่านรายชื่อฟิลด์ของตาราง
        $rs = Recordset::create('Index\World\Model');
        $fields = $rs->getFields();
        echo implode(', ', array_keys($fields)).'<br>';
        // ลบข้อมูลทั้งตาราง
        $rs->emptyTable();
        // insert new record
        for ($i = 0; $i < 10000; ++$i) {
            $query = World::create();
            $query->updated_at = Date::mktimeToSqlDateTime();
            $query->save();
        }
        // อัปเดตทุก record
        $rs->updateAll(array('created_at' => Date::mktimeToSqlDateTime()));
        // อ่านจำนวนข้อมูลทั้งหมดในตาราง
        echo 'All '.$rs->count().' records.<br>';
        // สุ่ม record มาแก้ไข
        for ($i = 0; $i < 5; ++$i) {
            $rnd = rand(1, 10000);
            $world = $rs->find($rnd);
            $world->name = 'Hello World!';
            $world->save();
        }
        // query รายการที่มีการแก้ไข
        $rs->where(array('name', '!=', ''));
        // อ่านจำนวนข้อมูลที่พบ
        echo 'Found '.$rs->count().' records.<br>';
        // แสดงผลรายการที่พบ
        foreach ($rs->all('id', 'name') as $item) {
            echo $item->id.'='.$item->name.'<br>';
            // ลบรายการที่กำลังแสดงผล
            $item->delete();
        }
        // อ่านรายชื่อฟิลด์ของ query
        $fields = $rs->getFields();
        echo implode(', ', array_keys($fields)).'<br>';
        // อ่านจำนวนข้อมูลที่เหลือ
        echo 'Remain '.Recordset::create('Index\World\Model')->count().' records.<br>';
    }
}
