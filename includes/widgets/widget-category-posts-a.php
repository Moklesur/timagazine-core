<?php
/**
 * Displays posts from a single category
 *
 */

class Timagazine_Widget_Category_Posts_A extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-category-posts-a',
            'description' => __( 'Displays posts from a single category', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget-category-posts-a', __( 'TM: Single Category posts', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'timagazine_widget_category_posts_a';
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $enable_meta = ! empty( $instance[ 'enable_meta' ] ) ? 1 : 0;
        $enable_category = ! empty( $instance[ 'enable_category' ] ) ? 1 : 0;
        $enable_post_title = ! empty( $instance[ 'enable_post_title' ] ) ? 1 : 0;
        $enable_view_all_cat = ! empty( $instance[ 'enable_view_all_cat' ] ) ? 1 : 0;
        $category_layout_style = isset( $instance['category_layout_style'] ) ? $instance['category_layout_style'] : '';
        $category = isset( $instance['category_dropdown'] ) ? $instance['category_dropdown'] : '';
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;

        $query = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'cat'		  		  => $category
        ) );

        if ( $query->have_posts() ) :
            echo $args['before_widget']; ?>
            <div class="widgets-heading overflow-h">
                <h3 class="widget-title float-left"><?php echo esc_html( get_cat_name( $category ) ); ?></h3>
                <?php if ( $enable_view_all_cat != 1 ) : ?>
                <a class="float-right mt-2 text-uppercase widget-cat-link" href="<?php echo esc_url( get_category_link( $category ) ); ?>"><?php esc_html_e( 'View All', 'timagazine' );?></a>
                <?php endif; ?>
            </div>
            <div class="widget-category-posts-a row">
                <?php $counter = 1;
                while ( $query->have_posts() ) : $query->the_post();
                 if( $counter == 1 ) {
                       $col = 'col-xl-12 col-lg-12 col-md-12 col-12 overflow-h';
                       $col2 = 'col-xl-8 col-lg-12 col-md-12 col-12 mt-30';
                       $col3 = 'col-xl-4 col-lg-12 col-md-12 col-12 mt-30';
                       $excerpt = '<p class="mt-20">'.get_the_excerpt().'</p>';
                       $title_tag = 'h5';
                       $d_bock = 'd-block';
                    if( $category_layout_style == 'category_layout_2' ){
                        $col = 'col-xl-12 col-lg-12 col-md-12 col-12 overflow-h';
                        $col2 = 'col-xl-12 col-lg-12 col-md-12 col-12 mt-30';
                        $col3 = 'col-xl-12 col-lg-12 col-md-12 col-12 mt-30';
                        $d_bock = 'd-block';
                    } elseif ( $category_layout_style == 'category_layout_3' ){
                        $col = 'col-xl-4 col-lg-12 col-md-12 col-12 overflow-h';
                        $col2 = 'col-xl-12 col-lg-12 col-md-12 col-12 mt-30';
                        $col3 = 'col-xl-12 col-lg-12 col-md-12 col-12 mt-30';
                        $d_bock = 'd-block';
                    }
                    } else {
                        $excerpt = '<p class="mt-20">'.get_the_excerpt().'</p>';
                        if( $category_layout_style == 'category_layout_1' ){
                           $col = 'col-xl-6 col-lg-12 col-md-12 col-12 overflow-h';
                           $col2 = 'col-xl-5 col-lg-12 col-md-12 col-12 mt-30';
                           $col3 = 'col-xl-7 col-lg-12 col-md-12 col-12 mt-30';
                           $title_tag = 'h6';
                           $d_bock = 'd-none';
                            $excerpt = '';
                        }
                        elseif( $category_layout_style == 'category_layout_2' ){
                           $col = 'col-xl-12 col-lg-12 col-md-12 col-12 overflow-h';
                           $col2 = 'col-xl-6 col-lg-12 col-md-12 col-12 mt-30';
                           $col3 = 'col-xl-6 col-lg-12 col-md-12 col-12 mt-30';
                           $d_bock = '';
                        }
                    }
                    ?>
                    <div class="<?php echo $col; ?> hover-images">
                        <div class="featured-mag-wrapper position-r row align-items-center">
                            <?php
                            if ( has_post_thumbnail() ) : ?>
                                <div class="featured-thumb <?php echo $col2; ?>">
                                    <div class="overflow-h">
                                        <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                                            <?php the_post_thumbnail(); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="category-posts-a-contents <?php echo $col3; ?>">
                            <?php if ( $enable_category != 1 ) : ?>
                                <div class="category-bg <?php echo $d_bock; ?>">
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
                                <<?php echo $title_tag;?>>
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                </<?php echo $title_tag;?>>
                                <?php  endif;
                                echo wp_kses_post( $excerpt ); ?>
                                <?php if ( 'post' === get_post_type() && $enable_meta != 1 ) : ?>
                                    <div class="entry-meta">
                                        <?php timagazine_posted_on(); ?>
                                    </div><!-- .entry-meta -->
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php  $counter++;
                 endwhile; ?>
            </div>
            <?php echo $args['after_widget'];
            wp_reset_postdata();
        endif;
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['category_layout_style'] = sanitize_text_field( $new_instance['category_layout_style'] );
        $instance['category_dropdown'] = sanitize_text_field( $new_instance['category_dropdown'] );
        $instance['number'] = absint( $new_instance['number'] );
        $instance[ 'enable_meta' ] = absint( $new_instance[ 'enable_meta' ] );
        $instance[ 'enable_category' ] = absint( $new_instance[ 'enable_category' ] );
        $instance[ 'enable_post_title' ] = absint( $new_instance[ 'enable_post_title' ] );
        $instance[ 'enable_view_all_cat' ] = absint( $new_instance[ 'enable_view_all_cat' ] );
        return $instance;
    }
    public function form( $instance ) {
        $category_layout_style = isset( $instance['category_layout_style'] ) ? esc_attr( $instance['category_layout_style'] ) : 'category_layout_1';
        $category_dropdown  = isset( $instance['category_dropdown'] ) ? esc_attr( $instance['category_dropdown'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $enable_meta = !empty( $instance['enable_meta'] ) ? $instance['enable_meta'] : '' ;
        $enable_category = !empty( $instance['enable_category'] ) ? $instance['enable_category'] : '' ;
        $enable_post_title = !empty( $instance['enable_post_title'] ) ? $instance['enable_post_title'] : '' ;
        $enable_view_all_cat = !empty( $instance['enable_view_all_cat'] ) ? $instance['enable_view_all_cat'] : '' ;
        ?>
        <div class="timagazine-wrap">
            <div class="">
                <h2>
                    <label for="<?php echo $this->get_field_id( 'category_dropdown' ); ?>"><?php _e( 'Choose Your Category', 'timagazine' ); ?></label>
                    <?php
                        $args = array(
                            'name'               => $this->get_field_name('category_dropdown'),
                            'id'                 => $this->get_field_id('category_dropdown'),
                            'class'              => 'chosen-dropdown-1 category-posts-dropdown-a',
                            'selected'			=> $category_dropdown,
                        );
                      wp_dropdown_categories($args);
                    ?>
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
                        <div class="col-3">
                            <h5>
                                <label>
                                    <input class="checkbox" type="checkbox" name="<?php echo $this->get_field_name( 'enable_view_all_cat' ); ?>" id="<?php echo $this->get_field_id( 'enable_view_all_cat' ); ?>" value="1" <?php checked( $enable_view_all_cat, '1' ); ?>>
                                    <span><?php _e( 'Hide/Show View All', 'timagazine' ); ?></span>
                                </label>
                            </h5>
                        </div>
                    </div>
                </div>
                <h3 class="title panel-title"><?php _e( 'Layout Settings', 'timagazine' ); ?></h3>
                <div class="accordion-contents content">
                    <div class="accordion-wrap">
                        <div class="col-3">
                                <label for="<?php echo $this->get_field_id('category_layout_1'); ?>">
                                    <?php _e('Layout Style 1  ', 'timagazine'); ?>
                                    <input class="" id="<?php echo $this->get_field_id('category_layout_1'); ?>" name="<?php echo $this->get_field_name('category_layout_style'); ?>" type="radio" value="category_layout_1" <?php if($category_layout_style === 'category_layout_1'){ echo 'checked="checked"'; } ?> />
                                </label>
                            </div>
                            <div class="col-3">
                                <label  for="<?php echo $this->get_field_id('category_layout_2'); ?>">
                                    <?php _e('Layout Style 2  ', 'timagazine'); ?>
                                    <input class="" id="<?php echo $this->get_field_id('category_layout_2'); ?>" name="<?php echo $this->get_field_name('category_layout_style'); ?>" type="radio" value="category_layout_2" <?php if($category_layout_style === 'category_layout_2'){ echo 'checked="checked"'; } ?> />
                                </label>
                            </div>
                            <div class="col-3">
                                <label  for="<?php echo $this->get_field_id('category_layout_3'); ?>">
                                    <?php _e('Layout Style 3  ', 'timagazine'); ?>
                                    <input class="" id="<?php echo $this->get_field_id('category_layout_3'); ?>" name="<?php echo $this->get_field_name('category_layout_style'); ?>" type="radio" value="category_layout_3" <?php if($category_layout_style === 'category_layout_3'){ echo 'checked="checked"'; } ?> />
                                </label>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}