<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Model;

use Magento\Framework\Model\AbstractModel;

class Invoice extends AbstractModel
{

protected $invoiceFactory;
protected $invoiceResultInterfaceFactory;

public function __construct(
    \Magento\Framework\Model\Context $context,
    \Magento\Framework\Registry $registry,
    \Intelipost\Push\Model\InvoiceFactory $invoiceFactory,
    \Intelipost\Push\Api\Data\InvoiceResultInterfaceFactory $invoiceResultInterfaceFactory
)
{
    $this->invoiceFactory = $invoiceFactory;
    $this->invoiceResultInterfaceFactory = $invoiceResultInterfaceFactory;

    parent::__construct($context, $registry);
}

protected function _construct()
{
    $this->_init('Intelipost\Push\Model\Resource\Invoice');
}

/*
 * {@inheritdoc}
 */
public function saveInvoice($invoice)
{
    foreach ($invoice as $nfe)
    {

        $object = $this->invoiceFactory->create();

        $object->setId ($nfe->getId());
        $object->setOrderNumber ($nfe->getOrderNumber());
        $object->setInvoiceSeries ($nfe->getInvoiceSeries());
        $object->setInvoiceNumber ($nfe->getInvoiceNumber());
        $object->setInvoiceKey ($nfe->getInvoiceKey());
        $object->setInvoiceDate ($nfe->getInvoiceDate());
        $object->setInvoiceTotalValue ($nfe->getInvoiceTotalValue());
        $object->setInvoiceProductsValue ($nfe->getInvoiceProductsValue());
        $object->setInvoiceCfop ($nfe->getInvoiceCfop());

        $object->save();
    }

    return true;
}

public function getList()
{
    $collection = $this->getCollection();
    $data = null;

    foreach ($collection as $child)
    {
        $data [] = $child->getData();
    }

    $result = $this->invoiceResultInterfaceFactory->create();
    $result->setInvoices($data);

    return $result;
}

}

