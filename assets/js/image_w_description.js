/**
 * Handles the JS for the Image With Description widget
 * 
 * @version 1.0
 * @author  Carlos Rios
 */
jQuery( document ).ready( function( $ )
{
	"use-strict";

	var frame;
	var image_widget = $( '.crwl-image-w-description-widget' );
	var upload_image_btn = image_widget.find( '.crwl-upload-image-btn' );
	var image_widget_input = image_widget.find( '.crwl-upload-image-input' );
	var image_widget_image_container = image_widget.find( '.crwl-image-container' );

	/**
	 * Handle image_widget button click
	 *
	 * @return void
	 */
	upload_image_btn.on( 'click', open_media_frame );

	/**
	 * Opens WordPress' media frame,
	 * This function is fired on the upload_image_btn click
	 * 
	 * @param  string event
	 * @return void
	 */
	function open_media_frame( event )
	{
		event.preventDefault();

		// Open existing frame if it exists
		if( frame ) {
			frame.open();
			return;
		}

		// Create a new frame with wp.media
		frame = wp.media({
			title: 'Select an image',
			button: {
				text: 'Use this image'
			},
			multiple: false
		});

		// Add the data to the DOM
		frame.on( 'select', add_image_data_to_DOM );

		// Open the modal
		frame.open();
	}

	/**
	 * Replaces the image's id in the DOM input
	 * 
	 * @return void
	 */
	function add_image_data_to_DOM()
	{
		// Get media attachment details from the frame state
		var attachment = frame.state().get('selection').first().toJSON();

		// Append the preview to the image container
		image_widget_image_container.html( '' ).append( '<img src="'+ attachment.url +'" alt="" style="max-width:100%" class="crwl-widget-image" />' );

		// Send the attachment id to our hidden input
		image_widget_input.val( attachment.id );
	}

});
