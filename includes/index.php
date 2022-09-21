<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('TIMAGAZINE_Widgets_Addons')) :

    /**
     * Main TIMAGAZINE_Widgets_Addons Class
     *
     */
    final class TIMAGAZINE_Widgets_Addons {

        /** Singleton *************************************************************/
        private static $instance;

        /**
         * Main TIMAGAZINE_Widgets_Addons Instance
         *
         * Insures that only one instance of TIMAGAZINE_Widgets_Addons exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         */
        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof TIMAGAZINE_Widgets_Addons)) {

                self::$instance = new TIMAGAZINE_Widgets_Addons;

                self::$instance->hooks();

            }
            return self::$instance;
        }

        /**
         * Throw error on object clone
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         */
        public function __clone() {
            // Cloning instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'timagazine'), TIMAGAZINE_VERSION);
        }

        /**
         * Disable unserializing of the class
         *
         */
        public function __wakeup() {
            // Unserializing instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'timagazine'), TIMAGAZINE_VERSION);
        }


        /**
         * Load Plugin Text Domain
         *
         * Looks for the plugin translation files in certain directories and loads
         * them to allow the plugin to be localised
         */
        public function load_plugin_textdomain() {


        }

        /**
         * Setup the default hooks and actions
         */
        private function hooks() {

            add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
            add_action('admin_enqueue_scripts', array($this, 'register_frontend_scripts'), 10);
            add_action('admin_enqueue_scripts', array($this, 'register_frontend_styles'), 10);
            add_action('widgets_init', array($this, 'include_widgets'));

        }

        /**
         * Load Frontend Scripts
         *
         */
        public function register_frontend_scripts() {
            global  $pagenow;

            if( $pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'widgets.php' || $pagenow == 'customize.php' ) {
                foreach (glob(TIMAGAZINE_PLUG_DIR . 'assets/admin/js/*.js') as $file) {
                    $filename = substr($file, strrpos($file, '/') + 1);
                    wp_enqueue_script($filename, TIMAGAZINE_PLUG_URL . 'assets/admin/js/' . $filename, array('jquery'), TIMAGAZINE_VERSION, true);
                }
            }
        }
        
        public function register_frontend_styles() {
            global  $pagenow;

            if( $pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'widgets.php' || $pagenow == 'customize.php' ) {
                foreach (glob(TIMAGAZINE_PLUG_DIR . 'assets/admin/css/*.css') as $file) {
                    $filename = substr($file, strrpos($file, '/') + 1);
                    wp_enqueue_style($filename, TIMAGAZINE_PLUG_URL . 'assets/admin/css/' . $filename, '', TIMAGAZINE_VERSION, '');
                }
            }
        }
       
        public function include_widgets() {
            foreach( glob( TIMAGAZINE_PLUG_DIR. 'includes/widgets/*.php' ) as $file ) {
                $widgets = substr($file, strrpos($file, '/') + 1);
                require_once TIMAGAZINE_PLUG_DIR . 'includes/widgets/'.$widgets;
            }

            register_widget( 'Timagazine_Widget_Featured_Posts' );
            register_widget( 'Timagazine_Widget_Category_Posts_A' );
            register_widget( 'Timagazine_Widget_latest_Posts' );
            register_widget( 'Timagazine_Widget_Trending_Posts' );
            register_widget( 'Timagazine_Widget_Social_Links' );
            register_widget( 'Timagazine_Newsletter' );
            register_widget( 'Timagazine_Widget_Author' );
            register_widget( 'Timagazine_Most_Popular' );
                                                                    
        }

    }

endif; // End if class_exists check


/**
 * The main function responsible for returning the one true TIMAGAZINE_Widgets_Addons
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $ae = TIMAGAZINE(); ?>
 */
function TIMAGAZINE() {
    return TIMAGAZINE_Widgets_Addons::instance();
}

// Get TIMAGAZINE Running
TIMAGAZINE();





