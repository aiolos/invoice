<?php
namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;

class OwnerTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getOwner($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveOwner(Owner $owner)
    {
        $data = array(
            'name' => $owner->getName(),
            'gender'  => $owner->getGender(),
            'street'  => $owner->getStreet(),
            'housenumber'  => $owner->getHousenumber(),
            'postalcode'  => $owner->getPostalcode(),
            'city'  => $owner->getCity(),
            'country'  => $owner->getCountry(),
        );

        $id = (int) $owner->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getOwner($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}