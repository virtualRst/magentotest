<?php
namespace Rst\Magentotest\Controller\Adminhtml\Allnews;

class Delete extends \Magento\Backend\App\Action {
    public function execute(){
        $id=$this->getRequest()->getParam('news_id');
        $resultRedirect=$this->resultRedirectFactory->create();
        if($id){
            $title="";
            try{
                $model=$this->_objectManager->create(\Rst\Magentotest\Model\Allnews::class);
                $model->load($id);
                $title=$model->getTitle();
                $model->delete();
                $this->messageManager->addSuccess(__('The news has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_news_on_delete',
                    ['title'=>$title,
                    'status'=>'success']
                );
                return $resultRedirect->setPath('*/*/');
            }catch (\Execption $e){
                $this->_eventManager->dispatch(
                    'adminhtml_news_on_delete',
                    ['title'=>$title,
                    'status'=>'fail']
                );
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit',['news_id'=>$id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a news to delete'));
        return $resultRedirect->setPath('*/*/');
    }
}