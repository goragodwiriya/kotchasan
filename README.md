# คชสาร เว็บเฟรมเวิร์ค (Kotchasan Web Framework)
ช้างนอกจากจะเป็นสัญลักษณ์ของ PHP แล้ว ยังเป็นสัญลักษณ์ประจำชาติของเราอีก
ผมเลยเลือกที่จะใช้ชื่อนี้เป็นชื่อของ Framework ที่ออกแบบโดยคนไทย 100%

## คุณสมบัติ
* สถาปัตยกรรม MMVC (Modules Model View Controller) ทำให้การเพิ่มหรือลดโมดูลเป็นไปโดยง่าย ไม่ขึ้นแก่กัน
* สนับสนุนการทำงานแบบหลายโปรเจ็ค
* ปฏิบัติตามมาตรฐาน PSR-1, PSR-2, PSR-3, PSR-4, PSR-6, PSR-7
* เป็น PHP Framework ที่ได้รับการ Optimize ทั้งทางด้านความเร็วและประสิทธิภาพในการทำงาน
ตลอดจนการใช้หน่วยความจำ ให้มีประสิทธิภาพดีที่สุด ทำให้สามารถทำงานได้เร็วกว่า และรองรับผู้เยี่ยมชมพร้อมกันได้มากกว่า

## องค์ประกอบของ คชสาร
คชสารจะประกอบด้วยเฟรมเวิร์คหลัก 3 ตัว ที่ออกแบบเพื่อใช้ร่วมกัน ทั้งส่วนของ PHP, CSS และ Javascript
* Kotchasan PHP Framework
* GCSS CSS Framework
* GAjax Javascript Framework

## ความต้องการ
* PHP 5.3 ขึ้นไป
* ext-mbstring
* PDO Mysql

## การติดตั้งและนำไปใช้งาน
ผมออกแบบคชสารเพื่อหลีกเลี่ยงการติดตั้งที่ยุ่งยากตามแบบของ PHP Framework ทั่วไป
โดยสามารถดาวน์โหลด source code ทั้งหมดจาก GitHub ไปใช้งานได้ทันทีโดยไม่ต้องติดตั้งหรือตั้งค่าใดๆ
หรือสามารถติดตั้งผ่าน Composer ได้ ```composer require goragod/kotchasan``` https://packagist.org/packages/goragod/kotchasan

## เงื่อนไขการใช้งาน (License)
* สามารถนำไปใช้งานได้ฟรี ไม่มีเงื่อนไข
* สามารถนำไปพัฒนาต่อยอดเป็นลิขสิทธิ์ของตัวเองได้ โดยใช้ชื่ออื่น

## ตัวอย่าง
โค้ดตัวอย่างทั้งหมดอยู่ในโฟลเดอร์ projects/ ถ้าต้องการทดสอบลองเรียกได้ในนั้น
ส่วนโปรเจ็ค recordset มีการเรียกใช้ฐานข้อมูลร่วมด้วย ต้องกำหนดค่าฐานข้อมูลที่ settings/database.php ให้ถูกต้องก่อน
และต้องสร้างตารางฐานข้อมูลด้วย ตามใน projects/orm/modules/index/models/world.php

* https://projects.kotchasan.com/welcome/ หน้าต้อนรับของคชสาร
* https://projects.kotchasan.com/site/ สร้างเว็บไซต์ด้วย template และมีเมนู แบบง่ายๆ
* https://projects.kotchasan.com/recordset/ ตัวอย่างการใช้งานฐานข้อมูล (Recordset)
* https://projects.kotchasan.com/admin/ ตัวอย่างการใช้งานฟอร์ม Login
* https://projects.kotchasan.com/youtube/ ตัวอย่างการใช้งาน Youtube API
* https://projects.kotchasan.com/api/ ตัวอย่างการสร้างและเรียกใช้ API ด้วยคชสาร
* https://projects.kotchasan.com/pdf/ ตัวอย่างการแปลง HTML เป็น PDF
* https://adminframework.kotchasan.com ตัวอย่างเว็บไซต์ที่สร้างจาก Kotchasan

## ขอขอบคุณ
* CKEditor https://ckeditor.com/
* PHPMailer https://github.com/PHPMailer/PHPMailer
* FPDF http://www.fpdf.org/
* IcoMoon https://icomoon.io/
* คุณ ชลสิทธิ์ จักรศรีพร สำหรับโลโกสวยๆของคชสาร

## การสนับสนุน
สามารถสนับสนุนผู้จัดทำได้ โดยการบริจาคผ่านบัญชีธนาคาร
```
กสิกรไทย สาขากาญจนบุรี
เลขบัญชี 221-2-78341-5
ชื่อบัญชี กรกฎ วิริยะ
```