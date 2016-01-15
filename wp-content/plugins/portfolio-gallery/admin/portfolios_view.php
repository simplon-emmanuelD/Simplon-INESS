<?php

if(function_exists('current_user_can'))
//if(!current_user_can('manage_options')) {
    
if(!current_user_can('delete_pages')) {
    die('Access Denied');
}	
if(!function_exists('current_user_can')){
	die('Access Denied');
}
/***<add>***/
function get_video_id_from_url($url){
	if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){	
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			return array ($match[1],'youtube');
		}
	}else {
		$vimeoid =  explode( "/", $url );
		$vimeoid =  end($vimeoid);
		return array($vimeoid,'vimeo');
	}
}
/***</add>***/

function html_showportfolios( $rows,  $pageNav,$sort,$cat_row){
	global $wpdb;
	?>
    <script language="javascript">
		function ordering(name,as_or_desc)
		{
			document.getElementById('asc_or_desc').value=as_or_desc;		
			document.getElementById('order_by').value=name;
			document.getElementById('admin_form').submit();
		}
		function saveorder()
		{
			document.getElementById('saveorder').value="save";
			document.getElementById('admin_form').submit();
			
		}
		function listItemTask(this_id,replace_id)
		{
			document.getElementById('oreder_move').value=this_id+","+replace_id;
			document.getElementById('admin_form').submit();
		}
		function doNothing() {  
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if( keyCode == 13 ) {


				if(!e) var e = window.event;

				e.cancelBubble = true;
				e.returnValue = false;

				if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
				}
			}
		}
	</script>


<div class="wrap">
	<?php $path_site2 = plugins_url("../images", __FILE__); ?>
		<style>
		.free_version_banner {
			position:relative;
			display:block;
			background-image:url(<?php echo $path_site2; ?>/wp_banner_bg.jpg);
			background-position:top left;
			backround-repeat:repeat;
			overflow:hidden;
		}
		
		.free_version_banner .manual_icon {
			position:absolute;
			display:block;
			top:15px;
			left:15px;
		}
		
		.free_version_banner .usermanual_text {
                        font-weight: bold !important;
			display:block;
			float:left;
			width:270px;
			margin-left:75px;
			font-family:'Open Sans',sans-serif;
			font-size:12px;
			font-weight:300;
			font-style:italic;
			color:#ffffff;
			line-height:10px;
                        margin-top: 0;
                        padding-top: 15px;
		}
		
		.free_version_banner .usermanual_text a,
		.free_version_banner .usermanual_text a:link,
		.free_version_banner .usermanual_text a:visited {
			display:inline-block;
			font-family:'Open Sans',sans-serif;
			font-size:17px;
			font-weight:600;
			font-style:italic;
			color:#ffffff;
			line-height:30.5px;
			text-decoration:underline;
		}
		
		.free_version_banner .usermanual_text a:hover,
		.free_version_banner .usermanual_text a:focus,
		.free_version_banner .usermanual_text a:active {
			text-decoration:underline;
		}
		
		.free_version_banner .get_full_version,
		.free_version_banner .get_full_version:link,
		.free_version_banner .get_full_version:visited {
                        padding-left: 60px;
                        padding-right: 4px;
			display: inline-block;
                        position: absolute;
                        top: 15px;
                        right: calc(50% - 167px);
                        height: 38px;
                        width: 268px;
                        border: 1px solid rgba(255,255,255,.6);
                        font-family: 'Open Sans',sans-serif;
                        font-size: 23px;
                        color: #ffffff;
                        line-height: 43px;
                        text-decoration: none;
                        border-radius: 2px;
		}
		
		.free_version_banner .get_full_version:hover {
			background:#ffffff;
			color:#bf1e2e;
			text-decoration:none;
			outline:none;
		}
		
		.free_version_banner .get_full_version:focus,
		.free_version_banner .get_full_version:active {
			
		}
		
		.free_version_banner .get_full_version:before {
			content:'';
			display:block;
			position:absolute;
			width:33px;
			height:23px;
			left:25px;
			top:9px;
			background-image:url(<?php echo $path_site2; ?>/wp_shop.png);
			background-position:0px 0px;
			background-repeat:repeat;
		}
		
		.free_version_banner .get_full_version:hover:before {
			background-position:0px -27px;
		}
		
		.free_version_banner .huge_it_logo {
			float:right;
			margin:15px 15px;
		}
		
		.free_version_banner .description_text {
                        padding:0 0 13px 0;
			position:relative;
			display:block;
			width:100%;
			text-align:center;
			float:left;
			font-family:'Open Sans',sans-serif;
			color:#fffefe;
			line-height:inherit;
		}
                .free_version_banner .description_text p{
                        margin:0;
                        padding:0;
                        font-size: 14px;
                }
		</style>
	<div class="free_version_banner">
		<img class="manual_icon" src="<?php echo $path_site2; ?>/icon-user-manual.png" alt="user manual" />
		<p class="usermanual_text">If you have any difficulties in using the options, Follow the link to <a href="http://huge-it.com/wordpress-portfolio-gallery-user-manual/" target="_blank">User Manual</a></p>
		<a class="get_full_version" href="http://huge-it.com/portfolio-gallery/" target="_blank">GET THE FULL VERSION</a>
                <a href="http://huge-it.com" target="_blank"><img class="huge_it_logo" src="<?php echo $path_site2; ?>/Huge-It-logo.png"/></a>
                <div style="clear: both;"></div>
		<div  class="description_text"><p>This is the free version of the plugin. Click "GET THE FULL VERSION" for more advanced options.   We appreciate every customer.</p></div>
	</div>
	<div id="poststuff">
		<div id="portfolios-list-page">
			<form method="post"  onkeypress="doNothing()" action="admin.php?page=portfolios_huge_it_portfolio" id="admin_form" name="admin_form">
			<h2><?php echo __( 'Huge-IT Portfolios', 'portfolio-gallery' );?>
				<a onclick="window.location.href='admin.php?page=portfolios_huge_it_portfolio&task=add_cat'" class="add-new-h2" ><?php echo __( 'Add New Portfolio', 'portfolio-gallery' );?></a>
			</h2>
			<?php
			$serch_value='';
			if(isset($_POST['serch_or_not'])) {if($_POST['serch_or_not']=="search"){ $serch_value=esc_html(stripslashes($_POST['search_events_by_title'])); }else{$serch_value="";}} 
			$serch_fields='<div class="alignleft actions"">
				<label for="search_events_by_title" style="font-size:14px">Filter: </label>
					<input type="text" name="search_events_by_title" value="'.$serch_value.'" id="search_events_by_title" onchange="clear_serch_texts()">
			</div>
			<div class="alignleft actions">
				<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
				 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
				 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=portfolios_huge_it_portfolio\'" class="button-secondary action">
			</div>';
			?>
			<table class="wp-list-table widefat fixed pages" style="width:95%">
				<thead>
				 <tr>
					<th scope="col" id="id" style="width:30px" ><span><?php echo __( 'ID', 'portfolio-gallery' );?></span><span class="sorting-indicator"></span></th>
					<th scope="col" id="name" style="width:85px" ><span><?php echo __( 'Name', 'portfolio-gallery' );?></span><span class="sorting-indicator"></span></th>
					<th scope="col" id="prod_count"  style="width:75px;" ><span><?php echo __( 'Images', 'portfolio-gallery' );?></span><span class="sorting-indicator"></span></th>
					<th style="width:40px"><?php echo __( 'Delete', 'portfolio-gallery' );?></th>
				 </tr>
				</thead>
				<tbody>
				 <?php 
				 $trcount=1;
				  for($i=0; $i<count($rows);$i++){
					$trcount++;
					$ka0=0;
					$ka1=0;
					if(isset($rows[$i-1]->id)){
						  if($rows[$i]->sl_width==$rows[$i-1]->sl_width){
						  $x1=$rows[$i]->id;
						  $x2=$rows[$i-1]->id;
						  $ka0=1;
						  }
						  else
						  {
							  $jj=2;
							  while(isset($rows[$i-$jj]))
							  {
								  if($rows[$i]->sl_width==$rows[$i-$jj]->sl_width)
								  {
									  $ka0=1;
									  $x1=$rows[$i]->id;
									  $x2=$rows[$i-$jj]->id;
									   break;
								  }
								$jj++;
							  }
						  }
						  if($ka0){
							$move_up='<span><a href="#reorder" onclick="return listItemTask(\''.$x1.'\',\''.$x2.'\')" title="Move Up">   <img src="'.plugins_url('images/uparrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Up"></a></span>';
						  }
						  else{
							$move_up="";
						  }
					}else{$move_up="";}
					
					
					if(isset($rows[$i+1]->id)){
						
						if($rows[$i]->sl_width==$rows[$i+1]->sl_width){
						  $x1=$rows[$i]->id;
						  $x2=$rows[$i+1]->id;
						  $ka1=1;
						}
						else
						{
							  $jj=2;
							  while(isset($rows[$i+$jj]))
							  {
								  if($rows[$i]->sl_width==$rows[$i+$jj]->sl_width)
								  {
									  $ka1=1;
									  $x1=$rows[$i]->id;
									  $x2=$rows[$i+$jj]->id;
									  break;
								  }
								$jj++;
							  }
						}
						
						if($ka1){
							$move_down='<span><a href="#reorder" onclick="return listItemTask(\''.$x1.'\',\''. $x2.'\')" title="Move Down">  <img src="'.plugins_url('images/downarrow.png',__FILE__).'" width="16" height="16" border="0" alt="Move Down"></a></span>';
						}else{
							$move_down="";	
						}
					}

					$uncat=$rows[$i]->par_name;
					if(isset($rows[$i]->prod_count))
						$pr_count=$rows[$i]->prod_count;
					else
						$pr_count=0;


					?>
					<tr <?php if($trcount%2==0){ echo 'class="has-background"';}?>>
						<td><?php echo $rows[$i]->id; ?></td>
						<td><a  href="admin.php?page=portfolios_huge_it_portfolio&task=edit_cat&id=<?php echo $rows[$i]->id?>"><?php echo esc_html(stripslashes($rows[$i]->name)); ?></a></td>
						<td>(<?php if(!($pr_count)){echo '0';} else{ echo $rows[$i]->prod_count;} ?>)</td>
						<td><a  href="admin.php?page=portfolios_huge_it_portfolio&task=remove_cat&id=<?php echo $rows[$i]->id?>"><?php echo __( 'Delete', 'portfolio-gallery' );?></a></td>
					</tr> 
				 <?php } ?>
				</tbody>
			</table>
			 <input type="hidden" name="oreder_move" id="oreder_move" value="" />
			 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo $_POST['asc_or_desc'];?>"  />
			 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo $_POST['order_by'];?>"  />
			 <input type="hidden" name="saveorder" id="saveorder" value="" />
<?php @session_start();
		  $hugeItCSRFToken = $_SESSION["csrf_token_hugeit_gallery"] = md5(time());
	?>
	<input type="hidden" name="csrf_token_hugeit_gallery" value="<?php echo $hugeItCSRFToken; ?>" />
			
			
		   
			</form>
		</div>
	</div>
</div>
    <?php

}
function Html_editportfolio($ord_elem, $count_ord,$images,$row,$cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat)

{
 global $wpdb;
	
	if(isset($_GET["addslide"])){
	if($_GET["addslide"] == 1){
	$strid=esc_html(stripslashes($row->id));
	header('Location: admin.php?page=portfolios_huge_it_portfolio&id='.$strid.'&task=apply');
	}
	}
		
	
?>
<script type="text/javascript">
function submitbutton(pressbutton) 
{
	if(!document.getElementById('name').value){
	alert("Name is required.");
	return;
	
	}
	filterInputs();		
	document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	document.getElementById("adminForm").submit();
	
}
var  name_changeRight = function(e) {
	document.getElementById("name").value = e.value;
}
var  name_changeTop = function(e) {
		document.getElementById("huge_it_portfolio_name").value = e.value;
		//alert(e);
	};
function change_select()
{
		submitbutton('apply'); 
	
}
	/***<add>***/

	function secondimageslistlisize(){
		var lisaze = jQuery('#images-list').width();
		lisaze=lisaze*0.06;
		jQuery('#images-list .widget-images-list li').not('.add-image-box').not('.first').height(lisaze);
	}
	
	function replaceAddImageBox() {
		jQuery(".widget-images-list").each(function(){
			var src = "";

				if(!jQuery(this).find('li').last().hasClass('add-image-box')) {
					var html = jQuery(this).find('.add-image-box').html();
					var li = jQuery('<li>');

					jQuery(this).find('.add-image-box').remove();
					li.addClass('add-image-box').append(html);
					jQuery(this).append(li);
					li.find('.add-thumb-project').css('display','block');
					li.find('.add-image-video').next().css('display','none');
					li.hover(function(){
						jQuery(this).find('.add-thumb-project').css('display','none');
						jQuery(this).find('.add-image-video').css('display','block');

					},function(){
						jQuery(this).find('.add-image-video').css('display','none');
						jQuery(this).find('.add-thumb-project').css('display','block');

					});

				}
				jQuery(this).find("li").not(".add-image-box").each(function() {										
					src += (jQuery(this).hasClass('editthisvideo')==true)?jQuery(this).find('img').attr('data-video-src'):jQuery(this).find('img').attr('src');
					src += ";";
				});
				jQuery(this).find('.all-urls').val(src);
					//alert(src);
		});
	}
	
	function filterInputs() {
		
			var mainInputs = "";
			
			jQuery("#images-list > li.highlights").each(function(){
				jQuery(this).next().addClass('submit-post');
				jQuery(this).prev().addClass('submit-post');
				jQuery(this).prev().prev().addClass('submit-post');
				jQuery(this).addClass('submit-post');
				jQuery(this).removeClass('highlights');
			})
					
			if(jQuery("#images-list > li.submit-post").length) {
				
			jQuery("#images-list > li.submit-post").each(function(){
				
				var inputs = jQuery(this).find('.order_by').attr("name");
				var n = inputs.lastIndexOf('_');
				var res = inputs.substring(n+1, inputs.length);
				res +=',';
				mainInputs += res;

			});

			mainInputs = mainInputs.substring(0,mainInputs.length-1);

				
			jQuery(".changedvalues").val(mainInputs);
			
			jQuery("#images-list > li").not('.submit-post').each(function(){
					jQuery(this).find('input').removeAttr('name');
					jQuery(this).find('textarea').removeAttr('name');
					jQuery(this).find('select').removeAttr('name');
			});
			return mainInputs;
			
			}
			jQuery("#images-list > li").each(function(){
					jQuery(this).find('input').removeAttr('name');
					jQuery(this).find('textarea').removeAttr('name');
					jQuery(this).find('select').removeAttr('name');
			});

	}
	
	/***</add>***/
jQuery(function() {

	((jQuery( "#images-list > li").length)==2)&&(jQuery( "#images-list > li")).addClass('submit-post');
	jQuery( "#images-list > li input" ).on('keyup',function(){
		jQuery(this).parents("#images-list > li").addClass('submit-post');
		//filterInputs();
	});
	jQuery( "#images-list > li textarea" ).on('keyup',function(){
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	//	filterInputs();
	});
	jQuery( "#images-list > li input" ).on('change',function(){
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	//	filterInputs();
	});
	jQuery( "#images-list > li select" ).on('change',function(){
		jQuery(this).parents("#images-list > li").addClass('submit-post');
	//	filterInputs();
	});
	jQuery('.add-thumb-project').on('hover',function(){
		jQuery(this).parent().parents("li").addClass('submit-post');
	//	filterInputs();		
	})

	jQuery( "#images-list" ).sortable({
	  stop: function() {
			jQuery("#images-list > li").removeClass('has-background');
			count=jQuery("#images-list > li").length;
			for(var i=0;i<=count;i+=2){
					jQuery("#images-list > li").eq(i).addClass("has-background");
			}
			jQuery("#images-list > li").each(function(){
				jQuery(this).find('.order_by').val(jQuery(this).index());
			});
	  },
	  change: function(event, ui) {
            var start_pos = ui.item.data('start_pos');
            var index = ui.placeholder.index();
            if (start_pos < index + 2) {
                jQuery('#images-list > li:nth-child(' + index + ')').addClass('highlights');
            } else {
                jQuery('#images-list > li:eq(' + (index + 1) + ')').addClass('highlights');
            }
      },
      update: function(event, ui) {
            jQuery('#sortable li').removeClass('highlights');
      },
	  revert: true
	});
	/***<add>***/
	jQuery( ".widget-images-list" ).sortable({
	  stop: function() {
			
			jQuery(".widget-images-list > li").each(function(){
				jQuery(this).removeClass('first');
				jQuery(".widget-images-list > li").first().addClass('first');
			});

			secondimageslistlisize();
			replaceAddImageBox();
	  },
	  change: function(event, ui) {
		  
		  	jQuery(this).parents('li').addClass('submit-post');
            var start_pos = ui.item.data('start_pos');
            var index = ui.placeholder.index();
            if (start_pos < index) {
                jQuery('.widget-images-list > li:nth-child(' + index + ')').addClass('highlights');

            } else {
                jQuery('widget-images-list > li:eq(' + (index + 1) + ')').addClass('highlights');
            }
      },
      update: function(event, ui) {
            jQuery('#sortable li').removeClass('highlights');
      },
	  revert: true
	});
	jQuery(".inside ul").sortable({
	  stop: function() {
			var allCategories = "";
			jQuery(this).find('.del_val').each(function(){
				var str = jQuery(this).val();
				str = str.replace(" ", "_");
				allCategories += str +",";
			});
			jQuery("#allCategories").val(allCategories);
	  },
	  revert: true
	});
	/***</add>***/
   // jQuery( "ul, li" ).disableSelection();
});
</script>

<!-- GENERAL PAGE, ADD IMAGES PAGE -->

	
<div class="wrap">
<?php $path_site2 = plugins_url("../images", __FILE__); ?>
	<style>
		.free_version_banner {
			position:relative;
			display:block;
			background-image:url(<?php echo $path_site2; ?>/wp_banner_bg.jpg);
			background-position:top left;
			backround-repeat:repeat;
			overflow:hidden;
		}
		
		.free_version_banner .manual_icon {
			position:absolute;
			display:block;
			top:15px;
			left:15px;
		}
		
		.free_version_banner .usermanual_text {
                        font-weight: bold !important;
			display:block;
			float:left;
			width:270px;
			margin-left:75px;
			font-family:'Open Sans',sans-serif;
			font-size:12px;
			font-weight:300;
			font-style:italic;
			color:#ffffff;
			line-height:10px;
                        margin-top: 0;
                        padding-top: 15px;
		}
		
		.free_version_banner .usermanual_text a,
		.free_version_banner .usermanual_text a:link,
		.free_version_banner .usermanual_text a:visited {
			display:inline-block;
			font-family:'Open Sans',sans-serif;
			font-size:17px;
			font-weight:600;
			font-style:italic;
			color:#ffffff;
			line-height:30.5px;
			text-decoration:underline;
		}
		
		.free_version_banner .usermanual_text a:hover,
		.free_version_banner .usermanual_text a:focus,
		.free_version_banner .usermanual_text a:active {
			text-decoration:underline;
		}
		
		.free_version_banner .get_full_version,
		.free_version_banner .get_full_version:link,
		.free_version_banner .get_full_version:visited {
                        padding-left: 60px;
                        padding-right: 4px;
			display: inline-block;
                        position: absolute;
                        top: 15px;
                        right: calc(50% - 167px);
                        height: 38px;
                        width: 268px;
                        border: 1px solid rgba(255,255,255,.6);
                        font-family: 'Open Sans',sans-serif;
                        font-size: 23px;
                        color: #ffffff;
                        line-height: 43px;
                        text-decoration: none;
                        border-radius: 2px;
		}
		
		.free_version_banner .get_full_version:hover {
			background:#ffffff;
			color:#bf1e2e;
			text-decoration:none;
			outline:none;
		}
		
		.free_version_banner .get_full_version:focus,
		.free_version_banner .get_full_version:active {
			
		}
		
		.free_version_banner .get_full_version:before {
			content:'';
			display:block;
			position:absolute;
			width:33px;
			height:23px;
			left:25px;
			top:9px;
			background-image:url(<?php echo $path_site2; ?>/wp_shop.png);
			background-position:0px 0px;
			background-repeat:repeat;
		}
		
		.free_version_banner .get_full_version:hover:before {
			background-position:0px -27px;
		}
		
		.free_version_banner .huge_it_logo {
			float:right;
			margin:15px 15px;
		}
		
		.free_version_banner .description_text {
                        padding:0 0 13px 0;
			position:relative;
			display:block;
			width:100%;
			text-align:center;
			float:left;
			font-family:'Open Sans',sans-serif;
			color:#fffefe;
			line-height:inherit;
		}
                .free_version_banner .description_text p{
                        margin:0;
                        padding:0;
                        font-size: 14px;
                }
		</style>
	<div class="free_version_banner">
		<img class="manual_icon" src="<?php echo $path_site2; ?>/icon-user-manual.png" alt="user manual" />
		<p class="usermanual_text">If you have any difficulties in using the options, Follow the link to <a href="http://huge-it.com/wordpress-portfolio-gallery-user-manual/" target="_blank">User Manual</a></p>
		<a class="get_full_version" href="http://huge-it.com/portfolio-gallery/" target="_blank">GET THE FULL VERSION</a>
                <a href="http://huge-it.com" target="_blank"><img class="huge_it_logo" src="<?php echo $path_site2; ?>/Huge-It-logo.png"/></a>
                <div style="clear: both;"></div>
		<div  class="description_text"><p>This is the free version of the plugin. Click "GET THE FULL VERSION" for more advanced options.   We appreciate every customer.</p></div>
	</div>
<form action="admin.php?page=portfolios_huge_it_portfolio&id=<?php echo esc_html(stripslashes($row->id)); ?>" method="post" name="adminForm" id="adminForm">
	<input type="hidden" class="changedvalues" value="" name="changedvalues" size="80">	
	<div id="poststuff" >
	<div id="portfolio-header">
		<ul id="portfolios-list">
			
			<?php
			foreach($rowsld as $rowsldires){
				if($rowsldires->id != $row->id){
				?>
					<li>
						<a href="#" onclick="window.location.href='admin.php?page=portfolios_huge_it_portfolio&task=edit_cat&id=<?php echo $rowsldires->id; ?>'" ><?php echo $rowsldires->name; ?></a>
					</li>
				<?php
				}
				else{ ?>
					<li class="active"  onclick = "this.firstElementChild.style.width = ((this.firstElementChild.value.length + 1) * 8) + 'px';" style="background-image:url(<?php echo plugins_url('../images/edit.png', __FILE__) ;?>);cursor:pointer;">
						<input class="text_area" onkeyup = "name_changeTop(this)" onfocus="this.style.width = ((this.value.length + 1) * 8) + 'px'" type="text" name="name" id="name" maxlength="250" value="<?php echo esc_html(stripslashes($row->name));?>" />
					</li>
				<?php	
				}
			}
		?>
			<li class="add-new">
				<a onclick="window.location.href='admin.php?page=portfolios_huge_it_portfolio&amp;task=add_cat'">+</a>
			</li>
		</ul>
		</div>
		<div id="post-body" class="metabox-holder columns-2">
			<!-- Content -->
			<div id="post-body-content">


			<?php add_thickbox(); ?>

				<div id="post-body">
					<div id="post-body-heading">
						<h3><?php echo __( 'Projects / Images', 'portfolio-gallery' );?></h3>
							<script>
jQuery(document).ready(function($){


	 

  jQuery('.huge-it-newuploader .button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
	
    var button = jQuery(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;

	jQuery("#"+id).val('');
	wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
	     jQuery("#"+id).val(attachment.url+';;;'+jQuery("#"+id).val());
		 jQuery("#save-buttom").click();
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }
  
    wp.media.editor.open(button);
	 
    return false;
  });
  
  	/*#####HIDE NEW UPLOADER'S LEFT MENU######*/  
										jQuery(".wp-media-buttons-icon").click(function() {
											jQuery(".media-menu .media-menu-item").css("display","none");
											jQuery(".media-menu-item:first").css("display","block");
											jQuery(".separator").next().css("display","none");
											jQuery('.attachment-filters').val('image').trigger('change');
											jQuery(".attachment-filters").css("display","none");
										});
	jQuery('.widget-images-list .add-image-box').hover(function() {
		jQuery(this).find('.add-thumb-project').css('display','none');
		jQuery(this).find('.add-image-video').css('display','block');
	},function() {
		jQuery(this).find('.add-image-video').css('display','none');
		jQuery(this).find('.add-thumb-project').css('display','block');
	});
	jQuery('#portfolio_effects_list').on('change',function(){
		var sel = jQuery(this).val();
		if(sel == 5) {
			jQuery('.for-content-slider').css('display','block')
		}
		else {
			jQuery('.for-content-slider').css('display','none')
		}
		});
		jQuery('#portfolio_effects_list').change();
});
</script>

						<input type="hidden" name="imagess" id="_unique_name" />
						<span class="wp-media-buttons-icon"></span>
						<div class="huge-it-newuploader uploader button button-primary add-new-image">
						<input type="button" class="button wp-media-buttons-icon" name="_unique_name_button" id="_unique_name_button" value="<?php echo __( 'Add Project / Image', 'portfolio-gallery' );?>" />
						</div>
																
						<a href="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video&id=<?php echo $_GET['id']; ?>&TB_iframe=1" class="button button-primary add-video-slide thickbox"  id="slideup3s" value="iframepop">
							<span class="wp-media-buttons-icon"></span><?php echo __( 'Add Video Slide', 'portfolio-gallery' );?>
						</a>
					</div>
					<ul id="images-list">
                                        <?php
										/***<add>***/
										function get_youtube_id_from_url($url){						
											if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
												return $match[1];
											}
										}
										function get_image_from_video($image_url) {
											if(strpos($image_url,'youtube') !== false || strpos($image_url,'youtu') !== false) {
												$liclass="youtube";
												$video_thumb_url=get_youtube_id_from_url($image_url);
												$thumburl='http://img.youtube.com/vi/'.$video_thumb_url.'/mqdefault.jpg';
											} else 
											if (strpos($image_url,'vimeo') !== false) {	
												$liclass="vimeo";
												$vimeo = $image_url;
												$vimeo_explode = explode( "/", $vimeo );
												$imgid =  end($vimeo_explode);
												$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$imgid.".php"));
												$imgsrc=$hash[0]['thumbnail_large'];
												$thumburl =$imgsrc;
											}
											return $thumburl;
										}
										/***</add>***/
                                        $j=2;
					                                        
                                        $myrows = explode(",",$row->categories);

					foreach ($rowim as $key=>$rowimages){?>
					<!--<add>  swirch case--->
					<?php if($rowimages->sl_type == ''){$rowimages->sl_type = 'image';}
						switch($rowimages->sl_type){
						case 'image':	?> 
						<li <?php if($j%2==0){echo "class='has-background'";}$j++; ?>>
							<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
							<div class="image-container">
								<ul class="widget-images-list">
									<?php $imgurl=explode(";",$rowimages->image_url);
									array_pop($imgurl);
									$i=0;
									//$imgurl = array_reverse($imgurl);
									foreach($imgurl as $key1=>$img)
									{//var_dump(youtube_or_vimeo($img));
										if(youtube_or_vimeo($img) != 'image') {?>
											<li class="editthisvideo editthisimage<?php echo $key; ?><?php if($i==0){echo 'first';} ?>" >
												<img class="editthisvideo" src="<?php echo get_image_from_video($img); ?>" data-video-src="<?php echo $img;?>"  alt = "<?php echo $img;?>" />
												<div class="play-icon <?php if (youtube_or_vimeo($img) == 'youtube') {?> youtube-icon<?php } else {?> vimeo-icon <?php }?>"></div>		
												<a class="thickbox" href="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video_edit&portfolio_id=<?php echo $rowimages->portfolio_id;?>&id=<?php echo $rowimages->id; ?>&thumb=<?php echo $i;?>&TB_iframe=1&closepop=1" id="xxx">
													<input type="button"   class="edit-video" id ="edit-video_<?php echo $rowimages->id; ?>_<?php echo $key; ?>" value="Edit" />
												</a>
												<a href="#remove" title = "<?php echo $i;?>" class="remove-image">remove</a>	
											</li>
											<?php
										}
										else {?>
											<li class="editthisimage<?php echo $key; ?> <?php if($i==0){echo 'first';} ?>">
												<img src="<?php echo $img; ?>" />
												<input type="button" class="edit-image"  id="" value="Edit" />
												<a href="#remove" title = "<?php echo $i;?>" class="remove-image">remove</a>	
											</li>
										
										<?php
										}	
										$i++; 
									} ?>
									<li class="add-image-box">
										<div class="add-thumb-project">
											<img src="<?php echo plugins_url( '../images/plus.png', __FILE__ ) ?>" class="plus" alt="" />
										</div>
										<div class="add-image-video">
											<input type="hidden" name="imagess<?php echo $rowimages->id; ?>" id="unique_name<?php echo $rowimages->id; ?>" class="all-urls" value="<?php echo $rowimages->image_url; ?>" />
											<a title="Add video"  class="add-video-slide thickbox" href="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video&id=<?php echo esc_html(stripslashes($row->id)); ?>&thumb_parent=<?php echo $rowimages->id;?>&TB_iframe=1"><!--</add> thumb parent is project's image id-->
												<img src="<?php echo plugins_url( '../images/icon-video.png', __FILE__ ) ?>" title="Add video" alt="" class="plus" />
												<input type="button" class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-video"  id="unique_name_button<?php echo $rowimages->id; ?>" value="+" />
											</a>
											<div class="add-image-slide" title="Add image">
												<img src="<?php echo plugins_url( '../images/icon-img.png', __FILE__ ) ?>" title="Add image" alt="" class="plus"  />
												<input type="button" class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-image"  id="unique_name_button<?php echo $rowimages->id; ?>" value="+" />	
											</div>
										</div>
									</li>
								</ul>
								<script>
									jQuery(document).ready(function($){
										function secondimageslistlisize(){
											var lisaze = jQuery('#images-list').width();
											lisaze=lisaze*0.06;
											jQuery('#images-list .widget-images-list li').not('.add-image-box').not('.first').height(lisaze);
										}
										jQuery(window).resize(function(){
											secondimageslistlisize()										
										});
										jQuery(".wp-media-buttons-icon").click(function() {
											jQuery(".attachment-filters").css("display","none");
										});
									  var _custom_media = true,
										  _orig_send_attachment = wp.media.editor.send.attachment;
										 
										/*#####ADD NEW PROJECT######*/ 
										jQuery('.huge-it-newuploader .button').click(function(e) {
											var send_attachment_bkp = wp.media.editor.send.attachment;
											var button = jQuery(this);
											var id = button.attr('id').replace('_button', '');
											_custom_media = true;

											jQuery("#"+id).val('');
											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {
												 jQuery("#"+id).val(attachment.url+';;;'+jQuery("#"+id).val());
												 jQuery("#save-buttom").click();
											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}
											wp.media.editor.open(button);
											return false;
										});
										  
										jQuery('.widget-images-list').on('click','.edit-image',function(e) {
											jQuery(this).parents("#images-list > li").addClass('submit-post');
											var send_attachment_bkp = wp.media.editor.send.attachment;
											var $src;
											var button = jQuery(this);
											var id = button.parents('.widget-images-list').find('.all-urls').attr('id');
											var img= button.prev('img');
											_custom_media = true;
											jQuery(".media-menu .media-menu-item").css("display","none");
											jQuery(".media-menu-item:first").css("display","block");
											jQuery(".separator").next().css("display","none");
											jQuery('.attachment-filters').val('image').trigger('change');
											jQuery(".attachment-filters").css("display","none");
											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {	 
												 img.attr('src',attachment.url);
												 var allurls ='';
												 img.parents('.widget-images-list').find('img').not('.plus').each(function(){
													if(jQuery(this).hasClass('editthisvideo')) {
														$src = jQuery(this).attr('data-video-src');
													}
													else $src = jQuery(this).attr('src');
													console.log($src);
													allurls = allurls+$src+';';
												 });
												 jQuery("#"+id).val(allurls);
												 secondimageslistlisize();
												 //jQuery("#save-buttom").click();
											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}
											wp.media.editor.open(button);
											return false;
										});

										jQuery('.add_media').on('click', function(){
											_custom_media = false;
										});
										 /*#####ADD IMAGE######*/  
										jQuery('.add-image.button<?php echo $rowimages->id; ?>').click(function(e) {
											jQuery(this).parents("#images-list > li").addClass('submit-post');

											var send_attachment_bkp = wp.media.editor.send.attachment;

											var button = jQuery(this);
											var id = button.attr('id').replace('_button', '');
											_custom_media = true;

											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {
													jQuery("#"+id).parent().parent().before('<li class="editthisimage1 "><img src="'+attachment.url+'" alt="" /><input type="button" class="edit-image"  id="" value="Edit" /><a href="#remove" class="remove-image">remove</a></li>');
													//alert(jQuery("#"+id).val());
													jQuery("#"+id).val(jQuery("#"+id).val()+attachment.url+';');
													
													secondimageslistlisize();

											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}

											wp.media.editor.open(button);
											 
											return false;
										});

										
										/*#####REMOVE IMAGE######*/  
										jQuery("ul.widget-images-list").on('click','.remove-image',function () {	
											jQuery(this).parents("#images-list > li").addClass('submit-post');

											jQuery(this).parent().find('img').remove();
											
											var allUrls="";
											var $src;
											
											jQuery(this).parents('ul.widget-images-list').find('img').not('.plus').each(function(){
												//console.log(jQuery(this).parent());
												if(jQuery(this).hasClass('editthisvideo')) {
													$src = jQuery(this).attr('data-video-src');
												}
												else $src = jQuery(this).attr('src');
												console.log($src);
												allUrls=allUrls+$src+';';
												jQuery(this).parent().parent().parent().find('input.all-urls').val(allUrls);
												secondimageslistlisize();
											});
											jQuery(this).parent().remove();
											return false;
										});
										

										/*#####HIDE NEW UPLOADER'S LEFT MENU######*/  
										jQuery(".wp-media-buttons-icon").click(function() {
											jQuery(".media-menu .media-menu-item").css("display","none");
											jQuery(".media-menu-item:first").css("display","block");
											jQuery(".separator").next().css("display","none");
											jQuery('.attachment-filters').val('image').trigger('change');
											jQuery(".attachment-filters").css("display","none");
										});
									});
								</script>
							</div>
							<div class="image-options">
								<div class="options-container">
									<div>
										<label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __( 'Title', 'portfolio-gallery' );?>:</label>
										<input  class="text_area" type="text" id="titleimage<?php echo $rowimages->id; ?>" name="titleimage<?php echo $rowimages->id; ?>" id="titleimage<?php echo $rowimages->id; ?>"  value="<?php echo htmlspecialchars($rowimages->name); ?>">
									</div>
									<div class="description-block">
										<label for="im_description<?php echo $rowimages->id; ?>"><?php echo __( 'Description', 'portfolio-gallery' );?>:</label>
										<textarea id="im_description<?php echo $rowimages->id; ?>" name="im_description<?php echo $rowimages->id; ?>" ><?php echo $rowimages->description; ?></textarea>
									</div>
									<div class="link-block">
										<label for="sl_url<?php echo $rowimages->id; ?>"><?php echo __( 'URL', 'portfolio-gallery' );?>:</label>
										<input class="text_area url-input" type="text" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo $rowimages->sl_url; ?>" >
										<label class="long" for="sl_link_target<?php echo $rowimages->id; ?>">
											<span><?php echo __( 'Open in new tab', 'portfolio-gallery' );?></span>
											<input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
											<input  <?php if($rowimages->link_target == 'on'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />
										</label>
									</div>
								</div>
								<div class="category-container">
                                                                    <strong><?php echo __( 'Select Categories', 'portfolio-gallery' );?></strong>
                                                                    <em>(<?php echo __( 'Press Ctrl And Select multiply', 'portfolio-gallery' );?>)</em>
                                                                    <select id="multipleSelect" multiple="multiple" disabled>
                                                                            <?php           //    var_dump($huge_cat);
                                                                            $huge_cat = explode(",",$rowimages->category);
                                                                            foreach ($myrows as $value) {
                                                                                if(!empty($value))
                                                                                { ?>
                                                                                    <option <?php if(in_array(str_replace(' ','_',$value),str_replace(' ','_',$huge_cat))) { echo "selected='selected' "; } ?> value="<?php echo str_replace(' ','_',$value); ?>" > <!-- attrForDelete="<?php// echo str_replace(" ","_",$value); ?>" -->
                                                                                        <?php echo str_replace('_',' ',$value); ?>
                                                                                    </option>
                                                                                <?php
                                                                                }
                                                                            }     ?>
                                                                    }
                                                                    </select>
                                                                    <input type="hidden" id="category<?php echo $rowimages->id; ?>" name="category<?php echo $rowimages->id; ?>" value="<?php echo str_replace(' ','_',$rowimages->category); ?>"/>
								</div>
								<div class="remove-image-container">
									<a class="button remove-image" href="admin.php?page=portfolios_huge_it_portfolio&id=<?php echo esc_html(stripslashes($row->id)); ?>&task=apply&removeslide=<?php echo $rowimages->id; ?>"><?php echo __( 'Remove Project', 'portfolio-gallery' );?></a>
								</div>
							</div>                 
							<div class="clear"></div>
						</li>
					<?php 
						break;
						case 'video'://$i = 0;?>
						<!--<add>-->
							<li <?php if($j%2==0){echo "class='has-background'";}$j++; ?>  >
							<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
							<div class="video-container">
								<ul class="widget-images-list">
									<?php $imgurl=explode(";",$rowimages->image_url);
									array_pop($imgurl);
									$i=0;
									
									//$imgurl = array_reverse($imgurl);
									foreach($imgurl as $key1=>$img)
									{//var_dump(youtube_or_vimeo($img));
										if(youtube_or_vimeo($img) != 'image') {?>
											<li class="editthisvideo editthisimage<?php echo $key; ?> <?php if($i==0){echo 'first';} ?>" >
												<img class="editthisvideo" src="<?php echo get_image_from_video($img); ?>"  data-video-src="<?php echo $img;?>"  alt = "<?php echo $img;?>"/>
												<div class="play-icon <?php if (youtube_or_vimeo($img) == 'youtube') {?> youtube-icon<?php } else {?> vimeo-icon <?php }?>"></div>		
												<a class="thickbox" href="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video_edit&portfolio_id=<?php echo $rowimages->portfolio_id;?>&id=<?php echo $rowimages->id; ?>&thumb=<?php echo $i;?>&TB_iframe=1&closepop=1" id="xxx">
													<input type="button"   class="edit-video" id ="edit-video_<?php echo $rowimages->id; ?>_<?php echo $key; ?>" value="Edit" />
												</a>
												<a href="#remove" title = "<?php echo $i;?>" class="remove-image">remove</a>	
											</li>
											<?php
										}
										else {?>
											<li class="editthisimage<?php echo $key; ?> <?php if($i==0){echo 'first';} ?>">
												<img src="<?php echo $img; ?>" />
												<input type="button" class="edit-image"  id="" value="Edit" />
												<a href="#remove" title = "<?php echo $i;?>" class="remove-image">remove</a>	
											</li>
										
										<?php
										}	
										$i++; 
									} ?>

									<li class="add-image-box">
										<div class="add-thumb-project">
											<img src="<?php echo plugins_url( '../images/plus.png', __FILE__ ) ?>" class="plus" alt="" />
										</div>
										<div class="add-image-video">
											<input type="hidden" name="imagess<?php echo $rowimages->id; ?>" id="unique_name<?php echo $rowimages->id; ?>" class="all-urls" value="<?php echo $rowimages->image_url; ?>" />
											<a title="Add video"  class="add-video-slide thickbox" href="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video&id=<?php echo esc_html(stripslashes($row->id)); ?>&thumb_parent=<?php echo $rowimages->id;?>&TB_iframe=1"><!--</add> thumb parent is project's image id-->
												<img src="<?php echo plugins_url( '../images/icon-video.png', __FILE__ ) ?>" title="Add video" alt="" class="plus" />
												<input type="button" class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-video"  id="unique_name_button<?php echo $rowimages->id; ?>" value="+" />
											</a>
											<div class="add-image-slide" title="Add image">
												<img src="<?php echo plugins_url( '../images/icon-img.png', __FILE__ ) ?>" title="Add image" alt="" class="plus"  />
												<input type="button" class="button<?php echo $rowimages->id; ?> wp-media-buttons-icon add-image"  id="unique_name_button<?php echo $rowimages->id; ?>" value="+" />	
											</div>
										</div>
									</li>
								</ul>
								<script>
									jQuery(document).ready(function($){

										(function secondimageslistlisize(){
											var lisaze = jQuery('#images-list').width();
											lisaze=lisaze*0.06;
											jQuery('#images-list .widget-images-list li').not('.add-image-box').not('.first').height(lisaze);
										}());
										jQuery(window).resize(function(){
											secondimageslistlisize()										
										});
										jQuery(".wp-media-buttons-icon").click(function() {
											jQuery(".attachment-filters").css("display","none");
										});
									  var _custom_media = true,
										  _orig_send_attachment = wp.media.editor.send.attachment;
										 
										/*#####ADD NEW PROJECT######*/ 
										jQuery('.huge-it-newuploader .button').click(function(e) {
											var send_attachment_bkp = wp.media.editor.send.attachment;
											var button = jQuery(this);
											var id = button.attr('id').replace('_button', '');
											_custom_media = true;

											jQuery("#"+id).val('');
											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {
												 jQuery("#"+id).val(attachment.url+';;;'+jQuery("#"+id).val());
												 jQuery("#save-buttom").click();
											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}
											wp.media.editor.open(button);
											return false;
										});
										  
										/*#####EDIT IMAGE######*/  
										jQuery('.widget-images-list').on('click','.edit-image',function(e) {
											jQuery(this).parents("#images-list > li").addClass('submit-post');
											var send_attachment_bkp = wp.media.editor.send.attachment;
											var $src;
											var button = jQuery(this);
											var id = button.parents('.widget-images-list').find('.all-urls').attr('id');
											var img= button.prev('img');
											_custom_media = true;
											jQuery(".media-menu .media-menu-item").css("display","none");
											jQuery(".media-menu-item:first").css("display","block");
											jQuery(".separator").next().css("display","none");
											jQuery('.attachment-filters').val('image').trigger('change');
											jQuery(".attachment-filters").css("display","none");
											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {	 
												 img.attr('src',attachment.url);
												 var allurls ='';
												 img.parents('.widget-images-list').find('img').not('.plus').each(function(){
													if(jQuery(this).hasClass('editthisvideo')) {
														$src = jQuery(this).attr('data-video-src');
													}
													else $src = jQuery(this).attr('src');
													console.log($src);
													allurls = allurls+$src+';';
												 });
												 jQuery("#"+id).val(allurls);
												 secondimageslistlisize();
												 //jQuery("#save-buttom").click();
											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}
											wp.media.editor.open(button);
											return false;
										});

										jQuery('.add_media').on('click', function(){
											_custom_media = false;
										});
										
										 /*#####ADD IMAGE######*/  
										jQuery('.add-image.button<?php echo $rowimages->id; ?>').click(function(e) {
											jQuery(this).parents("#images-list > li").addClass('submit-post');
											var send_attachment_bkp = wp.media.editor.send.attachment;

											var button = jQuery(this);
											var id = button.attr('id').replace('_button', '');
											_custom_media = true;

											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {
													jQuery("#"+id).parent().parent().before('<li class="editthisimage1 "><img src="'+attachment.url+'" alt="" /><input type="button" class="edit-image"  id="" value="Edit" /><a href="#remove" class="remove-image">remove</a></li>');
													//alert(jQuery("#"+id).val());
													jQuery("#"+id).val(jQuery("#"+id).val()+attachment.url+';');
													
													secondimageslistlisize();

											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}

											wp.media.editor.open(button);
											 
											return false;
										});

										
										/*#####REMOVE IMAGE######*/  
										jQuery("ul.widget-images-list").on('click','.remove-image',function () {	
											jQuery(this).parents("#images-list > li").addClass('submit-post');
											jQuery(this).parent().find('img').remove();
											
											var allUrls="";
											var $src;
											
											jQuery(this).parents('ul.widget-images-list').find('img').not('.plus').each(function(){
												//console.log(jQuery(this).parent());
												if(jQuery(this).hasClass('editthisvideo')) {
													$src = jQuery(this).attr('data-video-src');
												}
												else $src = jQuery(this).attr('src');
												console.log($src);
												allUrls=allUrls+$src+';';
												jQuery(this).parent().parent().parent().find('input.all-urls').val(allUrls);
												secondimageslistlisize();
											});
											jQuery(this).parent().remove();
											return false;
										});
										

										/*#####HIDE NEW UPLOADER'S LEFT MENU######*/  
										jQuery(".wp-media-buttons-icon").click(function() {
											jQuery(".media-menu .media-menu-item").css("display","none");
											jQuery(".media-menu-item:first").css("display","block");
											jQuery(".separator").next().css("display","none");
											jQuery('.attachment-filters').val('image').trigger('change');
											jQuery(".attachment-filters").css("display","none");
										});
										/*jQuery("ul.widget-images-list").on('click','.remove-video',function () {
											var new_video_list,del_video_number,old_video_list,old_video_array;
											
											new_video_list = "";
											del_video_number = jQuery(this).attr("title");
											old_video_list = jQuery(this).parent().parent().find('input.all-urls').val();	
											old_video_array = old_video_list.split(";");console.log(old_video_array);
	
											for(var video in old_video_array) {
												if(video==del_video_number) 
													continue;
													new_video_list += old_video_array[video]+";";

											}
											
											new_video_list = new_video_list.substr(0,new_video_list.length-1);
											
											jQuery(this).parent().parent().find('input.video-all-urls').val(new_video_list);

											jQuery(this).parent().remove();
											return;
										});*/
									});

								</script>
							</div>
								<div class="image-options">
								<div class="options-container">
									<div>
										<label for="titleimage<?php echo $rowimages->id; ?>"><?php echo __( 'Title', 'portfolio-gallery' );?>:</label>
										<input  class="text_area" type="text" id="titleimage<?php echo $rowimages->id; ?>" name="titleimage<?php echo $rowimages->id; ?>" id="titleimage<?php echo $rowimages->id; ?>"  value="<?php echo htmlspecialchars($rowimages->name); ?>">
									</div>
									<div class="description-block">
										<label for="im_description<?php echo $rowimages->id; ?>"><?php echo __( 'Description', 'portfolio-gallery' );?>:</label>
										<textarea id="im_description<?php echo $rowimages->id; ?>" name="im_description<?php echo $rowimages->id; ?>" ><?php echo $rowimages->description; ?></textarea>
									</div>
									<div class="link-block">
										<label for="sl_url<?php echo $rowimages->id; ?>"><?php echo __( 'URL', 'portfolio-gallery' );?>:</label>
										<input class="text_area url-input" type="text" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo $rowimages->sl_url; ?>" >
										<label class="long" for="sl_link_target<?php echo $rowimages->id; ?>">
											<span><?php echo __( 'Open in new tab', 'portfolio-gallery' );?></span>
											<input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
											<input  <?php if($rowimages->link_target == 'on'){ echo 'checked="checked"'; } ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />
										</label>
									</div>
								</div>
								<div class="category-container">
                                                                    <strong><?php echo __( 'Select Categories', 'portfolio-gallery' );?></strong>
                                                                    <em>(<?php echo __( 'Press Ctrl And Select multiply', 'portfolio-gallery' );?>)</em>
                                                                    <select id="multipleSelect" multiple="multiple" disabled >
                                                                            <?php
                                                                            $huge_cat = explode(",",$rowimages->category);
                                                                            foreach ($myrows as $value) {
                                                                                if(!empty($value))
                                                                                { ?>
                                                                                    <option <?php if(in_array(str_replace(' ','_',$value),str_replace(' ','_',$huge_cat))) { echo "selected='selected' "; } ?> value="<?php echo str_replace(' ','_',$value); ?>" > <!-- attrForDelete="<?php// echo str_replace(" ","_",$value); ?>" -->
                                                                                        <?php echo str_replace('_',' ',$value); ?>
                                                                                    </option>
                                                                                <?php
                                                                                }
                                                                            }     ?>
                                                                    }
                                                                    </select>
                                                                    <input type="hidden" id="category<?php echo $rowimages->id; ?>" name="category<?php echo $rowimages->id; ?>" value="<?php echo str_replace(' ','_',$rowimages->category); ?>"/>
								</div>
								<div class="remove-image-container">
									<a class="button remove-image" href="admin.php?page=portfolios_huge_it_portfolio&id=<?php echo esc_html(stripslashes($row->id)); ?>&task=apply&removeslide=<?php echo $rowimages->id; ?>"><?php echo __( 'Remove Project', 'portfolio-gallery' );?></a>
								</div>
							</div>              
							<div class="clear"></div>
							</li>
							<!--</add>-->
						<?php break;?>
					<?php	} ?>
					<?php } ?>
					</ul>
				</div>

			</div>
				
                        <script>
//                                    jQuery('.category-container select').change(function(){
//                                    var cat_new_val = jQuery(this).val();
//                                    var new_cat_name = jQuery(this).parent().find('input').attr('name');
//                                    jQuery('#'+new_cat_name).attr('value',cat_new_val+',');
//                                    //console.log(cat_new_val);  console.log(new_cat_name);
//                                    });
                                      //ok  
//                                    jQuery(document).on('click', '#add_new_cat_buddon', function () {
//                                       var newCatVal =  jQuery('.inside #add_cat_input input').val(); 
//                                       if(newCatVal !== "") {
//                                           var oldValue = jQuery('.inside input:hidden').val()
//                                           var newValue = oldValue + newCatVal + ',';
//                                           //console.log(newCatVal); console.log(newValue); console.log(oldValue);
//                                           jQuery('.inside input:hidden').val(newValue.replace(/ /g,"_"));
//                                           jQuery('.inside #add_cat_input input').val('');
//                                           jQuery('.inside ul').find('#allCategories').before("\n\
//                                                        <span style='display: block;'>\n\
//                                                            <li class='hndle'>\n\
//                                                                <input class='del_val' value='"+newCatVal+"' style=''>\n\
//                                                                <span id='delete_cat' style='' value='a'>\n\
//                                                                    <img src='../wp-content/plugins/portfolio-gallery/images/delete1.png' width='9' height='9' value='a'>\n\
//                                                                </span>\n\
//                                                                <span id='edit_cat' style=''>\n\
//                                                                    <img src='../wp-content/plugins/portfolio-gallery/images/edit3.png' width='10' height='10'>\n\
//                                                                </span>\n\
//                                                            </li>\n\
//                                                       </span>");
//                                                                
//                                          jQuery('.category-container #multipleSelect').each(function(){
//                                              jQuery(this).append("<option attrForDelete='"+newCatVal+"'>"+newCatVal+"</option>");
//                                          });
//                                       }
//                                       else { alert("Please fill the line"); }
//                                    });

//                                        jQuery(document).on('click', '#delete_cat', function (){
//                                            var del_val = jQuery(this).parent().find('.del_val').val().replace(/ /g, '_');
//                                            del_val = del_val + ",";
//                                            var old_val_for_delete = jQuery('.inside input:hidden').val();
//                                            var newValue = old_val_for_delete.replace(del_val, "");
//                                            jQuery('.inside input:hidden').val(newValue);
//                                            jQuery(this).parent().parent().find('.hndle').remove();
//                                            var valForDelete = del_val.replace(',', '').replace(/ /g, '_');
//                                            jQuery('.category-container').each(function(){
//                                                jQuery(this).find('option[value='+valForDelete+']').remove();
//                                            });
//                                             //console.log(del_val); console.log(old_val_for_delete); console.log(newValue); console.log(valForDelete);
//                                        });


//                                        jQuery(document).on('click', '#edit_cat', function (){
//                                            jQuery(this).parent().find('.del_val').focus();
//                                            var changing_val = jQuery(this).parent().find('.del_val').val().replace(/ /g, '_');
//                                            jQuery('#changing_val').removeAttr('value').attr('value',changing_val);
//                                            //console.log(changing_val);
//                                        });
//
                                        
                                        jQuery(document).on('click', '#portfolios-list .active', function (){
                                            jQuery(this).find('input').focus();                                         
                                        });
                                        
                                        //getting category old name
//                                        jQuery(document).on('focus', '.del_val', function (){ // Know which category we want to change 
//                                                var changing_val = jQuery(this).val().replace(/ /g,"_");  //console.log(changing_val);
//                                                jQuery('#changing_val').removeAttr('value').attr('value',changing_val);
//                                        });
                                        
//                                        jQuery(document).on('change', '.del_val', function (){
//                                            //alert("ok")
//                                                var no_edited_cats = jQuery("#allCategories").val().replace(/ /g,"_");
//                                                var old_name = jQuery('#changing_val').val();
//                                                var edited_cat = jQuery(this).val();
//                                                edited_cat = edited_cat.replace(/ /g,"_");
//                                                var new_cat = no_edited_cats.replace(old_name,edited_cat);
//                                                jQuery('#allCategories').val(new_cat);  // console.log(no_edited_cats); console.log(old_name); console.log(edited_cat); console.log(new_cat);
//                                        });
                                        jQuery(document).on('click', '.allowIsotope input', function (){
                                            
                                        });
                        </script>
                        
			<!-- SIDEBAR -->
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="portfolio-unique-options" class="postbox">
					<h3 class="hndle"><span><?php echo __( 'Select The Portfolio/Gallery View', 'portfolio-gallery' );?></span></h3>
					<ul id="portfolio-unique-options-list">
						<li>
							<label for="huge_it_portfolio_name"><?php echo __( 'Portfolio Name', 'portfolio-gallery' );?></label>
							<input type = "text" name="name" id="huge_it_portfolio_name" value="<?php echo esc_html(stripslashes($row->name));?>" onkeyup = "name_changeRight(this)">
						</li>
						<li style="display:none;">
							<label for="sl_width"><?php echo __( 'Width', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_width" id="sl_width" value="<?php echo $row->sl_width; ?>" class="text_area" />
						</li>
						<li style="display:none;">
							<label for="sl_height"><?php echo __( 'Height', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_height" id="sl_height" value="<?php echo $row->sl_height; ?>" class="text_area" />
						</li>
						<li style="display:none;">
							<label for="pause_on_hover"><?php echo __( 'Pause on hover', 'portfolio-gallery' );?></label>
							<input type="hidden" value="off" name="pause_on_hover" />					
							<input type="checkbox" name="pause_on_hover"  value="on" id="pause_on_hover"  <?php if($row->pause_on_hover  == 'on'){ echo 'checked="checked"'; } ?> />
						</li>
						<li>
							<label for="portfolio_effects_list"><?php echo __( 'Select The View', 'portfolio-gallery' );?></label>
							<select name="portfolio_effects_list" id="portfolio_effects_list">
									<option <?php if($row->portfolio_list_effects_s == '0'){ echo 'selected'; } ?>  value="0"><?php echo __( 'Blocks Toggle Up/Down', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '1'){ echo 'selected'; } ?>  value="1"><?php echo __( 'Full-Height Blocks', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '2'){ echo 'selected'; } ?>  value="2"><?php echo __( 'Gallery/Content-Popup', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '3'){ echo 'selected'; } ?>  value="3"><?php echo __( 'Full-Width Blocks', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '4'){ echo 'selected'; } ?>  value="4"><?php echo __( 'FAQ Toggle Up/Down', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '5'){ echo 'selected'; } ?>  value="5"><?php echo __( 'Content Slider', 'portfolio-gallery' );?></option>
									<option <?php if($row->portfolio_list_effects_s == '6'){ echo 'selected'; } ?>  value="6"><?php echo __( 'Lightbox-Gallery', 'portfolio-gallery' );?></option>
							</select>
						</li>

						<li style="display:none;" class="for-content-slider">
							<label for="sl_pausetime"><?php echo __( 'Pause time', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_pausetime" id="sl_pausetime" value="<?php echo $row->description; ?>" class="text_area" />
						</li>
						<li style="display:none;"  class="for-content-slider">
							<label for="sl_changespeed"><?php echo __( 'Change speed', 'portfolio-gallery' );?></label>
							<input type="text" name="sl_changespeed" id="sl_changespeed" value="<?php echo $row->param; ?>" class="text_area" />
						</li style="display:none;"  class="for-content-slider">
						<!--<li style="display:none;"  class="for-content-slider" >
							<label for="slider_effect">Slider Effect</label>
							<select name="sl_position" id="slider_effect">
									<option <?php if($row->sl_position == ''){ echo 'selected'; }?> value="">None</option>
									<option <?php if($row->sl_position == 'fadeOut_flipInX'){ echo 'selected'; }?> value="fadeOut_flipInX">Fade</option>
									<option <?php if($row->sl_position == 'bounceOutUp_bounceInDown'){ echo 'selected'; }?> value="bounceOutUp_bounceInDown">Bounce</option>
									<option <?php if($row->sl_position == 'rotateOutDownRight_rotateInDownRight'){ echo 'selected'; }?> value="rotateOutDownRight_rotateInDownRight">Rotate</option>
									<option <?php if($row->sl_position == 'rollOut_rollIn'){ echo 'selected'; }?> value="rollOut_rollIn">Roll</option>
									<option <?php if($row->sl_position == 'fadeOut_lightSpeedIn'){ echo 'selected'; }?> value="fadeOut_lightSpeedIn">LightSpeed</option>
							</select>
						</li>-->
						<li style="display:none;margin-top:10px"  class="for-content-slider">
							<label for="pause_on_hover">Autoslide </label>
							<input type="hidden" value="off" name="pause_on_hover" />					
							<input type="checkbox" name="pause_on_hover"  value="on" id="pause_on_hover"  <?php if($row->pause_on_hover  == 'on'){ echo 'checked="checked"'; } ?> />
						</li>


					</ul>
						<div id="major-publishing-actions">
							<div id="publishing-action">
								<input type="button" onclick="submitbutton('apply')" value="Save Portfolio" id="save-buttom" class="button button-primary button-large">
							</div>
							<div class="clear"></div>
							<!--<input type="button" onclick="window.location.href='admin.php?page=portfolios_huge_it_portfolio'" value="Cancel" class="button-secondary action">-->
						</div>
					</div>
                                    
                                        <div class="postbox">
                                            <div class="inside2">
                                                <ul>
                                                    <li class="allowIsotope">
                                                        <?php echo __( 'Show Sorting Buttons', 'portfolio-gallery' );?> :
                                                        <input type="hidden" value="off" name="ht_show_sorting" />
							<input type="checkbox" id="ht_show_sorting"  <?php if($row->ht_show_sorting  == 'on'){ echo 'checked="checked"'; } ?>  name="ht_show_sorting" value="on" />
                                                    </li>
                                                    <li class="allowIsotope">
                                                        <?php echo __( 'Show Category Buttons', 'portfolio-gallery' );?> :
                                                        <input type="hidden" value="off" name=" " />
                                                        <input type="checkbox" id=" " name=" " value="off" disabled="disabled" />
                                                        <a class="probuttonlink" href="http://huge-it.com/portfolio-gallery/" target="_blank">( <span style="color: red;font-size: 14px;"> PRO </span> )</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <div class="postbox">
                                            <h3 class="hndle"><span><?php echo __( 'Categories', 'portfolio-gallery' );?>:</span>&nbsp;&nbsp;<a class="probuttonlink" href="http://huge-it.com/portfolio-gallery/"  target="_blank">( <span style="color: red;font-size: 14px;"> PRO </span> )</a></h3>
                                            <div class="inside">
                                                <ul>
                                                <?php
                                                $ifforempty= $row->categories;
                                                $ifforempty= stripslashes($ifforempty);
                                                $ifforempty= esc_html($ifforempty);
                                                $ifforempty= empty($ifforempty);				
                                                if(!($ifforempty))
                                                {
                                                    foreach ($myrows as $value) {
                                                        if(!empty($value))
                                                        {
                                                        ?>
                                                            <span>
                                                                <li class="hndle">
                                                                    <input class="del_val" value="<?php echo str_replace("_", " ", $value); ?>" style="" disabled="disabled">
                                                                    <span id="delete_cat" style="" value="a"><img src="../wp-content/plugins/portfolio-gallery/images/delete1.png" width="9" height="9" value="a"></span>
                                                                    <span id="edit_cat" style=""><img src="../wp-content/plugins/portfolio-gallery/images/edit3.png" width="10" height="10"></span>
                                                                </li>
                                                            </span>
                                                        <?php
                                                        }
                                                    }
                                                }

                                                    ?>
                                                    <input type="hidden" value="<?php if (strpos($row->categories,',,') !== false)  { $row->categories = str_replace(",,",",",$row->categories); }echo $row->categories; ?>" id="allCategories" name="allCategories">
                                                    <li id="add_cat_input" style="">
                                                        <input type="text" size="12" disabled="disabled">
                                                        <a style="" id="add_new_cat_buddon">+ <?php echo __( 'Add New Category', 'portfolio-gallery' );?>	</a>
                                                    </li>
                                                </ul>
                                                <input type="hidden" value="" id="changing_val">
                                            </div>
                                        </div>
                                        
					<div id="portfolio-shortcode-box" class="postbox shortcode ms-toggle">
					<h3 class="hndle"><span><?php echo __( 'Usage', 'portfolio-gallery' );?></span></h3>
					<div class="inside">
						<ul>
							<li rel="tab-1" class="selected">
								<h4><?php echo __( 'Shortcode', 'portfolio-gallery' );?></h4>
								<p><?php echo __( 'Copy &amp; paste the shortcode directly into any WordPress post or page.', 'portfolio-gallery' );?></p>
								<textarea class="full" readonly="readonly">[huge_it_portfolio id="<?php echo esc_html(stripslashes($row->id)); ?>"]</textarea>
							</li>
							<li rel="tab-2">
								<h4><?php echo __( 'Template Include', 'portfolio-gallery' );?></h4>
								<p><?php echo __( 'Copy &amp; paste this code into a template file to include the slideshow within your theme.', 'portfolio-gallery' );?></p>
								<textarea class="full" readonly="readonly">&lt;?php echo do_shortcode("[huge_it_portfolio id='<?php echo esc_html(stripslashes($row->id)); ?>']"); ?&gt;</textarea>
							</li>
						</ul>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php @session_start();
		  $hugeItCSRFToken = $_SESSION["csrf_token_hugeit_gallery"] = md5(time());
	?>
	<input type="hidden" name="csrf_token_hugeit_gallery" value="<?php echo $hugeItCSRFToken; ?>" />
</form>
</div>

<?php

}
?>
<?php
/***<add>***/
function Html_portfolio_video(){
	global $wpdb;
	
	//var_dump("port");exit;
?>
	<style>
		html.wp-toolbar {
			padding:0px !important;
		}
		#wpadminbar,#adminmenuback,#screen-meta, .update-nag,#dolly {
			display:none;
		}
		#wpbody-content {
			padding-bottom:30px;
		}
		#adminmenuwrap {display:none !important;}
		.auto-fold #wpcontent, .auto-fold #wpfooter {
			margin-left: 0px;
		}
		#wpfooter {display:none;}
		iframe {height:250px !important;}
		#TB_window {height:250px !important;}
	</style>
	<script type="text/javascript">
		function youtube_parser(url){
			var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
			var match = url.match(regExp); 
			var match_vimeo = /vimeo.*\/(\d+)/i.exec( url );
			if (match&&match[7].length==11){
				return match[7];
			}else if(match_vimeo) {
				    return match_vimeo[1];
			}
			else {
				return false;
			}
		}

		jQuery(document).ready(function() {			
		
		jQuery('.huge-it-insert-video-button').click(function(){
				var ID1 = jQuery('#huge_it_add_video_input').val();
				if(ID1==""){alert("Please copy and past url form Youtube or Vimeo to insert into slider.");return false;}
				if (youtube_parser(ID1) == false) {
					alert("Url is incorrect");
					return false;
				}			
				jQuery('.huge-it-insert-post-button').on('click', function() {
				var ID1 = jQuery('#huge_it_add_video_input').val();
				if(ID1==""){alert("Please copy and past url form youtube or Vimeo to insert into slider.");return false;}
				
				window.parent.uploadID.val(ID1);
				
				tb_remove();
			//	jQuery("#save-buttom").click();
			});

			jQuery('#huge_it_add_video_input').change(function(){
				
				if (jQuery(this).val().indexOf("youtube") >= 0){
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .youtube').addClass('active');
				}else if (jQuery(this).val().indexOf("vimeo") >= 0){
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .vimeo').addClass('active');
				}else {
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .error-message').addClass('active');
				}
			})	
				});
					<?php	
			if(isset($_GET["closepop"])){
			if($_GET["closepop"] == 1){ ?>
					jQuery("#closepopup").click();
					self.parent.location.reload();
			<?php	}	} ?>
			jQuery('.updated').css({"display":"none"});
		});
	</script>
	<a id="closepopup"  onclick=" parent.eval('tb_remove()')" style="display:none;"> [X] </a>

	<div id="huge_it_slider_add_videos">
		<div id="huge_it_slider_add_videos_wrap">
			<h2><?php echo __( 'Add Video URL From Youtube or Vimeo', 'portfolio-gallery' );?></h2>
			<div class="control-panel">
			<?php if (!isset($_GET['thumb_parent'])) { ?>
				<form method="post" action="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video&id=<?php echo $_GET['id']; ?>&closepop=1" >
					<input type="text" id="huge_it_add_video_input" name="huge_it_add_video_input" />
					<button class='save-slider-options button-primary huge-it-insert-video-button' id='huge-it-insert-video-button'><?php echo __( 'Insert Video Slide', 'portfolio-gallery' );?></button>
					<div id="add-video-popup-options">
						<div>
							<div>
								<label for="show_title"><?php echo __( 'Title', 'portfolio-gallery' );?>:</label>	
								<div>
									<input name="show_title" value="" type="text" />
								</div>
							</div>
							<div>
								<label for="show_description"><?php echo __( 'Description', 'portfolio-gallery' );?>:</label>
								<textarea id="show_description" name="show_description"></textarea>
							</div>
							<div>
								<label for="show_url"><?php echo __( 'Url', 'portfolio-gallery' );?>:</label>
								<input type="text" name="show_url" value="" />	
							</div>
						</div>
					</div>
				</form>
			<?php } else { $thumb_parent = $_GET["thumb_parent"] //get project image's id and sent to form by get ,who addes thumb_video by $_get thumb_parent ?>
				<form method="post" action="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video&id=<?php echo $_GET['id']; ?>&thumb_parent=<?php echo $thumb_parent ; ?>&closepop=1" >
					<input type="text" id="huge_it_add_video_input" name="huge_it_add_video_input" />
					<button class='save-slider-options button-primary huge-it-insert-video-button' id='huge-it-insert-video-button'><?php echo __( 'Insert Video Slide', 'portfolio-gallery' );?></button>
				</form>
			<?php } ?>
			</div>
		</div>	
	</div>
<?php
//var_dump($_POST["huge_it_add_video_input"]);exit;
}
/***</add>***/
?>
<!--<add>-->
<?php 
function Html_portfolio_video_edit($thumb,$id_portfolio,$id,$param,$video,$edit) {		
	global $wpdb;$video_id = get_video_id_from_url($param);

?>
	<style>
		html.wp-toolbar {
			padding:0px !important;
		}
		#wpadminbar,#adminmenuback,#screen-meta, .update-nag,#dolly {
			display:none;
		}
		#wpbody-content {
			padding-bottom:30px;
		}
		#adminmenuwrap {display:none !important;}
		.auto-fold #wpcontent, .auto-fold #wpfooter {
			margin-left: 0px;
		}
		#wpfooter {display:none;}
		iframe {height:150px !important;}
		#TB_window {height:150px !important;}
		.html5-video-player:not(.ad-interrupting):not(.hide-info-bar) .html5-info-bar {
			display: none !important;
		}
		.iframe-text-area {
			float: left;
		}
		.iframe-area {
			float: left;
			height: 100%;
			width: 40%;
			margin: 5px;
		}
		.text-area {
			float: left;
			width: 50%;
			margin: 5px;
		}
	</style>
		<script type="text/javascript">
		function youtube_parser(url){
			var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
			var match = url.match(regExp); 
			var match_vimeo = /vimeo.*\/(\d+)/i.exec( url );
			if (match&&match[7].length==11){
				return match[7];
			}else if(match_vimeo) {
				    return match_vimeo[1];
			}
			else {
				return false;
			}
		}

		jQuery(document).ready(function() {			
		
		jQuery('.huge-it-insert-video-button').click(function(){
				var ID1 = jQuery('#huge_it_add_video_input').val();
				if(ID1==""){alert("Please copy and past url form Youtube or Vimeo to insert into slider.");return false;}
				if (youtube_parser(ID1) == false) {
					alert("Url is incorrect");
					return false;
				}
				jQuery('.huge-it-insert-post-button').on('click', function() {
				//var ID1 = jQuery('#huge_it_add_video_input').val();
				
				window.parent.uploadID.val(ID1);
				
				tb_remove();
				//jQuery("#save-buttom").click();
			});

			jQuery('#huge_it_add_video_input').change(function(){
				
				if (jQuery(this).val().indexOf("youtube") >= 0){
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .youtube').addClass('active');
				}else if (jQuery(this).val().indexOf("vimeo") >= 0){
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .vimeo').addClass('active');
				}else {
					jQuery('#add-video-popup-options > div').removeClass('active');
					jQuery('#add-video-popup-options  .error-message').addClass('active');
				}
			})	
				});
					<?php	
			if(isset($_GET["closepop"])){
			if($_GET["closepop"] == 1){ ?>
					jQuery("#closepopup").click();
					self.parent.location.reload();
			<?php	}	} ?>
			jQuery('.updated').css({"display":"none"});
		});
/***add***/
	jQuery(function($) {
		
		jQuery(".set-new-video").on('click',function() {
			var showcontrols,prefix;
			var new_video = jQuery("#huge_it_add_video_input").val();
			//alert(new_video);return;
			var new_video_id= youtube_parser(new_video);
			if(!new_video_id) 
				return;
			if(new_video_id.length == 11) {
				 showcontrols = "?modestbranding=1&showinfo=0&controls=0";
				 prefix = "//www.youtube.com/embed/";
			}
			else {
			 showcontrols = "?title=0&amp;byline=0&amp;portrait=0";
			 prefix = "//player.vimeo.com/video/";

			}
			var old_url =jQuery(".text-area");
			

	
			jQuery(".iframe-area").fadeOut(300);
			old_url.html("");
			jQuery(".text-area").html(new_video);
			jQuery(".iframe-area").attr("src",prefix+new_video_id+showcontrols);
			jQuery("#huge_it_edit_video_input").val(prefix+new_video_id+showcontrols);
			jQuery(".iframe-area").fadeIn(1000);
		})
		/*jQuery("#huge_it_add_video_input").change(function() {
			var showcontrols,prefix;
			var new_video = jQuery("#huge_it_add_video_input").val();
			var new_video_id= youtube_parser(new_video);
			if(!new_video_id) 
				return;
			if(new_video_id.length == 11) {
				 showcontrols = "?modestbranding=1&showinfo=0&controls=0";
				 prefix = "//www.youtube.com/embed/";
			}
			else {
			 showcontrols = "?title=0&amp;byline=0&amp;portrait=0";
			 prefix = "//player.vimeo.com/video/";

			}
			var old_url =jQuery(".text-area");
			

	
			jQuery(".iframe-area").fadeOut(300);
			old_url.html("");
			jQuery(".text-area").html(new_video);
			jQuery(".iframe-area").attr("src",prefix+new_video_id+showcontrols);
			jQuery("#huge_it_edit_video_input").val(prefix+new_video_id+showcontrols);
			jQuery(".iframe-area").fadeIn(1000);
		})*/
	});
	</script>
<h1><?php echo __( 'Update video', 'portfolio-gallery' );?></h1>
<form method="post" action="admin.php?page=portfolios_huge_it_portfolio&task=portfolio_video_edit&portfolio_id=<?php echo $id_portfolio;?>&id=<?php echo $id; ?>&thumb=<?php echo $thumb;?>&TB_iframe=1&closepop=1" >
<div class="iframe-text-area">
<?php if($edit == '') { ?>
<iframe class="iframe-area" src="<?php if($video == 'youtube') { ?>//www.youtube.com/embed/<?php echo $video_id[0]; ?>?modestbranding=1&showinfo=0&controls=0
 <?php }
 else { ?>//player.vimeo.com/video/<?php echo $video_id[0]; ?>?title=0&amp;byline=0&amp;portrait=0 <?php } ?>" frameborder="0" allowfullscreen></iframe>
<?php } else  { ?>
<iframe class="iframe-area" src=<?php echo $edit;?>  frameborder="0" allowfullscreen></iframe>
<?php } ?>
	<textarea rows="4" cols="50" class="text-area" disabled >
<?php echo $param;?>
	</textarea>
	<input type="text" id="huge_it_add_video_input" name="huge_it_add_video_input" value="" placeholder = "New video url" /><br />
	<input type="hidden" id="huge_it_edit_video_input" name="huge_it_edit_video_input" value="" placeholder = "New video url" /><br />
	</div>
	<a class='button-primary set-new-video'><?php echo __( 'See New Video', 'portfolio-gallery' );?></a>
	<button class='save-slider-options button-primary huge-it-insert-video-button' id='huge-it-insert-video-button'><?php echo __( 'Insert Video Slide', 'portfolio-gallery' );?></button>
</form>
<?php 
}
?>
<!--</add>-->
<script>
    jQuery(document).ready(function() {
        jQuery( "#category1" ).click(function() {
            alert(jQuery(this).attr('name'));
        });
    });
</script>