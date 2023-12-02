<?php



namespace Magentoyan\Breadcrumbs\Api\Data;

interface GridInterface {

    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ID = 'id';
    const ENTITY_ID = 'entity_id';
    const POSITION = 'position';
    const ENABLE = 'enable';
    

    
    public function getId();

    
    public function setId($id);

    
    public function getEntityId();

    public function setEntityId($entityId);

    public function getPosition();

    public function setPosition($position);

    public function getEnable();

    public function setEnable($enable);


}
