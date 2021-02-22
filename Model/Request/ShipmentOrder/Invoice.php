<?php

namespace Intelipost\Push\Model\Request\ShipmentOrder;

use Magento\Framework\Model\AbstractModel;

class Invoice extends AbstractModel
{
    private $invoice_number;
    private $invoice_series;
    private $invoice_key;
    private $invoice_date;
    private $invoice_total_value;
    private $invoice_products_value;
    private $invoice_cfop;

    protected $_collectionFactory;
    protected $_helper;

    public function __construct(
        \Intelipost\Push\Model\ResourceModel\Invoice\CollectionFactory $collectionFactory
    ) {
        $this->_collectionFactory = $collectionFactory;
    }

    public function getInformation($order_number)
    {
        $invoiceCollection = $this->getInvoiceCollection($order_number);
        foreach ($invoiceCollection as $invoice) {
            $this->invoice_number             = $invoice['invoice_number'];
            $this->invoice_series             = $invoice['invoice_series'];
            $this->invoice_key                 = $invoice['invoice_key'];
            $this->invoice_date             = str_replace(' ', 'T', $invoice['invoice_date']);
            $this->invoice_total_value      = $invoice['invoice_total_value'];
            $this->invoice_products_value   = $invoice['invoice_products_value'];
            $this->invoice_cfop             = $invoice['invoice_cfop'];
        }
        $shipment_order_volume_invoice_obj = $this->preapreInvoiceObj();
        return $shipment_order_volume_invoice_obj;
    }

    public function getInvoiceCollection($order_number)
    {
        $iCollection = $this->_collectionFactory->create();
        $iCollection->addFieldToFilter('order_number', ['eq' => $order_number]);
        return $iCollection->getData();
    }

    public function preapreInvoiceObj()
    {
        $shipment_order_volume_invoice                             = new \Stdclass();
        $shipment_order_volume_invoice->invoice_number             = $this->invoice_number;
        $shipment_order_volume_invoice->invoice_series             = $this->invoice_series;
        $shipment_order_volume_invoice->invoice_key             = $this->invoice_key;
        $shipment_order_volume_invoice->invoice_date             = $this->invoice_date;
        $shipment_order_volume_invoice->invoice_total_value     = $this->invoice_total_value;
        $shipment_order_volume_invoice->invoice_products_value  = $this->invoice_products_value;
        $shipment_order_volume_invoice->invoice_cfop             = $this->invoice_cfop;

        return $shipment_order_volume_invoice;
    }
}
