<?php
namespace Boxalino\Intelligence\Block;
use Magento\CatalogSearch\Block\Result as Mage_Result;
use Boxalino\Intelligence\Helper\Data as BxData;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\CatalogSearch\Helper\Data;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Search\Model\QueryFactory;
class Result extends Mage_Result
{
    protected $p13nHelper;
    protected $queryFactory;
    protected $bxListProducts;
    protected $queries;
    protected $phrase;
    protected $bxHelperData;

    public function __construct(
        Context $context,
        LayerResolver $layerResolver,
        Data $catalogSearchData,
        QueryFactory $queryFactory,
        \Boxalino\Intelligence\Helper\P13n\Adapter $p13nHelper,
        \Boxalino\Intelligence\Helper\Data $bxHelperData,
        array $data = [])
    {
        $this->p13nHelper = $p13nHelper;
        $this->bxHelperData = $bxHelperData;
        $this->queryFactory = $queryFactory;
        if($this->bxHelperData->isSearchEnabled() && $p13nHelper->areThereSubPhrases()){
            $this->queries = $p13nHelper->getSubPhrasesQueries();
        }

        parent::__construct($context, $layerResolver, $catalogSearchData, $queryFactory, $data);
    }

    public function getSubPhrasesResultText($index){
        return __("Search result for: '%1'", $this->queries[$index] );
    }

    public function getSearchQueryText()
    {
        if($this->bxHelperData->isSearchEnabled() && $this->p13nHelper->areResultsCorrected()){
            $query = $this->queryFactory->get();
            $query->setQueryText($this->p13nHelper->getCorrectedQuery());
            return __("Corrected search results for: '%1'", $this->catalogSearchData->getEscapedQueryText());
        } else if($this->hasSubPhrases()){
            return "";
        }
        return parent::getSearchQueryText();
    }

    public function getSearchQueryLink($index){
        return $this->_storeManager->getStore()->getBaseUrl() . "catalogsearch/result/?q=" . $this->queries[$index];
    }

    protected function _prepareLayout()
    {
        if($this->hasSubPhrases()){
            $title = "Search result for: " . implode(" ",  $this->queries);
            $this->pageConfig->getTitle()->set($title);
            // add Home breadcrumb
            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs) {
                $breadcrumbs->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'title' => __('Go to Home Page'),
                        'link' => $this->_storeManager->getStore()->getBaseUrl()
                    ]
                )->addCrumb(
                    'search',
                    ['label' => $title, 'title' => $title]
                );
            }
            return $this;
        }
        return parent::_prepareLayout();
    }

    public function hasSubPhrases()
    {
        if($this->bxHelperData->isSearchEnabled()){
            return $this->p13nHelper->areThereSubPhrases();
        }
        return 0;
        
    }

    public function getProductListHtml()
    {
        return $this->getChildHtml('search_result_list', false);
    }
}?>