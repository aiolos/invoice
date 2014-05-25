<?php
/**
 * Power Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PowerForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\Powermeasurement;

class PowerController extends AbstractActionController
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
            'measurements' => $this->getEntityManager()->getRepository('Application\Entity\Powermeasurement')->findAll(),
        ));
    }

    public function addAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $ownership = $this->getEntityManager()->find('Application\Entity\Objectownership', $this->params()->fromRoute('id'));

        $powerMeasurement = new Powermeasurement();
        $powerMeasurement->setObject($ownership);
        $form = $this->getServiceLocator()->get('FormElementManager')->get('PowerForm');

        $form->get('submit')->setValue('Add');
        $form->bind($powerMeasurement);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($powerMeasurement->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($powerMeasurement);
                $this->getEntityManager()->flush();

                // Redirect to list of measurements
                return $this->redirect()->toRoute('power', array('action' => 'view', 'id' => $id));
            }
        }
        return array('form' => $form, 'id' => $id, 'ownership' => $ownership);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $measurementId = (int) $this->params()->fromRoute('measurement', 0);
        if (!$measurementId) {
            return $this->redirect()->toRoute('power', array(
                'action' => 'add',
                'id' => $id
            ));
        }
        $powerMeasurement = $this->getEntityManager()->find('Application\Entity\Powermeasurement', $this->params()->fromRoute('measurement'));

        $form = $this->getServiceLocator()->get('FormElementManager')->get('PowerForm');
        $form->bind($powerMeasurement);
        $form->get('submit')->setAttribute('value', 'Edit');
        $form->get('value')->setValue($powerMeasurement->getValue());
        $form->get('date')->setValue($powerMeasurement->getDate()->format('Y-m-d'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($powerMeasurement->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->persist($powerMeasurement);
                $this->getEntityManager()->flush();

                // Redirect to list of albums
                return $this->redirect()->toRoute('power', array('action' => 'view', 'id' => $id));
            }
        }

        return array(
            'id' => $id,
            'measurement' => $measurementId,
            'ownership' => $powerMeasurement->getObject(),
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('power');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $powerMeasurement = $this->getEntityManager()->getRepository()->find('Application\Entity\Powermeasurement', (int) $request->getPost('id'));
                $this->getEntityManager()->remove($powerMeasurement);
                $this->getEntityManager()->flush();
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('power');
        }

        return array(
            'id'    => $id,
            'powermeasurement' => $this->getOwnerMapper()->getOwner($id)
        );
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('power');
        }
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('p')
            ->from('Application\Entity\Powermeasurement', 'p')
            ->where('p.objectId = ' . $id)
            ->orderBy('p.date');
        $powermeasurements = $queryBuilder->getQuery()->getResult();

        return new ViewModel(array(
            'powerobjects' => $powermeasurements,
            'ownership' => $this->getEntityManager()->getRepository('Application\Entity\Objectownership')->find((int) $this->params()->fromRoute('id')),
        ));
    }
}
