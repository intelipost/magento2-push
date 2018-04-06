<?php

/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Cron;

class CreateOrder
{
    protected $_scopeConfig;
    protected $_helper;
    protected $_collectionFactory;
    protected $_shipmentOrder;
    protected $_shipment;

    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Intelipost\Quote\Model\Resource\Shipment\CollectionFactory $collectionFactory,
        \Intelipost\Push\Model\Request\ShipmentOrderFactory $shipmentOrder,
        \Intelipost\Quote\Model\Shipment $shipment,
        \Intelipost\Push\Helper\Data $helper
    )
    {
        $this->_scopeConfig        = $scopeConfig;
        $this->_helper             = $helper;
        $this->_collectionFactory  = $collectionFactory;
        $this->_shipmentOrder      = $shipmentOrder;
        $this->_shipment           = $shipment;
    }

    public function execute()
    {
        $enable         = $this->_scopeConfig->getValue('intelipost_push/cron_config/enable_create_cron');
        $orderQty       = $this->_scopeConfig->getValue('intelipost_push/cron_config/order_qty_to_create');
        $frequency      = $this->_scopeConfig->getValue('intelipost_push/cron_config/frequency_to_create');
        $status = $this->_scopeConfig->getValue('intelipost_push/cron_config/cron_status_to_create');
        
        if($enable)
        {
            $shipmentObj = $this->_collectionFactory->create();
            
            $shipmentObj->addFieldToFilter('status', $status)
                ->addFieldToFilter('main_table.intelipost_status','pending');
            $colData = $shipmentObj->getData();

            if(sizeof($colData) >= $orderQty)
            {
                foreach($colData as $shipment)
                {
                    $col = $this->_shipmentOrder->create();
                    $col->shipmentOrder($shipment);
                }
            }
        }
    }
}