<?php
/**
 * Displays trending posts
 *
 */

class Timagazine_Widget_Trending_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-trending-posts',
            'description' => __( 'Displays Trending posts', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-trending-posts', __( 'TM: Trending Posts', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_trending_posts';
    }

    public function widget( $args, $instance )
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }
        $enable_meta = ! empty( $instance[ 'enable_meta' ] ) ? 1 : 0;
        $enable_category = ! empty( $instance[ 'enable_category' ] ) ? 1 : 0;
        $enable_post_title = ! empty( $instance[ 'enable_post_title' ] ) ? 1 : 0;
        $categories = isset( $instance['category_dropdown'] ) ? $instance['category_dropdown'] : '';
        $title = (!empty($instance['title'])) ? $instance['title'] : '';

        echo $args['before_widget'];
        ?>
        <div class="widget-trending-posts">
            <div class="trending-carousel">
                <div class="widgets-heading">
                    <?php if ( $title ) {
                        echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
                    } ?>
                </div>
                <div class="trending-slick-carousel slider">
                    <?php
                    if ( is_array( $categories ) || is_object( $categories ) ) {
                        foreach ( $categories as $single_cat ) {
                            $query = new WP_Query(
                                array(
                                    'orderby'        => 'menu_order',
                                    'order'   => 'DESC',
                                    'posts_per_page' => 1,
                                    'no_found_rows' => true,
                                    'post_status' => 'publish',
                                    'ignore_sticky_posts' => true,
                                    'cat'		  		  => $single_cat
                                ));
                            if ($query->have_posts()) :
                                while ( $query->have_posts() ) : $query->the_post(); ?>
                                    <div>
                                        <div class="row">
                                            <?php
                                            if ( has_post_thumbnail() ) : ?>
                                                <div class="featured-thumb mt-30 col-md-6 col-12">
                                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                                                        <?php the_post_thumbnail(); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="social-links-contents mt-30 col-md-6 col-12">
                                                <?php if ( $enable_category != 1 ) : ?>
                                                    <div class="category-bg">
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
                                                    <h6 class="mt-2">
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
                                <?php endwhile;
                                wp_reset_postdata();
                            endif;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['category_dropdown'] = array_map( 'sanitize_text_field', (array) $new_instance['category_dropdown'] );
        $instance[ 'enable_meta' ] = absint( $new_instance[ 'enable_meta' ] );
        $instance[ 'enable_category' ] = absint( $new_instance[ 'enable_category' ] );
        $instance[ 'enable_post_title' ] = absint( $new_instance[ 'enable_post_title' ] );

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $category_dropdown  = isset( $instance['category_dropdown'] ) ? array_map( 'esc_attr', $instance['category_dropdown'] ) : '';
        $enable_category = !empty( $instance['enable_category'] ) ? $instance['enable_category'] : '' ;
        $enable_meta = !empty( $instance['enable_meta'] ) ? $instance['enable_meta'] : '' ;
        $enable_post_title = !empty( $instance['enable_post_title'] ) ? $instance['enable_post_title'] : '' ;
        ?>
        <div class="timagazine-wrap">
            <div class="">
                <h2>
                    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:', 'timagazine' ); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
                </h2>
                <h2>
                    <label for="<?php echo $this->get_field_id('category_dropdown'); ?>"><?php _e('Choose as many categories as you want:', 'timagazine'); ?></label>
                    <select data-placeholder="<?php echo esc_attr__('Select the categories you wish to display posts from.', 'timagazine'); ?>" multiple="multiple" name="<?php echo $this->get_field_name('category_dropdown'); ?>" id="<?php echo $this->get_field_id('category_dropdown'); ?>" class="widefat chosen-dropdown trending-posts-dropdown chosen-sortable trending-posts-Sortable">
                        <?php
                        $cats = get_categories();
                        foreach( $cats as $single_cat ) : ?>
                            <?php printf(
                                '<option value="%s" %s>%s</option>',
                                $single_cat->cat_ID,
                                in_array( $single_cat->cat_ID, (array)$category_dropdown) ? 'selected="selected"' : '',
                                $single_cat->cat_name
                            );?>
                        <?php endforeach; ?>
                    </select>
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
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}