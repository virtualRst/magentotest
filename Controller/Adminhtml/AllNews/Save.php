<?php

namespace Rst\Magentotest\Controller\Adminhtml\Allnews;

use Magento\Backend\App\Action;
use Rst\Magentotest\Model\Allnews;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Rst\Magentotest\Model\AllnewsFactory
     */
    private $allnewsFactory;

    /**
     * @var \Rst\Magentotest\Api\AllnewsRepositoryInterface
     */
    private $allnewsRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Rst\Magentotest\Model\AllnewsFactory $allnewsFactory
     * @param \Rst\Magentotest\Api\AllnewsRepositoryInterface $allnewsRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Rst\Magentotest\Model\AllnewsFactory $allnewsFactory = null,
        \Rst\Magentotest\Api\AllnewsRepositoryInterface $allnewsRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->allnewsFactory = $allnewsFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Rst\Magentotest\Model\AllnewsFactory::class);
        $this->allnewsRepository = $allnewsRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Rst\Magentotest\Api\AllnewsRepositoryInterface::class);
        parent::__construct($context);
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Magetest_News::save');
	}

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = Allnews::STATUS_ENABLED;
            }
            if (empty($data['news_id'])) {
                $data['news_id'] = null;
            }

            /** @var \Rst\Magentotest\Model\Allnews $model */
            $model = $this->allnewsFactory->create();

            $id = $this->getRequest()->getParam('news_id');
            if ($id) {
                try {
                    $model = $this->allnewsRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This news no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'news_allnews_prepare_save',
                ['allnews' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->allnewsRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the news.'));
                $this->dataPersistor->clear('news_allnews');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['news_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news.'));
            }

            $this->dataPersistor->set('news_allnews', $data);
            return $resultRedirect->setPath('*/*/edit', ['news_id' => $this->getRequest()->getParam('news_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
?>
