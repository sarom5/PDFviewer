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
 * Generic single item display content plugin implementation class.
 */
class Pdfviewer_ContentType_Item extends Pdfviewer_ContentType_Base_Item
{
    // feel free to extend the content type here
}

function Pdfviewer_Api_ContentTypes_item($args)
{
    return new Pdfviewer_Api_ContentTypes_itemPlugin();
}
