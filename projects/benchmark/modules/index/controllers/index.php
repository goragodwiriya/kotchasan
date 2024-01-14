<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * Controller for the Index module.
 * This class handles the default actions for the Index module, including rendering the index page.
 * For more information, please visit: https://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Index;

use Kotchasan\Http\Request;

/**
 * Render the index page.
 *
 * This method is responsible for rendering the index page of the Index module.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Render the index page.
     *
     * This method is responsible for rendering the index page of the Index module.
     *
     * @param Request $request The HTTP request object.
     */
    public function index(Request $request)
    {
        $action = $request->get('action', 'hello')->username();
        $this->$action();
    }

    /**
     * Hello action.
     *
     * Renders the "Hello World!" message.
     */
    public function hello()
    {
        echo 'Hello World!';
    }

    /**
     * Select action.
     *
     * Performs a select operation using Recordset.
     */
    public function select()
    {
        $rs = \Kotchasan\Orm\Recordset::create('Index\World\Model');
        $rs->updateAll(array('name' => 'Hello World!'));
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $rs->find($rnd);
        }
        $result = $rs->find($result->id);
        echo $result->name;
    }

    /**
     * Recordset action.
     *
     * Performs select and update operations using Recordset.
     */
    public function recordset()
    {
        $rs = \Kotchasan\Orm\Recordset::create('Index\World\Model');
        $rs->updateAll(array('name' => ''));
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $rs->find($rnd);
            $result->name = 'Hello World!';
            $result->save();
        }
        $result = $rs->find($result->id);
        echo $result->name;
    }

    /**
     * Query Builder action.
     *
     * Performs select and update operations using Query Builder.
     */
    public function querybuilder()
    {
        $db = \Kotchasan\Database::create();
        $db->createQuery()->update('world')->set(array('name' => ''))->execute();
        $query = $db->createQuery()->from('world');
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $query->where(array('id', $rnd))->first();
            $db->createQuery()->update('world')->where(array('id', $result->id))->set(array('name' => 'Hello World!'))->execute();
        }
        $result = $query->where(array('id', $result->id))->first();
        echo $result->name;
    }

    /**
     * SQL Command action.
     *
     * Performs select and update operations using SQL commands.
     */
    public function sql()
    {
        $db = \Kotchasan\Database::create();
        $db->query("UPDATE `world` SET `name`=''");
        for ($i = 0; $i < 2; ++$i) {
            $rnd = mt_rand(1, 10000);
            $result = $db->customQuery('SELECT * FROM  `world` WHERE `id`='.$rnd);
            $db->query("UPDATE `world` SET `name`='Hello World!' WHERE `id`=".$result[0]->id);
        }
        $result = $db->customQuery('SELECT * FROM  `world` WHERE `id`='.$result[0]->id);
        echo $result[0]->name;
    }
}
