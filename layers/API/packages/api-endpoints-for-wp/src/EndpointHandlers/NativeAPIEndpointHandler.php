<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP\EndpointHandlers;

use PoP\APIEndpointsForWP\EndpointHandlers\AbstractEndpointHandler;
use PoP\APIEndpointsForWP\ComponentConfiguration;
use PoP\API\Response\Schemes as APISchemes;

class NativeAPIEndpointHandler extends AbstractEndpointHandler
{
    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
    {
        if ($this->isNativeAPIEnabled()) {
            parent::initialize();
        }
    }

    /**
     * Provide the endpoint
     *
     * @var string
     */
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getNativeAPIEndpoint();
    }

    /**
     * Check if the PoP API has been enabled
     *
     * @return boolean
     */
    protected function isNativeAPIEnabled(): bool
    {
        return
            \PoP\API\Component::isEnabled()
            && !ComponentConfiguration::isNativeAPIEndpointDisabled();
    }

    /**
     * Indicate this is an API request
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        // Set the params on the request, to emulate that they were added by the user
        $_REQUEST[\PoP\ComponentModel\Constants\Params::SCHEME] = APISchemes::API;
        // Enable hooks
        \do_action('EndpointHandler:setDoingAPI');
    }
}
