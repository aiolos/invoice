<?php
/**
 * Price Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\PriceForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Price;

class PriceController extends AbstractActionController
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
        $queryBuilder->select('o')->from('Application\Entity\Owner', 'o');

        $owners = $queryBuilder->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

        return new JsonModel(array(
            'owners' => $owners,
            'iTotalRecords' => count($owners),
            'iTotalDisplayRecords' => count($owners)
        ));
    }

    public function addAction()
    {
        $objecttypeId = (int) $this->params()->fromRoute('objecttype', 0);
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->layout('layout/ajax-layout.phtml');
        }
        $price = new Price();
        $objecttype = $this->getEntityManager()->find('Application\Entity\Objecttype', $objecttypeId);
        $price->setId($objecttype);
        $form = new PriceForm();
        $form->get('submit')->setValue('Add');
        $form->bind($price);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setInputFilter($price->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $price->setId($objecttype);
                $this->getEntityManager()->persist($price);
                $this->getEntityManager()->flush();

                if($this->getRequest()->isXmlHttpRequest()) {
                    return new JsonModel(array(
                        'redirect' => 'objecttype/view/' . $objecttype->getId(),
                        'success'=> true,
                    ));
                }
                // Redirect to objecttype
                return $this->redirect()->toRoute('objecttype', array('action' => 'view', 'id' => $objecttype->getId()));

            }
        }
        return array('form' => $form, 'is_xmlhttprequest' => $this->getRequest()->isXmlHttpRequest(), 'objecttype' => $objecttype->getId());
    }

    public function editAction()
    {
        $year = (int) $this->params()->fromRoute('year', 0);
        $objecttype = (int) $this->params()->fromRoute('objecttype', 0);
        if (!$year || !$objecttype) {
            return $this->redirect()->toRoute('owner', array(
                'action' => 'add'
            ));
        }
        if($this->getRequest()->isXmlHttpRequest()) {
            $this->layout('layout/ajax-layout.phtml');
        }
        //$owner = $this->getEntityManager()->find('Application\Entity\Price', $this->params()->fromRoute('id'));
        $prices = $this->getEntityManager()->getRepository('Application\Entity\Price')->findBy(array('id' => $objecttype, 'year' => $year));
        $price = $prices[0];
        $europrice = $price->setPrice(preg_replace('/\./', ',', $price->getPrice()));
        $form = new PriceForm();
        $form->bind($price);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            //$form->setInputFilter($price->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($price);
                $this->getEntityManager()->flush();

                if($this->getRequest()->isXmlHttpRequest()) {
                    return new JsonModel(array(
                        'redirect' => 'owner',
                        'success'=> true,
                    ));
                }
                // Redirect to list of owners
                return $this->redirect()->toRoute('objecttype', array('action' => 'view' , 'id' => $objecttype));
            }
        }

        return array(
            'objecttype' => $objecttype,
            'year' => $year,
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
