<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * An invoice
 *
 * @ORM\Entity
 * @ORM\Table(name="invoices")
 * @ORM\HasLifecycleCallbacks
 * @property integer $id
 * @property integer $year
 * @property integer $owner
 * @property string $filename
 * @property date $createdAt
 * @property date $updatedAt
 *
 */
class Invoice
{
    //protected $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * The owner
     * @var \Application\Entity\Owner
     * @ORM\ManyToOne(targetEntity="Owner", inversedBy="invoices")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\Column(type="string")
     */
    protected $filename;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    public function __construct($owner = null)
    {
        $this->owner = $owner;
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

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner(\Application\Entity\Owner $owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @return void
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * @ORM\PreUpdate
     * @return void
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

//    public function getArrayCopy()
//    {
//        return get_object_vars($this);
//    }

//    public function setInputFilter(InputFilterInterface $inputFilter)
//    {
//        throw new \Exception("Not used");
//    }
//
//    public function getInputFilter()
//    {
//        if (!$this->inputFilter) {
//            $inputFilter = new InputFilter();
//            $factory     = new InputFactory();
//
//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'id',
//                'required' => false,
//                'filters'  => array(
//                    array('name' => 'Int'),
//                ),
//            )));
//
//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'year',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'Int'),
//                ),
//            )));
//
//            $inputFilter->add($factory->createInput(array(
//                'name'     => 'filename',
//                'required' => true,
//                'filters'  => array(
//                    array('name' => 'StripTags'),
//                    array('name' => 'StringTrim'),
//                ),
//                'validators' => array(
//                    array(
//                        'name'    => 'StringLength',
//                        'options' => array(
//                            'encoding' => 'UTF-8',
//                            'min'      => 1,
//                            'max'      => 100,
//                        ),
//                    ),
//                ),
//            )));
//
//            $this->inputFilter = $inputFilter;
//        }
//
//        return $this->inputFilter;
//    }
}