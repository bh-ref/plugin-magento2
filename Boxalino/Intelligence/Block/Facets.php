<?php

namespace Boxalino\Intelligence\Block;

class Facets extends \Magento\Framework\View\Element\Template
{	
	private $objectManager;
	private $p13nHelper;
	private $layer;
	private $bxHelperData;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Boxalino\Intelligence\Helper\P13n\Adapter $p13nHelper,
		\Boxalino\Intelligence\Helper\Data $bxHelperData,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        array $data = []
    )
    {
        parent::__construct($context, $data);
		$this->p13nHelper = $p13nHelper;
		$this->layer = $layerResolver->get();
		$this->objectManager = $objectManager;
		$this->bxHelperData = $bxHelperData;
    }

    public function getTopFilters()
    {
		if($this->layer instanceof \Magento\Catalog\Model\Layer\Category\Interceptor && !$this->bxHelperData->isNavigationEnabled()){
			return array();
		}

		if($this->bxHelperData->isTopFilterEnabled() && $this->bxHelperData->isFilterLayoutEnabled()) {
			$facets = $this->p13nHelper->getFacets();
			$fieldName = $this->p13nHelper->getTopFacetFieldName();
			$attribute = $this->objectManager->create("Magento\Catalog\Model\ResourceModel\Eav\Attribute");
			$filter = $this->objectManager->create(
				"Boxalino\Intelligence\Model\Attribute",
				['data' => ['attribute_model' => $attribute], 'layer' => $this->layer]
			);
			$filter->setFacets($facets);
			$filter->setFieldName($fieldName);
			return $filter->getItems();
		}
		return array();
    }
}
