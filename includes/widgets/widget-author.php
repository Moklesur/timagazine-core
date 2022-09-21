<?php
/**
 * Displays Author
 *
 */

class Timagazine_Widget_Author extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-author',
            'description' => __( 'Displays Author', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-author', __( 'TM: Author', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_author';
    }
    public function widget( $args, $instance )
    {
        if ( !isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $name = ( !empty($instance['name']) ) ? $instance['name'] : '';
        $position = ( !empty($instance['position']) ) ? $instance['position'] : '';
        $content = ( !empty($instance['content']) ) ? $instance['content'] : '';
        $image_uri = ( !empty($instance['image_uri']) ) ? $instance['image_uri'] : '';
        $author_dropdown = isset( $instance['author_dropdown'] ) ? $instance['author_dropdown'] : '';

        echo $args['before_widget']; ?>

        <div class="widget-author text-center">
            <?php

            $author_link = '';
            if ( is_array( $author_dropdown ) || is_object( $author_dropdown )) {
                foreach ( $author_dropdown as $key => $result ) {
                    $author_link = get_author_posts_url( $result, '' );
                }
            }

            if ( $image_uri ) :
                echo '<img src="' . esc_url( $image_uri ) . '" class="img-responsive mb-30" />';
            endif;
            ?>
            <div class="author-contents">
                <?php if( $name != '' || $position != '' ) { ?>
                    <div class="widgets-heading mb-30">
                        <?php echo $args['before_title'] . esc_html( $name ) . $args['after_title']; ?>
                        <span class="author-role"><?php echo esc_html( $position ); ?></span>
                    </div>
                <?php }
                if( $content != '' ) { ?>
                    <p class="author-text mb-30"><?php echo esc_html( $content ); ?></p>
                <?php }
                if( $author_link != '' ) { ?>
                    <a href="<?php echo esc_url( $author_link ); ?>" class="text-uppercase"><?php esc_html_e( 'My Articles', 'timagazine' ); ?></a>
                <?php } ?>
            </div>
        </div>
        <?php echo $args['after_widget'];
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['name'] = sanitize_text_field( $new_instance['name'] );
        $instance['position'] = sanitize_text_field( $new_instance['position'] );
        $instance['content'] = sanitize_text_field( $new_instance['content'] );
        $instance['image_uri'] = esc_url_raw( $new_instance['image_uri'] );
        $instance['author_dropdown'] = array_map( 'sanitize_text_field', (array) $new_instance['author_dropdown'] );
        return $instance;
    }
    public function form( $instance ) {
        $name     = isset( $instance['name'] ) ? esc_attr( $instance['name'] ) : '';
        $position     = isset( $instance['position'] ) ? esc_attr( $instance['position'] ) : '';
        $content     = isset( $instance['content'] ) ? esc_attr( $instance['content'] ) : '';
        $image_uri     = isset( $instance['image_uri'] ) ? esc_url( $instance['image_uri'] ) : '';
        $author_dropdown  = isset( $instance['author_dropdown'] ) ? array_map( 'esc_attr', $instance['author_dropdown'] ) : '';
        ?>
        <div class="timagazine-wrap">
            <div class="full-width">
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e( 'Name', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo $name; ?>" />
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'position' ); ?>"><?php _e( 'Role', 'timagazine' ); ?></label>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>" type="text" value="<?php echo $position; ?>" />
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content', 'timagazine' ); ?></label>
                        <textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo $content; ?></textarea>
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label class="timagazine-author-link-label" for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Author Link', 'timagazine' ); ?></label>
                        <select data-placeholder="<?php echo esc_attr__( 'Select an author', 'timagazine' ); ?>" multiple="multiple" name="<?php echo $this->get_field_name( 'author_dropdown' ); ?>" id="<?php echo $this->get_field_id( 'author_dropdown' ); ?>" class="widefat  author-dropdown">
                            <?php $args = array(
                                'blog_id'      => $GLOBALS['blog_id'],
                                'role'         => '',
                                'meta_key'     => '',
                                'meta_value'   => '',
                                'meta_compare' => '',
                                'meta_query'   => array(),
                                'include'      => array(),
                                'exclude'      => array(),
                                'orderby'      => 'login',
                                'order'        => 'ASC',
                                'offset'       => '',
                                'search'       => '',
                                'number'       => '',
                                'count_total'  => false,
                                'fields'       => 'all',
                                'who'          => ''
                            );
                            $users = get_users( $args );
                            if( !empty( $users ) ) {
                                foreach( $users as $user ) {
                                    setup_postdata( $user );
                                    printf(
                                        '<option value="%s" %s>%s</option>',
                                        $user->ID,
                                        in_array( $user->ID, (array)$author_dropdown ) ? 'selected="selected"' : '',
                                        $user->display_name
                                    );
                                }
                            }
                            ?>
                        </select>
                    </h2>
                </div>
                <div class="col-3">
                    <h2>
                        <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e( 'Author Image', 'timagazine' ); ?></label>
                        <?php if ( $image_uri ) : ?>
                            <img class="custom_media_image timagazine-custom_media_image" src="<?php echo $image_uri; ?>" /><br />
                        <?php endif; ?>
                        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo $image_uri; ?>">
                        <input type="button" class="button button-primary custom_media_button timagazine_author_image_btn" id="custom_media_button" value="<?php _e( 'Upload Image', 'timagazine' ) ?>" />
                    </h2>
                </div>
            </div>
        </div>
        <?php
    }
}