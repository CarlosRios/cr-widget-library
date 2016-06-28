<?php
/**
 * Creates a widget for a random post or set of random posts
 *
 * @author Carlos Rios
 * @version 1.0
 * @package Carlos_Rios_Widget_Library / Widget
 */
class CRWL_Random_Post_Message extends WP_Widget {

	/**
	 * Setup the widget
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Override default widget settings
		$options = array(
			'description' => __( 'Display\'s a random post or set or random posts.', 'cr-widget' ),
			'customize_selective_refresh' => true
		);

		// Set the widget settings
		parent::__construct( 'cr_random_post_message', __( 'Random Post Message', 'cr-widget' ), $options );
	}

	/**
	 * Renders the form
	 *
	 * @param  object $instance
	 * @return void
	 */
	public function form( $instance )
	{
		/**
		 * Set the default title
		 */
		if( !isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}

		/**
		 * Set the default post type
		 */
		if( !isset( $instance['post_type'] ) ) {
			$instance['post_type'] = 'post';
		}

		/**
		 * Set the default count
		 */
		if( !isset( $instance['count'] ) ) {
			$instance['count'] = 1;
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'crwl-widget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'title'  ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type:', 'crwl-widget' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
				<?php
				$post_types = get_post_types( '', 'objects' );

				foreach( $post_types as $type ){
					echo sprintf( '<option value="%s" %3$s>%s</option>', $type->name, $type->labels->name, selected( $instance['post_type'], $type->name, false ) );
				} ?>
			</select>
		</p>
		<p>
			<label for="<?php $this->get_field_id( 'count' ); ?>"><?php _e( 'Number of posts to show:', 'crwl-widget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'count' ); ?>" type="number" class="tiny-text" value="<?php echo $instance['count']; ?>" name="<?php echo $this->get_field_name( 'count' ); ?>"/>
		</p>
		<?php
	}

	/**
	 * Save the widget settings
	 * 
	 * @param  array $new_instance
	 * @param  array $old_instance
	 * @return object WP_Widget
	 */
	public function update( $new_instance, $old_instance )
	{
		// Create the instance's variables
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : 'post';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? absint( $new_instance['count'] ) : 1;

		$this->instance_vars = $instance;
		return $instance;
	}

	/**
	 * Creates the output for the widget on the frontend
	 * 
	 * @param  array $args
	 * @param  object $instance
	 * @return void
	 */
	public function widget( $args, $instance )
	{	
		echo $args['before_widget'];

		if( isset( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		// Setup the args for the query
		$post_args = array(
			'post_type'			=> $instance['post_type'],
			'posts_per_page'	=> $instance['count']
		);

		// Get the posts
		$posts = new WP_Query( $post_args );

		// Loop through posts
		while( $posts->have_posts() ) {
			$posts->the_post();

			// The title
			echo sprintf( '<h4><a href="%2$s">%1$s</a></h4>', get_the_title(), get_the_permalink() );

			the_content();
		}

		// Reset the postdata
		wp_reset_postdata();

		// Echo the after widget stuff
		echo $args['after_widget'];
	}

}
