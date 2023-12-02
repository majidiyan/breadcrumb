<?php

namespace Magentoyan\Breadcrumbs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


class Data extends AbstractHelper {

    protected $_scopeConfig;
    
    protected $_objectManager;
    
    
    
    
    

    public function __construct(
            
            Template\Context $context
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
       
        
        
        
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }
    
    public function getConfig($value){

        return $this->_scopeConfig->getValue('magentoyan_breadcrumbs/general/'.$value, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
    
    

   
}
