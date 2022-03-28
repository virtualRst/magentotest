<?php
namespace Rst\Magentotest\Controller\Adminhtml\Allnews;
use Magento\Backend\App\Action;
class Edit extends \Magento\Backend\App\Action{
    protected $_coreRegistry;
    protected $resultPageFactory;
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }   
    protected function _initAction(){
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Rst_Magentotest::news_allnews')
        ->addBreadcrumb(__('News'),__('News'))
        ->addBreadcrumb(__('Manage All news'),__('Manage All News'));
        return $resultPage;
    }
    public function execute(){
        $id=$this->getRequest()->getParam('news_id');
        $model= $this->_objectManager->create(\Rst\Magentotest\Model\Allnews::class);
        if($id){
           $model->load($id);
           if(!$model->getId()){
               $this->messageManager->addError(__('This news no longer exists,'));
               $resultRedirect=$this->resultRedirectFactory->create();
               return $resultRedirect->setPath('*/*/');
           } 
        }
        $this->_coreRegistry->register('news_allnews',$model);
        $resultPage= $this->_initAction();
        $resultPage->addBreadcrumb(
            $id?__('Edit News') : __('Add News'),
            $id?__('Edit News') : __('Add News')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Allnews'));
        $resultPage->getconfig()->getTitle()->prepend($model->getId()?$model->getTitle():__('Add News'));
        return $resultPage;

    }
}