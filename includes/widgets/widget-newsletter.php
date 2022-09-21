<?php
/**
 * Displays Newsletter
 *
 */

class Timagazine_Newsletter extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-newsletter',
            'description' => __( 'Displays Newsletter', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-newsletter', __( 'TM: Newsletter', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_newsletter';
    }
    public function widget( $args, $instance )
    {
        if ( !isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        $url = ( !empty($instance['url']) ) ? $instance['url'] : '';
        $title = ( !empty($instance['title']) ) ? $instance['title'] : '';
        $paragraph = ( !empty($instance['paragraph']) ) ? $instance['paragraph'] : '';
        $alignment = ( !empty($instance['alignment']) ) ? $instance['alignment'] : 'text-center';
        $icon = ( !empty($instance['icon']) ) ? $instance['icon'] : 'fa fa-envelope-o fa-2x fa-2x';

        echo $args['before_widget']; ?>
        <div class="widget-newsletter <?php echo $alignment; ?>">
            <form class="" action="<?php echo esc_url( $url ); ?>" method="post" target="_blank">
                <?php if ( $icon ) { ?>
                    <p class="mb-3">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </p>
                <?php }
                if ( $title ) { ?>
                    <div class="widgets-heading mb-3">
                        <?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
                    </div>
                <?php }
                if ( $paragraph ) { ?>
                    <p class="mb-2">
                        <?php echo esc_html( $paragraph ); ?>
                    </p>
                <?php } ?>

                <input type="email" class="form-control mt-4 <?php echo esc_attr( $alignment ); ?>" name="EMAIL" id="newsletter-email" placeholder="info@youremail.com" required="">
                <button type="submit" class="btn btn-block mt-2"><?php esc_html_e( 'Submit', 'timagazine' ); ?></button>
            </form>
        </div>
        <?php echo $args['after_widget'];
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['url'] = esc_url_raw( $new_instance['url'] );
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['paragraph'] = sanitize_text_field( $new_instance['paragraph'] );
        $instance['alignment'] = sanitize_text_field( $new_instance['alignment'] );
        $instance['icon'] = sanitize_text_field( $new_instance['icon'] );
        return $instance;
    }
    public function form( $instance ) {
        $url     = isset( $instance['url'] ) ? esc_attr( $instance['url'] ) : '';
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $paragraph     = isset( $instance['paragraph'] ) ? esc_attr( $instance['paragraph'] ) : '';
        $alignment     = isset( $instance['alignment'] ) ? esc_attr( $instance['alignment'] ) : 'text-center';
        $icon     = isset( $instance['icon'] ) ? esc_attr( $instance['icon'] ) : 'fa fa-envelope-o fa-2x';
        ?>
        <div class="timagazine-wrap">
            <div class="">
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Text Alignment', 'timagazine' ); ?></label>
                        <select class='widefat' id="<?php echo $this->get_field_id('alignment'); ?>" name="<?php echo $this->get_field_name('alignment'); ?>" type="text">
                            <option value='text-left'<?php echo ( $alignment == 'text-left' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Text Alignment Left', 'timagazine' ); ?>
                            </option>
                            <option value='text-center'<?php echo ( $alignment == 'text-center') ? 'selected' : ''; ?>>
                                <?php _e( 'Text Alignment Center', 'timagazine' ); ?>
                            </option>
                            <option value='text-right'<?php echo ( $alignment == 'text-right' ) ? 'selected' : ''; ?>>
                                <?php _e( 'Text Alignment Right', 'timagazine' ); ?>
                            </option>
                        </select>
                    </h2>
                </div>

                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'icon', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>" type="text" value="<?php echo $icon; ?>" />
                        <small><em><?php _e( 'Take an icon from here http://fontawesome.io/icons/', 'timagazine' ); ?></em></small>
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'paragraph' ); ?>"><?php _e( 'Content', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'paragraph' ); ?>" name="<?php echo $this->get_field_name( 'paragraph' ); ?>" type="text" value="<?php echo $paragraph; ?>" />
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Action URL', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo $url; ?>" /><br/>
                        <small><em><?php _e( 'https://mailchimp.com/', 'timagazine' ); ?></em></small>
                    </h2>
                </div>
            </div>
        </div>
        <?php
    }
}