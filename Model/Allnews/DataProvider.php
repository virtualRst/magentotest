<?php 
namespace Rst\Magentotest\Model\Allnews;

use Rst\Magentotest\Model\ResourceModel\Allnews\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
protected $colelction;
protected $dataPersistor;
protected $loadedData;

public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    CollectionFactory $allnewsCollectionFactory,
    DataPersistorInterface $dataPersistor,
    array $meta = [],
    array $data = []
){
    $this->collection=$allnewsCollectionFactory->create();
    $this->dataPersistor = $dataPersistor;
    parent::__construct($name,$primaryFieldName,$requestFieldName,$meta,$data);
    $this->meta = $this->prepareMeta($this->meta);
}
public function prepareMeta(array $meta){
    return $meta;
}
public function getData(){
    if(isset($this->loadedData)){
        return $this->loadedData;
    }
    $items=$this->collection->getItems();
    foreach($items as $news){
        $this->loadedData[$news->getId()]=$news->getData();
    }
    $data=$this->dataPersistor->get('news_allnews');
    if(!empty($data)){
        $news=$this->collection->getNewEmptyItem();
        $news->setdata($data);
        $this->loadedData[$news->getId()]=$news->getData();
        $this->dataPersistor->clear('news_allnews');
    }
    return $this->loadedData;
}

}
