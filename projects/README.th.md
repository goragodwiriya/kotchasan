# ตัวอย่างการใช้คชสาร

[English](README.md)

โปรเจ็คขนาดเล็ก 8 ชุดที่ใช้โฟลเดอร์ `Kotchasan/` ของ repository นี้โดยตรง ไม่ต้องติดตั้งอะไรเพิ่ม ไม่ต้องใช้ Composer ไม่ต้องมีเซิร์ฟเวอร์ฐานข้อมูล และไม่ต้องเปิด `mod_rewrite` แต่ละตัวอย่างมีไม่กี่ไฟล์และสั้น

## วิธีรัน

ในโฟลเดอร์ที่มี `Kotchasan/` และ `projects/`

```bash
php -S localhost:8000
```

เปิด <http://localhost:8000/projects/> หน้านี้แสดงรายการตัวอย่าง ตรวจว่า PHP ในเครื่องพร้อมหรือไม่ และเปิดดูซอร์สโค้ดของทุกตัวอย่างได้ (`?source=hello`)

ถ้าใช้ Apache (XAMPP, MAMP, AppServ) ให้วางทั้งโฟลเดอร์ไว้ใต้ document root แล้วเปิด `http://localhost/<ชื่อโฟลเดอร์>/projects/`

ต้องใช้ PHP 7.4 ขึ้นไป พร้อม `mbstring` และ `pdo_sqlite` ส่วน `gd` ใช้วาดบาร์โค้ดในตัวอย่าง utilities และเว็บเซิร์ฟเวอร์ต้องเขียนไฟล์ใน `database/datas/` และ `api/datas/` ได้

## ตัวอย่างทั้งหมด

| | ตัวอย่าง | แสดงอะไร | ดูที่ไฟล์ |
|---|---|---|---|
| 1 | `hello/` | โปรเจ็คที่เล็กที่สุด ไฟล์ทางเข้ากับ Controller หนึ่งตัวที่ตอบกลับเป็น HTML | `modules/index/controllers/index.php` |
| 2 | `site/` | เว็บไซต์ที่มีเลย์เอาต์ หลายหน้า เมนู ธีมพร้อม CSS ของตัวเอง และภาษาไทย/อังกฤษ | `themes/default/`, `language/th.php` |
| 3 | `form/` | ฟอร์มติดต่อ: CSRF token ตรวจข้อมูลฝั่งเซิร์ฟเวอร์ด้วย `Validator` แสดงข้อผิดพลาดใต้แต่ละช่อง และ Post/Redirect/Get | `modules/index/controllers/index.php` |
| 4 | `database/` | CRUD สินค้าบน SQLite: ค้นหา เรียงลำดับ แบ่งหน้า ใช้ Query Builder และ `DB` พร้อมแสดง SQL ที่สร้าง | `modules/index/models/product.php` |
| 5 | `login/` | เข้าและออกจากระบบด้วย session เก็บรหัสผ่านเป็น hash ด้วย `Password` หน้าเฉพาะสมาชิก และสิทธิ์ผู้ใช้ | `modules/index/models/auth.php` |
| 6 | `ajax/` | `fetch()` เรียกเมธอดของ Controller ผ่าน URL แล้วรับ JSON: เวลาเซิร์ฟเวอร์ ค้นหาจังหวัด และส่งฟอร์ม | `modules/index/controllers/ajax.php` |
| 7 | `api/` | REST API (GET, POST, PUT, DELETE) ด้วย `ApiController` และ Bearer token พร้อมหน้าทดลองเรียก | `modules/v1/controllers/books.php` |
| 8 | `utilities/` | วันที่ภาษาไทย (พ.ศ.) จำนวนเงินเป็นตัวหนังสือ พร้อมเพย์ บาร์โค้ด Code 128 JWT `Validator` และ `Province` | `modules/index/controllers/index.php` |

`assets/style.css` คือ stylesheet ที่ทุกตัวอย่างใช้ร่วมกัน

## โครงสร้างของแต่ละตัวอย่าง

```text
example/
├── index.php                      ไฟล์ทางเข้า: ROOT_PATH, require Kotchasan/load.php, run()
├── settings/config.php            อ่านได้จาก self::$cfg
├── settings/database.php          การเชื่อมต่อฐานข้อมูล (เฉพาะ database และ api)
├── modules/index/controllers/     Index\Index\Controller คือ controllers/index.php
├── modules/index/models/          Index\Product\Model คือ models/product.php
├── themes/default/                index.html (เลย์เอาต์) และ template อื่น ๆ
└── datas/                         ไฟล์ SQLite (ต้องเขียนได้ และไม่เปิดให้ดาวน์โหลด)
```

`index.php` ของแต่ละตัวอย่างกำหนด `ROOT_PATH` เป็นโฟลเดอร์ของตัวเอง ตัวอย่างจึงอ่าน `settings/`, `modules/` และ `themes/` ของตัวเอง และ `WEB_URL` คือ URL ของตัวอย่างนั้น

URL ใช้ query string (`index.php?module=about`) และ path ต่อท้าย `index.php` (`index.php/index/controller/ajax/time`, `api.php/v1/books/3`) จึงไม่ต้องตั้งกฎ rewrite

## เริ่มโปรเจ็คของคุณจากตัวอย่าง

1. คัดลอกโฟลเดอร์ตัวอย่าง และคัดลอก `Kotchasan/` ไปไว้ข้างกัน
2. แก้ `require` ใน `index.php` ให้ชี้ไปที่ใหม่ เช่น `require __DIR__.'/Kotchasan/load.php';`
3. คัดลอก `assets/style.css` ไปด้วยถ้าต้องการหน้าตาแบบเดียวกัน แล้วแก้ลิงก์ stylesheet ในเลย์เอาต์
4. ก่อนขึ้นเว็บจริง ให้เปลี่ยน `password_key` และ token ของ API ใน `settings/config.php` และตั้ง `DEBUG` เป็น `0`

โปรเจ็คเดียวกันนี้ใช้กับ Composer ได้ด้วย: `composer require goragodwiriya/kotchasan` แล้วใช้ `require __DIR__.'/vendor/autoload.php';` แทน `Kotchasan/load.php`
