<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\Hooks;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;

abstract class AbstractCustomPostMutationResolverHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
            array($this, 'maybeAddSchemaFieldArgs'),
            10,
            4
        );
        $this->hooksAPI->addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            array($this, 'maybeSetCategories'),
            10,
            2
        );
    }

    public function maybeAddSchemaFieldArgs(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        string $fieldName,
        ?string $entityTypeResolverClass
    ): array {
        // Only for the specific CPT
        if ($entityTypeResolverClass !== $this->getTypeResolverClass()) {
            return $fieldArgs;
        }
        $fieldArgs[] = [
            SchemaDefinition::ARGNAME_NAME => MutationInputProperties::CATEGORIES,
            SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The categories to set', 'custompost-mutations'),
        ];
        return $fieldArgs;
    }

    abstract protected function getTypeResolverClass(): string;

    public function maybeSetCategories(int | string $customPostID, array $form_data): void
    {
        // Only for that specific CPT
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!isset($form_data[MutationInputProperties::CATEGORIES])) {
            return;
        }
        $postCategories = $form_data[MutationInputProperties::CATEGORIES];
        $customPostCategoryTypeMutationAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeMutationAPI->setCategories($customPostID, $postCategories, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;
}