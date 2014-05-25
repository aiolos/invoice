<?php
/**
 * Owner Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\OwnerForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Owner;

class OwnerController extends AbstractActionController
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
            //'owners' => $this->getEntityManager()->getRepository('Application\Entity\Owner')->findAll(),
        ));
    }

    public function listAction()
    {
        /* @todo something has to be done with the start and limit */

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('o')
            ->from('Application\Entity\Owner', 'o')
            ->setFirstResult($this->params()->fromQuery('iDisplayStart', 0))
            ->setMaxResults($this->params()->fromQuery('iDisplayLength', 5));;

        $owners = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $totalOwners = $queryBuilder->select('count(o)')->setFirstResult(0)->getQuery()->getSingleScalarResult();

        return new JsonModel(array(
            'owners' => $owners,
            'iTotalRecords' => $totalOwners,
            'iTotalDisplayRecords' => $totalOwners
        ));
    }

    public function addAction()
    {
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->layout('layout/ajax-layout.phtml');
        }
        $owner = new Owner();
        $form = new OwnerForm();
        $form->get('submit')->setValue('Add');
        $form->bind($owner);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($owner->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($owner);
                $this->getEntityManager()->flush();

                if($this->getRequest()->isXmlHttpRequest()) {
                    return new JsonModel(array(
                        'redirect' => 'owner',
                        'success'=> true,
                    ));
                }
                // Redirect to list of owners
                return $this->redirect()->toRoute('owner');

            }
        }
        return array('form' => $form, 'is_xmlhttprequest' => $this->getRequest()->isXmlHttpRequest());
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner', array(
                'action' => 'add'
            ));
        }
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->layout('layout/ajax-layout.phtml');
        }
        $owner = $this->getEntityManager()->find('Application\Entity\Owner', $this->params()->fromRoute('id'));

        $form  = new OwnerForm();
        $form->bind($owner);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($owner->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($owner);
                $this->getEntityManager()->flush();

                if($this->getRequest()->isXmlHttpRequest()) {
                    return new JsonModel(array(
                        'redirect' => 'owner',
                        'success'=> true,
                    ));
                }
                // Redirect to list of owners
                return $this->redirect()->toRoute('owner', array('action' => 'view' , 'id' => $id));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'is_xmlhttprequest' => $this->getRequest()->isXmlHttpRequest()
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
                $owner = $this->getEntityManager()->find('Application\Entity\Owner', (int) $request->getPost('id'));
                $this->getEntityManager()->remove($owner);
                $this->getEntityManager()->flush();
            }

            // Redirect to list of owners
            return $this->redirect()->toRoute('owner');
        }

        return new ViewModel(array(
            'owner' => $this->getEntityManager()->find('Application\Entity\Owner', $id),
        ));
    }


    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('owner');
        }

        return new ViewModel(array(
            'owner' => $this->getEntityManager()->find('Application\Entity\Owner', (int) $this->params()->fromRoute('id')),
        ));
    }
}
