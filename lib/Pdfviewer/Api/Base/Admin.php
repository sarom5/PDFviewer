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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Jul 09 16:46:30 CEST 2013.
 */

/**
 * This is the Admin api helper class.
 */
class Pdfviewer_Api_Base_Admin extends Zikula_AbstractApi
{
    /**
     * Returns available admin panel links.
     *
     * @return array Array of admin links.
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'main'),
                             'text' => $this->__('Frontend'),
                             'title' => $this->__('Switch to user area.'),
                             'class' => 'z-icon-es-home');
        }
        /*if (SecurityUtil::checkPermission($this->name . ':Document:', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'document')),
                             'text' => $this->__('Documents'),
                             'title' => $this->__('Document list'));
        }*/
		$links[] = array(
			'url'=> "modules/Help/docs/pdf/Autoren_Pfarrbrief.pdf",
			'text'  => $this->__('Hilfe'),
			'title' => $this->__('Hilfe'),
			'class' => 'z-icon-es-help',
		);
        return $links;
    }
}
