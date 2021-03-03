<?php

namespace Intelipost\Push\Model\Request;

use Magento\Framework\Model\AbstractModel;

class ShipmentOrder extends AbstractModel
{
    public $order_number;
    public $sales_order_number;
    public $quote_id;
    public $delivery_method_id;
    public $estimated_delivery_date;
    public $customer_shipping_costs;
    public $provider_shipping_costs;
    public $origin_warehouse_code;
    public $sales_channel;
    public $scheduled;
    public $scheduling_window_start;
    public $scheduling_window_end;
    public $shipment_order_type;
    public $shipment_order_sub_type;
    public $created;
    public $shipped_date;
    public $message;
    public $tracking_codes;

    public $end_customer;
    public $shipment_order_volume_array = [];
    public $shipment_order_volume_invoice;

    protected $_end_customer_obj;
    protected $_shipment_order_volume_array_obj;
    protected $_shipment_order_volume_invoice_obj;
    protected $_helper;
    protected $_helperApi;
    protected $_shipment;
    protected $_scopeConfig;
    protected $_timezone;
    protected $_shipmentFactory;

    public function __construct(
        \Intelipost\Quote\Model\ResourceModel\Shipment\CollectionFactory $shipmentFactory,
        \Intelipost\Push\Model\Request\ShipmentOrder\CustomerFactory $customer,
        \Intelipost\Push\Model\Request\ShipmentOrder\VolumeFactory $volume,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Intelipost\Push\Model\Request\ShipmentOrder\InvoiceFactory $invoice,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Intelipost\Basic\Client\Intelipost $helperApi,
        \Intelipost\Quote\Model\Shipment $shipment,
        \Intelipost\Push\Helper\Data $helper
    ) {
        $this->_shipmentFactory                   = $shipmentFactory;
        $this->_end_customer_obj                  = $customer;
        $this->_shipment_order_volume_array_obj   = $volume;
        $this->_shipment_order_volume_invoice_obj = $invoice;
        $this->_helper                            = $helper;
        $this->_helperApi                         = $helperApi;
        $this->_shipment                          = $shipment;
        $this->_scopeConfig                       = $scopeConfig;
        $this->_timezone                          = $timezone;
    }


    public function shipmentOrder($collectionData)
    {
        $invoiceObj = $this->_shipment_order_volume_invoice_obj->create();
        $volObj     = $this->_shipment_order_volume_array_obj->create();
        $ecObj      = $this->_end_customer_obj->create();

        $this->order_number                    = $collectionData['order_number'];
        $this->sales_order_number              = $collectionData['increment_id'];
        $this->quote_id                        = $collectionData['quote_id'];
        $this->delivery_method_id              = $collectionData['delivery_method_id'];
        $this->estimated_delivery_date         = str_replace(' ', 'T', $collectionData['delivery_estimate_date_exact_iso']);
        $this->customer_shipping_costs         = $collectionData['customer_shipping_costs'];
        $this->provider_shipping_costs         = $collectionData['provider_shipping_costs'];
        $this->sales_channel                   = $collectionData['sales_channel'];
        $this->scheduled                       = (bool)$collectionData['scheduled'];
        $this->scheduling_window_start         = $collectionData['scheduling_window_start'];
        $this->scheduling_window_end           = $collectionData['scheduling_window_end'];
        $this->shipment_order_type             = $collectionData['shipment_order_type'];
        $this->shipment_order_sub_type         = $collectionData['shipment_order_sub_type'];
        $this->shipment_order_volume_invoice   = $invoiceObj->getInformation($collectionData['order_number']);
        $this->shipment_order_volume_array     = $volObj->getInformation($collectionData['volumes'], $this->shipment_order_volume_invoice);
        $this->end_customer                    = $ecObj->getInformation($collectionData['entity_id'], $collectionData['customer_taxvat']);
        if ($this->_scopeConfig->getValue('intelipost_push/order_status/create_and_ship')) {
            $this->shipped_date                = $this->getNowDateTime();
        }
        $this->created                         = $this->getNowDateTime();
        //$this->origin_warehouse_code         = null;

        $requestBody = $this->prepareShipmentRequestBody();
        $requestBody->delivery_method_external_id = $requestBody->delivery_method_id;
        $this->_helper->logIntelipost(json_encode($requestBody));
        $this->sendShipmentRequest(json_encode($requestBody), $collectionData);
    }

    public function prepareShipmentRequestBody()
    {
        $requestBodyObj = new \stdClass();
        $requestBodyObj->order_number                   = $this->order_number;
        $requestBodyObj->sales_order_number             = $this->sales_order_number;
        $requestBodyObj->quote_id                       = $this->quote_id;
        $requestBodyObj->delivery_method_id             = $this->delivery_method_id;
        $requestBodyObj->estimated_delivery_date        = $this->estimated_delivery_date;
        $requestBodyObj->customer_shipping_costs        = $this->customer_shipping_costs;
        $requestBodyObj->provider_shipping_costs        = $this->provider_shipping_costs;
        $requestBodyObj->sales_channel                  = $this->sales_channel;
        $requestBodyObj->scheduled                      = $this->scheduled;
        $requestBodyObj->scheduling_window_start        = $this->scheduling_window_start;
        $requestBodyObj->scheduling_window_end          = $this->scheduling_window_end;
        $requestBodyObj->shipment_order_type            = $this->shipment_order_sub_type;
        $requestBodyObj->shipment_order_sub_type        = $this->shipment_order_sub_type;
        $requestBodyObj->end_customer                   = $this->end_customer;
        $requestBodyObj->shipment_order_volume_array    = $this->shipment_order_volume_array;
        foreach ($requestBodyObj->shipment_order_volume_array as $volume) {
            if (!isset($volume->shipment_order_volume_invoice->invoice_number)) {
                unset($volume->shipment_order_volume_invoice);
            }
        }
        $requestBodyObj->shipped_date                   = $this->shipped_date;
        $requestBodyObj->created                        = $this->created;
        //$requestBodyObj->origin_warehouse_code        = $this->origin_warehouse_code;

        return $requestBodyObj;
    }


    public function sendShipmentRequest($requestBody, $collectionData)
    {
        $response = $this->_helperApi->apiRequest('POST', 'shipment_order', $requestBody);
        $result = json_decode($response);

        if(!$result)
        {
            $this->message = "Erro desconhecido na API";
            return;
        }

        if ($result->status == 'ERROR') {
            $messages = null;
            $errorCount = 1;

            foreach ($result->messages as $_message) {
                $messages .= ' Erro ('. $errorCount . '): ' .$_message->text. "</br>";
                $errorCount++;
            }

            $this->message = $messages;

            $_collectionFactory = $this->_shipment->load($collectionData['id'], "id");
            if (($result->messages[0])->key != 'shipmentOrder.save.already.existing.order.number') {
                $_collectionFactory->setIntelipostStatus('error');
            } else {
                $_collectionFactory->setIntelipostStatus('created');
                $_collectionFactory->setIntelipostMessage('Ok.');
            }
            $_collectionFactory->setIntelipostMessage(str_replace('</br>', '', $this->message));
            $_collectionFactory->save();
        }

        if ($result->status == 'OK') {
            foreach ($result->content->shipment_order_volume_array as $volume) {
                if (isset($volume->tracking_code)) {
                    $this->tracking_codes .= $volume->tracking_code;
                    if ($volume !== end($result->content->shipment_order_volume_array)) {
                        $this->tracking_codes .= ', ';
                    }
                }
            }
            $this->saveTrackingCodes($collectionData);
            $_collectionFactory = $this->_shipment->load($collectionData['id'], "id");
            $_collectionFactory->setIntelipostStatus('created');
            $this->_helper->logIntelipost(json_encode($this->_scopeConfig->getValue('intelipost_push/order_status/magento_status_after_create')));
            $this->_helper->logIntelipost(json_encode($collectionData['entity_id']));

            $orderId = $collectionData['entity_id'];
            $status = $this->_scopeConfig->getValue('intelipost_push/order_status/magento_status_after_create');

            $this->updateOrderStatus($orderId, $status);

            if (isset($this->shipped_date)) {
                $_collectionFactory->setIntelipostStatus('shipped');
            }
            $_collectionFactory->setIntelipostMessage('Ok.');
            $_collectionFactory->save();
        }
    }

    public function getErrorMessages()
    {
        return $this->message;
    }

    public function saveTrackingCodes($collectionData)
    {
        $_collectionFactory = $this->_shipment->load($collectionData['id'], "id");
        if ($this->tracking_codes) {
            $_collectionFactory->setTrackingCode($this->tracking_codes);
        }
        $_collectionFactory->save();
    }

    public function updateOrderStatus($orderId, $status)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($orderId);
        $order->setStatus($status);
        $order->save();
    }

    public function getNowDateTime()
    {
        $currentDateTimeUTC = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        $localizedDateTimeISO = $this->_timezone->date(new \DateTime($currentDateTimeUTC))->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        $now = str_replace(' ', 'T', $localizedDateTimeISO);
        return $now;
    }
}
