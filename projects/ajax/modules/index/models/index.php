<?php
/**
 * @filesource modules/index/models/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Index;

use Kotchasan\Http\Request;

/**
 * Model สำหรับรับค่าจาก Ajax.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model
{
    /**
     * โหลดเว็บไซต์ด้วย Ajax.
     *
     * @param Request $request
     *
     * @return string
     */
    public function web(Request $request)
    {
        // ตรวจสอบว่าเรียกมาจากภายในไซต์
        if ($request->isReferer()) {
            // ดูค่าที่ส่งมา
            //print_r($_POST);
            // รับค่า URL ที่ส่งมา
            $url = $request->post('url')->url();
            if ($url != '' && preg_match('/^https?:\/\/.*/', $url)) {
                // โหลด URL ที่ส่งมา
                $content = file_get_contents($url);
                // คืนค่า HTML ไปยัง Ajax
                echo $content;
            } else {
                // ไม่ใช่ http
                echo $url;
            }
        }
    }

    /**
     * ส่งข้อมูลไปบันทึกด้วย Ajax.
     *
     * @param Request $request
     */
    public function save(Request $request)
    {
        // ตรวจสอบว่าเรียกมาจากภายในไซต์
        if ($request->isReferer()) {
            // ดูค่าที่ส่งมา
            //print_r($_POST);
            // create Model
            $model = new \Kotchasan\Model();
            // วนลูปค่าที่ส่งมาจาก $_POST
            foreach ($_POST as $key => $value) {
                if ($key == 'test') {
                    // test รับค่าเป็นตัวเลข
                    $save['test'] = $request->post($key)->toInt();
                } else {
                    // name รับค่าเป็นข้อความบรรทัดเดียว
                    $save['name'] = $request->post($key)->topic();
                }
            }
            if (!empty($save)) {
                if (isset($save['name']) && $save['name'] == '') {
                    $json = array('error' => 'กรุณากรอกข้อความ');
                } else {
                    // query INSERT
                    $query = $model->db()->createQuery()->insert('world', $save);
                    // ประมวลผลคำสั่ง SQL ในตอนใช้งานจริง
                    //$query->execute();
                    // ข้อมูล JSON สำหรับส่งกลับไปแสดงผล
                    $json = array(
                        // คืนค่าคำสั่ง SQL ที่สร้าง
                        'sql' => $query->text(),
                    );
                }
                // คืนค่าเป็น JSON
                echo json_encode($json);
            }
        }
    }

    /**
     * อ่านเวลาจาก Server.
     *
     * @param Request $request
     */
    public function time(Request $request)
    {
        // คืนค่าเวลาปัจจุบันจาก Server
        echo date('H:i:s');
    }
}
