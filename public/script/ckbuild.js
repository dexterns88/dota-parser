(function($) {

	$('document').ready(function(){

		if( $('textarea.ck-editor').length ){
			$('textarea.ck-editor').ckeditor();	
		}

	});

})(jQuery);