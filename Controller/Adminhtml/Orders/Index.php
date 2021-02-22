<?php
/*
 * @package     Intelipost_Pickup
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Push\Controller\Adminhtml\Orders;

class Index extends \Intelipost\Push\Controller\Adminhtml\Orders
{

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Intelipost_Push::Orders');
    
        $resultPage->getConfig()->getTitle()->prepend(__('Orders'));

        $resultPage->addBreadcrumb(__('Push'), __('Manage Orders'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Intelipost_Push::Orders');
    }
}
