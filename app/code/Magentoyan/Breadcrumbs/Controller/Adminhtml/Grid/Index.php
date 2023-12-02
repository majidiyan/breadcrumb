<?php
/**
 * Grid Record Index Controller.
 * @category  Magentoyan
 * @package   Magentoyan_Breadcrumbs
 * @author    Magentoyan
 * @copyright Copyright (c) 2010-2017 Magentoyan Software Private Limited (https://magentoyan.com)
 * @license   https://store.magentoyan.com/license.html
 */
namespace Magentoyan\Breadcrumbs\Controller\Adminhtml\Grid;

use Magentoyan\Breadcrumbs\Model\Grid as MyGrid;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as ListAllCats;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    
    private $_mygrid;
    private $_listAllCats;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
            
            MyGrid $myGrid,
            ListAllCats $listAllCats
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        
        $this->_mygrid = $myGrid;
        $this->_listAllCats = $listAllCats;
        
        $this->refreshListCategories();
    }

    /**
     * Mapped eBay Order List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magentoyan_Breadcrumbs::grid_list');
        $resultPage->getConfig()->getTitle()->prepend(__('Breadcrumbs List'));
        return $resultPage;
    }

    /**
     * Check Order Import Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magentoyan_Breadcrumbs::grid_list');
    }
    
    private function refreshListCategories()
    {
        $currentIds = [0];
        
        
        
        $collection = $this->_mygrid->getCollection();
        
        foreach($collection as $item)
            $currentIds[] = $item->getData('entity_id');
        
        
        
        $neededCategories = $this->_listAllCats->create()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('is_active', 1)
                ->addFieldToFilter('entity_id', ['nin' => $currentIds])
                ->setOrder('entity_id', 'ASC');
        
        
        foreach ($neededCategories as $neededCategory) {
            $this->_mygrid->setData([
                'entity_id' => $neededCategory->getId(),
                'position' => 50,
                'enable' => 1,
            ])->save();
        }
        
        
        
    }
}
