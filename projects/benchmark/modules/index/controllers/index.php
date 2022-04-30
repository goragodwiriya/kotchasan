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

use Kotchasan\Http\Request;

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
        $action = $request->get('action', 'hello')->username();
        $this->$action();
    }

    /**
     * Loading Performance
     * ทดสอบการโหลดเว็บไซต์แบบน้อยที่สุด.
     */
    public function hello()
    {
        echo 'Hello World!';
    }

    /**
     * Recordset Performance (select only)
     * ทดสอบการเรียกข้อมูลด้วย Recordset.
     */
    public function select()
    {
        $rs = \Kotchasan\Orm\Recordset::create('Index\World\Model');
        $rs->updateAll(array('name' => 'Hello World!'));
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $rs->find($rnd);
        }
        $result = $rs->find($result->id);
        echo $result->name;
    }

    /**
     * Recordset Performance (select and update)
     * ทดสอบการเรียกข้อมูลและอัปเดตข้อมูลด้วย Recordset.
     */
    public function recordset()
    {
        $rs = \Kotchasan\Orm\Recordset::create('Index\World\Model');
        $rs->updateAll(array('name' => ''));
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $rs->find($rnd);
            $result->name = 'Hello World!';
            $result->save();
        }
        $result = $rs->find($result->id);
        echo $result->name;
    }

    /**
     * Query Builder Performance
     * ทดสอบการเรียกข้อมูลและอัปเดตข้อมูลด้วย Query Builder.
     */
    public function querybuilder()
    {
        $db = \Kotchasan\Database::create();
        $db->createQuery()->update('world')->set(array('name' => ''))->execute();
        $query = $db->createQuery()->from('world');
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $query->where(array('id', $rnd))->first();
            $db->createQuery()->update('world')->where(array('id', $result->id))->set(array('name' => 'Hello World!'))->execute();
        }
        $result = $query->where(array('id', $result->id))->first();
        echo $result->name;
    }

    /**
     * SQL Command Performance
     * ทดสอบการเรียกข้อมูลและอัปเดตข้อมูลโดยใช้คำสั่ง SQL Command.
     */
    public function sql()
    {
        $db = \Kotchasan\Database::create();
        $db->query("UPDATE `world` SET `name`=''");
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $db->customQuery('SELECT * FROM  `world` WHERE `id`='.$rnd);
            $db->query("UPDATE `world` SET `name`='Hello World!' WHERE `id`=".$result[0]->id);
        }
        $result = $db->customQuery('SELECT * FROM  `world` WHERE `id`='.$result[0]->id);
        echo $result[0]->name;
    }
}
