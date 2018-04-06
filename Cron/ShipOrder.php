<?php

/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Cron;

class ShipOrder
{
    protected $_scopeConfig;
    protected $_helper;
    protected $_collectionFactory;
    protected $_shipmentOrder;
    protected $_shipment;
    protected $_shipped;

    public function __construct
    (
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Intelipost\Quote\Model\Resource\Shipment\CollectionFactory $collectionFactory,
        \Intelipost\Push\Model\Request\ShipmentOrderFactory $shipmentOrder,
        \Intelipost\Push\Model\Request\ShippedFactory $shipped,
        \Intelipost\Quote\Model\Shipment $shipment,
        \Intelipost\Push\Helper\Data $helper
    )
    {
        $this->_scopeConfig        = $scopeConfig;
        $this->_helper             = $helper;
        $this->_collectionFactory  = $collectionFactory;
        $this->_shipmentOrder      = $shipmentOrder;
        $this->_shipment           = $shipment;
        $this->_shipped            = $shipped;
    }

    public function execute()
    {
        $enable         = $this->_scopeConfig->getValue('intelipost_push/cron_config/enable_ship_cron');
        $orderQty       = $this->_scopeConfig->getValue('intelipost_push/cron_config/order_qty_to_ship');
        $frequency      = $this->_scopeConfig->getValue('intelipost_push/cron_config/frequency_to_ship');
        $status         = $this->_scopeConfig->getValue('intelipost_push/cron_config/cron_status_to_ship');
        
        if($enable)
        {
            $shipmentObj = $this->_collectionFactory->create();
            
            $shipmentObj->addFieldToFilter('status', ['eq' => $status])
                ->addFieldToFilter('main_table.intelipost_status', ['neq' => 'shipped']);
            $colData = $shipmentObj->getData();
            
            if(sizeof($colData) >= $orderQty)
            {
                foreach($colData as $shipment)
                {
                    $col = $this->_shipped->create();
                    $col->shippedRequestBody($shipment);
                }
            }
        }
    }
}