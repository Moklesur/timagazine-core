<?php

/**
 * Hooks for site origin.
 *
 * This file contains hook functions attached to core hooks of site origin bundle.
 *
 * @package timagazine
 */

if (!function_exists('timagazine_add_tab_in_builer_widgets_panel')) :

    /**
     * Add tab in builder widgets section.
     *
     * @param array $tabs Tabs.
     * @return array Modified tabs.
     * @since 1.0.0
     *
     */
    function timagazine_add_tab_in_builer_widgets_panel($tabs)
    {
        $tabs['timagazine'] = array(
            'title' => __('Timagazine Widgets', 'timagazine'),
            'filter' => array(
                'groups' => array('timagazine'),
            ),
        );
        return $tabs;
    }
endif;
add_filter('siteorigin_panels_widget_dialog_tabs', 'timagazine_add_tab_in_builer_widgets_panel');

if (!function_exists('timagazine_group_theme_widgets_in_builder')) :

    /**
     * Grouping theme widgets in builder.
     *
     * @param array $widgets Widgets array.
     * @return array Modified widgets array.
     * @since 1.0.0
     *
     */
    function timagazine_group_theme_widgets_in_builder($widgets)
    {
        if (isset($GLOBALS['wp_widget_factory']) && !empty($GLOBALS['wp_widget_factory']->widgets)) {
            $all_widgets = array_keys($GLOBALS['wp_widget_factory']->widgets);
            foreach ($all_widgets as $widget) {
                if (false !== strpos($widget, 'Timagazine_')) {
                    $widgets[$widget]['groups'] = array('timagazine');
                    $widgets[$widget]['icon'] = 'dashicons dashicons-align-none';
                }
            }
        }
        return $widgets;
    }
endif;
add_filter('siteorigin_panels_widgets', 'timagazine_group_theme_widgets_in_builder');
if (!function_exists('timagazine_customize_so_widgets_status')) :

    /**
     * Customize to make widgets active.
     *
     * @param array $active Array of widgets.
     * @return array Modified array.
     * @since 1.0.0
     *
     */
    function timagazine_customize_so_widgets_status($active)
    {
        $active['so-google-map-widget'] = true;
        $active['google-map'] = true;

        return $active;
    }
endif;

add_filter('siteorigin_widgets_active_widgets', 'timagazine_customize_so_widgets_status');

/**
 * Theme Widgets.
 */
$theme_widgets = array(
    'widget-category-posts-a',
    'widget-featured-posts',
    'widget-lastest-posts',
    'widget-trending-posts',
    'widget-social-links',
    'widget-newsletter',
    'widget-most-popular',
    'widget-author'
);

$template_dir = TIMAGAZINE_PLUG_DIR;

foreach ($theme_widgets as $widget) {
    require_once $template_dir . 'includes/widgets/' . $widget . '.php';
}
// add admin scripts
add_action('admin_enqueue_scripts', 'timagazine_media_admin');
function timagazine_media_admin()
{
    global $pagenow;
    if ($pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'widgets.php' || $pagenow == 'customize.php') {
        wp_enqueue_media();
    }
}