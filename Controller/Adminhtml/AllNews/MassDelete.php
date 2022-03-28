<?php 
namespace Rst\Magentotest\Controller\Adminhtml\Allnews;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Compoenet\MassAction\Filter;
use Rst\Magentotest\Model\ResourceModel\Allnews\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action {

    protected $filter;
    protected $collectionFactory;
    public function __contruct(Content $context,Filter $filter,CollectionFactory $collectionFactory)
    {
        $this->filter-$filter;
        $this->collectionFactory=$collectionFactory;
        parent::__construct($context);
    }
    public function execute(){
        $collection =$this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $news){
            $news->delete();
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.',$collectionSize));
        $resultRedirect=$this->resultFactory->crate(ResultFacory::TYPE__REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

}