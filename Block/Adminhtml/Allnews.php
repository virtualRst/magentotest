<?php
namespace Rst\Magentotest\Block\Adminhtml;

class Allnews extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_allnews';
        $this->_blockGroup = 'Rst_Magentotest';
        $this->_headerText = __('Manage News');

        parent::_construct();

        if ($this->_isAllowedAction('Rst_Magentotest::save')) {
            $this->buttonList->update('add', 'label', __('Add News'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
?>
