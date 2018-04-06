<?php
/*
 * @package     Intelipost_Push
 * @copyright   Copyright (c) Intelipost
 * @author      Alex Restani <alex.restani@intelipost.com.br>
 */

namespace Intelipost\Push\Api;

interface InvoiceInterface
{

/**
 * Retrieve items list
 *
 * @api
 * @return \Intelipost\Push\Api\Data\InvoiceResultInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getList();

/**
 * Retrive item information
 *
 * @api
 * @param int $id
 * @return \Intelipost\Push\Api\Data\ItemsInterface
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function getInfo($id);

/**
 * Save item information
 *
 * @api
 * @param \Intelipost\Push\Api\Data\InvoiceInterface[] $invoice
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function saveInvoice($invoice);

/**
 * Delete item
 *
 * @api
 * @param  string $id
 * @return bool
 * @throws \Magento\Framework\Exception\LocalizedException
 */
public function deleteItem($id);

}