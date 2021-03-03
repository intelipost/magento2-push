<?php

namespace Intelipost\Push\Model\Request;

use Magento\Framework\Model\AbstractModel;

class Shipped extends AbstractModel
{

    public $shipArray = [];
    public $order_number;
    public $event_date;

    public $message;

    protected $_helper;
    protected $_helperApi;
    protected $_date;
    protected $_timezone;
    protected $_shipment;

    public function __construct(
        \Intelipost\Basic\Client\Intelipost $helperApi,
        \Intelipost\Push\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Intelipost\Quote\Model\Shipment $shipment,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    ) {
        $this->_helper    = $helper;
        $this->_helperApi = $helperApi;
        $this->_date      = $date;
        $this->_timezone  = $timezone;
        $this->_shipment  = $shipment;
    }


    public function shippedRequestBody($collectionData)
    {
        $currentDateTimeUTC = (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        $localizedDateTimeISO = $this->_timezone->date(new \DateTime($currentDateTimeUTC))->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
        $this->event_date = (str_replace(' ', 'T', $localizedDateTimeISO)).'';

        $this->order_number = $collectionData['order_number'];

        $requestBody = $this->prepareShippedRequestBody();
        $this->sendShippedRequest(json_encode($requestBody), $collectionData);
    }

    public function prepareShippedRequestBody()
    {
        $bodyObj               = new \stdClass();
        $bodyObj->order_number = $this->order_number;
        $bodyObj->event_date   = $this->event_date;
        array_push($this->shipArray, $bodyObj);
        return $this->shipArray;
    }


    public function sendShippedRequest($requestBody, $collectionData)
    {
        $response = $this->_helperApi->apiRequest('POST', 'shipment_order/multi/shipped/with_date', $requestBody);
        $result = json_decode($response);
        
        if ($result->status == 'ERROR') {
            $messages = null;
            $errorCount = 1;

            foreach ($result->messages as $_message) {
                $messages .= ' Erro ('. $errorCount . '): ' .$_message->text. "</br>";
                $errorCount++;
            }
            $this->message = $messages;

            $_collectionFactory = $this->_shipment->load($collectionData['id'], "id");
            $_collectionFactory->setIntelipostStatus('error');
            $_collectionFactory->setIntelipostMessage(str_replace('</br>', '', $this->message));
            $_collectionFactory->save();
        }

        if ($result->status == 'OK') {
            $_collectionFactory = $this->_shipment->load($collectionData['id'], "id");
            $_collectionFactory->setIntelipostStatus('shipped');
            $_collectionFactory->setIntelipostMessage('Ok.');
            $_collectionFactory->save();
        }
    }

    public function getErrorMessages()
    {
        return $this->message;
    }
}
