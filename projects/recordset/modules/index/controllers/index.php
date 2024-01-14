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

use Index\World\Model as World;
use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Orm\Recordset;

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
        // Read the field names of the table
        $recordset = Recordset::create('Index\World\Model');
        $fields = $recordset->getFields();
        echo implode(', ', array_keys($fields)).'<br>';

        // Empty the table
        $recordset->emptyTable();

        // Insert new records
        for ($i = 0; $i < 10; ++$i) {
            $world = World::create();
            $world->updated_at = Date::format('Y-m-d H:i:s');
            $world->save();
        }

        // Update all records
        $recordset->updateAll(array('created_at' => Date::format('Y-m-d H:i:s')));

        // Read the total number of records in the table
        echo 'All '.$recordset->count().' records.<br>';

        // Modify a random set of records
        for ($i = 0; $i < 5; ++$i) {
            $rnd = rand(1, 10);
            $world = $recordset->find($rnd);
            $world->name = 'Hello World!';
            $world->save();
        }

        // Query the records that have been modified
        $recordset->where(array('name', '!=', ''));

        // Read the number of found records
        echo 'Found '.$recordset->count().' records.<br>';

        // Display the found records
        foreach ($recordset->all('id', 'name') as $item) {
            echo $item->id.'='.$item->name.'<br>';
            // Delete the displayed item
            $item->delete();
        }

        // Read the field names of the query
        $fields = $recordset->getFields();
        echo implode(', ', array_keys($fields)).'<br>';

        // Read the remaining number of records
        echo 'Remain '.Recordset::create('Index\World\Model')->count().' records.<br>';
    }
}
