jQuery(document).ready(function($){

	frs_admin_loading('hide');

	if($('.widget-liquid-right').length > 0) $('.widget-liquid-right').accordion();

	$(".postbox-container").on('change','#tonjoo-frs-show_button select',function(){
		value = $(this).attr('value')

		if(value=='false'){			
			$(".button_attr").hide('slow');
			$("#tonjoo-frs-button_skin").hide('slow');
		}

		else{
			$(".button_attr").show('slow');
			$("#tonjoo-frs-button_skin").show('slow');
		}
	})

	$(".postbox-container").on('change','#tonjoo-frs-padding_type select',function(){
		value = $(this).attr('value')

		if(value=='auto'){			
			$("#textbox_padding").hide('slow');
		}
		else{
			$("#textbox_padding").show('slow');
		}
	})

	$(".postbox-container").on('change','#tonjoo-frs-is-fluid select',function(){
		value = $(this).attr('value')

		if(value=='true'){			
			$("#max_image_width").hide('slow');
		}
		else{
			$("#max_image_width").show('slow');
		}
	})

	$(".postbox-container").on('change',' #tonjoo_show_navigation_arrow select',function(){
		value = $(this).attr('value')

		if(value=='true'){

			$(".tonjoo_nav_option").show('slow');
		}
		else{
			$(".tonjoo_nav_option").hide('slow');
		}
	})

	$(".postbox-container").on('change','#tonjoo-frs-bg-textbox-type select',function(){
		value = $(this).attr('value')

		if(value=='picture'){			
			$("#tonjoo-frs-textbox-bg").show('slow');
			$("#textbox_color").hide('slow');
		}
		else if(value=='color'){
			$("#tonjoo-frs-textbox-bg").hide('slow');
			$("#textbox_color").show('slow');
		}
		else{
			$("#textbox_color").hide('slow');
			$("#tonjoo-frs-textbox-bg").hide('slow');
		}
	})

	// Preview Padding
	$(".postbox-container").on('change','#tonjoo-frs-padding_type select',function(){
		preview_padding();
	});

	$(".postbox-container").on('keyup','#frs_textbox_padding',function(){
		preview_padding();
	});

	function preview_padding()
	{
		var type = $('#tonjoo-frs-padding_type select').val();

		if(type == 'auto')
		{
			$('#frs-position-preview-padding-left').html('A');
			$('#frs-position-preview-padding-top').html('Automatic padding type');
			$('#frs-position-preview-padding-right').html('A');
			$('#frs-position-preview-padding-bottom').html('A');
		}
		else
		{
			var padding = $('#textbox_padding input').val();
			var arr_pad = padding.split('px ');

			// get rid last 'px'
			arr_pad[3] = arr_pad[3].slice(0,-2);

			$('#frs-position-preview-padding-left').html(arr_pad[0]);
			$('#frs-position-preview-padding-top').html(arr_pad[1]);
			$('#frs-position-preview-padding-right').html(arr_pad[2]);
			$('#frs-position-preview-padding-bottom').html(arr_pad[3]);
		}
	}


	// Preview Position	
	$(".postbox-container ").on('change','#tonjoo-frs-text_position select',function(){
		preview_position();
	});

	$(".postbox-container ").on('change','#tonjoo-frs-textbox_width select',function(){
		preview_position();
	});

	function preview_position(){
		var position = $('#tonjoo-frs-text_position select').val();
		var width = $('#tonjoo-frs-textbox_width select').val();

		position = position.substring(21);
		obj = $('#frs-position-preview-obj');

		obj.removeAttr('style');

		// width
		obj.css({
			"width": (470 * width / 12) + 'px'
		})

		// position
		switch (position)
		{
			case 'left': 
				obj.css({
					"margin-top": '65px'
				}).html('Text Box<br/><span>( Left )</span>')
			    break;
			case 'top-left': 
				obj.css({
					"margin-right": 'auto'
				}).html('Text Box<br/><span>( Top Left )</span>')
			    break;
			case 'top': 
				obj.css({
					"margin-left": 'auto',
					"margin-right": 'auto'
				}).html('Text Box<br/><span>( Top )</span>')
			    break;
			case 'top-right': 
				obj.css({
					"margin-left": 'auto'
				}).html('Text Box<br/><span>( Top Right )</span>')
			    break;
			case 'right': 
				obj.css({
					"margin-left": 'auto',
					"margin-top": '65px'
				}).html('Text Box<br/><span>( Right )</span>')
			    break;
			case 'bottom-right': 
				obj.css({
					"margin-left": 'auto',
					"margin-top": '135px'
				}).html('Text Box<br/><span>( Bottom Right )</span>')
			    break;
			case 'bottom': 
				obj.css({
					"margin-left": 'auto',
					"margin-right": 'auto',
					"margin-top": '135px'
				}).html('Text Box<br/><span>( Bottom )</span>')
			    break;
			case 'bottom-left': 
				obj.css({
					"margin-right": 'auto',
					"margin-top": '135px'
				}).html('Text Box<br/><span>( Bottom Left )</span>')
			    break;
			case 'center': 
				obj.css({
					"margin-left": 'auto',
					"margin-right": 'auto',
					"margin-top": '65px'
				}).html('Text Box<br/><span>( Center )</span>')
			    break;
			case 'sticky-top': 
				obj.css({
					"margin-left": '-41px',
					"margin-top": '-41px',
					"width": '510px'
				}).html('Text Box<br/><span>( Sticky Top )</span>')
			    break;
			case 'sticky-bottom': 
				obj.css({
					"margin-left": '-41px',
					"margin-top": '175px',
					"width": '510px'
				}).html('Text Box<br/><span>( Sticky Bottom )</span>')
			    break;
			default:  
				// no action
		}		
	}
	
	/**
	 * Slideshow submenu
	 */
	var category = $('table#table-slide tbody').attr('category');

	if($('table#table-slide tbody').length > 0) frs_resort_data_table();	    

   	$('table#table-slide tbody , .frs-modal-container  ').on('click','[frs-delete-slide]',function(){
   		
   		deleteConfirmation = confirm("Are you sure to delete the slide ? ");	

   		button = $(this)	

   		post_id = button.data('post-id')

   		if(deleteConfirmation){
   			 ajax_button_spin(button)
   			 data = {
	   			action:'frs_delete',
	   			post_id:button.data('post-id')
	   		}

	   		frs_admin_loading();

   			$.post(ajaxurl, data,function(response){

   				frs_admin_loading('hide');

   				var response = $.parseJSON(response);

   				if(response.success)
   				{
   					$('.frs-modal-backdrop').removeClass('active');
					$('.frs-modal-container .frs-table-left').html('');	
					$('.frs-modal-container').hide();	

   		 			$('#list_item_'+post_id).hide('3000', function(){ 
   		 				$('#list_item_'+post_id).remove() 
   		 				frs_check_table_size()
   		 			});
   		 			ajax_button_spin_stop(button)
   		 			frs_notice_updated()
   				}
   				else{
   					frs_notice_error_updated()
   				}
   			})
   		}else{
   			ajax_button_spin_stop(button)
   		}	  
   	})

	// add slide category
	$('[frs-add-slide-type]').click(function(){
		var string = prompt("Enter the category name", "");
		
		if (string != null) 
		{
			data = {
	   			action:'frs_add_slidetype',
	   			name: string
	   		}

	   		frs_admin_loading();

   			$.post(ajaxurl, data,function(response){	   

   				frs_admin_loading('hide');

   				var response = $.parseJSON(response);

   				if(response.success) {	   	
   					window.location.href = admin_url + '&tab=' + response.slug + '&tabtype=slide';
   				}
   			});
		}
		});

		// select all input text on click
	$('[frs-input-shortcode]').click(function(){
		$(this).select();
		});

		// create first slideshow
	$('#frs-first-add-slideshow').click(function(){
		var string = $('#frs-first-slideshow-input').val();

		if (string != null && string != '')
		{
			// do loading
			$(this)
				.html('Loading...')
				.attr('id','its-loaded');

			// ajax
			data = {
	   			action:'frs_add_slidetype',
	   			name: string
	   		}

	   		frs_admin_loading();

   			$.post(ajaxurl, data,function(response){

   				frs_admin_loading('hide');

   				var response = $.parseJSON(response);

   				if(response.success) {	   	
   					window.location.href = admin_url + '&tab=' + response.slug + '&tabtype=slide';
   				}
   			});
		}
		else
		{
			alert("Please enter your new slideshow name");
		}
		});

		// delete slide category
	$('[frs-delete-slide-type]').click(function(){
		var x = confirm("Are you sure want to delete this slideshow?");
		
		if (x == true) 
		{
			data = {
	   			action:'frs_delete_slidetype',
	   			id: $(this).attr('id')
	   		}

	   		frs_admin_loading();

   			$.post(ajaxurl, data,function(response){	

   				frs_admin_loading('hide');

   				var response = $.parseJSON(response);

   				if(response.success) {	   	
   					window.location.href = admin_url + '&settings-updated=true';
   				}
   			});
		}
		});

		$('.frs-modal-container').on('click','[frs-modal-close-modal]',function(){
		$('.frs-modal-backdrop').removeClass('active');
		$('.frs-modal-cat-container').hide();	
		$('.spinner').removeClass('active')

	})

		// add slide
   	$('[frs-add-slide]').click(function(){
		add_slide();
   	});

   	if(get_other == 'add-new-intro')
   	{
   		add_slide();

   		window.setTimeout(function(){
   			introJs()
	   		.setOption('tooltipPosition', 'auto')
	   		.setOption('positionPrecedence', ['left', 'right', 'bottom', 'top'])
	   		.goToStep(8)
	   		.start();
   		},1000);	   		
   	}

   	function add_slide()
   	{
   		button = $('[frs-add-slide]');

		ajax_button_spin(button)

			data = {
   			action:'frs_show_modal',
   			post_id: 'false'
   		}

   		frs_admin_loading();

			$.post(ajaxurl, data,function(response){	   		

				frs_admin_loading('hide');

				var response = $.parseJSON(response);

				if(response.success){	   					

					decoded = $("<div/>").html(response.modal).text();

		 			$('.frs-modal-container .frs-table-left').html(decoded)

		 			/* set right content value */
		 			var frs_id = response.id
		 			var frs_title = response.title
		 			var img_default = response.img_default
		 			var post_thumbnail_id = response.post_thumbnail_id

		 			$('#frs-tonjoo-modal .floating-modal-button .button-primary').data('post-id',frs_id);
		 			
		 			$('#frs-tonjoo-modal .floating-modal-button .button-danger').data('post-id',frs_id);
		 			
		 			$('#frs-tonjoo-modal input#frs-title').val(frs_title);

		 			$('#frs-tonjoo-modal [media-upload-image]').attr('src',response.scr);
		 			
		 			$('#frs-tonjoo-modal [media-upload-id]').attr('value',post_thumbnail_id);
		 			
		 			$('#frs-tonjoo-modal [frs-remove-image]').attr('data-image-default',img_default);

		 			$('#frs-tonjoo-modal .floating-modal-button .button-danger').hide();

		 			if(typeof tinyMCE != 'undefined' && tinyMCE.get('frs-modal-content'))
		 				tinyMCE.get('frs-modal-content').setContent(response.content)
		 			else
		 				$('#frs-modal-content').val('')

		 			/* set active */
		 			$('.frs-modal-backdrop').addClass('active')
		 			$('.frs-modal-container').show().addClass('active')

		 			preview_position();
		 			preview_padding();

		 			ajax_button_spin_stop(button)
				}
			})	   
   	}


	// edit slide
    $('table#table-slide tbody ').on('click','[frs-edit-slide]',function(){

		button = $(this)	

		ajax_button_spin(button)

			data = {
   			action:'frs_show_modal',
   			post_id:button.data('post-id')
   		}

   		frs_admin_loading();

			$.post(ajaxurl, data,function(response){	

				frs_admin_loading('hide');

				var response = $.parseJSON(response);   				

				if(response.success){	   					

					ajax_button_spin_stop(button)

					decoded = $("<div/>").html(response.modal).text();

		 			$('.frs-modal-container .frs-table-left').html(decoded)

		 			/* set right content value */
		 			var frs_id = response.id
		 			var frs_title = response.title
		 			var img_default = response.img_default
		 			var post_thumbnail_id = response.post_thumbnail_id

		 			$('#frs-tonjoo-modal .floating-modal-button .button-primary').data('post-id',frs_id);
		 			
		 			$('#frs-tonjoo-modal .floating-modal-button .button-danger').data('post-id',frs_id);
		 			
		 			$('#frs-tonjoo-modal input#frs-title').val(frs_title);

		 			$('#frs-tonjoo-modal [media-upload-image]').attr('src',response.scr);
		 			
		 			$('#frs-tonjoo-modal [media-upload-id]').attr('value',post_thumbnail_id);
		 			
		 			$('#frs-tonjoo-modal [frs-remove-image]').attr('data-image-default',img_default);

		 			$('#frs-tonjoo-modal .floating-modal-button .button-danger').show();

		 			/* set active */
		 			$('.frs-modal-backdrop').addClass('active')

		 			$('.frs-modal-container').show().addClass('active')

		 		if(typeof tinyMCE != 'undefined' && tinyMCE.get('frs-modal-content'))
		   	 		tinyMCE.get('frs-modal-content').setContent(response.content)
			    else
			    	$('#frs-modal-content').val(response.content);

				preview_position();		
				preview_padding();		    

		 			frs_check_table_size()
				}
			})
   	})

	$('.frs-modal-backdrop').click(function(){
		$(this).removeClass('active');
		$('.frs-modal-container .frs-table-left').html('');
		$('.frs-modal-container').hide();	
		$('.spinner').removeClass('active')
		frs_check_table_size()
	})
	
	$('.frs-modal-container').on('click','[frs-modal-close-modal]',function(){
		$('.frs-modal-backdrop').removeClass('active');		
		$('.frs-modal-container .frs-table-left').html('');
		$('.frs-modal-container').hide();
		$('.spinner').removeClass('active')

	})

	
	/**
	 * Save 
	 */
	$('.frs-modal-container').on('click','[frs-save-slider]',function(){

	 	if($('#frs-modal-form #frs-title').val() == "")
	 	{	 		
	 		alert("Please fill the slider title");

	 		post_id = $(this).data('post-id')	 		
	 	}
	 	else
	 	{
	 		button = $(this)

	 		ajax_button_spin(button)

	 		if(typeof tinyMCE != 'undefined' && tinyMCE.get('frs-modal-content')){
       	 		content =  tinyMCE.get('frs-modal-content').getContent()
		    }else{
		        content =  $('#frs-modal-content').val();
		    }

			post_id = $('[frs-save-slider]').data('post-id')

			var data =  $('#frs-modal-form').serialize() + '&action=frs_save&content=' + content +"&post_id="+post_id+"&slide_type="+current_frs_slide_type;

			frs_admin_loading();

			$.post(ajaxurl, data,function(response){	  

				frs_admin_loading('hide');

				var response = $.parseJSON(response); 				

				if(response.success){
					var data = 'action=frs_render_row&post_id=' + response.id

					frs_admin_loading();

					replace_id = response.id

					$.post(ajaxurl, data,function(response){

						frs_admin_loading('hide');

						var response = $.parseJSON(response);

						if(response.success){

							decoded = $("<div/>").html(response.row).text();

							// edit data / replace row
							if(!isNaN(post_id)){
								$("#list_item_"+replace_id ).replaceWith(decoded );
							}
							else{
								//New data , add row
								if($('#table-slide tr:last').length){
									$('#table-slide tr:last').after(decoded)
								}
								else
									$('#table-slide tbody').html(decoded)

								//re sort jquery table
								save_sort_table();
							}
							frs_check_table_size()
							$('.frs-modal-backdrop').removeClass('active');
							$('.frs-modal-container .frs-table-left').html('');	
							$('.frs-modal-container').hide();	

							ajax_button_spin_stop(button)

							frs_notice_updated()
						}
						else{
							frs_notice_error_updated()
						}
					})	   	

				}
			})
	 	}		
	})


	/**
	 * Media Uploader
	 */
	var custom_uploader
    var media_button
    
    $('.postbox-container').on('click','[mediauploadbutton]',function(e) {

        media_button = $(this);
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('[frs-mediauploader]').find('[media-upload-id]').val(attachment.id);

            thumbnail = attachment.url
            
            $('[frs-mediauploader]').find('[media-upload-image]').attr('src',thumbnail);

        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });

	$('.postbox-container').on('click','[frs-remove-image]',function(){

		$('[media-upload-id]').val('');

		$('[media-upload-image]').attr('src',$(this).data('image-default'));
	})

	$('#frs-how-to-use').on('click',function(){
		startTour();
	})

	function startTour() {
		var tour = introJs();

		tour.setOption('tooltipPosition', 'auto');
		tour.setOption('positionPrecedence', ['left', 'right', 'bottom', 'top']);

		// go to the next page
		// tour.setOption('doneLabel', 'Next page').oncomplete(function() {
	 	// 		window.location.href = '?page=frs-setting-page&tab=a-first-one&tabtype=slide&other=add-new-intro';
	 	// });

		tour.start();
	}

	function frs_check_table_size(){
		if($("table#table-slide tr").size()==0){
			$('.no-slide').removeClass('hide')
		}
		else{
			$('.no-slide').addClass('hide')
		}
	}

	function save_sort_table()
	{
		var ordr = $('table#table-slide tbody').sortable('serialize') + '&action=frs_list_update_order';

		frs_admin_loading();

	    $.post(ajaxurl, ordr, function(response){
	       	frs_notice_updated() 

	       	frs_admin_loading('hide');
	    });
	}

	function frs_resort_data_table(){
	 	$('table#table-slide tbody').sortable({
		    items: '.list_item',
		    opacity: 0.5,
		    cursor: 'pointer',
		    axis: 'y',
	    	distance: 5,
		    update: function() {
		        var ordr = $(this).sortable('serialize') + '&action=frs_list_update_order';

		        frs_admin_loading();

		        $.post(ajaxurl, ordr, function(response){
		           	frs_notice_updated() 

		           	frs_admin_loading('hide');
		        });
		    },
		    helper: function(e, tr){
			    
			    var originals = tr.children();
			    var helper = tr.clone();
			    helper.children().each(function(index)
			    {
			      	// Set helper cell sizes to match the original sizes
			      	$(this).width(originals.eq(index).width());
			
			    });
			    
			    return helper;
			}
		});
	}

	function ajax_button_spin(button){
		if(button.next('.spinner').size()!=0)
			button.next('.spinner').addClass('active')
		else
			button.siblings('.spinner').addClass('active')
	}

	function ajax_button_spin_stop(button){
		if(button.next('.spinner').size()!=0)
			button.next('.spinner').removeClass('active')
		else
			button.siblings('.spinner').removeClass('active')
	}

	function frs_notice_updated() {
		$('.frs-notice-wrapper').addClass('active');
		$('.frs-updated').hide();
		$('.frs-updated').stop().show('slow');
	}

	function frs_notice_error_updated() {	
		$('.frs-notice-wrapper').addClass('active');
		$('.frs-updated-error').hide()
		$('.frs-updated-error').stop().show('slow')
	}

	function frs_admin_loading(action)
	{
		if(action == 'hide')
		{
			$('#frs_ajax_on_progress').css({
				'background': 'none',
				'display': 'none'
			})
		}
		else
		{
			$('#frs_ajax_on_progress').css({
				'background': '',
				'display': 'block'
			})
		}
	}
});

