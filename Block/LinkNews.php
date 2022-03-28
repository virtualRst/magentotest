<?php
namespace Rst\Magentotest\Block;

Class LinkNews extends \Magento\Framework\View\Element\Template
{
	protected $dataHelper;
	
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Rst\Magentotest\Helper\Data $dataHelper
	){
		parent::__construct($context);
		$this->dataHelper = $dataHelper;
	}
	
	public function getNewsLink()
	{
		$newsLink = $this->dataHelper->getStorefrontConfig('news_link');
		
		return $newsLink;
	}
	
	public function getBaseUrl()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}
}
?>
