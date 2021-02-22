<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Controller\Adminhtml;

abstract class Orders extends \Magento\Backend\App\Action
{

    protected $coreRegistry;

    protected $filter;

    protected $resultForwardFactory;

    protected $resultPageFactory;

    protected $itemsFactory;

    protected $collectionFactory;

    protected $_helper;

    protected $_shipment;

    public $_shipmentOrder;

    public $_shipped;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Intelipost\Quote\Model\ResourceModel\Shipment\CollectionFactory $_collectionFactory,
        \Intelipost\Quote\Model\Shipment $shipment,
        \Intelipost\Push\Model\Request\ShipmentOrderFactory $shipmentOrder,
        \Intelipost\Push\Model\Request\ShippedFactory $shipped,
        \Intelipost\Push\Helper\Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->filter = $filter;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->collectionFactory = $_collectionFactory;
        $this->_shipment = $shipment;
        $this->_shipmentOrder = $shipmentOrder;
        $this->_helper = $helper;
        $this->_shipped = $shipped;
        parent::__construct($context);
    }

    protected function _initCurrentItem()
    {
        $itemId = $this->getRequest()->getParam('id');

        if ($itemId) {
            $this->coreRegistry->register(RegistryConstants::CURRENT_INTELIPOST_PUSH_ITEM_ID, $itemId);
        }

        return $itemId;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Intelipost_Push::orders');
    }
}
