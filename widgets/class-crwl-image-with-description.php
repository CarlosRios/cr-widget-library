<?php
/**
 * Creates a widget with an image and description
 *
 * @author Carlos Rios
 * @version 1.0
 * @package Carlos_Rios_Widget_Library / Widget
 */
class CRWL_Image_With_Description extends WP_Widget {

	/**
	 * The version of this widget
	 * 
	 * @var string
	 */
	public $version = '1.0';

	/**
	 * Setup the widget
	 *
	 * @return void
	 */
	public function __construct()
	{
		// Override default widget settings
		$options = array(
			'description' => __( 'Displays an image and a text description.', 'crwl-widget' ),
			'customize_selective_refresh' => true,
		);

		// Set the widget settings
		parent::__construct( 'cr_image_w_description', __( 'Image With Description', 'crwl-widget' ), $options );

		// Run the widgets hooks
		$this->hooks();
	}

	/**
	 * Registers the hooks for the widget
	 *
	 * @return void
	 */
	public function hooks()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'upload_scripts' ) );
	}


	/**
	 * Renders the form
	 *
	 * @param  object $instance
	 * @return void
	 */
	public function form( $instance )
	{
		if( !isset( $instance['title'] ) ) {
			$instance['title'] = '';
		}

		if( !isset( $instance['image'] ) ) {
			$instance['image'] = '';
		}

		if( !isset( $instance['desc'] ) ) {
			$instance['desc'] = '';
		}

		?>
		<div class="crwl-image-w-description-widget">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'crwl-widget' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" type="text" class="widefat" name="<?php echo $this->get_field_name( 'title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Author Image:', 'crwl-widget' ); ?></label>
				<div class="crwl-image-container">
					<?php
					if( !empty( $instance['image'] ) ) {
						$atts = array(
							'class' => 'crwl-widget-image'
						);

						echo wp_get_attachment_image( $instance['image'], 'full', false, $atts );
					}
					?>
				</div>
				<input class="crwl-upload-image-input" type="hidden" name="<?php echo $this->get_field_name( 'image'); ?>" value="<?php echo esc_attr( $instance['image'] ); ?>" />
				<button id="<?php echo $this->get_field_id('image'); ?>" class="crwl-upload-image-btn button button-primary"><?php _e( 'Choose An Image', 'crwl-widget' ); ?></button>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e( 'Short Description:', 'crwl-widget' ); ?></label>
				<textarea rows="10" cols="20" name="<?php echo $this->get_field_name('desc'); ?>" id="<?php echo $this->get_field_id('bio'); ?>" class="widefat"><?php echo esc_html( $instance['desc'] ); ?></textarea>
			</p>
		</div>
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
		$instance['image'] = ( ! empty( $new_instance['image'] ) ) ? absint( $new_instance['image'] ) : '';
		$instance['desc'] = ( ! empty( $new_instance['desc'] ) ) ? strip_tags( $new_instance['desc'] ) : '';

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
		echo $args['before_widget']; ?>

		<div class="crwl-image-w-description">

			<?php
			// Display the image
			if( isset( $instance['image'] ) && !empty( $instance['image'] ) ) {
				echo wp_get_attachment_image( $instance['image'], 'full' );
			}

			// Display the title
			if( isset( $instance['title'] ) && !empty( $instance['title'] ) ) {
				echo $args['before_title'] . $instance['title'] . $args['after_title'];
			}

			// Display the desc
			if( isset( $instance['desc'] ) && !empty( $instance['desc'] ) ) {
				echo $instance['desc'];
			}
			?>

		</div>

		<?php
		echo $args['after_widget'];
	}

	/**
	 * Upload the Javascripts for the media uploader
	 * 
	 * @since  1.0
	 */
	public function upload_scripts()
	{
		wp_enqueue_media();
		wp_enqueue_script( 'crwl-image-w-description', CRWL_URL . 'assets/js/image_w_description.js', 'jquery', $this->version );
	}

}
