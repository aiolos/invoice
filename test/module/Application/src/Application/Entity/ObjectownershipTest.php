<?php

namespace Application\Entity;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-08 at 20:20:26.
 */
class ObjectownershipTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Objectownership
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Objectownership;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /**
     * @covers Application\Entity\Objectownership::getId
     * @todo   Implement testGetId().
     */
    public function testGetId()
    {
        $id = 1;
        $this->assertNull($this->object->getId());
        $this->object->setId($id);
        $this->assertEquals($id, $this->object->getId());
    }

    /**
     * @covers Application\Entity\Objectownership::setId
     * @todo   Implement testSetId().
     */
    public function testSetId()
    {
        $this->assertEquals($this->object, $this->object->setId(1));
    }

    /**
     * @covers Application\Entity\Objectownership::getOwner
     * @todo   Implement testGetOwner().
     */
    public function testGetOwner()
    {
        $owner = new \Application\Entity\Owner;
        $this->assertNull($this->object->getOwner());
        $this->object->setOwner($owner);
        $this->assertEquals($owner, $this->object->getOwner());
    }

    /**
     * @covers Application\Entity\Objectownership::setOwner
     * @todo   Implement testSetOwner().
     */
    public function testSetOwner()
    {
        $this->assertEquals($this->object, $this->object->setOwner(new \Application\Entity\Owner));
    }

    /**
     * @covers Application\Entity\Objectownership::getObject
     */
    public function testGetObject()
    {
        $objecttype = new \Application\Entity\Objecttype;
        $this->assertNull($this->object->getObject());
        $this->object->setObject($objecttype);
        $this->assertEquals($objecttype, $this->object->getObject());
    }

    /**
     * @covers Application\Entity\Objectownership::setObject
     */
    public function testSetObject()
    {
        $this->assertEquals($this->object, $this->object->setObject(new \Application\Entity\Objecttype));
    }

    /**
     * @covers Application\Entity\Objectownership::getFromDate
     */
    public function testGetFromDate()
    {
        $date = new \DateTime('now');
        $this->assertNull($this->object->getFromDate());
        $this->object->setFromDate($date);
        $this->assertEquals($date, $this->object->getFromDate());
    }

    /**
     * @covers Application\Entity\Objectownership::setFromDate
     */
    public function testSetFromDate()
    {
        $this->assertEquals($this->object, $this->object->setFromDate(new \DateTime('now')));
    }

    /**
     * @covers Application\Entity\Objectownership::getToDate
     */
    public function testGetToDate()
    {
        $date = new \DateTime('now');
        $this->assertNull($this->object->getToDate());
        $this->object->setToDate($date);
        $this->assertEquals($date, $this->object->getToDate());
    }

    /**
     * @covers Application\Entity\Objectownership::setToDate
     */
    public function testSetToDate()
    {
        $this->assertEquals($this->object, $this->object->setToDate(new \DateTime('now')));
    }

    /**
     * @covers Application\Entity\Objectownership::getName
     */
    public function testGetName()
    {
        $name = 'Alpha';
        $this->assertNull($this->object->getName());
        $this->object->setName($name);
        $this->assertEquals($name, $this->object->getName());
    }

    /**
     * @covers Application\Entity\Objectownership::setName
     * @todo   Implement testSetName().
     */
    public function testSetName()
    {
        $this->assertEquals($this->object, $this->object->setName('naam'));
    }

    /**
     * @covers Application\Entity\Objectownership::getArrayCopy
     * @todo   Implement testGetArrayCopy().
     */
    public function testGetArrayCopy()
    {
        $id = 123;
        $this->object->setId($id);

        $data = $this->object->getArrayCopy();
        $this->assertEquals($data['id'], $id);
    }

    /**
     * @covers Application\Entity\Objectownership::populate
     */
    public function testPopulate()
    {
        $data = array(
            'name' => 'een naam',
            'fromDate' => '2013-12-31 00:00:00',
            'toDate' => '2015-12-31 00:00:00',
        );
        $this->object->populate($data);

        $this->assertEquals($data['name'], $this->object->getName());
        $this->assertEquals(new \DateTime($data['fromDate']), $this->object->getFromDate());
        $this->assertEquals(new \DateTime($data['toDate']), $this->object->getToDate());
    }

    /**
     * @covers Application\Entity\Objectownership::setInputFilter
     */
    public function testSetInputFilter()
    {
        $this->setExpectedException('Exception');
        $inputFilter = new \Zend\InputFilter\InputFilter;
        $this->object->setInputFilter($inputFilter);
    }

    /**
     * @covers Application\Entity\Objectownership::getInputFilter
     * @todo   Implement testGetInputFilter().
     */
    public function testGetInputFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->object->getInputFilter());
    }

}
