( function( $ ) {
	$(document).on('panelsopen', function(e) {
		$(".featured-posts-dropdown").chosen({
			disable_search_threshold: 5,
			max_selected_options: 5
		});
		$(".chosen-dropdown-3").chosen({
			disable_search_threshold: 10,
			max_selected_options: 3
		});
		$(".category-posts-dropdown-a,.author-dropdown").chosen({
			disable_search_threshold: 10,
			max_selected_options: 1
		});

		$(".trending-posts-dropdown").chosen({
			disable_search_threshold: 10
		});

		$('.trending-posts-Sortable').chosenSortable();
	});
	if ( ( '.timagazine_author_image_btn' ).length ){
		function media_upload(button_class) {
			var _custom_media = true,
				_orig_send_attachment = wp.media.editor.send.attachment;

			$('body').on('click', button_class, function(e) {
				var button_id ='#'+$(this).attr('id');
				var self = $(button_id);
				var send_attachment_bkp = wp.media.editor.send.attachment;
				var button = $(button_id);
				var id = button.attr('id').replace('_button', '');
				_custom_media = true;
				wp.media.editor.send.attachment = function(props, attachment){
					if ( _custom_media  ) {
						$('.custom_media_id').val(attachment.id);
						$('.custom_media_url').val(attachment.url);
						$('.custom_media_image').attr('src',attachment.url).css('display','block');
					} else {
						return _orig_send_attachment.apply( button_id, [props, attachment] );
					}
				}
				wp.media.editor.open(button);
				return false;
			});
		}
		media_upload('.custom_media_button.button');
	}
})( jQuery );