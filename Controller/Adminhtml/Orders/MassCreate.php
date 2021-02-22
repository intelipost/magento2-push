<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */
namespace Intelipost\Push\Controller\Adminhtml\Orders;

use Magento\Framework\Controller\ResultFactory;

class MassCreate extends \Intelipost\Push\Controller\Adminhtml\Orders
{
    protected $redirectUrl = 'intelipost_push/orders/index';

    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            return $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    protected function massAction(\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection)
    {
        $errorCount = 0;
        $totalCount = 0;
        $collectionData = $collection->getData();
        foreach ($collectionData as $cData) {
            $col = $this->_shipmentOrder->create();
            $col->shipmentOrder($cData);
            if ($col->getErrorMessages()) {
                $this->messageManager->addError('Entrega ' . $cData['order_number'] . "</br>" . $col->getErrorMessages());
                $errorCount++;
            }
            $totalCount++;
        }

        $successCount = $totalCount - $errorCount;

        if ($successCount == 1) {
            $this->messageManager->addSuccess('Entrega criada com sucesso: 1.');
        }

        if ($successCount > 1) {
            $this->messageManager->addSuccess('Entregas criadas com sucesso: ' . $successCount . '.');
        }

        foreach ($collection->getAllIds() as $itemId) {
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }

    protected function getComponentRefererUrl()
    {
        return $this->filter->getComponentRefererUrl()?: 'intelipost_push/orders/index';
    }
}
