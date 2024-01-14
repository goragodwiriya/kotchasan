# Kotchasan Web Framework
The elephant, besides being a symbol of PHP, is also our national symbol.
So I chose to use this name as the name of a framework designed by 100% Thai people.

## Features
* MMVC (Modules Model View Controller) architecture, making module addition or removal easy and independent of each other.
* Supports multi-project work.
* Adheres to standards such as PSR-1, PSR-2, PSR-3, PSR-4, PSR-6, PSR-7.
* A PHP Framework optimized for both speed and performance, including memory usage, providing the best efficiency. This allows for faster execution and supports a larger number of concurrent visitors.

## Components of Kotchasan
Kotchasan consists of three main frameworks designed to work together: PHP, CSS, and Javascript.
* Kotchasan PHP Framework
* GCSS CSS Framework
* GAjax Javascript Framework

## Requirements
* PHP 5.6 or higher
* ext-mbstring
* PDO Mysql

## Installation and Usage
I designed Kotchasan to avoid the complex installation process commonly associated with PHP frameworks. You can download the entire source code from GitHub and start using it immediately without any installation or configuration. Alternatively, you can install it via Composer using the command: ```composer require goragod/kotchasan``` https://packagist.org/packages/goragod/kotchasan (Installing via composer will decrease the performance of the framework.)

## Usage Conditions (License)
* You can use it for free without any conditions.
* You can modify it and develop it further under your own copyright using a different name.

## Examples
All sample codes are located in the "projects/" directory. You can test them there. For the "recordset" project, database settings in "settings/database.php" must be correctly configured, and the corresponding database table must be created as mentioned in "projects/orm/modules/index/models/world.php".

* https://projects.kotchasan.com/welcome/: Kotchasan welcome page.
* https://projects.kotchasan.com/site/: Website creation using a simple template and menu.
* https://projects.kotchasan.com/recordset/: Example of using a database (Recordset).
* https://projects.kotchasan.com/admin/: Example of using a login form.
* https://projects.kotchasan.com/youtube/: Example of using the YouTube API.
* https://projects.kotchasan.com/api/: Example of creating and using an API with Kotchasan.
* https://projects.kotchasan.com/pdf/: Example of converting HTML to PDF.
* https://adminframework.kotchasan.com: Example website created using Kotchasan.

## Acknowledgments
* CKEditor https://ckeditor.com/
* PHPMailer https://github.com/PHPMailer/PHPMailer
* FPDF http://www.fpdf.org/
* IcoMoon https://icomoon.io/

## Support
You can support the developers by making a donation to the following bank account
```
Kasikorn Bank, Kanchanaburi Branch, Thailand
Account No. 221-2-78341-5
Account Name: Goragod Wiriya

```