<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;

class AccessControlListsSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected AccessControlGraphQLQueryConfigurator $accessControlGraphQLQueryConfigurator,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        // Check it is enabled by module
        if (!$this->moduleRegistry->isModuleEnabled(AccessControlFunctionalityModuleResolver::ACCESS_CONTROL)) {
            return;
        }

        /** @var BlockHelpers */
        $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
        /**
         * @var SchemaConfigAccessControlListBlock
         */
        $block = $this->instanceManager->getInstance(SchemaConfigAccessControlListBlock::class);
        $schemaConfigACLBlockDataItem = $blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
        if (!is_null($schemaConfigACLBlockDataItem)) {
            if ($accessControlLists = $schemaConfigACLBlockDataItem['attrs'][SchemaConfigAccessControlListBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS] ?? null) {
                foreach ($accessControlLists as $accessControlListID) {
                    $this->accessControlGraphQLQueryConfigurator->executeSchemaConfiguration($accessControlListID);
                }
            }
        }
    }
}
