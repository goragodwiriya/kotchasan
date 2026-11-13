# Kotchasan Web Framework

[ภาษาไทย](README.th.md)

Kotchasan (คชสาร) is a lightweight PHP framework for websites and JSON APIs. It needs nothing but PHP: no other Composer packages and no build step.

- **MVC by folders.** `Product\List\Controller` is `modules/product/controllers/list.php`. Nothing is registered by hand.
- **Router.** Turns `/product`, `/product/controller/list` and `/api/product/item/show` into a controller and a method.
- **Database.** A query builder and a `DB` helper that write the SQL for MySQL/MariaDB, PostgreSQL, SQLite and SQL Server. Values given to the builder are bound as parameters, and you get table prefixes, transactions and an optional query cache (file, memory or Redis).
- **HTTP.** PSR-7 style `Request`, `Response`, `Uri`, `Stream` and `UploadedFile`, plus typed input helpers (`$request->post('id')->toInt()`, `->topic()`, `->date()`), CSRF tokens and secure sessions.
- **JSON API.** `ApiController` gives the same response shape on every endpoint and checks Bearer tokens, IP allow lists and the HTTP method. `Http\Application` is a router with middleware for standalone APIs.
- **Views.** `Template` and `View` render HTML files from a theme folder. `Language` handles i18n with `{LNG_...}` placeholders.
- **Utilities.** Thai and English dates with the Buddhist year, baht amounts in words (Thai and English), number and currency formats, a validator, password hashing, JWT, PromptPay QR payloads, Code 128 barcodes, image resizing, CSV, email over SMTP, cURL and logging.

## Requirements

- PHP 7.4 or newer (tested on PHP 8.3 and 8.4)
- The `json`, `mbstring` and `pdo` extensions, plus the PDO driver of your database
- Optional extensions: `gd` (images and barcodes), `curl`, `openssl`, `fileinfo`, `iconv`, `intl`, `redis`. The `suggest` section of `composer.json` lists them all.

## Installation

### Downloading from GitHub (recommended)

Download this repository (with `git clone` or **Code > Download ZIP**) and copy the `Kotchasan/` folder into your project. This gives the best performance: `Kotchasan/load.php` is the only class loader, with no Composer autoloader and files added to every request. You need no Composer on the server, and the site uploads to any host over FTP as it is.

```bash
git clone --depth 1 https://github.com/goragodwiriya/kotchasan.git
cp -r kotchasan/Kotchasan mysite/
```

### Installing with Composer (optional)

If your project already uses Composer, install it with this command, then use `vendor/autoload.php` in place of `Kotchasan/load.php` in the example below.

```bash
composer require goragodwiriya/kotchasan
```

## Quick start

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

`index.php` receives every request. Define the settings constants **before** loading the framework, because the framework reads them while it loads:

```php
<?php
define('DATA_FOLDER', 'datas/'); // writable folder for logs and cache
define('DEBUG', 0);              // 2 shows errors on the page while developing

require __DIR__.'/Kotchasan/load.php'; // with Composer: vendor/autoload.php

Kotchasan::createWebApplication()->run();
```

`settings/config.php` holds the site settings. `self::$cfg` reads them in every controller, model and view:

```php
<?php
return [
    'web_title' => 'My site',
    'languages' => ['en'],
    'timezone' => 'Asia/Bangkok',
];
```

`Index\Index\Controller` is the default controller. It answers every URL that does not name another controller:

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

`http://localhost:8000/?name=Kotchasan` shows `Hello Kotchasan`.

### A query

`settings/database.php` holds the connections. `Database::config()` sets them in code:

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
    ->from('product')          // the table app_product
    ->where(['price', '>', 50])
    ->orderBy('name')
    ->fetchAll();

echo $rows[0]->name; // Coffee
```

## Settings constants

Define these before the framework loads. Each one has a default.

| Constant | Default | Purpose |
|---|---|---|
| `ROOT_PATH` | the project folder | Where `settings/`, `modules/`, `language/` and `themes/` are. With Composer it is the folder that holds `vendor/` |
| `DATA_FOLDER` | none | Writable folder under `ROOT_PATH` for the file cache and logs, e.g. `datas/` |
| `DEBUG` | `0` | `0` logs serious errors, `1` logs warnings too, `2` shows errors on the page |
| `LOG_DESTINATION` | `LOG_BOTH` | `LOG_FILE` (`error_log.php`), `LOG_SYSTEM` (PHP's error log) or `LOG_BOTH` |
| `INIT_LANGUAGE` | first language | Language for visitors without a choice: a code or `auto` |
| `DB_LOG` | `false` | Log every SQL query, for development |
| `DB_CACHE` / `CACHE_DRIVER` | `false` / `file` | Query cache and its storage: `file`, `memory` or `redis` |
| `TRUSTED_PROXIES` | none | IPs of reverse proxies whose `X-Forwarded-For` is trusted |

`APP_PATH`, `BASE_PATH`, `WEB_URL`, `HOST` and `HTTPS` are worked out from the request. Define them yourself only when the server reports the wrong values.

## Examples

[`projects/`](projects/README.md) holds eight small examples that load the `Kotchasan/` folder of this repository directly, with no Composer and no setup: Hello World, a website with templates and Thai/English, a form with validation, CRUD on SQLite, login, Ajax, a REST API and the Thai utilities. Run this in the repository folder and open <http://localhost:8000/projects/>:

```bash
php -S localhost:8000
```

Every example also runs with Composer: [`projects/README.md`](projects/README.md) shows the one line to change.

## Documentation

The manual, in Thai and English, is at [kotchasan.com](https://www.kotchasan.com).

## Maintainers

The framework is developed in the `Kotchasan/` folder of adminframework, which the application projects copy. This repository publishes that same folder, byte for byte. Never edit `Kotchasan/` here: change it at the source, commit it there, then copy it to this repository and to every application project with adminframework's sync script, which also removes the files deleted at the source:

```bash
cd ../adminframework
# list what differs; changes nothing
php install/cli-framework-sync.php kotchasan
# copy it here
php install/cli-framework-sync.php --apply kotchasan
```

`VERSION` in `Kotchasan/load.php` is the version of this package (2.0.0), not of an application. To release, update it at the source, sync, add the changes to `CHANGELOG.md`, commit, then tag and push. Packagist reads the tag:

```bash
git tag v2.0.0
git push origin main --tags
```

## License

[MIT](LICENSE)
