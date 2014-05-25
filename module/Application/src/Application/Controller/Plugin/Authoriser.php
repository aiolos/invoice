<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Application\Entity\Domain;

class Authoriser extends AbstractPlugin implements EventManagerAwareInterface
{
    const UNAUTHORISED = 'unauthorised';

    /**
     *
     * @var type
     */
    protected $service;

    /**
     *
     * @return \Application\Controller\Plugin\Authoriser
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     *
     * @param type $operation
     * @param type $object
     * @param \Application\Entity\Domain $domain
     */
    public function checkPermission($operation, $object, Domain $domain = null)
    {
        if (!$this->getService()->hasPermission($operation, $object, $domain)) {
            $this->getEventManager()->trigger(self::UNAUTHORISED, $this->getController());
        }
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->getService(), $name)) {
            throw new \Exception('Call to undefined method ' . get_class($this->getService()) . '::' . $name . '()');
        }

        return call_user_func_array(array($this->getService(), $name), $arguments);
    }

    /**
     *
     * @return type
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     *
     * @param type $service
     * @return \Application\Controller\Plugin\Authoriser
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Set the event manager
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return type
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
            )
        );
        $this->events = $events;
        return $this;
    }

    /**
     * Get the event manager
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}
