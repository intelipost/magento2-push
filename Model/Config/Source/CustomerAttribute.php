<?php
/**
 * Copyright © Intelipost. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Intelipost\Push\Model\Config\Source;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class CustomerAttribute implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory
            ->create()
            ->setEntityTypeFilter(CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER)
            ->addOrder('frontend_label', 'ASC');

        $result = [
            [
                'value' => '',
                'label' => __('Select CPF attribute')
            ]
        ];

        foreach ($collection as $item) {
            $result[] = [
                'value' => $item->getAttributeCode(),
                'label' => $item->getFrontendLabel()
            ];
        }

        return $result;
    }
}
