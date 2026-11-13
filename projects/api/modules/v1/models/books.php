<?php
/**
 * @filesource api/modules/v1/models/books.php
 */

namespace V1\Books;

/**
 * The book table of the API (api_book in datas/api.sqlite).
 */
class Model extends \Kotchasan\Model
{
    /**
     * Books per page of GET api.php/v1/books.
     *
     * @var int
     */
    const PER_PAGE = 10;

    /**
     * Creates the table with a few books when it does not exist yet.
     */
    public static function install()
    {
        $db = static::createDB();
        if ($db->fieldExists('book', 'id')) {
            return;
        }

        $table = $db->getTableName('book');
        if ($db->getDatabase()->getDriverName() === 'mysql') {
            $db->raw("CREATE TABLE `$table` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `title` VARCHAR(150) NOT NULL,
                `author` VARCHAR(100) NOT NULL,
                `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME NOT NULL
            ) DEFAULT CHARSET=utf8mb4");
        } else {
            $db->raw("CREATE TABLE \"$table\" (
                \"id\" INTEGER PRIMARY KEY AUTOINCREMENT,
                \"title\" VARCHAR(150) NOT NULL,
                \"author\" VARCHAR(100) NOT NULL,
                \"price\" DECIMAL(10,2) NOT NULL DEFAULT 0,
                \"created_at\" DATETIME NOT NULL,
                \"updated_at\" DATETIME NOT NULL
            )");
        }

        foreach ([
            ['สี่แผ่นดิน', 'ม.ร.ว.คึกฤทธิ์ ปราโมช', 650],
            ['ข้างหลังภาพ', 'ศรีบูรพา', 180],
            ['คู่กรรม', 'ทมยันตี', 350],
            ['ความสุขของกะทิ', 'งามพรรณ เวชชาชีวะ', 165]
        ] as $book) {
            self::add(['title' => $book[0], 'author' => $book[1], 'price' => $book[2]]);
        }
    }

    /**
     * One page of books, searched in the title and the author.
     *
     * @param string $search
     * @param int    $page
     *
     * @return array items, total, page, pages
     */
    public static function search($search, $page)
    {
        $count = static::createQuery()->selectCount()->from('book');
        $query = static::createQuery()->select('id', 'title', 'author', 'price', 'created_at', 'updated_at')->from('book');
        if ($search !== '') {
            // (title LIKE ? OR author LIKE ?): the array of conditions is joined with OR
            $where = [['title', 'LIKE', '%'.$search.'%'], ['author', 'LIKE', '%'.$search.'%']];
            $count->where($where, 'OR');
            $query->where($where, 'OR');
        }

        $total = (int) $count->first()->count;
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(max(1, (int) $page), $pages);

        $items = $query->orderBy('id')
            ->limit(self::PER_PAGE, ($page - 1) * self::PER_PAGE)
            ->fetchAll();

        return [
            'items' => array_map([self::class, 'toArray'], $items),
            'total' => $total,
            'page' => $page,
            'pages' => $pages
        ];
    }

    /**
     * One book as an array, or null.
     *
     * @param int $id
     *
     * @return array|null
     */
    public static function find($id)
    {
        $book = static::createDB()->first('book', [['id', (int) $id]]);

        return $book === null ? null : self::toArray($book);
    }

    /**
     * Adds a book.
     *
     * @param array $data title, author, price
     *
     * @return int The new id
     */
    public static function add(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = $data['created_at'];

        return (int) static::createDB()->insert('book', $data);
    }

    /**
     * Changes a book.
     *
     * @param int   $id
     * @param array $data title, author, price
     */
    public static function change($id, array $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        static::createDB()->update('book', [['id', (int) $id]], $data);
    }

    /**
     * Deletes a book.
     *
     * @param int $id
     *
     * @return bool false when there was no such book
     */
    public static function remove($id)
    {
        return static::createDB()->delete('book', [['id', (int) $id]]) > 0;
    }

    /**
     * A row with the right types for JSON (SQLite returns every value as text).
     *
     * @param object $book
     *
     * @return array
     */
    public static function toArray($book)
    {
        return [
            'id' => (int) $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'price' => (float) $book->price,
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at
        ];
    }
}
