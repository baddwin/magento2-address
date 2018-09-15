<?php

namespace Acommerce\Address\Plugin\Magento\Ui\Component\Form;

class AttributeMapper
{
    /**
     * @var \Magento\Framework\App\Request
     */
    protected $requestInterface;

    public function __construct(
        \Magento\Framework\App\RequestInterface $requestInterface
    ) {
        $this->requestInterface = $requestInterface;
    }

    public function afterMap(
        \Magento\Ui\Component\Form\AttributeMapper $subject,
        $result,
        $attribute
    ) {
        if ($this->requestInterface->getFullActionName() == 'checkout_index_index' &&
            in_array($attribute->getAttributeCode(), ['township_id'])
        ) {
            unset($result['options']);
        }
        return $result;
    }
}