<?php

namespace Intelipost\Push\Model\Request\ShipmentOrder;

use Magento\Framework\Model\AbstractModel;

class Customer extends AbstractModel
{
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $cellphone;
    public $is_company;
    public $federal_tax_payer_id;
    public $shipping_address;
    public $shipping_number;
    public $shipping_additional;
    public $shipping_reference;
    public $shipping_quarter;
    public $shipping_city;
    public $shipping_state;
    public $shipping_zip_code;
    public $shipping_country;
    public $_collectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
    }

    public function getInformation($entity_id, $tax_vat)
    {
        $information = $this->selectCustomerAddress($entity_id);

        foreach ($information as $info) {
            $this->getAddressData($info['street']);
            $this->first_name               = $info['firstname'];
            $this->last_name               = $info['lastname'];
            $this->email                   = $info['email'];
            $this->phone                   = $info['telephone'];
            $this->is_company               = false;
            $this->federal_tax_payer_id   = $tax_vat;
            $this->shipping_city          = $info['city'];
            $this->shipping_state            = $info['region'];
            $this->shipping_zip_code      = $info['postcode'];
            $this->shipping_country        = $info['country_id'];
        }
        return $this->prepareEndCustomerObj();
    }

    public function selectCustomerAddress($entity_id)
    {
        $collectionObj = $this->_collectionFactory->create();
        $collectionObj->addFieldToFilter('parent_id', $entity_id)
            ->addFieldToFilter('address_type', 'shipping');
        $data = $collectionObj->getData();

        return $data;
    }

    public function getAddressData($addressArray)
    {
        $addressArray = explode("\n", $addressArray);
        $this->shipping_address     = (isset($addressArray[0]) && $addressArray[0] != '') ? $addressArray[0] : "";
        $this->shipping_number         = (isset($addressArray[1]) && $addressArray[1] != '') ? $addressArray[1] : $this->getAddressNumber();
        $this->shipping_additional  = (isset($addressArray[2]) && $addressArray[2] != '') ? $addressArray[2] : "";
        $this->shipping_quarter     = (isset($addressArray[3]) && $addressArray[3] != '') ? $addressArray[3] : "";

        return $this;
    }

    public function getAddressNumber()
    {
        $number = explode(',', $this->shipping_address);

        $retorno = "s/n";

        if ($number) {
            if (is_numeric(trim($number[1]))) {
                $retorno = trim($number[1]);
            }
        }
        return $retorno;
    }

    public function prepareEndCustomerObj()
    {
        $endCustomer = new \stdClass();
        $endCustomer->first_name             = $this->first_name;
        $endCustomer->last_name             = $this->last_name;
        $endCustomer->email                 = $this->email;
        $endCustomer->phone                 = $this->phone;
        $endCustomer->cellphone             = $this->cellphone;
        $endCustomer->is_company             = $this->is_company;
        $endCustomer->federal_tax_payer_id  = $this->federal_tax_payer_id;
        $endCustomer->shipping_address         = $this->shipping_address;
        $endCustomer->shipping_number         = $this->shipping_number;
        $endCustomer->shipping_additional   = $this->shipping_additional;
        $endCustomer->shipping_reference    = $this->shipping_reference;
        $endCustomer->shipping_quarter         = $this->shipping_quarter;
        $endCustomer->shipping_city         = $this->shipping_city;
        $endCustomer->shipping_state         = $this->shipping_state;
        $endCustomer->shipping_zip_code     = $this->shipping_zip_code;
        $endCustomer->shipping_country         = $this->shipping_country;

        return $endCustomer;
    }
}
