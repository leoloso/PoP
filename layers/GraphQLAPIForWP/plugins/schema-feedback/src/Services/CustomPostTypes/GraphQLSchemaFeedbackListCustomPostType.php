<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractCustomPostType;
use GraphQLAPI\SchemaFeedback\Blocks\SchemaFeedbackBlock;
use GraphQLAPI\SchemaFeedback\ModuleResolvers\FunctionalityModuleResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GraphQLSchemaFeedbackListCustomPostType extends AbstractCustomPostType
{
    /**
     * Custom Post Type name
     */
    public const CUSTOM_POST_TYPE = 'graphql-feedback-list';

    /**
     * Custom Post Type name
     */
    protected function getCustomPostType(): string
    {
        return self::CUSTOM_POST_TYPE;
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return FunctionalityModuleResolver::SCHEMA_FEEDBACK;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 7;
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('Schema Feedback List', 'graphql-api-schema-feedback');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Schema Feedback Lists', 'graphql-api-schema-feedback');
    }

    /**
     * Indicate if, whenever this CPT is saved/updated,
     * the timestamp must be regenerated
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     */
    protected function getGutenbergTemplate(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaFeedbackBlock
         */
        $schemaFeedbackBlock = $instanceManager->getInstance(SchemaFeedbackBlock::class);
        return [
            [$schemaFeedbackBlock->getBlockFullName()],
        ];
    }
}
