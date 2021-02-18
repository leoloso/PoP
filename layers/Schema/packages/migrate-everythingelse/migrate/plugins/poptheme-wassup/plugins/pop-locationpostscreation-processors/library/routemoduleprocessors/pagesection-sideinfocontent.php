<?php

use PoP\Routing\RouteNatures;

class PoPTheme_Wassup_LocationPostsCreation_Module_SideInfoContentPageSectionRouteModuleProcessor extends PoP_Module_SideInfoContentPageSectionRouteModuleProcessorBase
{
    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        $ret = array();

        $modules = array(
            POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => [PoPSPEM_Module_Processor_SidebarMultiples::class, PoPSPEM_Module_Processor_SidebarMultiples::MODULE_MULTIPLE_SECTION_MYLOCATIONPOSTS_SIDEBAR],
        );
        foreach ($modules as $route => $module) {
            $ret[RouteNatures::STANDARD][$route][] = ['module' => $module];
        }

        return $ret;
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new PoPTheme_Wassup_LocationPostsCreation_Module_SideInfoContentPageSectionRouteModuleProcessor()
	);
}, 200);
