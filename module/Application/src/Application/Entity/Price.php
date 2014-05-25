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
 * @ORM\Table(name="prices")
 * @property integer $id
 * @property integer $year
 * @property integer $price
 * @property string $unit
 */
class Price implements InputFilterAwareInterface
{
    /**
     * The objecttype id
     * @var \Application\Entity\Objecttype
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Objecttype", inversedBy="prices")
     * @ORM\JoinColumn(name="id", referencedColumnName="id")
     */
    protected $id;

    /**
     * The year for this price
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * The actual price (in euros)
     * @var integer
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $price;

    /**
     * The unit for this price (m2, kwh, year)
     * @var string
     * @ORM\Column(type="string")
     */
    protected $unit;

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

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function getPrice()
    {

        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    public function getUnit()
    {

        return $this->unit;
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

//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'id',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'Int'),
//                ),
//            )));

            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'year',
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
                                'min'      => 1,
                                'max'      => 100,
                            ),
                        ),
                    ),
                )
            ));

            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'price',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => 'numberFormat', 'options' => array('locale' => 'nl_NL')),
                    ),
                    'validators' => array(
                        array(
                            'name' => 'Float',
                            'options' => array(
                                'min' => 0,
                                'locale' => 'nl_NL'
                            ),
                        ),
                    ),
                )
            ));

            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'unit',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                )
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}