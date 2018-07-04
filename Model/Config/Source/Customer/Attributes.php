<?php

namespace Intelipost\Push\Model\Config\Source\Customer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory as AttributeCollectionFactory;

class Attributes implements OptionSourceInterface
{
    const CUSTOMER_ATTRIBUTE_SET = 1;

    /** @var AttributeCollectionFactory $attributeCollectionFactory */
    protected $attributeCollectionFactory;

    /**
     * Attributes constructor.
     * @param AttributeCollectionFactory $attributeCollectionFactory
     */
    public function __construct(
        AttributeCollectionFactory $attributeCollectionFactory
    )
    {
        $this->setAttributeCollectionFactory($attributeCollectionFactory);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $attributeCollection = $this->getAttributeCollectionFactory()->create();
        $attributeCollection->setAttributeSetFilter(self::CUSTOMER_ATTRIBUTE_SET);
        $attributeCollection->getSelect()->order('frontend_label');

        $options = [];
        foreach ($attributeCollection as $attribute)  {
            $result [] = ['value' => $attribute->getAttributeCode(), 'label' => $attribute->getFrontendLabel()];
        }

        return $options;
    }

    /**
     * @return AttributeCollectionFactory
     */
    protected function getAttributeCollectionFactory()
    {
        return $this->attributeCollectionFactory;
    }

    /**
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @return Attributes
     */
    protected function setAttributeCollectionFactory($attributeCollectionFactory)
    {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        return $this;
    }
}