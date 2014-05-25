<?php
/**
 * Winter Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Owner;
use Application\Form\OwnerForm;

class WinterController extends AbstractActionController
{
    protected $ownerTable;
    protected $ownerMapper;

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
            'ownerships' => $this->getEntityManager()->getRepository('Application\Entity\Objectownership')->findBy(array('object' => array('type' => 3))),
        ));
    }

    public function getOwnerMapper()
    {
        if (!$this->ownerMapper) {
            $sm = $this->getServiceLocator();
            $this->ownerMapper = $sm->get('Application\Model\Mapper\OwnerMapper');
        }
        return $this->ownerMapper;
    }
}
