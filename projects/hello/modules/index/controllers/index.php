<?php
/**
 * @filesource hello/modules/index/controllers/index.php
 */

namespace Index\Index;

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Http\Response;
use Kotchasan\Text;

/**
 * The default controller (Kotchasan::DEFAULT_CONTROLLER).
 *
 * The class name gives the file: Index\Index\Controller is
 * modules/index/controllers/index.php, so nothing is registered anywhere.
 * Kotchasan\Router calls index() for index.php and for index.php?name=...
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // get() reads the query string, topic() keeps one line of plain text
        $name = $request->get('name', 'Kotchasan')->topic(50);
        if ($name === '') {
            $name = 'Kotchasan';
        }

        // Text::htmlspecialchars() makes any text safe to put in HTML
        $safeName = Text::htmlspecialchars($name);
        $url = Text::htmlspecialchars(rawurldecode((string) $request->getUri()));
        $method = $request->getMethod();
        $now = Date::format();
        $title = Text::htmlspecialchars(self::$cfg->web_title);
        $version = VERSION;

        $html = <<<HTML
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hello World · Kotchasan</title>
<link rel="icon" href="../assets/kotchasan-vector.svg">
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="../"><span class="logo-mark"><img src="../assets/kotchasan-vector.svg" alt="" width="64" height="64"></span>Kotchasan <small>ตัวอย่าง</small></a>
    <nav class="nav"><a href="../?source=hello">ดูซอร์สโค้ด</a></nav>
  </div>
</header>
<main class="container">
  <header class="page-head">
    <span class="eyebrow">01 · Hello World</span>
    <h1>สวัสดี {$safeName}!</h1>
    <p class="lead">หน้านี้สร้างจากไฟล์ PHP เพียง 2 ไฟล์ คือ <code>index.php</code> กับ <code>modules/index/controllers/index.php</code></p>
  </header>

  <div class="split even">
    <section class="card">
      <h2>ลองเปลี่ยนชื่อ</h2>
      <form method="get" action="index.php">
        <div class="field">
          <label for="name">ชื่อของคุณ</label>
          <input type="text" id="name" name="name" value="{$safeName}" maxlength="50" autofocus>
          <p class="hint">ค่านี้ส่งไปทาง URL เป็น <code>?name=...</code> แล้ว Controller อ่านด้วย <code>\$request->get('name')</code></p>
        </div>
        <button class="btn" type="submit">ทักทาย</button>
      </form>
    </section>

    <section class="card">
      <h2>คำขอนี้</h2>
      <div class="table-wrap">
        <table class="table facts">
          <tr><th scope="row">URL</th><td><code>{$url}</code></td></tr>
          <tr><th scope="row">Method</th><td><code>{$method}</code></td></tr>
          <tr><th scope="row">ชื่อเว็บ</th><td><code>{$title}</code><br><span class="muted small">ค่าเริ่มต้นของ Kotchasan\Config เพราะตัวอย่างนี้ไม่มี settings/config.php</span></td></tr>
          <tr><th scope="row">เวลา</th><td>{$now}</td></tr>
        </table>
      </div>
    </section>
  </div>

  <section class="card">
    <h2>เกิดอะไรขึ้นบ้าง</h2>
    <ol class="steps">
      <li><code>index.php</code> กำหนด <code>ROOT_PATH</code> แล้วโหลด <code>Kotchasan/load.php</code> ซึ่งลงทะเบียน autoloader ให้เอง</li>
      <li><code>Kotchasan::createWebApplication()->run()</code> ให้ <code>Kotchasan\Router</code> อ่าน URL ไม่ได้ระบุ Controller จึงใช้ <code>Index\Index\Controller</code></li>
      <li>ชื่อคลาสบอกตำแหน่งไฟล์ <code>Index\Index\Controller</code> คือ <code>modules/index/controllers/index.php</code> และถูกโหลดเมื่อใช้ครั้งแรก</li>
      <li>เมธอด <code>index()</code> อ่าน <code>?name=</code> แล้วคืนค่า <code>(new Response())->html(...)</code> Router ส่ง Response นั้นไปที่เบราว์เซอร์</li>
    </ol>
  </section>
</main>
<footer class="footer">Kotchasan {$version} · <a href="../">ตัวอย่างทั้งหมด</a></footer>
</body>
</html>
HTML;

        return (new Response())->html($html);
    }
}
