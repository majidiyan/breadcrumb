<?php
 
    /**
     * Magentoyan Grid collection
     *
     * @category    Magentoyan
     * @package     Magentoyan_Breadcrumbs
     * @author      Magentoyan Software Private Limited
     *
     */
 
namespace Magentoyan\Breadcrumbs\Model\ResourceModel\Grid;
 
/* use required classes */
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
 
    /**
     * @param EntityFactoryInterface $entityFactory,
     * @param LoggerInterface        $logger,
     * @param FetchStrategyInterface $fetchStrategy,
     * @param ManagerInterface       $eventManager,
     * @param StoreManagerInterface  $storeManager,
     * @param AdapterInterface       $connection,
     * @param AbstractDb             $resource
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        StoreManagerInterface $storeManager,
        AdapterInterface $connection = null,
        AbstractDb $resource = null
    ) {
        $this->_init('Magentoyan\Breadcrumbs\Model\Grid', 'Magentoyan\Breadcrumbs\Model\ResourceModel\Grid');
        //Class naming structure 
        // 'NameSpace\ModuleName\Model\ModelName', 'NameSpace\ModuleName\Model\ResourceModel\ModelName'
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);
        $this->storeManager = $storeManager;
    }
     
    protected function _initSelect()
    {
        parent::_initSelect();
        
        $this->addFilterToMap('entity_id', 'main_table.entity_id');
        
        $this->getSelect()
                ->joinLeft(
            ['secondTable' => $this->getTable('catalog_category_entity')],
            'main_table.entity_id = secondTable.entity_id',
            ['level']
        )
                ->joinLeft(
            ['thirdTable' => $this->getTable('catalog_category_entity_varchar')],
            'main_table.entity_id = thirdTable.entity_id AND thirdTable.attribute_id = 45 AND thirdTable.store_id = 0',
            ['value']
        );
        
    }
}