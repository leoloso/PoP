<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Highlights\TypeResolvers\HighlightTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostFieldInterfaceResolver::class,
        );
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'highlights',
            'hasHighlights',
            'highlightsCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'highlights' => SchemaDefinition::TYPE_ID,
            'hasHighlights' => SchemaDefinition::TYPE_BOOL,
            'highlightsCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'hasHighlights',
            'highlightsCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'highlights'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'highlights' => $this->translationAPI->__('', ''),
            'hasHighlights' => $this->translationAPI->__('', ''),
            'highlightsCount' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPost = $resultItem;
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'highlights':
                $query = array(
                    // 'fields' => 'ids',
                    'limit' => -1, // Bring all the results
                    'meta-query' => [
                        [
                            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_HIGHLIGHTEDPOST),
                            'value' => $typeResolver->getID($customPost),
                        ],
                    ],
                    'custompost-types' => [POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT],
                    'orderby' => $this->nameResolver->getName('popcms:dbcolumn:orderby:customposts:date'),
                    'order' => 'ASC',
                );

                return $customPostTypeAPI->getCustomPosts($query, ['return-type' => ReturnTypes::IDS]);

            case 'hasHighlights':
                $referencedbyCount = $typeResolver->resolveValue($resultItem, 'highlightsCount', $variables, $expressions, $options);
                if (GeneralUtils::isError($referencedbyCount)) {
                    return $referencedbyCount;
                }
                return $referencedbyCount > 0;

            case 'highlightsCount':
                $referencedby = $typeResolver->resolveValue($resultItem, 'highlights', $variables, $expressions, $options);
                if (GeneralUtils::isError($referencedby)) {
                    return $referencedby;
                }
                return count($referencedby);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'highlights':
                return HighlightTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
