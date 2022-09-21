<?php
/**
 * Displays latest posts
 *
 */

class Timagazine_Widget_latest_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-latest-posts',
            'description' => __( 'Displays Latest posts', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-latest-posts', __( 'TM: Latest Posts', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_latest_posts';
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        $enable_meta = ! empty( $instance[ 'enable_meta' ] ) ? 1 : 0;
        $enable_category = ! empty( $instance[ 'enable_category' ] ) ? 1 : 0;
        $enable_post_title = ! empty( $instance[ 'enable_post_title' ] ) ? 1 : 0;
        $enable_nav = ! empty( $instance[ 'enable_nav' ] ) ? 'false' : 'true';
        $enable_autoplay = ! empty( $instance[ 'enable_autoplay' ] ) ? 'false' : 'true';
        $enable_loop = ! empty( $instance[ 'enable_loop' ] ) ? 'false' : 'true';
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 4;
        $items = ( ! empty( $instance['items'] ) ) ? absint( $instance['items'] ) : 1;
        $margin = ( ! empty( $instance['margin'] ) ) ? absint( $instance['margin'] ) : 30;

        if ( ! $number )
            $number = 4;

        $query = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ) );
        if ($query->have_posts()) :
            echo $args['before_widget'];
            if ( $title ) { ?>
                <div class="widgets-heading overflow-h">
                    <h3 class="widget-title float-left"><?php echo esc_html( $title ); ?></h3>
                </div>
            <?php } ?>
            <div class="widget-latest-posts latest-posts-carousel owl-carousel">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $cat_position = '';
                    if ( has_post_thumbnail() ){
                        $cat_position = ' cat-position';
                    }
                    ?>
                    <div class="d-flex align-items-center mt-30 hover-images">
                        <div class="featured-mag-wrapper position-r">
                            <?php
                            if ( has_post_thumbnail() ) : ?>
                                <div class="featured-thumb mb-20 overflow-h">
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                                        <?php the_post_thumbnail(); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="latest-posts-contents">
                                <?php if ( $enable_category != 1 ) : ?>
                                    <div class="category-bg<?php echo $cat_position; ?>">
                                        <?php
                                        $categories_list = get_the_category();
                                        foreach( $categories_list as $category ){
                                            $cat_bg_color = get_theme_mod( 'category_color_' . $category->term_id );

                                            if ( $cat_bg_color != '' ){
                                                echo '<a class="category-unique-bg" href="' . esc_url( get_category_link( $category->term_id ) ) . '" style="background:' . esc_attr( $cat_bg_color ) . '" rel="category tag">'. esc_html( $category->cat_name ) .'</a>';
                                            }else{
                                                echo '<a class="category-unique-empty" href="' . esc_url( get_category_link( $category->term_id ) ) . '" rel="category tag">'. esc_html( $category->cat_name ) .'</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif;
                                if ( $enable_post_title != 1 ) : ?>
                                    <h6>
                                        <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                    </h6>
                                <?php endif;
                                if ( 'post' === get_post_type() && $enable_meta != 1 ) : ?>
                                    <div class="entry-meta">
                                        <?php timagazine_posted_on(); ?>
                                    </div><!-- .entry-meta -->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <?php echo $args['after_widget'];
            wp_reset_postdata();
            endif;
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = absint ($new_instance['number'] );
        $instance['items'] = absint( $new_instance['items'] );
        $instance['margin'] = absint( $new_instance['margin'] );
        $instance[ 'enable_meta' ] = absint( $new_instance[ 'enable_meta' ] );
        $instance[ 'enable_category' ] = absint( $new_instance[ 'enable_category' ] );
        $instance[ 'enable_nav' ] = absint( $new_instance[ 'enable_nav' ] );
        $instance[ 'enable_autoplay' ] = absint( $new_instance[ 'enable_autoplay' ] );
        $instance[ 'enable_loop' ] = absint( $new_instance[ 'enable_loop' ] );

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $items    = isset( $instance['items'] ) ? absint( $instance['items'] ) : 1;
        $margin    = isset( $instance['margin'] ) ? absint( $instance['margin'] ) : 30;
        $enable_meta = !empty( $instance['enable_meta'] ) ? $instance['enable_meta'] : '' ;
        $enable_category = !empty( $instance['enable_category'] ) ? $instance['enable_category'] : '' ;
        $enable_post_title = !empty( $instance['enable_post_title'] ) ? $instance['enable_post_title'] : '' ;
        $enable_nav = !empty( $instance['enable_nav'] ) ? $instance['enable_nav'] : '' ;
        $enable_autoplay = !empty( $instance['enable_autoplay'] ) ? $instance['enable_autoplay'] : '' ;
        $enable_loop = !empty( $instance['enable_loop'] ) ? $instance['enable_loop'] : '' ;
        ?>

        <div class="timagazine-wrap">
            <div class="">
                <h2>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:', 'timagazine' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
                </h2>
            </div>
            <div class="accordion-fix panel">
                <h3 class="title panel-title"><?php _e( 'Post Options', 'timagazine' ); ?></h3>
                <div class="accordion-contents content">
                    <div class="accordion-wrap">
                        <div class="col-3">
                            <h5>
                                <label>
                                    <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_post_title' ); ?>" id="<?php echo $this->get_field_id( 'enable_post_title' ); ?>" value="1" <?php checked( $enable_post_title, '1' ); ?>>
                                    <span><?php _e( 'Hide/Show Title', 'timagazine' ); ?></span>
                                </label>
                            </h5>
                        </div>
                        <div class="col-3">
                            <h5>
                                <label>
                                    <input type="checkbox" name="<?php echo $this->get_field_name( 'enable_category' ); ?>" id="<?php echo $this->get_field_id( 'enable_category' ); ?>" value="1" <?php checked( $enable_category, '1' ); ?>> <span><?php _e( 'Hide/Show Category', 'timagazine' ); ?></span>
                                </label>
                            </h5>
                        </div>
                        <div class="col-3">
                            <h5>
                                <label>
                                    <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_meta' ); ?>" id="<?php echo $this->get_field_id( 'enable_meta' ); ?>" value="1" <?php checked( $enable_meta, '1' ); ?>>
                                    <span><?php _e( 'Hide/Show Meta', 'timagazine' ); ?></span>
                                </label>
                            </h5>
                        </div>
                        <div class="col-3">
                            <h5>
                                <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'timagazine' ); ?></label>
                                <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
                            </h5>
                        </div>
                    </div>
                </div>
                <h3 class="title panel-title"><?php _e( 'Slider Settings ( Pro )', 'timagazine' ); ?></h3>
                <div class="accordion-contents content">
                    <div class="accordion-wrap">
                        <div class="col-3">
                            <h5>
                                <label for="<?php echo $this->get_field_id( 'items' ); ?>"><?php _e( 'Slider Column : ', 'timagazine' ); ?> </label>
                                <input class="tiny-text" id="<?php echo $this->get_field_id( 'items' ); ?>" name="<?php echo $this->get_field_name( 'items' ); ?>" type="number" step="1" min="1" value="<?php echo $items; ?>" size="1" />
                                <br/><small>
                                    <em><?php _e( 'The number of items you want to see on the screen.', 'timagazine' ); ?></em>
                                </small>
                            </h5>
                        </div>
                        <div class="col-3">
                            <h5>
                                <label for="<?php echo $this->get_field_id( 'Margin' ); ?>"><?php _e( 'Margin : ', 'timagazine' ); ?> </label>
                                <input class="tiny-text" id="<?php echo $this->get_field_id( 'margin' ); ?>" name="<?php echo $this->get_field_name( 'margin' ); ?>" type="number" step="1" min="0" value="<?php echo $margin; ?>" />
                                <br/><small>
                                    <em><?php _e( 'margin-right(px) on item.', 'timagazine' ); ?></em>
                                </small>
                            </h5>
                        </div>
                        <div class="full-width">
                            <div class="col-3">
                                <h5>
                                    <label>
                                        <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_nav' ); ?>" id="<?php echo $this->get_field_id( 'enable_nav' ); ?>" value="1" <?php checked( $enable_nav, '1' ); ?>>
                                        <span><?php _e( 'Hide/Show next/prev buttons', 'timagazine' ); ?></span>
                                    </label>
                                </h5>
                            </div>
                            <div class="col-3">
                                <h5>
                                    <label>
                                        <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_autoplay' ); ?>" id="<?php echo $this->get_field_id( 'enable_autoplay' ); ?>" value="1" <?php checked( $enable_autoplay, '1' ); ?>>
                                        <span><?php _e( 'Hide/Show Auto play', 'timagazine' ); ?></span>
                                    </label>
                                </h5>
                            </div>
                            <div class="col-3">
                                <h5>
                                    <label>
                                        <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_loop' ); ?>" id="<?php echo $this->get_field_id( 'enable_loop' ); ?>" value="1" <?php checked( $enable_loop, '1' ); ?>>
                                        <span><?php _e( 'Hide/Show Infinity loop.', 'timagazine' ); ?></span>
                                    </label>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}