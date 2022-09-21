<?php
/**
 * Displays social links
 *
 */

class Timagazine_Widget_Social_Links extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine_widget_social_links',
            'description' => __( 'Displays Social links', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-social-links', __( 'TM: Social Links', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_social_links';
    }
    public function widget( $args, $instance )
    {
        if ( !isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        $title = ( !empty($instance['title']) ) ? $instance['title'] : '';
        echo $args['before_widget'];
        if ( $title ) { ?>
            <div class="widgets-heading mb-30">
                <?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
            </div>
        <?php } ?>
        <div class="widget-social-links">
            <?php do_action('timagazine_social'); ?>
        </div>
        <?php echo $args['after_widget'];
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        return $instance;
    }
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        ?>
        <div class="timagazine-wrap">
            <div class="">
                <h2>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:', 'timagazine' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
                    <small><em><?php _e( 'Appearance -> Customize -> Social Media', 'timagazine' ); ?></em></small>
                </h2>
            </div>
        </div>
        <?php
    }
}