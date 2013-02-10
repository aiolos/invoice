<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Owner;
use Application\Form\OwnerForm;

class OwnerController extends AbstractActionController
{
    protected $ownerTable;
    
    public function indexAction()
    {
        return new ViewModel(array(
            'owners' => $this->getOwnerTable()->fetchAll(),
        ));
    }
    
    public function addAction()
    {
        $form = new OwnerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $owner = new Owner();
            $form->setInputFilter($owner->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $owner->exchangeArray($form->getData());
                $this->getOwnerTable()->saveOwner($owner);

                // Redirect to list of albums
                return $this->redirect()->toRoute('owner');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner', array(
                'action' => 'add'
            ));
        }
        $owner = $this->getOwnerTable()->getOwner($id);

        $form  = new OwnerForm();
        $form->bind($owner);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($owner->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getOwnerTable()->saveOwner($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('owner');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAlbumTable()->deleteOwner($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('owner');
        }

        return array(
            'id'    => $id,
            'owner' => $this->getOwnerTable()->getOwner($id)
        );
    }
    
    public function getOwnerTable()
    {
        if (!$this->ownerTable) {
            $sm = $this->getServiceLocator();
            $this->ownerTable = $sm->get('Application\Model\OwnerTable');
        }
        return $this->ownerTable;
    }
}
