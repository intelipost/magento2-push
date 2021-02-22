<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Api\Data;

class InvoiceResultInterface
{

    protected $invoice;

/**
 * Get invoice list.
 *
 * @api
 * @return \Intelipost\Push\Api\Data\InvoiceResultInterface[]
 */
    public function getInvoice()
    {
        return $this->invoice;
    }

/**
 * Set items list.
 *
 * @api
 * @param \Intelipost\Push\Api\Data\InvoiceResultInterface[] $invoice
 * @return $this
 */
    public function setInvoices(array $invoice = null)
    {
        $this->invoice = $invoice;

        return $this;
    }
}
