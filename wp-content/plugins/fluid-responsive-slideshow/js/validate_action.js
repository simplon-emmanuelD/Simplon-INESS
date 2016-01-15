jQuery(document).ready(function($) {

	/* Post Meta */	
    $("#post").validate({
	  	rules: 
	  	{
	  		"tonjoo_frs_order_number": 
	    	{	    		
	      		required: true,
	      		number: true
	    	},
	    	"tonjoo_frs_meta[title_color]": 
	    	{	    		
	      		required: true,
	      		color: true
	    	},
	    	"tonjoo_frs_meta[text_color]": 
	    	{	    		
	      		required: true,
	      		color: true
	    	},
	    	"tonjoo_frs_meta[slider_bg]": 
	    	{	    		
	      		required: true,
	      		color: true
	    	},
	    	"tonjoo_frs_meta[slider_height]": 
	    	{	    		
	      		required: true,
	      		number: true
	    	},
	    	"tonjoo_frs_meta[button_caption]": 
	    	{	    		
	      		required: function(element) {
	      			if($('#tonjoo-frs-show_button :selected').val() == "true") return true;
	      			else return false;
	      		}
	    	},
	    	"tonjoo_frs_meta[button_href]": 
	    	{	    		
	      		required: function(element) {
	      			if($('#tonjoo-frs-show_button :selected').val() == "true") return true;
	      			else return false;
	      		}
	    	},
	    	"tonjoo_frs_meta[textbox_padding]": 
	    	{	    		
	      		required: function(element) {
	      			if($('#tonjoo-frs-padding_type :selected').val() == "manual") return true;
	      			else return false;
	      		},
	      		padding: true
	    	}
	  	},
	  	invalidHandler: function(event, validator) {
	  		/* tweak wp submit button */
	  		$('#publishing-action .spinner').css('display','none');
	  		$('#publishing-action .button-primary').removeClass('button-primary-disabled');
	  	}
	});

	/* Option Page */
	$("#frs-option-form").validate();

	/* Validator addMethod */
	$.validator.addMethod("color", function(value, element) {
		return this.optional(element) || /^(#){1}([a-fA-F0-9]){6}$/.test(value);
	}, "Please specify the correct color code (# + 6 digit)");

	$.validator.addMethod("padding", function(value, element) {
		return this.optional(element) || /^([0-9]+px ){3}([0-9]+px)$/.test(value);
	}, "Invalid padding value");
});


