<?php
/**
 * @filesource Kotchasan/Orm/Field.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/desktop
 *
 * @see http://www.kotchasan.com/
 */

namespace Kotchasan\Orm;

/**
 * ORM Field base class
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Field extends \Kotchasan\Database\Db
{
    /**
     * ชื่อรองของตาราง
     *
     * @var string
     */
    public $table_alias;
    /**
     * ชื่อตาราง
     *
     * @var string
     */
    public $table_name;
    /**
     * ชื่อของการเชื่อมต่อ ใช้สำหรับโหลด config จาก settings/database.php
     *
     * @var string
     */
    protected $conn = 'mysql';
    /**
     * true ถ้ามาจากการ query, false ถ้าเป็นรายการใหม่
     *
     * @var bool
     */
    protected $exists;
    /**
     * ชื่อฟิลด์ที่จะใช้เป็น Primary Key INT(11) AUTO_INCREMENT
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * ชื่อตาราง
     *
     * @var string
     */
    protected $table;

    /**
     * class constructor
     *
     * @param array|object $param ข้อมูลเริ่มต้น
     */
    public function __construct($param = null)
    {
        if (!empty($param)) {
            foreach ($param as $key => $value) {
                $this->{$key} = $value;
            }
            $this->exists = true;
        } else {
            $this->exists = false;
        }
    }

    /**
     * สร้าง record
     *
     * @return \static
     */
    public static function create()
    {
        $obj = new static();
        return $obj;
    }

    /**
     * ลบ record
     */
    public function delete()
    {
        $rs = new Recordset(get_called_class());
        return $rs->delete(array($this->primaryKey, (int) $this->{$this->primaryKey}), 1);
    }

    /**
     * อ่านค่าตัวแปร conn (ชื่อของการเชื่อมต่อ)
     *
     * @return string
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * ฟังก์ชั่นอ่านชื่อตารางจากการตั้งค่าฐานข้อมุล
     * คืนค่าชื่อตารางรวม prefix ถ้าไม่มีชื่อกำหนดไว้ จะคืนค่า $table ครอบชื่อตารางด้วย ``
     *
     * @param string $table ชื่อตารางตามที่กำหนดใน settings/datasbase.php
     *
     * @return string
     */
    public function getFullTableName($table)
    {
        $dbname = empty($this->db->settings->dbname) ? '' : '`'.$this->db->settings->dbname.'`.';
        $prefix = empty($this->db->settings->prefix) ? '' : $this->db->settings->prefix.'_';
        return $dbname.'`'.$prefix.(isset($this->db->tables->$table) ? $this->db->tables->$table : $table).'`';
    }

    /**
     * คืนค่าชื่อฟิลด์ที่เป็น Primary Key
     *
     * @return string
     */
    public function getPrimarykey()
    {
        return $this->primaryKey;
    }

    /**
     * อ่านชื่อตาราง
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->table_name;
    }

    /**
     * อ่านชื่อตารางรวม Alias
     *
     * @param string|null $alias Alias ที่ต้องการ ถ้าไม่ระบุจะใช้ Alias ตามที่กำหนดไว้
     *
     * @return string
     */
    public function getTableWithAlias($alias = null)
    {
        return $this->table_name.' AS '.(empty($alias) ? $this->table_alias : $alias);
    }

    /**
     * ฟังก์ชั่นตรวจสอบชื่อตารางและชื่อรอง
     *
     * @param \Kotchasan\Database\Query $db
     */
    public function initTableName($db)
    {
        $this->db = $db;
        if (empty($this->table)) {
            $class = get_called_class();
            if (preg_match('/[a-z0-9]+\\\\([a-z0-9_]+)\\\\Model/i', $class, $match)) {
                $t = strtolower($match[1]);
            } elseif (preg_match('/Models\\\\([a-z0-9_]+)/i', $class, $match)) {
                $t = strtolower($match[1]);
            } else {
                $t = strtolower($class);
            }
            $this->table_name = $this->getFullTableName($t);
            $this->table_alias = $t;
        } elseif (preg_match('/^([a-z0-9A-Z_]+)(\s+(as|AS))?\s+([a-zA-Z0-9]{1,})$/', $this->table, $match)) {
            $this->table_name = $this->getFullTableName($match[1]);
            $this->table_alias = strlen($match[4]) < 3 ? $match[4] : '`'.$match[4].'`';
        } else {
            $this->table_name = $this->getFullTableName($this->table);
            $this->table_alias = '`'.$this->table.'`';
        }
    }

    /**
     * insert or update record
     */
    public function save()
    {
        $rs = new Recordset(get_called_class());
        if ($this->exists) {
            $rs->update(array($this->primaryKey, (int) $this->{$this->primaryKey}), $this);
        } else {
            $rs->insert($this);
        }
    }
}
