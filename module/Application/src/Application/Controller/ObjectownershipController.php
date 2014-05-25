<?php
/**
 * Objectownership Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ObjectownershipForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Objectownership;

class ObjectownershipController extends AbstractActionController
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
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }
        $owner = $this->getEntityManager()->find('Application\Entity\Owner', (int) $this->params()->fromRoute('id'));
        $objectownership = new Objectownership($owner);
        $form = new ObjectownershipForm();
        $form->get('submit')->setValue('Add');
        $form->bind($objectownership);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($objectownership->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $post = $request->getPost();
                $object = $this->getEntityManager()->find('Application\Entity\Objecttype', $post['type']);
                $objectownership->setObject($object);

                $this->getEntityManager()->persist($objectownership);
                $this->getEntityManager()->flush();

                // Redirect to the owner
                return $this->redirect()->toRoute('owner', array('action' => 'view', 'id' => $owner->getId()));
            }
        }
        return array('form' => $form, 'id' => $id, 'owner' => $owner);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objectownership');
        }
        $objectownership = $this->getEntityManager()->find('Application\Entity\Objectownership', $this->params()->fromRoute('id'));

        $form  = new ObjectownershipForm();

        $form->get('submit')->setAttribute('value', 'Edit');
        $form->get('name')->setValue($objectownership->getName());
        $form->get('fromDate')->setValue($objectownership->getFromDate()->format('Y-m-d'));
        $form->get('toDate')->setValue($objectownership->getToDate()->format('Y-m-d'));
        $form->get('type')->setValue($objectownership->getObject()->getId());

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($objectownership->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $post = $request->getPost();
                $object = $this->getEntityManager()->find('Application\Entity\Objecttype', $post['type']);
                $objectownership->setObject($object);

                $this->getEntityManager()->persist($objectownership);
                $this->getEntityManager()->flush();

                // Redirect to owner
                return $this->redirect()->toRoute('owner', array('action' => 'view', 'id' => $objectownership->getOwner()->getId()));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'ownerId' => $objectownership->getOwner()->getId(),
            'owner' => $objectownership->getOwner(),
        );
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('objecttype');
        }

        return new ViewModel(array(
            'objectownership' => $this->getEntityManager()->find('Application\Entity\Objectownership', $id),
        ));
    }
}
