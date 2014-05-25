<?php
/**
 * Surface Controller
 *
 * @copyright 2013 Henri de Jong
 * @author Henri de Jong <henridejong@gmail.com>
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SurfaceController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {
        throw new \Exception('Not implemented');
    }

    public function editAction()
    {
        throw new \Exception('Not implemented');
    }

    public function deleteAction()
    {
        throw new \Exception('Not implemented');
    }


    public function viewAction()
    {
        return new ViewModel(array('surfaceobjects' => array()));
    }
}
