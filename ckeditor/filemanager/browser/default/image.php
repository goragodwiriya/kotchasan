<?php
// ckeditor/filemanager/browser/default/image.php
if (isset($_GET['fid']) && isset($_GET['w']) && isset($_GET['h'])) {
    session_start();
    // load Kotchasan
    include '../../../../load.php';
    // Initial Kotchasan Framework
    Kotchasan::createWebApplication();
    // hotfix: these checks need to be changed later
    $ext = explode('.', $_GET['fid']);
    if (Kotchasan\Login::isMember() && strpos($_GET['fid'], '..') === false && in_array(strtolower(end($ext)), array('jpg', 'jpeg', 'gif', 'png'))) {
        // ค่าที่ส่งมา
        $id = ROOT_PATH.$_GET['fid'];
        $idW = $_GET['w'];
        $idH = $_GET['h'];
        if (is_file($id)) {
            $image_info = @getimagesize($id);
            if (isset($image_info[0]) && isset($image_info[1]) && $image_info[0] > 0 && $image_info[1] > 0) {
                // ปรับขนาดตามที่ต้องการ ถ้ารูปใหญ่กว่าปกติ
                if ($image_info[0] > $idW || $image_info[1] > $idH) {
                    // คำนวณขนาดใหม่
                    if ($image_info[0] <= $image_info[1]) {
                        // รูปสูงกว่ากว้าง
                        $h = $idH;
                        $w = round($h * $image_info[0] / $image_info[1]);
                    } else {
                        $w = $idW;
                        $h = round($w * $image_info[1] / $image_info[0]);
                    }
                    //สร้างรูปใหม่จากรูปเดิม
                    switch ($image_info['mime']) {
                        case 'image/gif':
                            $o_im = imageCreateFromGIF($id);
                            break;
                        case 'image/jpg':
                        case 'image/jpeg':
                        case 'image/pjpeg':
                            $o_im = imageCreateFromJPEG($id);
                            break;
                        case 'image/png':
                        case 'image/x-png':
                            $o_im = imageCreateFromPNG($id);
                            break;
                    }
                    // สร้าง รูปจากรูปที่ส่งเข้ามา
                    $o_wd = imagesx($o_im);
                    $o_ht = imagesy($o_im);
                    // สร้าง image ใหม่ ขนาดที่กำหนดมา
                    $t_im = ImageCreateTrueColor($w, $h);
                    // วาดลงบน image ใหม่
                    ImageCopyResampled($t_im, $o_im, 0, 0, 0, 0, $w + 1, $h + 1, $o_wd, $o_ht);
                    // png image
                    header('Content-type: image/jpeg');
                    ImageJPEG($t_im);
                    // ทำลายออปเจ็คที่สร้าง
                    imageDestroy($o_im);
                    imageDestroy($t_im);
                } else {
                    //แสดงรูปขนาดเดิม
                    $data = @file_get_contents($id);
                    echo $data;
                }
            }
        }
    }
}
