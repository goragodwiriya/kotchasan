<?php
/**
 * @filesource database/modules/index/models/product.php
 */

namespace Index\Product;

/**
 * Database work of the product list.
 *
 * search() and stats() use the query builder (createQuery()). The others use
 * the DB helper (createDB()), which wraps the one-line queries. Both use the
 * connection 'default' of settings/database.php and add the table prefix,
 * so 'product' is the table demo_product.
 */
class Model extends \Kotchasan\Model
{
    /**
     * Products per page.
     *
     * @var int
     */
    const PER_PAGE = 5;

    /**
     * Sort orders of the list: name => [column, direction].
     *
     * @var array
     */
    const SORTS = [
        'newest' => ['id', 'DESC'],
        'name' => ['name', 'ASC'],
        'cheap' => ['price', 'ASC'],
        'expensive' => ['price', 'DESC'],
        'stock' => ['stock', 'ASC']
    ];

    /**
     * Creates the table with a few products when it does not exist yet,
     * so the example works without an installer.
     */
    public static function install()
    {
        $db = static::createDB();
        // fieldExists() reads the columns of the table on every driver
        if ($db->fieldExists('product', 'id')) {
            return;
        }

        $table = $db->getTableName('product');
        if ($db->getDatabase()->getDriverName() === 'mysql') {
            $db->raw("CREATE TABLE `$table` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `price` DECIMAL(10,2) NOT NULL DEFAULT 0,
                `stock` INT NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL
            ) DEFAULT CHARSET=utf8mb4");
        } else {
            $db->raw("CREATE TABLE \"$table\" (
                \"id\" INTEGER PRIMARY KEY AUTOINCREMENT,
                \"name\" VARCHAR(100) NOT NULL,
                \"price\" DECIMAL(10,2) NOT NULL DEFAULT 0,
                \"stock\" INTEGER NOT NULL DEFAULT 0,
                \"created_at\" DATETIME NOT NULL
            )");
        }

        $samples = [
            ['เมล็ดกาแฟคั่วกลาง 250 กรัม', 280, 24],
            ['เมล็ดกาแฟคั่วเข้ม 250 กรัม', 260, 3],
            ['ดริปเปอร์เซรามิก', 450, 8],
            ['กระดาษกรองกาแฟ 100 แผ่น', 120, 40],
            ['แก้วเก็บความเย็น 20 ออนซ์', 390, 0],
            ['เครื่องบดกาแฟมือหมุน', 1290, 6],
            ['กาต้มน้ำคอห่าน', 890, 4]
        ];
        foreach ($samples as $i => $item) {
            $db->insert('product', [
                'name' => $item[0],
                'price' => $item[1],
                'stock' => $item[2],
                'created_at' => date('Y-m-d H:i:s', time() - (count($samples) - $i) * 86400)
            ]);
        }
    }

    /**
     * One page of products.
     *
     * @param string $search text to find in the name
     * @param string $sort   a key of SORTS
     * @param int    $page   1, 2, ...
     *
     * @return array items, total, page, pages, and the SQL of the query for the page to show
     */
    public static function search($search, $sort, $page)
    {
        $count = static::createQuery()->selectCount()->from('product');
        $query = static::createQuery()->select('id', 'name', 'price', 'stock', 'created_at')->from('product');
        if ($search !== '') {
            // The value is sent to the database as a parameter, never written into the SQL
            $count->where(['name', 'LIKE', '%'.$search.'%']);
            $query->where(['name', 'LIKE', '%'.$search.'%']);
        }

        $total = (int) $count->first()->count;
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min(max(1, (int) $page), $pages);

        list($column, $direction) = isset(self::SORTS[$sort]) ? self::SORTS[$sort] : self::SORTS['newest'];
        $query->orderBy($column, $direction)->limit(self::PER_PAGE, ($page - 1) * self::PER_PAGE);

        return [
            // toSql() and getBindings() show what is sent to the database
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'items' => $query->fetchAll(),
            'total' => $total,
            'page' => $page,
            'pages' => $pages
        ];
    }

    /**
     * Totals for the boxes at the top of the page.
     *
     * @return object total, stock, value, low
     */
    public static function stats()
    {
        $row = static::createQuery()
            ->selectRaw('COUNT(*) AS total, SUM(stock) AS stock, SUM(price * stock) AS value')
            ->from('product')
            ->first();
        // count() of the DB helper with a condition: [column, operator, value]
        $row->low = static::createDB()->count('product', [['stock', '<', 5]]);

        return $row;
    }

    /**
     * One product, or null.
     *
     * @param int $id
     *
     * @return object|null
     */
    public static function get($id)
    {
        return static::createDB()->first('product', [['id', (int) $id]]);
    }

    /**
     * Adds a product ($id = 0) or updates one.
     *
     * @param int   $id
     * @param array $data name, price, stock
     *
     * @return int The id of the product
     */
    public static function save($id, array $data)
    {
        $db = static::createDB();
        if ($id > 0) {
            $db->update('product', [['id', (int) $id]], $data);

            return (int) $id;
        }
        $data['created_at'] = date('Y-m-d H:i:s');

        return (int) $db->insert('product', $data);
    }

    /**
     * Deletes a product.
     *
     * @param int $id
     *
     * @return bool false when there was no such product
     */
    public static function remove($id)
    {
        return static::createDB()->delete('product', [['id', (int) $id]]) > 0;
    }
}
