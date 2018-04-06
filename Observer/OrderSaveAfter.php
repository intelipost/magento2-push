<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;


class OrderSaveAfter implements ObserverInterface
{
    private $intelipostStatus;

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

    public function execute(Observer $observer)
    {
        $order         = $observer->getOrder();
        $magentoStatus = $order->getStatus();
        $entity_id     = $order->getEntityId();
        
        $statusToCreate           = $this->_scopeConfig->getValue('intelipost_push/order_status/status_to_create');
        $magentoStatusAfterCreate = $this->_scopeConfig->getValue('intelipost_push/order_status/magento_status_after_create');
        $createAndShip            = $this->_scopeConfig->getValue('intelipost_push/order_status/create_and_ship');
        $statusToShip             = $this->_scopeConfig->getValue('intelipost_push/order_status/status_to_ship');

        $shipmentObj = $this->_collectionFactory->create();
        $shipmentObj->addFieldToFilter('main_table.entity_id', $entity_id);
        $colData = $shipmentObj->getData();

        //Apenas uma entrega
        if(sizeof($shipmentObj) == 1)
        {   
            foreach($colData as $shipment)
            {
                //criação do pedido
                if($magentoStatus == $statusToCreate && $shipment['intelipost_status'] == 'pending')
                {
                    $col = $this->_shipmentOrder->create();
                    $col->shipmentOrder($shipment);            
                }

                //despacho do pedido
                if(!$createAndShip && $magentoStatus == $statusToShip && $shipment['intelipost_status'] == 'created')
                {
                    $col = $this->_shipped->create();
                    $col->shippedRequestBody($shipment);
                }
            }
        }



        //Mais de uma entrega, podem ser criados/despachados separadamente
        if(sizeof($shipmentObj) > 1)
        {
            $this->_helper->logIntelipost(json_encode('duas entregas'));
            //criação do pedidos
            foreach($colData as $shipment)
            {   
                //fazer o match dos skus e criar as entregas relativas na Intelipost
            }

            //despacho do pedidos
            if(!$createAndShip && $magentoStatus == $statusToShip && $shipment['intelipost_status'] == 'shipped')
            {
                // fazer o match dos skus e depachar as entregas relativas na Intelipost
                // $col = $this->_shipped->create();
                // $col->shippedRequestBody($cData);
            }
        }
    }
}