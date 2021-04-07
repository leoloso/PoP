<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetCategoriesOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    public function execute(array $form_data): mixed
    {
        $customPostID = $form_data[MutationInputProperties::CUSTOMPOST_ID];
        $postCategories = $form_data[MutationInputProperties::CATEGORIES];
        $append = $form_data[MutationInputProperties::APPEND];
        $customPostCategoryTypeAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeAPI->setCategories($customPostID, $postCategories, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];

        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }

        $translationAPI = TranslationAPIFacade::getInstance();
        if (!$form_data[MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = sprintf(
                $translationAPI->__('The %s ID is missing.', 'custompost-category-mutations'),
                $this->getEntityName()
            );
        }
        return $errors;
    }

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-category-mutations');
    }
}