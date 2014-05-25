<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * An owner
 *
 * @ORM\Entity
 * @ORM\Table(name="owners")
 * @property string $initials
 * @property string $name
 * @property string $street
 * @property string $housenumber
 * @property string $postalcode
 * @property string $city
 * @property string $country
 * @property string $gender
 * @property string $email
 * @property string $language
 * @property string $shipname
 * @property string $telephone
 * @property integer $debinr
 * @property integer $id
 * @property string $objectownerships
 * @property string $invoices
 */
class Owner implements InputFilterAwareInterface
{
    protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $initials;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $street;

    /**
     * @ORM\Column(type="string")
     */
    protected $housenumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $postalcode;

    /**
     * @ORM\Column(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $gender;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $language;

    /**
     * @ORM\Column(type="string")
     */
    protected $shipname;

    /**
     * @ORM\Column(type="string")
     */
    protected $telephone;

    /**
     * @ORM\Column(type="integer")
     */
    protected $debinr;

    /**
     * The objectownerships of the owner
     *
     * @ORM\OneToMany(targetEntity="Objectownership", mappedBy="owner")
     */
    protected $objectownerships;

    /**
     * The invoices of the owner
     *
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="owner")
     */
    protected $invoices;

    public function __construct()
    {
        $this->objectownerships = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getInitials()
    {
        return $this->initials;
    }

    public function setInitials($initials)
    {
        $this->initials = $initials;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getHousenumber()
    {
        return $this->housenumber;
    }

    public function setHousenumber($housenumber)
    {
        $this->housenumber = $housenumber;
        return $this;
    }

    public function getPostalcode()
    {
        return $this->postalcode;
    }

    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    public function getShipname()
    {
        return $this->shipname;
    }

    public function setShipname($shipname)
    {
        $this->shipname = $shipname;
        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getDebinr()
    {
        return $this->debinr;
    }

    /**
     *
     * @param int $debinr
     * @return \Application\Entity\Owner
     */
    public function setDebinr($debinr)
    {
        $this->debinr = $debinr;
        return $this;
    }

    /**
     *
     * @return \Application\Entity\Objectownership
     */
    public function getObjectownerships()
    {
        return $this->objectownerships;
    }

    /**
     *
     * @return \Application\Entity\Owner
     */
    public function addObjectownerships(\Application\Entity\Objectownership $objectownership)
    {
        $this->objectownerships[] = $objectownership;

        return $this;
    }

    /**
     *
     * @return \Application\Entity\Invoice
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     *
     * @return \Application\Entity\Owner
     */
    public function addInvoices(Invoice $invoice)
    {
        $this->invoices[] = $invoice;

        return $this;
    }

    /**
     *
     * @param \Application\Entity\Objectownership $objectTypes
     * @return \Application\Entity\Owner
     */
//    public function setObjectownerships(\Application\Entity\Objectownership $objectownerships)
//    {
//        $this->objectownerships = $objectownerships;
//        return $this;
//    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->id = $data['id'];
        $this->initials = $data['initials'];
        $this->name = $data['name'];
        $this->city = $data['city'];
        $this->country = $data['country'];
        $this->debinr = $data['debinr'];
        $this->email = $data['email'];
        $this->gender = $data['gender'];
        $this->housenumber = $data['housenumber'];
        $this->language = $data['language'];
        $this->postalcode = $data['postalcode'];
        $this->shipname = $data['shipname'];
        $this->street = $data['street'];
        $this->telephone = $data['telephone'];
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $factory = new InputFactory();
            $defaultStringFilter = array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                );
            $defaultStringValidators = array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                );

            $inputFilter->add($factory->createInput(array(
                'name'       => 'id',
                'required'   => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'initials',
                'required' => false,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'city',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'country',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'debinr',
                'required' => false,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => false,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'gender',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'housenumber',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'language',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'postalcode',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'shipname',
                'required' => false,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'street',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'telephone',
                'required' => false,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}