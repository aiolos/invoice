<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'login',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'zfcuser',
                        'action' => 'logout',
                    ),
                ),
            ),

            // The owner route
            'owner' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/owner[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Owner',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The objecttype route
            'objecttype' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/objecttype[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Objecttype',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The objectownership route
            'objectownership' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/objectownership[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Objectownership',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The power route
            'power' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/power[/:action][/:id[/:measurement]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Power',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The invoice route
            'invoice' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/invoice[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Invoice',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The surface route
            'surface' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/surface[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Surface',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The winter route
            'winter' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/winter[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Winter',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The price route
            'price' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/price[/:action][/:objecttype[/:year]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Price',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The price route
            'stats' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/stats[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Stats',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The user route
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/users[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'MailTransport' => function ($sm) {
                $config = $sm->get('config');
                //$transport = new \Zend\Mail\Transport\Sendmail();
                $transport = new \Zend\Mail\Transport\Smtp();
                $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));

                return $transport;
            },
            'InvoiceDocument' => function ($sm) {
                $service = new \Application\Document\Invoice();
                $service
                    ->setServiceManager($sm);

                return $service;
            },
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Owner' => 'Application\Controller\OwnerController',
            'Application\Controller\Winter' => 'Application\Controller\WinterController',
            'Application\Controller\Objecttype' => 'Application\Controller\ObjecttypeController',
            'Application\Controller\Objectownership' => 'Application\Controller\ObjectownershipController',
            'Application\Controller\Power' => 'Application\Controller\PowerController',
            'Application\Controller\Price' => 'Application\Controller\PriceController',
            'Application\Controller\Surface' => 'Application\Controller\SurfaceController',
            'Application\Controller\Stats' => 'Application\Controller\StatsController',
            'Application\Controller\Invoice' => 'Application\Controller\InvoiceController',
            'Application\Controller\User' => 'Application\Controller\UserController',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'Authoriser' => function($sm) {
                $authoriser = new \Application\Controller\Plugin\Authoriser();
                $authoriser
                    ->setService($sm->getServiceLocator()->get('Authorisation'))
                    ->setEventManager($sm->getServiceLocator()->get('eventmanager'));

                return $authoriser;
            }
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'FormElementWw' => 'Application\Form\View\Helper\FormElementWw',
            'FormElementValid' => 'Application\Form\View\Helper\FormElementValid',
            'FormElementStaticText' => 'Application\Form\View\Helper\FormElementStaticText',
            'Datatable' => 'Application\View\Helper\Datatable'
        ),
        'factories' => array(
            'Authoriser' => function($sm) {
                $authoriser = new Application\View\Helper\Authoriser();
                $authoriser->setService($sm->getServiceLocator()->get('Authorisation'));

                return $authoriser;
            }
        )
    ),
    'form_elements' => array(
        'invokables' => array(
            'Application\Form\Element\StaticText' => 'Application\Form\Element\StaticText',
        ),
        'factories' => array(
            'invoiceForm' => function ($sm) {
                $form = new \Application\Form\InvoiceForm();
                $form->setServiceLocator($sm);
                $form->init();
                $form
                    ->setInputFilter($sm->getServiceLocator()->get('InputFilterManager')->get('InvoiceInputFilter'))
                    ->setHydrator(new \DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity($sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), '\Application\Entity\Invoice'));
                return $form;
            },
            'powerForm' => function ($sm) {
                $form = new \Application\Form\PowerForm();
                $form->setServiceLocator($sm);
                $form->init();
                $form
                    //->setInputFilter($sm->getServiceLocator()->get('InputFilterManager')->get('InvoiceInputFilter'))
                    ->setHydrator(new \DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity($sm->getServiceLocator()->get('Doctrine\ORM\EntityManager'), '\Application\Entity\Powermeasurement'));
                return $form;
            },
        ),
    ),
    'input_filters' => array(
        'invokables' => array(
            'UserInputFilter' => '\Application\InputFilter\UserInputFilter',
            'InvoiceInputFilter' => '\Application\InputFilter\InvoiceInputFilter',
         ),
    ),
    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);
