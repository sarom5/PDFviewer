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
 * Event handler implementation class for frontend controller interaction events.
 */
class Pdfviewer_Listener_FrontController extends Pdfviewer_Listener_Base_FrontController
{
    /**
     * Listener for the `frontcontroller.predispatch` event.
     *
     * Runs before the front controller does any work.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function preDispatch(Zikula_Event $event)
    {
        parent::preDispatch($event);
    }
}
