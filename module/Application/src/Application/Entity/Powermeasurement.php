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
 * @ORM\Table(name="powerConsumption")
 * @property integer $id
 * @property integer $value
 * @property date $date
 */
class Powermeasurement implements InputFilterAwareInterface
{
    /**
     * The value
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * The object
     * @var \Application\Entity\Objectownership
     * @ORM\ManyToOne(targetEntity="Objectownership", inversedBy="powermeasurements")
     * @ORM\JoinColumn(name="object_id", referencedColumnName="id")
     */
    protected $objectId;

    /**
     * The value
     * @var integer
     * @ORM\Column(type="integer")
     */
    protected $value;

    /**
     * The date of the measurement
     * @ORM\Column(type="date")
     */
    protected $date;

    protected $inputFilter;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getObject()
    {
        return $this->objectId;
    }

    public function setObject($object)
    {
        $this->objectId = $object;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        //$this->id = $data['id'];
        //$this->name = $data['name'];
        $this->date = new \DateTime($data['date']);
        $this->value = $data['value'];
        //$this->object = $data['type'];
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'value',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'date',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 10,
                            'max'      => 10,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}