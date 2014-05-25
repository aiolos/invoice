<?php
namespace Application\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // ignore the name passed
        parent::__construct('company');
        $this->setAttribute('method', 'post');

        // Username
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Gebruikersnaam',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Gebruikersnaam',
            ),
        ));
        // Email
        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'E-mail',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));
        // DisplayName
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'displayName',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Naam',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Naam',
            ),
        ));
        // password
        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'placeholder' => 'Wachtwoord',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Wachtwoord',
            ),
        ));
        // Submit
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
