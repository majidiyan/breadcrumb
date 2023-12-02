<?php


namespace Magentoyan\Breadcrumbs\Model;

use Magentoyan\Breadcrumbs\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'magentoyan_breadcrumbs';

    /**
     * @var string
     */
    protected $_cacheTag = 'magentoyan_breadcrumbs';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'magentoyan_breadcrumbs';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Magentoyan\Breadcrumbs\Model\ResourceModel\Grid');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

   
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
    

    
    
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

   
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }
    
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

   
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }
    
    public function getEnable()
    {
        return $this->getData(self::ENABLE);
    }

   
    public function setEnable($enable)
    {
        return $this->setData(self::ENABLE, $enable);
    }

   
}
