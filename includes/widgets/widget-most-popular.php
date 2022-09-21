<?php
/**
 * Most popular posts this week sorted by comment count
 */

class Timagazine_Most_Popular extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname' => 'timagazine-most-popular',
			'description' => __( 'Most popular posts in the current week, sorted by comment count', 'timagazine' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'timagazine-most-popular', __( 'TM: Most popular', 'timagazine' ), $widget_ops );
		$this->alt_option_name = 'timagazine_most_popular';
	}

	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 3;
		if ( ! $number )
			$number = 3;

		$query_most_popular = new WP_Query( array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'orderby' 			  => 'comment_count',
			'date_query' => array(
				array(
					'year' => date( 'Y' ),
					'week' => date( 'W' ),
				),
			),
		) );

		if ( $query_most_popular->have_posts() ) : ?>
			<?php echo $args['before_widget']; ?>
			<div class="widget-most-popular">
				<?php if ( $title ) :
					echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
				endif; ?>
				<div class="most-popular-post-lists">
					<?php while ( $query_most_popular->have_posts() ) : $query_most_popular->the_post(); ?>
						<div class="row">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="col-md-4 col-sm-6 col-12 mb-10">
									<div class="overflow-h">
										<a href="<?php echo esc_url( get_the_permalink() ); ?>">
											<?php the_post_thumbnail( 'timagazine-most-popular-thumb' ); ?>
										</a>
									</div>
								</div>
							<?php endif; ?>
							<div class="col-md-8 col-sm-6 col-12 mb-10">
								<h6>
									<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
								</h6>
								<?php echo '<p class="post-meta mb-0"><a href="' . esc_url( get_the_permalink() ) . '" class="date">' . esc_html( get_the_date() ) . '</a></p>'; ?>
							</div>
							<div class="col-12 border-hide">
								<hr class="mb-10">
							</div>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
			<?php echo $args['after_widget'];
			wp_reset_postdata();
		endif;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );

		return $instance;
	}

	public function form( $instance ) {
		$title     			= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 3;
		?>
		<div class="timagazine-wrap">
			<div>
				<h2>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Heading:', 'timagazine' ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
				</h2>
			</div>
		</div>
		<div>
			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'timagazine' ); ?></label>
				<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
			</p>
			<p><em><?php _e('Please note: this widget will display the most popular posts from the current week, sorted by comment count.', 'timagazine'); ?></em></p>
		</div>
		<?php
	}
}