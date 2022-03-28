<?php 
namespace Rst\Magentotest\Controller\Adminhtml\Allnews;

use Magento\Backend\App\Action\Context;
use Rst\Magentotest\Api\AllnewsRepositoryInterface as AllnewsRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Rst\Magentotest\Api\Data\AllnewsInterface;

class InlineEdit extends \Magento\Backend\App\Action {
    protected $allnewsRespository;
    protected $jsonFactory;
    public function __construct(
        Context $context,
        AllnewsRepository $allnewsRespository,
        JsonFactory $jsonFactory
    ){
        parent::__construct($context);
        $this->allnewsRepository=$allnewsRespository;
        $this->jsonFactory=$jsonFactory;
    }
    public function execute(){
        $resultJson=$this->jsonFactory->create();
        $error=false;
        $messages=[];
        $postItems=$this->getRequest()->getParam('items',[]);
        if(!($this->getRequest()->getParam('isAjax') && count($postItems))){
            return $resultJson->setData([
                'messages' =>[__('Please correct the data sent')],
                'error'=>true,
            ]);
        }
        foreach(array_keys($postItems) as $newsId){
                $news=$this->allnewsRepository->getbyId($newsId);
                try{
                    $newsData=$postItems[$newsId];
                    $extendedNewsData=$news->getData();
                    $this->setNewsData($news,$extendedNewsData,$newsData);
                    $this->allnewsRepository->save($news);
                }catch(\Magento\Framework\Exeception\LocalizedExeception $e){
                    $messages[]=$this->getErrorWithNewsId($news,$e->getMessage());
                    $error=true;
                }catch(\RuntimeExeception $e){
                    $messages[]=$this->getErrorWithNewsId($news,$e->getMessage());
                    $error=true;
                }catch(\Exeception $e){
                    $messages[]=$this->getErrorWithNewsId($news,
                __('Something went wrong while saving the news'));
                    $error=true;
                }
            }
            return $resultJson->setData([
                'messages'=>$messages,
                'error'=>$error]);

    }
    protected function getErrorWithNewsId(AllnewsInterface $news,$errorText){
        return '[News ID:'.$news->getId().']'.$errorText;
    }
    public function setNewsData(\Rst\Magentotest\Model\Allnews $news,array $extendedNewsData,array $newsData){
        $news->setData(array_merge($news->getData(),$extendedNewsData,$newsData));
        return $this;
    }
}