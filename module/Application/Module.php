<?php
/**
 * Application
 *
 * PHP Version 5.3
 *
 * @category  Application
 * @package   Application
 * @author    Henri de Jong <henridejong@gmail.com>
 * @copyright 2013-2014 Henri de Jong
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getTarget();
        $eventManager        = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        $e->getApplication()->getServiceManager()->get('translator');
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->getSharedManager()->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'authPreDispatch'), 100);

        $sm = $application->getServiceManager();

        $sharedEventManager->attach('Application\Controller\Plugin\Authoriser', 'unauthorised', function ($event) use ($sm) {
            $url = $sm->get('router')->assemble(array('action' => 'not-allowed'), array('name' => 'error'));
            $response = $event->getTarget()->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(405);
            $response->sendHeaders();
            exit;
        });
    }

    public function init(ModuleManager $moduleManager)
    {
        /* Change the layout, when the module is ZfcUser */
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach('ZfcUser', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controller->layout('layout/login');
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    // Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
//                'Application\Model\DbTable\Owners' =>  function($sm) {
//                    $tableGateway = $sm->get('OwnersGateway');
//                    $table = new Owners($tableGateway);
//                    return $table;
//                },
//                'Application\Model\Mapper\OwnerMapper' =>  function($sm) {
//                    $tableGateway = $sm->get('OwnersGateway');
//                    $table = new OwnerMapper($tableGateway);
//                    return $table;
//                },
//                'Application\Model\Mapper\ObjecttypeMapper' =>  function($sm) {
//                    $tableGateway = $sm->get('ObjecttypesGateway');
//                    $table = new ObjecttypeMapper($tableGateway);
//                    return $table;
//                },
//                'Application\Model\Mapper\ObjectMapper' =>  function($sm) {
//                    $tableGateway = $sm->get('ObjectsGateway');
//                    $table = new ObjectMapper($tableGateway);
//                    return $table;
//                },
//                'Application\Model\Mapper\PowerMapper' =>  function($sm) {
//                    $tableGateway = $sm->get('PowerGateway');
//                    $table = new PowerMapper($tableGateway);
//                    return $table;
//                },
//                'OwnersGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    //$resultSetPrototype = new ResultSet();
//                    $resultSetPrototype = new HydratingResultSet();
//                    $resultSetPrototype->setObjectPrototype(new Owner());
//                    return new TableGateway('owners', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\DbTable\Objecttypes' =>  function($sm) {
//                    $tableGateway = $sm->get('ObjecttypesGateway');
//                    $table = new Objecttypes($tableGateway);
//                    return $table;
//                },
//                'ObjecttypesGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Objecttype());
//                    return new TableGateway('objecttypes', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\DbTable\Prices' =>  function($sm) {
//                    $tableGateway = $sm->get('PricesGateway');
//                    $table = new Prices($tableGateway);
//                    return $table;
//                },
//                'PricesGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Price());
//                    return new TableGateway('prices', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\DbTable\Objects' =>  function($sm) {
//                    $tableGateway = $sm->get('ObjectsGateway');
//                    $table = new Objects($tableGateway);
//                    return $table;
//                },
//                'ObjectsGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Object());
//                    return new TableGateway('objects', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\DbTable\Powerconsumption' =>  function($sm) {
//                    $tableGateway = $sm->get('PowerGateway');
//                    $table = new Powerconsumption($tableGateway);
//                    return $table;
//                },
//                'PowerGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Power());
//                    return new TableGateway('powerConsumption', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\DbTable\Surfaces' =>  function($sm) {
//                    $tableGateway = $sm->get('SurfaceGateway');
//                    $table = new Surfaces($tableGateway);
//                    return $table;
//                },
//                'SurfaceGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Surface());
//                    return new TableGateway('surface', $dbAdapter, null, $resultSetPrototype);
//                },
//                'Application\Model\Mapper\SurfaceMapper' =>  function($sm) {
//                    $tableGateway = $sm->get('SurfaceGateway');
//                    $table = new SurfaceMapper($tableGateway);
//                    return $table;
//                },
            ),
        );
    }

    /**
     * Require an identy for each dispatch into the Application namespace
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function authPreDispatch(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $authService = $serviceManager->get('zfcuser_auth_service');

        /* Console requests can be skipped for validation as this is already done by the system */
        if ($e->getRequest() instanceof ConsoleRequest){
            return;
        }

        if (!$authService->hasIdentity()) {
            $url = $e->getRouter()->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            exit;
        }
    }
}
