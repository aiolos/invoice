<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PowerForm extends Form implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

    public function init($name = null)
    {
        $this->setAttribute('method', 'post');
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => 'date',
            'attributes' => array(
                'type'  => 'date',
            ),
            'options' => array(
                'label' => 'Opnamedatum',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'value',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Meterstand',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
