# Kotchasan examples

[ภาษาไทย](README.th.md)

Eight small projects that use the `Kotchasan/` folder of this repository directly. There is nothing to install: no Composer, no database server, no `mod_rewrite`. Each example is a few short files.

## Running them

In the folder that holds `Kotchasan/` and `projects/`:

```bash
php -S localhost:8000
```

Open <http://localhost:8000/projects/>. The page lists the examples, checks what your PHP has, and shows the source of every example (`?source=hello`).

With Apache (XAMPP, MAMP, AppServ), put the whole folder under the document root and open `http://localhost/<folder>/projects/`.

PHP 7.4 or newer with `mbstring` and `pdo_sqlite`. `gd` draws the barcode of the utilities example. The web server must be able to write to `database/datas/` and `api/datas/`.

## The examples

| | Example | What it shows | Look at |
|---|---|---|---|
| 1 | `hello/` | The smallest project: an entry file and one controller that answers with HTML | `modules/index/controllers/index.php` |
| 2 | `site/` | A website with a layout, pages, a menu, a theme with its own CSS, and Thai/English | `themes/default/`, `language/th.php` |
| 3 | `form/` | A contact form: CSRF token, server-side checks with `Validator`, an error under each field, Post/Redirect/Get | `modules/index/controllers/index.php` |
| 4 | `database/` | CRUD of products on SQLite: search, sort, pages, the query builder and `DB`, and the SQL it writes | `modules/index/models/product.php` |
| 5 | `login/` | Log in and out with a session, password hashes with `Password`, a page for members, roles | `modules/index/models/auth.php` |
| 6 | `ajax/` | `fetch()` calls a controller method by URL and gets JSON: server time, province search, a form | `modules/index/controllers/ajax.php` |
| 7 | `api/` | A REST API (GET, POST, PUT, DELETE) on `ApiController` with a Bearer token, and a page to try it | `modules/v1/controllers/books.php` |
| 8 | `utilities/` | Thai dates (Buddhist year), baht in words, PromptPay, Code 128 barcodes, JWT, `Validator`, `Province` | `modules/index/controllers/index.php` |

`assets/style.css` is the stylesheet that every example shares.

## How an example is laid out

```text
example/
├── index.php                      entry: ROOT_PATH, require Kotchasan/load.php, run()
├── settings/config.php            read as self::$cfg
├── settings/database.php          database connection (database and api only)
├── modules/index/controllers/     Index\Index\Controller is controllers/index.php
├── modules/index/models/          Index\Product\Model is models/product.php
├── themes/default/                index.html (the layout) and the other templates
└── datas/                         SQLite file (writable, not served)
```

Each `index.php` defines `ROOT_PATH` as its own folder, so the example reads its own `settings/`, `modules/` and `themes/`, and `WEB_URL` is the URL of the example.

URLs use the query string (`index.php?module=about`) and the path after `index.php` (`index.php/index/controller/ajax/time`, `api.php/v1/books/3`), so no rewrite rules are needed.

## Starting your own project from an example

1. Copy the example folder, and copy `Kotchasan/` next to it.
2. In `index.php`, point the `require` at the new place: `require __DIR__.'/Kotchasan/load.php';`
3. Copy `assets/style.css` too if you want the same look, and change the stylesheet link in the layout.
4. Before going online, change `password_key` and the API tokens in `settings/config.php`, and keep `DEBUG` at `0`.

The same project also runs with Composer: `composer require goragodwiriya/kotchasan`, then `require __DIR__.'/vendor/autoload.php';` instead of `Kotchasan/load.php`.
