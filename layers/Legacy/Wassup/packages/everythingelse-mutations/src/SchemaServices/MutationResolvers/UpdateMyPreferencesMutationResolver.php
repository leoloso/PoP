<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class UpdateMyPreferencesMutationResolver extends AbstractMutationResolver
{
    public function execute(array $form_data): mixed
    {
        $user_id = $form_data['user_id'];
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $form_data['userPreferences']);
        return $user_id;
    }
}
