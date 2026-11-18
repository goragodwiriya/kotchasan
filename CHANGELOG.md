# Changelog

## 2.0.0 (unreleased)

This is the first release of Kotchasan as a Composer package on its own. The
framework code is the same as the `Kotchasan/` folder of adminframework.

- `composer require goragodwiriya/kotchasan` installs only the framework. The old
  repository also carried a demo site, CKEditor, a PDF library and skins.
- Composer loads the classes from a classmap. The old `"psr-4": {"": "Kotchasan/"}`
  mapping could not find any class, so only `Kotchasan/load.php` loaded them.
  The bundled `Psr\Log` interfaces and PHPMailer 5 are left out of the classmap,
  so they never replace the `psr/log` package or a global `PHPMailer` class of
  an application. `Kotchasan\Email` still includes its own PHPMailer copy.
- `load.php` finds the project folder when the framework is installed in
  `vendor/`. `ROOT_PATH` and `WEB_URL` used to point at
  `vendor/goragodwiriya/kotchasan/` unless the application defined `ROOT_PATH`.
- Including `load.php` a second time does nothing. Composer's `vendor/autoload.php`
  already includes it, and an application that also included it by path used
  to stop with "Cannot redeclare function".
- PHP 7.4 or newer is required.
- `Request::isValidReferer()` (and `isReferer()`) compares the port as well as
  the host. On a port other than 80 or 443, such as `php -S localhost:8000`,
  it was always false, so `Kotchasan\Login` could not log anyone in. A Referer
  from another port of the same host is no longer accepted.
- `InputItem::email()` keeps `+`. `name+shop@example.com` used to become
  `nameshop@example.com`, which is another mailbox.
- A URL that names a class or method that does not exist is answered with 404.
  The router still throws, so the error is logged as before.
- An uncaught exception is answered with 500 instead of 200 and an empty page,
  unless an error status was already set.
- The error handler of `load.php` skips errors silenced with `@` (below
  `error_reporting()`). Every `@mkdir`, `@unlink` and `@file_get_contents` of the
  framework used to be written to the log.
- `VERSION` is `2.0.0`, the version of this package. It used to hold `7.0.0`,
  the version of AdminFramework, which keeps its own in `settings/config.php`.
- Queries that differ per database now work on all four drivers (tested on MySQL,
  PostgreSQL, SQL Server and SQLite):
  - `UPDATE`/`DELETE` with `limit()` on PostgreSQL pick their rows with
    `WHERE ctid IN (SELECT ctid ... LIMIT n)`; they used to throw, so
    `DB::delete()` (one row by default) failed on every PostgreSQL connection.
  - `UPDATE`/`DELETE` with `orderBy()` and `limit()` on SQL Server go through a
    CTE (`TOP (n)` takes no `ORDER BY`); they were a syntax error.
  - `insert()->ignore()` writes `INSERT OR IGNORE` on SQLite and
    `ON CONFLICT DO NOTHING` on PostgreSQL, and throws on SQL Server; it wrote
    MySQL's `INSERT IGNORE` everywhere.
  - `Sql::NEXT()`, `POSITION()`, `FORMAT()` and `_strValue()` follow the driver of
    the default connection (`Sql::getDatabaseType()`); they always wrote MySQL.
  - `DB::nextId()` casts with `SIGNED` on MySQL (MySQL has no `CAST(... AS INT)`).
- `Database::transaction()` rolls back on any `Throwable`. A `TypeError` in the
  callback left the transaction open.
- `debug()` does nothing while `DEBUG` is `0`. A call left in the code printed
  `<script>console.log(file : line)</script>` after the page, a JSON body too.
- With `DB_CACHE` on and no `cache_expire`, cached query results live 3600 seconds;
  `cache_expire` 0 (the default of `Config`) used to mean they never expired.
- `Curl` accepts only `http://` and `https://` by default, for the request and
  for redirects (`setOptions()` can allow others). It also read `file://` URLs.
- `SecurityMiddleware` starts the session with the cookie flags of
  `Request::startSecureSession()`.
- `Barcode` leaves out the label when no font file exists, instead of four
  warnings; a Kotchasan without Now.js beside it has no fallback font.
- `Validator::date()`, `time()`, `phone()`, `username()`, `password()` and the
  `date` rule of `ApiController::validate()` take `null` without a PHP 8.1
  deprecation.
- `PDOResult::rowCount()`, `count()` and `isEmpty()` of a `SELECT` are right on
  every driver. SQLite gave the rows changed by the last write and SQL Server -1;
  there the rows are now read ahead to count them, and `fetch()`/`fetchAll()`
  hand them out afterwards. `isEmpty()` works before the first row is read.
- `Kotchasan\Session` (sessions in the database, `USE_SESSION_DATABASE`) locks a
  session from `_read()` to `_close()` like PHP's file storage (GET_LOCK,
  PostgreSQL advisory lock, sp_getapplock, a file lock on SQLite;
  `Session::$lockTimeout`), its writes no longer empty the query cache
  (`QueryBuilder::keepQueryCache()`), and `createTable()` writes the SQL of the
  connection's database instead of MySQL only.
- `Logger::error()`, `warning()`, `info()` and `debug()` write where
  `LOG_DESTINATION` says, like `exception()` and PHP's own errors. They always
  wrote `error_log.php`, so a site with `LOG_SYSTEM` still got that file; with
  `LOG_SYSTEM` they now go to PHP's error log only.
- `HtmlTable` and `TableRow` escape attribute values (entities already there are
  kept) and skip names that are not attribute names; a value with `"` added
  attributes such as `onclick`.
- `Template::pregReplace()` reads the `e` of `'/{LNG_([^}]+)}/e'` only as a
  modifier after the last `/`. A key such as `'/{name}/'` whose value read like
  `Class::method` used to call that method.
- `projects/` holds eight examples that load `Kotchasan/` directly, with no
  Composer and no setup. `php -S localhost:8000`, then open `/projects/`.

### Security fixes (audit 2026-11-12)

These change behaviour that code may rely on; each item says what to write instead.

- `QueryBuilder::where()` binds every string value. `':name'` and `'?'` used to
  be written into the SQL as placeholders, so a form field holding `':qb_p0'`
  compared the column with the value of another condition. Write a placeholder
  that `execute()` fills as `Sql::raw(':name')`.
- Comparison operators in `where()`, `having()`, joins and `whereExists()` must
  be one of `=`, `!=`, `<>`, `<`, `>`, `<=`, `>=`, `<=>`, `LIKE`, `NOT LIKE`,
  `ILIKE`, `NOT ILIKE`, `IN`, `NOT IN`, `IS`, `IS NOT`, `BETWEEN`,
  `NOT BETWEEN`, `REGEXP`, `NOT REGEXP`, `RLIKE`, `NOT RLIKE` (any case);
  anything else throws `InvalidArgumentException`.
- Keys of `values()`, `rows()` and `set()` must be column names (letters,
  digits, `_`, `table.column`, optionally in backticks); others throw.
  A `Sql` value with bindings in `set()`/`values()` now works (its placeholders
  were renamed in the bindings only), and `rows()` follows the first row's
  column order.
- `Sql::IN()`, `NOT_IN()`, `BETWEEN()` and `NEXT()` conditions take a string as
  a value: `'U.id'` is no longer a column. Use `Sql::column('U.id')`.
- `Language::save()` writes keys and values as PHP string literals and accepts
  only two-letter language codes and the types `php` and `json`. A translation
  could run PHP code, and a language named `../x` wrote outside `language/`.
- `Request::validateCsrfToken()` and `removeCsrfToken()` accept only the 64-hex
  tokens `generateCsrfToken()` makes. The token `login` emptied the login entry
  of the session.
- `Http\Middleware\JwtMiddleware` signs and checks with `Kotchasan\Jwt`. It
  refuses an empty secret, verifies the HS384/HS512 tokens it issues, and checks
  `issuer`, `audience` and `leeway`. `Jwt::decode()` takes a `$leeway` and
  refuses a payload that is not an array and an `exp`/`nbf` that is not a number.
- `Text::password()` keeps every character except control characters. It kept a
  short list only, so stored hashes may be of the shortened text.
  `Password::legacyInput()` gives the shortened form for code that checks hashes
  itself.
- `Password::verify($password, $stored, $salt, $passwordKey, &$outdated)` accepts
  only the form `Password::hash()` writes, bcrypt of the password keyed with
  `password_key`. The forms of earlier versions (`sha1(key.password.salt)`,
  `sha1(password.salt)`, bcrypt without the key, and any of them of the text the
  old `Text::password()` shortened) return `false` even with the right password,
  and set `$outdated` to `true` so the application can ask for a new password.
  An upgrade script that wants to take them checks `$outdated` and stores
  `hash()` at once. The `$unpeppered` and `$legacyInput` arguments are gone.
- `Text::highlighter()` leaves a `[url]` that would run script as text and
  escapes link addresses. `InputItem::url()` and `Validator::url()` accept the
  schemes in `Validator::$urlSchemes` only (`http`, `https`, `ftp`, `mailto`).
  `Text::generateRandomString()` uses `random_int()`.
- `Validator::isFileSafe()` reads the whole file, not its first 4 KB, and its
  HTML/SVG checks ignore case.
- `BearerTokenAuthMiddleware` does not call the callback without a token.
  `BasicAuthMiddleware` helpers compare with `hash_equals()`.
  `DigestAuthMiddleware` (new `$secret` and `$nonceLifetime` arguments) accepts
  only nonces it signed in the last five minutes, and checks `uri`, `qop` and
  `opaque`.
- `Email` checks the SMTP server's certificate unless the host is this machine
  or `email_ssl_verify` is `false`. The bundled PHPMailer 5.2.21 is patched for
  CVE-2017-5223 (`msgHTML()` embeds local files only from inside `$basedir`),
  CVE-2021-3603, CVE-2021-34551 and CVE-2018-19296.
- `Template::pregReplace()` puts the value of a pattern without groups in as
  text: `$1` or `\1` in user content was read as a reference.
- `Response::contentDisposition()` builds the header for `download()`, `file()`,
  `Csv::send()` and `Xls::send()` (a `"` in a name ended the parameter).
  `Xls::cell()` escapes values. `Csv::send()` has a new `$escapeFormulas`
  argument, `true` by default (see `Csv::escapeFormula()`).
  `Response::setCorsHeaders()` no longer adds `Access-Control-Allow-Origin: *`.
- `_method[]=x` and `?lang[]=x` no longer end in a 500, `Uri` handles nested
  query arrays, and `Text::description()`/`keywords()` keep text that has bytes
  that are not UTF-8.
