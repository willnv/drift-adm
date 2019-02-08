jQuery(function($) {
        
    // Uploading files
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    var set_to_post_id = $("#driftadm-settings-form #login_bg_img").value;

    /**
     * vars passed via localize_script()
     */
    var lib_title = buttons_text.title;
    var lib_upload_btn =  buttons_text.upload_btn;

    $('.driftadm-upload-media-button').on('click', function(event) {

        event.preventDefault();

        $(".driftadm-upload-media-button").removeClass("current-selecting");
        this.classList.add("current-selecting");

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: lib_title,
            button: {
                text: lib_upload_btn
            },
            multiple: false	// Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // Do something with attachment.id and/or attachment.url here
            $(".current-selecting").parent().find('.img-preview').attr( 'src', attachment.url );
            //$('#driftadm-settings-form #login_bg_img').val( attachment.url );
            $(".current-selecting").parent().find('.media-hidden-value').val( attachment.url );

            // Restore the main post ID
            wp.media.model.settings.post.id = wp_media_post_id;
        });

            // Finally, open the modal
            file_frame.open();
    });

    // Restore the main ID when the add media button is pressed
    $('a.add_media').on('click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});
