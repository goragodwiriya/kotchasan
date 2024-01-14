<?php
/**
 * @filesource modules/index/controllers/index.php
 *
 * Controller for the Export module.
 * This class handles the export actions for the Export module, including exporting to PDF and DOC formats.
 * For more information, please visit: https://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license https://www.kotchasan.com/license/
 * @author Goragod Wiriya <admin@goragod.com>
 */

namespace Index\Export;

use Kotchasan\Http\Request;

/**
 * Default controller for exporting to PDF and DOC formats.
 *
 * This class handles the export actions for the Export module.
 * It receives a request containing the export type (PDF or DOC) and the content to be exported.
 * Depending on the export type, it generates the corresponding output (PDF or DOC) using the provided content.
 */
class Controller extends \Kotchasan\Controller
{
    /**
     * Export to PDF or DOC format.
     *
     * @param Request $request The HTTP request object.
     */
    public function index(Request $request)
    {
        // Get the export type (PDF or DOC)
        $type = $request->post('type')->toString();
        // Get the content to be exported
        $content = $request->post('content')->detail();

        if ($type === 'doc') {
            // Export as DOC
            $doc = new \Kotchasan\Htmldoc();
            $doc->createDoc($content);
        } else {
            // Export as PDF
            $pdf = new \Kotchasan\Pdf();
            $pdf->AddPage();
            $pdf->WriteHTML($content);
            $pdf->Output();
        }
    }
}
