<?php
/**
 * Objecttype Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ObjecttypeForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Objecttype;

class ObjecttypeController extends AbstractActionController
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'objecttypes' => $this->getEntityManager()->getRepository('Application\Entity\Objecttype')->findAll(),
        ));
    }

    public function addAction()
    {
        $objecttype = new Objecttype();
        $form = new ObjecttypeForm();
        $form->get('submit')->setValue('Add');
        $form->bind($objecttype);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($objecttype->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($objecttype);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('objecttype');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objecttype', array(
                'action' => 'add'
            ));
        }
        $objecttype = $this->getEntityManager()->find('Application\Entity\Objecttype', $this->params()->fromRoute('id'));

        $form  = new ObjecttypeForm();
        $form->bind($objecttype);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($objecttype->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($objecttype);
                $this->getEntityManager()->flush();

                // Redirect to list of objecttypes
                return $this->redirect()->toRoute('objecttype');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objecttype');
        }

        return new ViewModel(array(
            'objecttype' => $this->getEntityManager()->find('Application\Entity\Objecttype', $id),
        ));
    }
}
