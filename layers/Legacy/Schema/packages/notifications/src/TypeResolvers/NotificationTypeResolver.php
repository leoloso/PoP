<?php

declare(strict_types=1);

namespace PoPSchema\Notifications\TypeResolvers;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Notifications\TypeDataLoaders\NotificationTypeDataLoader;

class NotificationTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Notification';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Notifications for the user', 'notifications');
    }

    public function getID(object $resultItem): string | int | null
    {
        $notification = $resultItem;
        return $notification->histid;
    }

    public function getTypeDataLoaderClass(): string
    {
        return NotificationTypeDataLoader::class;
    }
}
