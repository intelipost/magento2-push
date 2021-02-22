<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Api\Data;

class InvoiceInterface
{

/**#@+
 * Constants defined for keys of the data array. Identical to the name of the getter in snake case
 */
    const ID = 'id';
    const INVOICE_NUMBER = 'invoice_number';
    const ORDER_NUMBER = 'order_number';
    const INVOICE_SERIES = 'invoice_series';
    const INVOICE_KEY = 'invoice_key';
    const INVOICE_DATE = 'invoice_date';
    const INVOICE_TOTAL_VALUE = 'invoice_total_value';
    const INVOICE_PRODUCTS_VALUE = 'invoice_products_value';
    const INVOICE_CFOP = 'invoice_cfop';


/**#@-*/

    protected $id;
    protected $invoice_number;
    protected $order_number;
    protected $invoice_series;
    protected $invoice_key;
    protected $invoice_date;
    protected $invoice_total_value;
    protected $invoice_products_value;
    protected $invoice_cfop;

/**
 * Get item id
 *
 * @api
 * @return int|null
 */
    public function getId()
    {
        return $this->id;
    }

/**
 * Set item id
 *
 * @api
 * @param int $id
 * @return $this
 */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

/**
 * Get invoice number
 *
 * @api
 * @return int|null
 */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

/**
 * Set invoice number
 *
 * @api
 * @param int $id
 * @return $this
 */
    public function setInvoiceNumber($invoice_number)
    {
        $this->invoice_number = $invoice_number;

        return $this;
    }

/**
 * Get order number
 *
 * @api
 * @return string|null
 */
    public function getOrderNumber()
    {
        return $this->order_number;
    }

/**
 * Set order number
 *
 * @api
 * @param string $order_number
 * @return $this
 */
    public function setOrderNumber($order_number)
    {
        $this->order_number = $order_number;

        return $this;
    }

/**
 * Get invoice series
 *
 * @api
 * @return string|null
 */
    public function getInvoiceSeries()
    {
        return $this->invoice_series;
    }

/**
 * Set invoice series
 *
 * @api
 * @param string $invoice_series
 * @return $this
 */
    public function setInvoiceSeries($invoice_series)
    {
        $this->invoice_series = $invoice_series;

        return $this;
    }

/**
 * Get invoice key
 *
 * @api
 * @return string|null
 */
    public function getInvoiceKey()
    {
        return $this->invoice_key;
    }

/**
 * Set invoice key
 *
 * @api
 * @param string $invoice_key
 * @return $this
 */
    public function setInvoiceKey($invoice_key)
    {
        $this->invoice_key = $invoice_key;

        return $this;
    }

/**
 * Get invoice date
 *
 * @api
 * @return string|null
 */
    public function getInvoiceDate()
    {
        return $this->invoice_date;
    }

/**
 * Set operation time
 *
 * @api
 * @param string $invoice_date
 * @return $this
 */
    public function setInvoiceDate($invoice_date)
    {
        $this->invoice_date = $invoice_date;

        return $this;
    }

/**
 * Get invoice total value
 *
 * @api
 * @return string|null
 */
    public function getInvoiceTotalValue()
    {
        return $this->invoice_total_value;
    }

/**
 * Set invoice total value
 *
 * @api
 * @param string $invoice_total_value
 * @return $this
 */
    public function setInvoiceTotalValue($invoice_total_value)
    {
        $this->invoice_total_value = $invoice_total_value;

        return $this;
    }

/**
 * Get invoice products value
 *
 * @api
 * @return string|null
 */
    public function getInvoiceProductsValue()
    {
        return $this->invoice_products_value;
    }

/**
 * Set invoice products value
 *
 * @api
 * @param string $invoice_products_value
 * @return $this
 */
    public function setInvoiceProductsValue($invoice_products_value)
    {
        $this->invoice_products_value = $invoice_products_value;

        return $this;
    }

/**
 * Get invoice cfop
 *
 * @api
 * @return string|null
 */
    public function getInvoiceCfop()
    {
        return $this->invoice_cfop;
    }

/**
 * Set invoice cfop
 *
 * @api
 * @param string $invoice_cfop
 * @return $this
 */
    public function setInvoiceCfop($invoice_cfop)
    {
        $this->invoice_cfop = $invoice_cfop;

        return $this;
    }
}
