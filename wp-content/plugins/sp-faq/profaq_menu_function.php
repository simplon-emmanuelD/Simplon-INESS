<?php
add_action('admin_menu', 'faq_submenu_page');
function faq_submenu_page() {
	add_submenu_page( 'edit.php?post_type=sp_faq', 'Pro FAQ Designs', 'Pro FAQ Designs', 'manage_options', 'profaq-submenu-page', 'register_faq_page_callback' );
}
function register_faq_page_callback() {
	$result ='<div class="wrap"><div id="icon-tools" class="icon32"></div><h2 style="padding:15px 0">Pro FAQ Designs</h2></div>
				<div class="medium-12 columns" style="margin:10px 0 15px 0;"><a href="http://wponlinesupport.com/sp_plugin/sp-responsive-wp-faq-with-category-plugin/" target="_blank" ><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/probanner.png"></a></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-1.jpg"><p><code>[sp_faq design="design-1"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-2.jpg"><p><code>[sp_faq design="design-2"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-3.jpg"><p><code>[sp_faq design="design-3"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-4.jpg"><p><code>[sp_faq design="design-4"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-5.jpg"><p><code>[sp_faq design="design-5"]</code></p></div></div>				
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-6.jpg"><p><code>[sp_faq design="design-6"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-7.jpg"><p><code>[sp_faq design="design-7"]</code></p></div></div>
				<div class="medium-4 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-8.jpg"><p><code>[sp_faq design="design-8"]</code></p></div></div>
				<div class="medium-12 columns"><h2>Added customization options in the shortcode</h2></div>
				<div class="medium-12 columns"><div class="postdesigns"><p><code>[sp_faq design="design-1" background_color="#000" font_color="#fff" border_color="#444" icon_color="white" icon_type="plus" icon_position="left" ]</code></p>
				</div></div>
				<div class="medium-12 columns"><h2>Add FAQ in Grid view with categories</h2></div>
				<div class="medium-6 columns"><div class="postdesigns"><img  src="'.plugin_dir_url( __FILE__ ).'pro-designs/design-9.jpg"><p><code>[faq-category-grid grid="2"]</code></p>
				<p><strong>Complete shortcode is</strong><br /> <code>[faq-category-grid grid="2" background_color="#f1f1f1" font_color="#000" font_size="20"]</code></p></div></div>
				
				<div class="medium-12 columns"><p><strong>Check Demo Link</strong> <a href="http://demo.wponlinesupport.com/prodemo/pro-faq-plugin-demo/" target="_blank">Pro FAQ plugin</a></p></div>';
	echo $result;
}
function faq_admin_style(){
	?>
	<style type="text/css">
	.postdesigns{-moz-box-shadow: 0 0 5px #ddd;-webkit-box-shadow: 0 0 5px#ddd;box-shadow: 0 0 5px #ddd; background:#fff; padding:10px;  margin-bottom:15px;}
	.column, .columns {-webkit-box-sizing: border-box; 
-moz-box-sizing: border-box;    
box-sizing: border-box;}
.postdesigns img{width:100%; height:auto}
@media only screen and (min-width: 40.0625em) {  
  .column,
  .columns {position: relative;padding-left:10px;padding-right:10px;float: left; }
  .medium-1 {    width: 8.33333%; }
  .medium-2 {    width: 16.66667%; }
  .medium-3 {    width: 25%; }
  .medium-4 {    width: 33.33333%; }
  .medium-5 {    width: 41.66667%; }
  .medium-6 {    width: 50%; }
  .medium-7 {    width: 58.33333%; }
  .medium-8 {    width: 66.66667%; }
  .medium-9 {    width: 75%; }
  .medium-10 {    width: 83.33333%; }
  .medium-11 {    width: 91.66667%; }
  .medium-12 {    width: 100%; } 
   }
	</style>
<?php }
add_action('admin_head', 'faq_admin_style');
