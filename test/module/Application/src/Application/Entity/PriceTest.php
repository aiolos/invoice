<?php

namespace Application\Entity;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-01-08 at 21:01:31.
 */
class PriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Price
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Price;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Application\Entity\Price::getId
     */
    public function testGetId()
    {
        $id = 1;
        $this->assertNull($this->object->getId());
        $this->object->setId($id);
        $this->assertEquals($id, $this->object->getId());
    }

    /**
     * @covers Application\Entity\Price::setId
     */
    public function testSetId()
    {
        $this->assertEquals($this->object, $this->object->setId(1));
    }

    /**
     * @covers Application\Entity\Price::getYear
     */
    public function testGetYear()
    {
        $year = 2013;
        $this->assertNull($this->object->getYear());
        $this->object->setYear($year);
        $this->assertEquals($year, $this->object->getYear());
    }

    /**
     * @covers Application\Entity\Price::setYear
     */
    public function testSetYear()
    {
        $this->assertEquals($this->object, $this->object->setYear(2014));
    }

    /**
     * @covers Application\Entity\Price::getPrice
     */
    public function testGetPrice()
    {
        $price = 21352;
        $this->assertNull($this->object->getPrice());
        $this->object->setPrice($price);
        $this->assertEquals($price, $this->object->getPrice());
    }

    /**
     * @covers Application\Entity\Price::setPrice
     */
    public function testSetPrice()
    {
        $this->assertEquals($this->object, $this->object->setPrice(5214));
    }

    /**
     * @covers Application\Entity\Price::setUnit
     * @todo   Implement testSetUnit().
     */
    public function testSetUnit()
    {
        $this->assertEquals($this->object, $this->object->setUnit('dag'));
    }

    /**
     * @covers Application\Entity\Price::getUnit
     */
    public function testGetUnit()
    {
        $unit = 'jaar';
        $this->assertNull($this->object->getUnit());
        $this->object->setUnit($unit);
        $this->assertEquals($unit, $this->object->getUnit());
    }

    /**
     * @covers Application\Entity\Price::exchangeArray
     */
    public function testExchangeArray()
    {
        $data = array(
            'year' => 2014,
            'unit' => 'jaar',
            'price' => 123,
        );
        $this->object->exchangeArray($data);

        $this->assertEquals($data['year'], $this->object->getYear());
        $this->assertEquals($data['unit'], $this->object->getUnit());
        $this->assertEquals($data['price'], $this->object->getPrice());
    }

    /**
     * @covers Application\Entity\Price::getArrayCopy
     */
    public function testGetArrayCopy()
    {
        $id = 123;
        $this->object->setId($id);

        $data = $this->object->getArrayCopy();
        $this->assertEquals($data['id'], $id);
    }

    /**
     * @covers Application\Entity\Price::setInputFilter
     */
    public function testSetInputFilter()
    {
        $this->setExpectedException('Exception');
        $inputFilter = new \Zend\InputFilter\InputFilter;
        $this->object->setInputFilter($inputFilter);
    }

    /**
     * @covers Application\Entity\Price::getInputFilter
     */
    public function testGetInputFilter()
    {
        $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->object->getInputFilter());
    }

}