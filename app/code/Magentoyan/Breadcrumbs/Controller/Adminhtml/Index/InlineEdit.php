<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magentoyan\Breadcrumbs\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\MessageInterface;
use Magento\Framework\App\ObjectManager;


class InlineEdit extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;
    
    private $_mygrid;

    
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magentoyan\Breadcrumbs\Model\Grid $mygrid,
        \Magento\Framework\Escaper $escaper = null
    ) {
        
        $this->resultJsonFactory = $resultJsonFactory;
        
        $this->dataObjectHelper = $dataObjectHelper;
        $this->logger = $logger;
        
        $this->escaper = $escaper ?: ObjectManager::getInstance()->get(\Magento\Framework\Escaper::class);
        
        $this->_mygrid = $mygrid;
        
        parent::__construct($context);
    }

    

    /**
     * Inline edit action execute
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $postItems = $this->getRequest()->getParam('items', []);
        
        
        
        
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData(
                [
                    'messages' => [
                        __('Please correct the data sent.')
                    ],
                    'error' => true,
                ]
            );
        }

        foreach (array_keys($postItems) as $id) {
            
            
            $row = $this->_mygrid->load($id);
            $row->setData('position', $postItems[$id]['position'])->save();
            $row->setData('enable', $postItems[$id]['enable'])->save();
        }

        return $resultJson->setData(
            [
                'messages' => $this->getErrorMessages(),
                'error' => $this->isErrorExists()
            ]
        );
    }

    

    

    /**
     * Get array with errors
     *
     * @return array
     */
    protected function getErrorMessages()
    {
        $messages = [];
        foreach ($this->getMessageManager()->getMessages()->getErrors() as $error) {
            $messages[] = $error->getText();
        }
        return $messages;
    }

    /**
     * Check if errors exists
     *
     * @return bool
     */
    protected function isErrorExists()
    {
        return (bool)$this->getMessageManager()->getMessages(true)->getCountByType(MessageInterface::TYPE_ERROR);
    }

    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magentoyan_Breadcrumbs::inline_edit');
    }
    

    
    
}
