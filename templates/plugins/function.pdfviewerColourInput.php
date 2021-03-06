<?php
/**
 * Pdfviewer.
 *
 * @copyright Sascha Rösler (SR)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Pdfviewer
 * @author Sascha Rösler <sa.roesler@st-marien-spandau.de>.
 * @link http://st-marien-spandau.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Jul 09 16:46:31 CEST 2013.
 */

/**
 * The pdfviewerColourInput plugin handles fields carrying a html colour code.
 * It provides a colour picker for convenient editing.
 *
 * @param array            $params  All attributes passed to this function from the template.
 * @param Zikula_Form_View $view    Reference to the view object.
 *
 * @return string The output of the plugin.
 */
function smarty_function_pdfviewerColourInput($params, $view)
{
    return $view->registerPlugin('Pdfviewer_Form_Plugin_ColourInput', $params);
}
