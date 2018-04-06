<?php
/*
 * @package     Intelipost_Quote
 * @copyright   Copyright (c) 2016 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

namespace Intelipost\Push\Model\Config\Source\Order;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    protected $_statusCollectionFactory;

    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $statusCollectionFactory
        ) 
    {       
        $this->_statusCollectionFactory = $statusCollectionFactory;
    }

    public function toOptionArray()
    {
        $pleaseSelect = array(
        array ('value' => '', 'label' => __(' --- Please Select --- '))
        );
        $options = $this->_statusCollectionFactory->create()->toOptionArray();
        return array_merge ($pleaseSelect, $options);
    }
}