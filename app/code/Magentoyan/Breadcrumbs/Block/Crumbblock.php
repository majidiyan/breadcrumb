<?php

namespace Magentoyan\Breadcrumbs\Block;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\Store;
use Magento\Framework\Registry;
use Magento\Catalog\Model\CategoryRepository;
use Magentoyan\Breadcrumbs\Model\Grid as MyGrid;
use Magentoyan\Breadcrumbs\Helper\Data as MyHelper;

class Crumbblock extends \Magento\Framework\View\Element\Template {

    /**
     * Catalog data
     *
     * @var Data
     */
    protected $_catalogData = null;
    protected $_categoryRepository;
    protected $_model;
    protected $_helper;

    /**
     * @param Context $context
     * @param Data $catalogData
     * @param array $data
     */
    public function __construct(
            Context $context,
            Data $catalogData,
            Registry $registry,
            CategoryRepository $catRepository,
            MyGrid $myGrid,
            MyHelper $myHelper,
            array $data = []) {
        $this->_catalogData = $catalogData;
        $this->registry = $registry;
        $this->_categoryRepository = $catRepository;
        $this->_model = $myGrid;
        $this->_helper = $myHelper;
        parent::__construct($context, $data);
    }

    public function getCrumbs() {
        $evercrumbs = array();

        $evercrumbs[] = array(
            'label' => 'Home',
            'title' => 'Go to Home Page',
            'link' => $this->_storeManager->getStore()->getBaseUrl()
        );

        $path = $this->_catalogData->getBreadcrumbPath();
        $product = $this->registry->registry('current_product');
        $categoryCollection = clone $product->getCategoryCollection();
        $categoryCollection->clear();
        $categoryCollection->addAttributeToSort('level', $categoryCollection::SORT_ORDER_DESC)->addAttributeToFilter('path', array('like' => "1/" . $this->_storeManager->getStore()->getRootCategoryId() . "/%"));
        $categoryCollection->setPageSize(1);
        $breadcrumbCategories = $categoryCollection->getFirstItem()->getParentCategories();

        if ($this->_helper->getConfig('enable')) {
            //add cats

            $prodCat = $this->getProductCat($product);
            $catItem = $prodCat['result'];
            $catParents = $prodCat['parents'];

            foreach ($catParents as $catParent) {

                if (@$catItem['id'] != $catParent['id']) {
                    $evercrumbs[] = array(
                        'label' => @$catParent['label'],
                        'title' => @$catParent['title'],
                        'link' => @$catParent['link'],
                    );
                }
            }

            $evercrumbs[] = array(
                'label' => @$catItem['label'],
                'title' => @$catItem['title'],
                'link' => @$catItem['link'],
            );

            //add cats end
        }

        $evercrumbs[] = array(
            'label' => $product->getName(),
            'title' => $product->getName(),
            'link' => ''
        );

        return $evercrumbs;
    }

    private function getProductCat($_product) {

        $cats = $_product->getCategoryIds();
        
        $catsAr = [];
        $catsNameAr = [];
        
        
        $collectionx = $this->_model->getCollection()
                ->addFieldToFilter('enable', 1)
                ->addFieldToFilter('entity_id', ['in' => $cats])
                ->setOrder('position', 'ASC');
        
        foreach ($collectionx as $row) {
            
            $category_id = $row->getData('entity_id');
            $_cat = $this->_categoryRepository->get($category_id);
             //subcats
            $subcats = $_cat->getChildrenCategories();
            $countSubcats = count($subcats);
            //subcats END
            
            if ($_cat->getIncludeInMenu() && $_cat->getIsActive()){
                
                $catsNameAr[$_cat->getId()] = ['name' => $_cat->getName(), 'subcats' => $countSubcats,
                    'pos' => $row->getData('position'), 'url' => $_cat->getUrl()];
                break;
            }
            
        }
        
        krsort($catsNameAr);

        $result = [];
        foreach ($catsNameAr as $k => $v) {

            $result[] = ['id' => $k, 'label' => $v['name'], 'title' => $v['name'], 'link' => $v['url'], 'pos' => $v['pos']];
        }

        $pos = array_column($result, 'pos');
        array_multisort($pos, SORT_ASC, $result);
        
        //parents
        $_caty = $this->_categoryRepository->get(@$result[0]['id']);
        $parentCats = $_caty->getParentCategories();
        $parentsArray = [];
        foreach ($parentCats as $parentCat) {
            $parentsArray[] = [
                'id' => $parentCat->getId(),
                'label' => $parentCat->getName(),
                'title' => $parentCat->getName(),
                'link' => $parentCat->getUrl(),
                'pos' => 1,
            ];
        }

        //parents end
        
        
        
        return ['result' => @$result[0], 'parents' => $parentsArray];
    }

    private function getSubcats($category) {
        
    }
}
