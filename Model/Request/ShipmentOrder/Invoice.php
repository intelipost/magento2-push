<?php

namespace Intelipost\Push\Model\Request\ShipmentOrder;

use Magento\Framework\Model\AbstractModel;
use Magento\Sales\Api\OrderRepositoryInterface;

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

    /** @var OrderRepositoryInterface $orderRepository */
    protected $orderRepository;

    /**
     * Invoice constructor.
     * @param \Intelipost\Push\Model\Resource\Invoice\CollectionFactory $collectionFactory
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        \Intelipost\Push\Model\Resource\Invoice\CollectionFactory $collectionFactory,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
    }

    public function getInformation($orderId)
    {
        $order = $this->orderRepository->get((int) $orderId);

        $this->invoice_number 			= $order->getEntityId();
        $this->invoice_series 			= $order->getIncrementId();
        $this->invoice_key 		    	= '01234567890123456789012345678901234567891234';
        $this->invoice_date 			= str_replace(' ', 'T', $order->getCreatedAt());
        $this->invoice_total_value      = number_format($order->getGrandTotal(), '2', '.', '');
        $this->invoice_products_value   = number_format($order->getSubtotalInclTax(), '2', '.', '');
        $this->invoice_cfop 			= "1612";

        $shipment_order_volume_invoice_obj = $this->preapreInvoiceObj();
        return $shipment_order_volume_invoice_obj;
    }

    public function preapreInvoiceObj()
    {
        $shipment_order_volume_invoice 							= new \Stdclass();
        $shipment_order_volume_invoice->invoice_number 			= $this->invoice_number;
        $shipment_order_volume_invoice->invoice_series 			= $this->invoice_series;
        $shipment_order_volume_invoice->invoice_date 			= $this->invoice_date;
        $shipment_order_volume_invoice->invoice_total_value     = $this->invoice_total_value;
        $shipment_order_volume_invoice->invoice_products_value  = $this->invoice_products_value;
        $shipment_order_volume_invoice->invoice_cfop 			= $this->invoice_cfop;

        return $shipment_order_volume_invoice;
    }
}