# คชสาร เว็บเฟรมเวิร์ค (Kotchasan Web Framework)

[English](README.md)

คชสารเป็นเฟรมเวิร์ก PHP ขนาดเล็กสำหรับสร้างเว็บไซต์และ JSON API ใช้แค่ PHP ไม่ต้องพึ่งแพ็กเกจ Composer อื่นและไม่ต้อง build

- **MVC ตามโฟลเดอร์** `Product\List\Controller` คือไฟล์ `modules/product/controllers/list.php` ไม่ต้องลงทะเบียนคลาสเอง
- **Router** แปลง `/product`, `/product/controller/list` และ `/api/product/item/show` เป็น controller กับเมธอด
- **ฐานข้อมูล** มี query builder และตัวช่วย `DB` ที่เขียน SQL ให้ตรงกับ MySQL/MariaDB, PostgreSQL, SQLite และ SQL Server ค่าที่ส่งให้ builder ถูก bind เป็นพารามิเตอร์ รองรับ prefix ของตาราง, transaction และ cache ของคิวรี (file, memory หรือ Redis)
- **HTTP** มี `Request`, `Response`, `Uri`, `Stream` และ `UploadedFile` แบบ PSR-7 ตัวช่วยแปลงค่าที่รับเข้ามา (`$request->post('id')->toInt()`, `->topic()`, `->date()`), CSRF token และ session ที่ตั้งค่าความปลอดภัยแล้ว
- **JSON API** `ApiController` ตอบกลับรูปแบบเดียวกันทุก endpoint ตรวจ Bearer token, IP ที่อนุญาต และ HTTP method ได้ ส่วน `Http\Application` เป็น router พร้อม middleware สำหรับ API ที่แยกเดี่ยว
- **View** `Template` และ `View` สร้าง HTML จากไฟล์ในโฟลเดอร์ธีม `Language` ทำหลายภาษาด้วยตัวแปร `{LNG_...}`
- **ยูทิลิตี** วันที่ภาษาไทยและอังกฤษพร้อมปี พ.ศ., จำนวนเงินเป็นตัวหนังสือ (ไทยและอังกฤษ), รูปแบบตัวเลขและเงิน, ตัวตรวจข้อมูล, การเข้ารหัสรหัสผ่าน, JWT, ข้อมูลสำหรับ QR พร้อมเพย์, บาร์โค้ด Code 128, การย่อรูป, CSV, ส่งอีเมลผ่าน SMTP, cURL และ log

## ความต้องการของระบบ

- PHP 7.4 ขึ้นไป (ทดสอบบน PHP 8.3 และ 8.4)
- extension `json`, `mbstring`, `pdo` และไดรเวอร์ PDO ของฐานข้อมูลที่ใช้
- extension ที่ใช้เมื่อต้องการ: `gd` (รูปและบาร์โค้ด), `curl`, `openssl`, `fileinfo`, `iconv`, `intl`, `redis` ดูรายการทั้งหมดได้ในส่วน `suggest` ของ `composer.json`

## การติดตั้ง

### ดาวน์โหลดจาก GitHub (แนะนำ)

ดาวน์โหลด repository นี้ (ด้วย `git clone` หรือ **Code > Download ZIP**) แล้วคัดลอกโฟลเดอร์ `Kotchasan/` ไปไว้ในโปรเจ็ค วิธีนี้ให้ประสิทธิภาพสูงสุด เพราะ `Kotchasan/load.php` เป็นตัวโหลดคลาสตัวเดียว ไม่มี autoloader และไฟล์ของ Composer มาเพิ่มในทุกคำขอ ไม่ต้องติดตั้ง Composer บนเซิร์ฟเวอร์ และอัปโหลดขึ้นโฮสต์ด้วย FTP ได้ทันที

```bash
git clone --depth 1 https://github.com/goragodwiriya/kotchasan.git
cp -r kotchasan/Kotchasan mysite/
```

### ติดตั้งด้วย Composer (ทางเลือก)

ถ้าโปรเจ็คใช้ Composer อยู่แล้ว ติดตั้งได้ด้วยคำสั่งนี้ แล้วใช้ `vendor/autoload.php` แทน `Kotchasan/load.php` ในตัวอย่างข้างล่าง

```bash
composer require goragodwiriya/kotchasan
```

## เริ่มต้นใช้งาน

```text
mysite/
├── Kotchasan/
├── index.php
├── settings/
│   └── config.php
└── modules/
    └── index/
        └── controllers/
            └── index.php
```

`index.php` รับทุกคำขอ ให้กำหนดค่าคงที่ของการตั้งค่า**ก่อน**โหลดเฟรมเวิร์ก เพราะเฟรมเวิร์กอ่านค่าเหล่านี้ตอนโหลด

```php
<?php
define('DATA_FOLDER', 'datas/'); // โฟลเดอร์ที่เขียนได้ สำหรับ log และ cache
define('DEBUG', 0);              // 2 แสดงข้อผิดพลาดบนหน้าจอ ใช้ตอนพัฒนา

require __DIR__.'/Kotchasan/load.php'; // ถ้าใช้ Composer: vendor/autoload.php

Kotchasan::createWebApplication()->run();
```

`settings/config.php` เก็บค่าตั้งของเว็บไซต์ อ่านได้จาก `self::$cfg` ในทุก controller, model และ view

```php
<?php
return [
    'web_title' => 'My site',
    'languages' => ['en'],
    'timezone' => 'Asia/Bangkok',
];
```

`Index\Index\Controller` คือ controller หลัก ตอบทุก URL ที่ไม่ได้ระบุ controller อื่น

```php
<?php
namespace Index\Index;

use Kotchasan\Http\Request;
use Kotchasan\Http\Response;

class Controller extends \Kotchasan\Controller
{
    public function index(Request $request)
    {
        $name = $request->get('name', 'world')->text();

        return (new Response())->html('<h1>Hello '.$name.'</h1><p>'.self::$cfg->web_title.'</p>');
    }
}
```

```bash
php -S localhost:8000
```

`http://localhost:8000/?name=Kotchasan` จะแสดง `Hello Kotchasan`

### คิวรีฐานข้อมูล

การเชื่อมต่ออยู่ใน `settings/database.php` หรือกำหนดในโค้ดด้วย `Database::config()`

```php
use Kotchasan\Database;
use Kotchasan\DB;
use Kotchasan\Model;

Database::config(['default' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => 'app']]);

$db = DB::create();
$db->raw('CREATE TABLE app_product (id INTEGER PRIMARY KEY, name TEXT, price REAL)');
$db->insert('product', ['name' => 'Tea', 'price' => 45]);
$db->insert('product', ['name' => 'Coffee', 'price' => 60]);

$rows = Model::createQuery()
    ->select('name', 'price')
    ->from('product')          // ตาราง app_product
    ->where(['price', '>', 50])
    ->orderBy('name')
    ->fetchAll();

echo $rows[0]->name; // Coffee
```

## ค่าคงที่ของการตั้งค่า

กำหนดก่อนโหลดเฟรมเวิร์ก ทุกตัวมีค่าเริ่มต้น

| ค่าคงที่ | ค่าเริ่มต้น | ใช้ทำอะไร |
|---|---|---|
| `ROOT_PATH` | โฟลเดอร์ของโปรเจ็ค | ที่อยู่ของ `settings/`, `modules/`, `language/` และ `themes/` เมื่อใช้ Composer คือโฟลเดอร์ที่มี `vendor/` |
| `DATA_FOLDER` | ไม่มี | โฟลเดอร์ที่เขียนได้ภายใต้ `ROOT_PATH` สำหรับ cache และ log เช่น `datas/` |
| `DEBUG` | `0` | `0` บันทึกเฉพาะข้อผิดพลาดร้ายแรง, `1` บันทึกคำเตือนด้วย, `2` แสดงข้อผิดพลาดบนหน้าจอ |
| `LOG_DESTINATION` | `LOG_BOTH` | `LOG_FILE` (`error_log.php`), `LOG_SYSTEM` (error log ของ PHP) หรือ `LOG_BOTH` |
| `INIT_LANGUAGE` | ภาษาแรก | ภาษาสำหรับผู้เข้าชมที่ยังไม่ได้เลือก เป็นรหัสภาษาหรือ `auto` |
| `DB_LOG` | `false` | บันทึกทุกคิวรี SQL ใช้ตอนพัฒนา |
| `DB_CACHE` / `CACHE_DRIVER` | `false` / `file` | cache ของคิวรีและที่เก็บ: `file`, `memory` หรือ `redis` |
| `TRUSTED_PROXIES` | ไม่มี | IP ของ reverse proxy ที่เชื่อ `X-Forwarded-For` ได้ |

`APP_PATH`, `BASE_PATH`, `WEB_URL`, `HOST` และ `HTTPS` คำนวณจากคำขอ กำหนดเองเฉพาะเมื่อเซิร์ฟเวอร์ให้ค่าผิด

## โปรเจ็คตัวอย่าง

[`projects/`](projects/README.th.md) มีตัวอย่างขนาดเล็ก 8 ชุดที่โหลดโฟลเดอร์ `Kotchasan/` ของ repository นี้โดยตรง ไม่ต้องใช้ Composer และไม่ต้องตั้งค่าอะไร ได้แก่ Hello World, เว็บไซต์ด้วย template และสองภาษา, ฟอร์มพร้อมการตรวจข้อมูล, CRUD บน SQLite, ระบบสมาชิก, Ajax, REST API และยูทิลิตีภาษาไทย รันคำสั่งนี้ในโฟลเดอร์ของ repository แล้วเปิด <http://localhost:8000/projects/>

```bash
php -S localhost:8000
```

ทุกตัวอย่างใช้กับ Composer ได้ด้วย [`projects/README.th.md`](projects/README.th.md) บอกบรรทัดที่ต้องเปลี่ยน

## คู่มือ

คู่มือภาษาไทยและอังกฤษอยู่ที่ [kotchasan.com](https://www.kotchasan.com)

## สำหรับผู้ดูแล

เฟรมเวิร์กพัฒนาในโฟลเดอร์ `Kotchasan/` ของ adminframework ซึ่งโปรเจ็คแอปพลิเคชันคัดลอกไปใช้ repository นี้เผยแพร่โฟลเดอร์เดียวกันนั้นทุกไบต์ ห้ามแก้ `Kotchasan/` ที่นี่ ให้แก้และ commit ที่ต้นฉบับ แล้วคัดลอกมาที่นี่และไปทุกโปรเจ็คแอปพลิเคชันด้วยสคริปต์ sync ของ adminframework ซึ่งลบไฟล์ที่ต้นฉบับลบไปแล้วให้ด้วย

```bash
cd ../adminframework
# แสดงไฟล์ที่ต่างกัน ไม่แก้อะไร
php install/cli-framework-sync.php kotchasan
# คัดลอกมาที่นี่
php install/cli-framework-sync.php --apply kotchasan
```

`VERSION` ใน `Kotchasan/load.php` คือรุ่นของแพ็กเกจนี้ (2.0.0) ไม่ใช่รุ่นของแอปพลิเคชัน การออกรุ่น: แก้ค่านี้ที่ต้นฉบับแล้ว sync, เพิ่มรายการใน `CHANGELOG.md`, commit จากนั้นติด tag แล้ว push โดย Packagist อ่านรุ่นจาก tag

```bash
git tag v2.0.0
git push origin main --tags
```

## สัญญาอนุญาต

[MIT](LICENSE)
