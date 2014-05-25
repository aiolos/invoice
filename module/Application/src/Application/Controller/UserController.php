<?php
/**
 * Application
 *
 * PHP Version 5.3
 *
 * @category  Application
 * @package   Application\Controller
 * @author    Henri de Jong <henridejong@gmail.com>
 * @copyright 2013 Henri de Jong
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Application\Entity\User;
use Application\Entity\Operation;

/**
 * UserController
 *
 * @category Application
 * @package  Application\Controller
 * @author   Henri de Jong <henridejong@gmail.com>
 *
 * @method \Application\Controller\Plugin\Authoriser authoriser()
 */
class UserController extends AbstractActionController
{
    /**
     * The entity manager
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * The user from the route
     * @var Application\Entity\User
     */
    protected $user;

    /**
     * The view accept criteria
     * @var mixed
     */
    protected $acceptCriteria = array();

    /**
     * Show a list of distribution point for a single company
     * @return Zend\View\Model\ModelInterface
     */
    public function indexAction()
    {
        //$this->authoriser()->checkPermission(Operation::READ, new User());
        $query = $this->getEntityManager()->createQuery('SELECT u FROM Application\Entity\User u');
        $users = $query->getResult();

        return $this->acceptableViewModelSelector($this->acceptCriteria)
            ->setVariables(array(
                'users' => $users
            ));
    }

    /**
     * View a single distribution point
     * @return Zend\View\Model\ModelInterface
     */
    public function viewAction()
    {
        //$this->authoriser()->checkPermission(Operation::READ, new User());
        return $this->acceptableViewModelSelector($this->acceptCriteria)
            ->setVariables(array(
                'user' => $this->getUser()
            ));
    }

    /**
     * Add a new distribution point to a company
     * @return Zend\View\Model\ModelInterface
     */
    public function addAction()
    {
        //$this->authoriser()->checkPermission(Operation::CREATE, new User());
        $entityManager = $this->getEntityManager();
        $user = new User();
        $form = $this->getServiceLocator()->get('FormElementManager')->get('\Application\Form\UserForm');
        $form
            ->setInputFilter($this->getServiceLocator()->get('InputFilterManager')->get('UserInputFilter'))
            ->setHydrator(new DoctrineEntity($entityManager, 'Application\Entity\User'))
            ->bind($user);
        $form->get('submit')->setValue('toevoegen');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /* Crypt the password */
                $crypt = new \Zend\Crypt\Password\Bcrypt();
                $password = $crypt->create($user->getPassword());
                $user->setPassword($password);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirect()->toRoute('user');
            }
        }
        return $this->acceptableViewModelSelector($this->acceptCriteria)
            ->setVariables(array(
                'form' => $form
            ));
    }

    public function editAction()
    {
        $user = $this->getUser();
        //$this->authoriser()->checkPermission(Operation::UPDATE, $user);

        $form = $this->getServiceLocator()->get('FormElementManager')->get('\Application\Form\UserForm');
        $form
            ->setInputFilter($this->getServiceLocator()->get('InputFilterManager')->get('UserInputFilter'))
            ->setHydrator(new DoctrineEntity($this->getEntityManager(), 'Application\Entity\User'))
            ->bind($user);
        $form->get('submit')->setValue('Bewerken');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('user');
            }
        }
        return $this->acceptableViewModelSelector($this->acceptCriteria)
            ->setVariables(array(
                'form' => $form
            ));
    }

    public function removeAction()
    {
        throw new \Exception('Not implemented');
    }

    /**
     * Get the entity manager
     * @return Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    /**
     * Get the entity manager
     * @param Doctrine\ORM\EntityManager $entityManager
     * @return \Application\Controller\DepartmentController
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    public function getUser()
    {
        if (!$this->user) {
            $this->user = $this->getEntityManager()->find('Application\Entity\User', $this->params()->fromRoute('id'));

            if (is_null($this->user)) {
                throw new \InvalidArgumentException('Unable to find user with identifier ' . $this->params()->fromRoute('id'));
            }
        }
        return $this->user;
    }

    public function setUser(Application\Entity\User $user)
    {
        $this->user = $user;
        return $this;
    }
}
