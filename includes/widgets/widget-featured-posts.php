<?php
/**
 * Featured posts
 *
 */

class Timagazine_Widget_Featured_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'timagazine-widget-featured-posts',
            'description' => __( '5 featured posts displayed', 'timagazine' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'timagazine-widget', __( 'TM: Featured Posts', 'timagazine' ), $widget_ops );
        $this->alt_option_name = 'widget-featured-posts';
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $enable_meta = ! empty( $instance[ 'enable_meta' ] ) ? 1 : 0;
        $enable_category = ! empty( $instance[ 'enable_category' ] ) ? 1 : 0;
        $enable_post_title = ! empty( $instance[ 'enable_post_title' ] ) ? 1 : 0;
        $posts = isset( $instance['posts_dropdown'] ) ? $instance['posts_dropdown'] : '';
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

        $query = new WP_Query( array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'post__in'			  => $posts
        ) );

        if ( $query->have_posts() ) :  ?>
            <?php echo $args['before_widget']; ?>
            <div class="featured-mag-post-widget">
                <?php $counter = 1;
                while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php if( $counter == 1 ) {
                        $col = 'featured-col-6 hover-images';
                    } elseif ( $counter == 6) {
                        $col = 'featured-col-6 float-right hover-images';
                    }else{
                        $col = 'featured-col-3 hover-images';
                    } ?>
                    <div class="<?php echo esc_attr( $col ); ?>">
                        <div class="featured-mag-wrapper position-r"><?php
                            if ( has_post_thumbnail() ) : ?>
                                <div class="featured-mag-thumb overflow-h">
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                                        <?php
                                            if( absint( $counter ) == 1 ) {
                                                the_post_thumbnail( 'timagazine-featured-medium-thumb' );
                                            }else{
                                                the_post_thumbnail( 'timagazine-featured-small-thumb' );
                                            }
                                        ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="featured-mag-contents<?php if ( has_post_thumbnail() ) {}else{ echo " featured-img-contents-fix";}?>">
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
                                    } ?>
                                </div>
                            <?php endif;
                            if ( $enable_post_title != 1 ) : ?>
                                <h5>
                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                </h5>
                            <?php endif;
                            if ( 'post' === get_post_type() && $enable_meta != 1 ) : ?>
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

        $instance['posts_dropdown'] = array_map( 'sanitize_text_field', (array) $new_instance['posts_dropdown'] );
        $instance['number'] = (int) $new_instance['number'];
        $instance[ 'enable_meta' ] = absint( $new_instance[ 'enable_meta' ] );
        $instance[ 'enable_category' ] = absint( $new_instance[ 'enable_category' ] );
        $instance[ 'enable_post_title' ] = absint( $new_instance[ 'enable_post_title' ] );

        return $instance;
    }

    public function form( $instance ) {
        $posts_dropdown  = isset( $instance['posts_dropdown'] ) ? array_map( 'esc_attr', $instance['posts_dropdown'] ) : '';

        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $enable_meta = !empty( $instance['enable_meta'] ) ? $instance['enable_meta'] : '' ;
        $enable_category = !empty( $instance['enable_category'] ) ? $instance['enable_category'] : '' ;
        $enable_post_title = !empty( $instance['enable_post_title'] ) ? $instance['enable_post_title'] : '' ;

        ?>
        <div class="timagazine-wrap">
            <div class="">
                <h2>
                    <label for="<?php echo $this->get_field_id( 'posts_dropdown' ); ?>"><?php _e( 'Choose Your Posts', 'timagazine' ); ?></label>
                    <select data-placeholder="<?php esc_attr_e( 'Select five posts to display in this widget', 'timagazine' ); ?>" multiple="multiple" name="<?php echo $this->get_field_name( 'posts_dropdown' ); ?>" id="<?php echo $this->get_field_id( 'posts_dropdown' ); ?>" class="widefat chosen-dropdown-10 featured-posts-dropdown">
                        <?php
                        global $post;
                        $args = array( 'numberposts' => -1 );
                        $posts = get_posts( $args );
                        foreach( $posts as $post ) : setup_postdata( $post ); ?>
                            <?php printf(
                                '<option value="%s" %s>%s</option>',
                                $post->ID,
                                in_array( $post->ID, (array)$posts_dropdown ) ? 'selected="selected"' : '',
                                $post->post_title
                            );?>
                        <?php endforeach; ?>
                    </select>
                    <small>
                        <em><?php _e('Please note: you can select up to five posts to display in this widget for free version.', 'timagazine'); ?></em>
                    </small>
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
                                <label>
                                    <span><?php _e( 'Number of posts to show : ', 'timagazine' ); ?></span>
                                    <input class="tiny-text" type="number" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" step="1" min="1" max="5" value="<?php echo $number; ?>" size="5">
                                </label><br/>
                                <small><?php _e( 'Max Limit 5 ( Unlock Pro )', 'timagazine' ); ?></small>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}