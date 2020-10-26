<?php

namespace Intelipost\Push\Model\Request\ShipmentOrder;

use Magento\Framework\Model\AbstractModel;

class Volume extends AbstractModel
{
	public $_collectionFactory;
	protected $_helper;
	private $shipment_order_volume_number;
	private $volume_type_code;
	private $weight;
	private $height;
	private $length;
	private $width;
	private $products_quantity;
	private $products_nature;
	private $is_icms_exempt;
	private $tracking_code;
	private $shipment_order_volume_invoice;

	public function __construct(
		\Magento\Sales\Model\ResourceModel\Order\Address\CollectionFactory $collectionFactory,
		\Intelipost\Push\Helper\Data $helper
	)
	{	
		$this->_helper = $helper;
		$this->_collectionFactory = $collectionFactory;
	}

	public function getInformation($volumes, $invoice)
	{		
		$vNumber 		= 1;
		$vol 			= json_decode($volumes);
		$volumeArray 	= array();

		foreach($vol as $volume)
		{
			$this->volume_type_code				 = 'BOX';
			$this->shipment_order_volume_number  = $vNumber;
			$this->weight 						 = $volume->weight;
			$this->height 						 = $volume->height;
			$this->length 						 = $volume->length;
			$this->width 						 = $volume->width;
			$this->products_quantity 			 = $volume->products_quantity;
			$this->products_nature 				 = 'beverages';
			$this->is_icms_exempt 				 = false;
			$this->shipment_order_volume_invoice = $invoice;
			//$this->tracking_code 				 = $tracking_code;

			$shipment_order_volume_obj = $this->prepareVolumeObj($vNumber);
			array_push($volumeArray, $shipment_order_volume_obj);	
			$vNumber++;
		}
		return $volumeArray;
	}

	public function prepareVolumeObj($vNumber)
	{
		$volumeObj 									= new \stdClass();
		$volumeObj->shipment_order_volume_number 	= $vNumber;
		$volumeObj->volume_type_code 				= $this->volume_type_code;
		$volumeObj->weight 							= $this->weight;
		$volumeObj->height 							= $this->height;
		$volumeObj->length 							= $this->length;
		$volumeObj->width 							= $this->width;
		$volumeObj->products_quantity 				= $this->products_quantity;
		$volumeObj->products_nature 				= $this->products_nature;
		$volumeObj->is_icms_exempt 					= $this->is_icms_exempt;
		$volumeObj->shipment_order_volume_invoice   = $this->shipment_order_volume_invoice;

		return $volumeObj;
	}
}
