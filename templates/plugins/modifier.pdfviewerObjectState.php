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
 * The pdfviewerObjectState modifier displays the name of a given object's workflow state.
 * Examples:
 *    {$item.workflowState|pdfviewerObjectState}       {* with led icon *}
 *    {$item.workflowState|pdfviewerObjectState:false} {* no icon *}
 *
 * @param string  $state    Name of given workflow state.
 * @param boolean $withIcon Whether a led icon should be displayed before the name.
 *
 * @return string Enriched and translated workflow state ready for display.
 */
function smarty_modifier_pdfviewerObjectState($state = 'initial', $withIcon = true)
{
    $serviceManager = ServiceUtil::getManager();
    $workflowHelper = new Pdfviewer_Util_Workflow($serviceManager);
    $stateInfo = $workflowHelper->getStateInfo($state);

    $result = $stateInfo['text'];
    if ($withIcon === true) {
        $result = '<img src="' . System::getBaseUrl() . 'images/icons/extrasmall/' . $stateInfo['icon'] . '" width="16" height="16" alt="' . $result . '" />&nbsp;&nbsp;' . $result;
    }

    return $result;
}
