<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Collections\Criteria;

/**
 * An objecttype
 *
 * @ORM\Entity
 * @ORM\Table(name="objecttypes")
 * @property string $name
 * @property integer $price
 * @property integer $id
 */
class Objecttype implements InputFilterAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * The object of the objecttype
     *
     * @ORM\OneToMany(targetEntity="Objectownership", mappedBy="object")
     *
     */
    protected $objects;

    /**
     * The prices of the objecttype
     *
     * @ORM\OneToMany(targetEntity="Price", mappedBy="id")
     *
     */
    protected $prices;

    protected $inputFilter;

    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPrices()
    {
        return $this->prices;
    }

    public function addPrice($price)
    {
        $this->prices[] = $price;

        return $this;
    }

    public function getCurrentPrice()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("year", 2013))
            ->orderBy(array("year" => Criteria::ASC))
            ->setFirstResult(0)
            ->setMaxResults(1)
        ;

        return $this->getPrices()->matching($criteria)->first();
    }

    public function exchangeArray($data)
    {
        if (isset($data['id'])) {
            $this->id     = $data['id'];
        } elseif (isset($data['objecttype_id'])) {
            $this->id     = $data['objecttype_id'];
        } else {
            $this->id     = null;
        }
        $this->name = (isset($data['name'])) ? $data['name'] : null;

        return $this;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
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
                'name'     => 'name',
                'required' => true,
                'filters'  => $defaultStringFilter,
                'validators' => $defaultStringValidators,
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'price',
                'required' => true,
                'filters' => array(
                    array('name'    => 'Int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
