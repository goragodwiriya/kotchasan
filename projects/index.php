<?php
/**
 * projects/index.php
 * The list of examples, and a viewer for their source code (?source=hello).
 *
 * Run PHP's built-in server in the folder that holds Kotchasan/ and projects/:
 *
 *   php -S localhost:8000
 *
 * then open http://localhost:8000/projects/
 */

// The examples: folder => [title, description, the classes it shows]
$examples = [
    'hello' => [
        'Hello World',
        'โปรเจ็คที่เล็กที่สุด มีไฟล์ทางเข้ากับ Controller อย่างละไฟล์ รับค่าจาก URL แล้วตอบกลับเป็น HTML',
        ['Controller', 'Request', 'Response']
    ],
    'site' => [
        'เว็บไซต์ด้วย Template',
        'เว็บไซต์หลายหน้าที่มีเมนู เลย์เอาต์ ธีม และปุ่มสลับภาษาไทย/อังกฤษ',
        ['Template', 'View', 'Language']
    ],
    'form' => [
        'ฟอร์มและการตรวจข้อมูล',
        'ฟอร์มติดต่อที่ป้องกัน CSRF ตรวจข้อมูลทีละช่อง จำค่าที่พิมพ์ไว้ และใช้ Post/Redirect/Get',
        ['Validator', 'CSRF token', 'Session']
    ],
    'database' => [
        'ฐานข้อมูล (CRUD)',
        'จัดการสินค้าบน SQLite: เพิ่ม แก้ไข ลบ ค้นหา และแบ่งหน้า พร้อมดู SQL ที่ Query Builder สร้าง',
        ['Model', 'Query Builder', 'DB']
    ],
    'login' => [
        'ระบบสมาชิก (Login)',
        'เข้าสู่ระบบด้วย session เก็บรหัสผ่านแบบ hash ป้องกัน CSRF และมีหน้าเฉพาะสมาชิก',
        ['Password', 'Session', 'CSRF token']
    ],
    'ajax' => [
        'Ajax ด้วย fetch()',
        'JavaScript เรียกเมธอดของ Controller ผ่าน URL แล้วรับ JSON กลับ: นาฬิกา ค้นหาจังหวัด และส่งฟอร์ม',
        ['Router', 'Response::json', 'Province']
    ],
    'api' => [
        'REST API',
        'API หนังสือแบบ REST (GET POST PUT DELETE) ด้วย ApiController และ Bearer token พร้อมหน้าทดลองเรียก',
        ['ApiController', 'Bearer token', 'JSON']
    ],
    'utilities' => [
        'ยูทิลิตีภาษาไทย',
        'วันที่ พ.ศ. จำนวนเงินเป็นตัวหนังสือ พร้อมเพย์ บาร์โค้ด JWT การตรวจข้อมูล และอื่น ๆ',
        ['Date', 'Currency', 'Promptpay', 'Jwt']
    ]
];

// Load Kotchasan only for its version number and Text::htmlspecialchars()
define('ROOT_PATH', str_replace('\\', '/', __DIR__).'/');
require __DIR__.'/../Kotchasan/load.php';

use Kotchasan\Text;

$source = isset($_GET['source']) ? preg_replace('/[^a-z]/', '', (string) $_GET['source']) : '';
if ($source !== '' && isset($examples[$source])) {
    $title = 'ซอร์สโค้ด: '.$examples[$source][0];
    $content = sourcePage($source, $examples[$source]);
} else {
    $title = 'ตัวอย่างการใช้งาน';
    $content = listPage($examples);
}

/**
 * The list of examples with a check of what this server has.
 *
 * @param array $examples
 *
 * @return string
 */
function listPage(array $examples)
{
    $cards = '';
    $n = 0;
    foreach ($examples as $folder => $item) {
        $chips = '';
        foreach ($item[2] as $chip) {
            $chips .= '<span class="chip">'.Text::htmlspecialchars($chip).'</span>';
        }
        $cards .= '<article class="card example">'
            .'<span class="num">'.sprintf('%02d', ++$n).'</span>'
            .'<h2><a href="'.$folder.'/">'.Text::htmlspecialchars($item[0]).'</a></h2>'
            .'<p>'.Text::htmlspecialchars($item[1]).'</p>'
            .'<div class="chips">'.$chips.'</div>'
            .'<div class="links"><a class="btn btn-sm" href="'.$folder.'/">เปิดตัวอย่าง</a>'
            .'<a class="btn btn-sm btn-secondary" href="?source='.$folder.'">ดูโค้ด</a></div>'
            .'</article>';
    }

    // [ok, label, detail, required]
    $writable = is_writable(__DIR__.'/database/datas') && is_writable(__DIR__.'/api/datas');
    $checks = [
        [version_compare(PHP_VERSION, '7.4', '>='), 'PHP 7.4 ขึ้นไป', 'เครื่องนี้ใช้ PHP '.PHP_VERSION, true],
        [extension_loaded('mbstring'), 'ส่วนขยาย mbstring', 'ใช้จัดการข้อความภาษาไทย', true],
        [extension_loaded('pdo_sqlite'), 'ส่วนขยาย pdo_sqlite', 'ใช้ในตัวอย่าง database และ api', true],
        [$writable, 'โฟลเดอร์ datas/ เขียนได้', 'database/datas/ และ api/datas/ เก็บไฟล์ SQLite', true],
        [extension_loaded('gd'), 'ส่วนขยาย gd', 'ใช้วาดบาร์โค้ดในตัวอย่าง utilities (ไม่บังคับ)', false]
    ];
    $list = '';
    foreach ($checks as $check) {
        $class = $check[0] ? 'ok' : ($check[3] ? 'fail' : 'warn');
        $list .= '<li class="'.$class.'"><span class="mark">'.($check[0] ? '&#10003;' : '!').'</span>'
            .'<div><b>'.$check[1].'</b><br><span class="muted small">'.Text::htmlspecialchars($check[2]).'</span></div></li>';
    }

    return <<<HTML
<section class="hero">
  <div>
    <span class="eyebrow">Kotchasan PHP Framework</span>
    <h1>เรียนรู้คชสารจาก<span>ตัวอย่าง</span></h1>
    <p class="lead">ตัวอย่างทั้ง 8 ชุดใช้เฟรมเวิร์กในโฟลเดอร์ <code>Kotchasan/</code> ที่มากับ repository นี้โดยตรง ไม่ต้องติดตั้ง Composer ไม่ต้องตั้งค่าฐานข้อมูล ตัวอย่างละไม่กี่ไฟล์ อ่านจบได้ในไม่กี่นาที</p>
  </div>
  <img class="logo" src="assets/kotchasan-vector.svg" alt="Kotchasan PHP Framework" width="260" height="260">
</section>

<div class="split">
  <section class="card quickstart">
    <h2>เริ่มใช้งาน</h2>
    <p>เปิดเทอร์มินัลในโฟลเดอร์ที่มี <code>Kotchasan/</code> และ <code>projects/</code> แล้วรัน</p>
    <pre><code>php -S localhost:8000</code></pre>
    <p>จากนั้นเปิด <a href="http://localhost:8000/projects/">http://localhost:8000/projects/</a></p>
    <p class="muted small">ใช้ Apache (XAMPP, AppServ, MAMP) ก็ได้ วางทั้งโฟลเดอร์ไว้ใน htdocs แล้วเปิด <code>http://localhost/ชื่อโฟลเดอร์/projects/</code> ไม่ต้องเปิด mod_rewrite</p>
  </section>
  <section class="card">
    <h2>ความพร้อมของเครื่องนี้</h2>
    <ul class="checklist">{$list}</ul>
  </section>
</div>

<h2 class="section-title">ตัวอย่างทั้งหมด</h2>
<div class="grid">{$cards}</div>

<section class="card">
  <h2>นำไปเริ่มโปรเจ็คของคุณเอง</h2>
  <ol class="steps">
    <li>คัดลอกโฟลเดอร์ตัวอย่างที่ใกล้เคียงที่สุด (เช่น <code>projects/site/</code>) ไปเป็นโฟลเดอร์ใหม่ และคัดลอกโฟลเดอร์ <code>Kotchasan/</code> ไปไว้ข้างกัน</li>
    <li>แก้บรรทัด <code>require</code> ใน <code>index.php</code> ให้ชี้ไปที่ <code>Kotchasan/load.php</code> ตัวใหม่ เช่น <code>require __DIR__.'/Kotchasan/load.php';</code></li>
    <li>คัดลอก <code>assets/style.css</code> ไปด้วยถ้าต้องการหน้าตาแบบเดียวกัน แล้วแก้ลิงก์ CSS ในเลย์เอาต์</li>
    <li>เปลี่ยน <code>password_key</code> และ token ใน <code>settings/config.php</code> และตั้ง <code>DEBUG</code> เป็น <code>0</code> ก่อนขึ้นเว็บจริง</li>
  </ol>
</section>
HTML;
}

/**
 * Every text file of one example, PHP with syntax colours.
 *
 * @param string $folder
 * @param array  $item
 *
 * @return string
 */
function sourcePage($folder, array $item)
{
    $root = __DIR__.'/'.$folder;
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        $path = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        // Only source files: no database, logs or images
        if (preg_match('/\.(php|html|css|js)$|(^|\/)\.htaccess$/', $path) && !preg_match('/^datas\/|error_log\.php$/', $path)) {
            $files[] = $path;
        }
    }
    // index.php first, then the files in the order a request uses them
    usort($files, function ($a, $b) {
        $order = function ($path) {
            foreach (['index.php', 'api.php', 'settings/', 'language/', 'modules/', 'themes/'] as $i => $prefix) {
                if (strpos($path, $prefix) === 0) {
                    return $i;
                }
            }

            return 9;
        };

        return [$order($a), $a] <=> [$order($b), $b];
    });

    // Colours of highlight_string() on the dark code background
    ini_set('highlight.default', '#9cdcfe');
    ini_set('highlight.keyword', '#c792ea');
    ini_set('highlight.string', '#e6c07b');
    ini_set('highlight.comment', '#7f9cb5');
    ini_set('highlight.html', '#dce7f3');

    $nav = '';
    $blocks = '';
    foreach ($files as $i => $path) {
        $code = file_get_contents($root.'/'.$path);
        $html = substr($path, -4) === '.php' ? highlight_string($code, true) : Text::htmlspecialchars($code);
        $nav .= '<a href="#file-'.$i.'">'.Text::htmlspecialchars($path).'</a>';
        $blocks .= '<section class="source-file" id="file-'.$i.'"><h2>'.$folder.'/'.Text::htmlspecialchars($path).'</h2>'
            .'<div class="code">'.$html.'</div></section>';
    }

    $title = Text::htmlspecialchars($item[0]);
    $count = count($files);

    return <<<HTML
<header class="page-head">
  <span class="eyebrow">ซอร์สโค้ด · {$count} ไฟล์</span>
  <h1>{$title}</h1>
  <p class="lead">ทุกไฟล์ของตัวอย่าง <code>projects/{$folder}/</code> เรียงตามลำดับที่คำขอหนึ่งครั้งเดินผ่าน</p>
  <p class="actions" style="margin-top:16px"><a class="btn" href="{$folder}/">เปิดตัวอย่าง</a><a class="btn btn-secondary" href="./">ตัวอย่างทั้งหมด</a></p>
</header>
<div class="source">
  <nav class="card source-nav" aria-label="ไฟล์">{$nav}</nav>
  <div>{$blocks}</div>
</div>
HTML;
}

$version = VERSION;
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo Text::htmlspecialchars($title); ?> · Kotchasan</title>
<link rel="icon" href="assets/kotchasan-vector.svg">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="./"><span class="logo-mark"><img src="assets/kotchasan-vector.svg" alt="" width="64" height="64"></span>Kotchasan <small>ตัวอย่าง</small></a>
    <nav class="nav" aria-label="ลิงก์">
      <a href="https://www.kotchasan.com">คู่มือ</a>
      <a href="https://github.com/goragodwiriya/kotchasan">GitHub</a>
    </nav>
  </div>
</header>
<main class="container">
<?php echo $content; ?>
</main>
<footer class="footer">Kotchasan <?php echo $version; ?> · PHP <?php echo PHP_VERSION; ?></footer>
</body>
</html>
