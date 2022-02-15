<?php

namespace Gleb\IdentityVerification\Setup\Patch\Data;

use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Eav\Model\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * Class CreateProductAttribute data patch.
 *
 * @package Magento\Catalog\Setup\Patch
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CreateCustomerAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private $eavSetupFactory;
    private $eavConfig;
    private $attributeResource;

    public function __construct(ModuleDataSetupInterface $setup, EavSetupFactory $eavSetupFactory, Config $eavConfig, Attribute $attributeResource)
    {
        $this->eavSetupFactory   = $eavSetupFactory;
        $this->eavConfig         = $eavConfig;
        $this->attributeResource = $attributeResource;
        $this->moduleDataSetup = $setup;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'identity_verification',
            [
                'type'         => 'varchar',
                'label'        => 'Identity Verification',
                'input'        => 'image',
                'backend' => 'Gleb\IdentityVerification\Model\Customer\Attribute\Backend\File',
                'frontend' => '',
                'required'     => false,
                'visible'      => true,
                'user_defined' => 0,
                'position'     => 1000,
                'system'       => 0,
            ]
        );

        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'identity_verification');
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);

        // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
        $attribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'customer_account_create', 'customer_account_edit']
        );

        $this->attributeResource->save($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    public function revert()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY, 'identity_verification');

    }
}
