<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\AbstractIntrospectionTypeResolver;

class SchemaTypeResolver extends AbstractIntrospectionTypeResolver
{
    public function getTypeName(): string
    {
        return '__Schema';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Schema type, to implement the introspection fields', 'graphql-server');
    }

    public function getID(object $resultItem): string | int
    {
        $schema = $resultItem;
        return $schema->getID();
    }

    public function getTypeDataLoaderClass(): string
    {
        return SchemaTypeDataLoader::class;
    }
}
