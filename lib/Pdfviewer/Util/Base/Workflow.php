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
 * Utility base class for workflow helper methods.
 */
class Pdfviewer_Util_Base_Workflow extends Zikula_AbstractBase
{
    /**
      * This method returns a list of possible object states.
      *
      * @return array List of collected state information.
      */
     public function getObjectStates()
     {
         $states = array();
         $states[] = array('value' => 'initial',
                           'text' => $this->__('Initial'),
                           'icon' => 'redled.png');
         $states[] = array('value' => 'deferred',
                           'text' => $this->__('Deferred'),
                           'icon' => 'redled.png');
         $states[] = array('value' => 'approved',
                           'text' => $this->__('Approved'),
                           'icon' => 'greenled.png');
         $states[] = array('value' => 'deleted',
                           'text' => $this->__('Deleted'),
                           'icon' => 'redled.png');
    
         return $states;
     }
    
    /**
     * This method returns information about a certain state.
     *
     * @param string $state The given state value.
     *
     * @return array|null The corresponding state information.
     */
    public function getStateInfo($state = 'initial')
    {
        $result = null;
        $stateList = $this->getObjectStates();
        foreach ($stateList as $singleState) {
            if ($singleState['value'] != $state) {
                continue;
            }
            $result = $singleState;
            break;
        }
    
        return $result;
    }
    
    /**
     * This method returns the workflow name for a certain object type.
     *
     * @param string $objectType Name of treated object type.
     *
     * @return string Name of the corresponding workflow.
     */
    public function getWorkflowName($objectType = '')
    {
        $result = '';
        switch ($objectType) {
            case 'document':
                $result = 'none';
                break;
        }
    
        return $result;
    }
    
    /**
     * This method returns the workflow schema for a certain object type.
     *
     * @param string $objectType Name of treated object type.
     *
     * @return array|null The resulting workflow schema
     */
    public function getWorkflowSchema($objectType = '')
    {
        $schema = null;
        $schemaName = $this->getWorkflowName($objectType);
        if ($schemaName != '') {
            $schema = Zikula_Workflow_Util::loadSchema($schemaName, $this->name);
        }
    
        return $schema;
    }
    
    /**
     * Retrieve the available actions for a given entity object.
     *
     * @param Zikula_EntityAccess $entity The given entity instance.
     *
     * @return array List of available workflow actions.
     */
    public function getActionsForObject($entity)
    {
        // get possible actions for this object in it's current workflow state
        $objectType = $entity['_objectType'];
        $schemaName = $this->getWorkflowName($objectType);
    
        $idcolumn = $entity['__WORKFLOW__']['obj_idcolumn'];
        $wfActions = Zikula_Workflow_Util::getActionsForObject($entity, $objectType, $idcolumn, $this->name);
    
        // as we use the workflows for multiple object types we must maybe filter out some actions
        $listHelper = new Pdfviewer_Util_ListEntries($this->serviceManager);
        $states = $listHelper->getEntries($objectType, 'workflowState');
        $allowedStates = array();
        foreach ($states as $state) {
            $allowedStates[] = $state['value'];
        }
    
        $actions = array();
        foreach ($wfActions as $actionId => $action) {
            $nextState = (isset($action['nextState']) ? $action['nextState'] : '');
            if ($nextState != '' && !in_array($nextState, $allowedStates)) {
                continue;
            }
    
            $actions[$actionId] = $action;
            $actions[$actionId]['buttonClass'] = $this->getButtonClassForAction($actionId);
        }
    
        return $actions;
    }
    
    /**
     * Returns a button class for a certain action.
     *
     * @param string $actionId Id of the treated action.
     */
    protected function getButtonClassForAction($actionId)
    {
        $buttonClass = '';
        switch ($actionId) {
            case 'defer':
                $buttonClass = '';
                break;
            case 'submit':
                $buttonClass = 'ok';//'new';
                break;
            case 'update':
                $buttonClass = 'ok';//'edit';
                break;
            case 'reject':
                $buttonClass = '';
                break;
            case 'delete':
                $buttonClass = 'delete z-btred';
                break;
        }
    
        if (!empty($buttonClass)) {
            $buttonClass = 'z-bt-' . $buttonClass;
        }
    
        return $buttonClass;
    }
    
    /**
     * Executes a certain workflow action for a given entity object.
     *
     * @param Zikula_EntityAccess $entity   The given entity instance.
     * @param string              $actionId Name of action to be executed. 
     *
     * @return bool False on error or true if everything worked well.
     */
    public function executeAction($entity, $actionId = '')
    {
        $objectType = $entity['_objectType'];
        $schemaName = $this->getWorkflowName($objectType);
    
        $result = Zikula_Workflow_Util::executeAction($schemaName, $entity, $actionId, $objectType, $this->name, $idcolumn);
    
        return ($result !== false);
    }
    
    /**
     * Collects amount of moderation items foreach object type.
     *
     * @return array List of collected amounts.
     */
    public function collectAmountOfModerationItems()
    {
        $amounts = array();
    
        // nothing required here as no entities use enhanced workflows including approval actions
    
        return $amounts;
    }
    
    /**
     * Retrieves the amount of moderation items for a given object type
     * and a certain workflow state.
     *
     * @param string $objectType Name of treated object type.
     * @param string $state The given state value.
     *
     * @return integer The affected amount of objects.
     */
    public function getAmountOfModerationItems($objectType, $state)
    {
        $entityClass = $this->name . '_Entity_' . ucwords($objectType);
        $entityManager = $this->serviceManager->getService('doctrine.entitymanager');
    
        $repository = $entityManager->getRepository($entityClass);
    
        $where = 'tbl.workflowState = \'' . $state . '\'';
        $useJoins = false;
        $amount = $repository->selectCount($where, $useJoins);
    
        return $amount;
    }
}
