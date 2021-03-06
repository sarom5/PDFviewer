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
 * Controller for external calls base class.
 */
class Pdfviewer_Controller_Base_External extends Zikula_AbstractController
{
    /**
     * Post initialise.
     *
     * Run after construction.
     *
     * @return void
     */
    protected function postInitialize()
    {
        // Set caching to false by default.
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    }

    /**
     * Displays one item of a certain object type using a separate template for external usages.
     *
     * @param array  $args              List of arguments.
     * @param string $args[ot]          The object type
     * @param int    $args[id]          Identifier of the item to be shown
     * @param string $args[source]      Source of this call (contentType or scribite)
     * @param string $args[displayMode] Display mode (link or embed)
     *
     * @return string Desired data output.
     */
    public function display(array $args = array())
    {
        $getData = $this->request->query;
        $controllerHelper = new Pdfviewer_Util_Controller($this->serviceManager);
    
        $objectType = isset($args['objectType']) ? $args['objectType'] : '';
        $utilArgs = array('controller' => 'external', 'action' => 'display');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controller', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerType', $utilArgs);
        }
    
        $id = (isset($args['id'])) ? $args['id'] : $getData->filter('id', null, FILTER_SANITIZE_STRING);
    
        $component = $this->name . ':' . ucwords($objectType) . ':';
        if (!SecurityUtil::checkPermission($component, $id . '::', ACCESS_READ)) {
            return '';
        }
    
        $source = (isset($args['source'])) ? $args['source'] : $getData->filter('source', '', FILTER_SANITIZE_STRING);
        if (!in_array($source, array('contentType', 'scribite'))) {
            $source = 'contentType';
        }
    
        $displayMode = (isset($args['displayMode'])) ? $args['displayMode'] : $getData->filter('displayMode', 'embed', FILTER_SANITIZE_STRING);
        if (!in_array($displayMode, array('link', 'embed'))) {
            $displayMode = 'embed';
        }
    
        unset($args);
    
        $entityClass = 'Pdfviewer_Entity_' . ucwords($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
        $idFields = ModUtil::apiFunc('Pdfviewer', 'selection', 'getIdFields', array('ot' => $objectType));
        $idValues = array('id' => $id);
    
        $hasIdentifier = $controllerHelper->isValidIdentifier($idValues);
        //$this->throwNotFoundUnless($hasIdentifier, $this->__('Error! Invalid identifier received.'));
        if (!$hasIdentifier) {
            return $this->__('Error! Invalid identifier received.');
        }
    
        // assign object data fetched from the database
        $entity = $repository->selectById($idValues);
        if ((!is_array($entity) && !is_object($entity)) || !isset($entity[$idFields[0]])) {
            //$this->throwNotFound($this->__('No such item.'));
            return $this->__('No such item.');
        }
    
        $entity->initWorkflow();
    
        $instance = $id . '::';
    
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);
        // set cache id
        $accessLevel = ACCESS_READ;
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        $this->view->setCacheId($objectType . '|' . $id . '|a' . $accessLevel);
    
        $this->view->assign('objectType', $objectType)
                  ->assign('source', $source)
                  ->assign('item', $entity)
                  ->assign('displayMode', $displayMode);
    
        return $this->view->fetch('external/' . $objectType . '/display.tpl');
    }
    
    /**
     * Popup selector for scribite plugins.
     * Finds items of a certain object type.
     *
     * @param array $args List of arguments.
     *
     * @return output The external item finder page
     */
    public function finder(array $args = array())
    {
        PageUtil::addVar('stylesheet', ThemeUtil::getModuleStylesheet('Pdfviewer'));
    
        $getData = $this->request->query;
        $controllerHelper = new Pdfviewer_Util_Controller($this->serviceManager);
    
        $objectType = isset($args['objectType']) ? $args['objectType'] : $getData->filter('objectType', 'document', FILTER_SANITIZE_STRING);
        $utilArgs = array('controller' => 'external', 'action' => 'finder');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controller', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerType', $utilArgs);
        }
    
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Pdfviewer:' . ucwords($objectType) . ':', '::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());
    
        $entityClass = 'Pdfviewer_Entity_' . ucwords($objectType);
        $repository = $this->entityManager->getRepository($entityClass);
    
        $editor = (isset($args['editor']) && !empty($args['editor'])) ? $args['editor'] : $getData->filter('editor', '', FILTER_SANITIZE_STRING);
        if (empty($editor) || !in_array($editor, array('xinha', 'tinymce'/*, 'ckeditor'*/))) {
            return 'Error: Invalid editor context given for external controller action.';
        }
        $sort = (isset($args['sort']) && !empty($args['sort'])) ? $args['sort'] : $getData->filter('sort', '', FILTER_SANITIZE_STRING);
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
    
        $sdir = (isset($args['sortdir']) && !empty($args['sortdir'])) ? $args['sortdir'] : $getData->filter('sortdir', '', FILTER_SANITIZE_STRING);
        $sdir = strtolower($sdir);
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
    
        $sortParam = $sort . ' ' . $sdir;
    
        // the current offset which is used to calculate the pagination
        $currentPage = (int) (isset($args['pos']) && !empty($args['pos'])) ? $args['pos'] : $getData->filter('pos', 1, FILTER_VALIDATE_INT);
    
        // the number of items displayed on a page for pagination
        $resultsPerPage = (int) (isset($args['num']) && !empty($args['num'])) ? $args['num'] : $getData->filter('num', 0, FILTER_VALIDATE_INT);
        if ($resultsPerPage == 0) {
            $resultsPerPage = $this->getVar('pageSize', 20);
        }
        $where = '';
        list($entities, $objectCount) = $repository->selectWherePaginated($where, $sortParam, $currentPage, $resultsPerPage);
    
        foreach ($entities as $k => $entity) {
            $entity->initWorkflow();
        }
    
        $view = Zikula_View::getInstance('Pdfviewer', false);
    
        $view->assign('editorName', $editor)
             ->assign('objectType', $objectType)
             ->assign('items', $entities)
             ->assign('sort', $sort)
             ->assign('sortdir', $sdir)
             ->assign('currentPage', $currentPage)
             ->assign('pager', array('numitems'     => $objectCount,
                                     'itemsperpage' => $resultsPerPage));
    
        return $view->display('external/' . $objectType . '/find.tpl');
    }
}
