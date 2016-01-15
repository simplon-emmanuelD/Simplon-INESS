<?php
    if(function_exists('current_user_can'))
//        if(!current_user_can('manage_options')) {
        if(!current_user_can('delete_pages')) {
            die('Access Denied');
}	
if(!function_exists('current_user_can')){
	die('Access Denied');
}
/****<add>****/

function youtube_or_vimeo($url){
	if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){	
		if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
			return 'youtube';
		}
	}
	elseif(strpos($url,'vimeo') !== false) {
		$explode = explode("/",$url);
		$end = end($explode);
		if(strlen($end) == 8 || strlen($end) == 9 )
			return 'vimeo';
	}
	return 'image';
}

/****</add>****/

function showportfolio() 
  {
	  
  global $wpdb;
	$limit=0;

	if(isset($_POST['search_events_by_title'])){
	$search_tag=esc_html(stripslashes($_POST['search_events_by_title']));
	}
	else {
	$search_tag = '';
	}
	$cat_row_query="SELECT id,name FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE sl_width=0";
	$cat_row=$wpdb->get_results($cat_row_query);
	

	$query = $wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE name LIKE %s" , "%{$search_tag}}%");
	
	$total = $wpdb->get_var($query);

	
	 $query =$wpdb->prepare("SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_itportfolio_portfolios  AS a LEFT JOIN ".$wpdb->prefix."huge_itportfolio_portfolios AS b ON a.id = b.sl_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_itportfolio_portfolios.ordering as ordering,".$wpdb->prefix."huge_itportfolio_portfolios.id AS id, COUNT( ".$wpdb->prefix."huge_itportfolio_images.portfolio_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itportfolio_images, ".$wpdb->prefix."huge_itportfolio_portfolios
WHERE ".$wpdb->prefix."huge_itportfolio_images.portfolio_id = ".$wpdb->prefix."huge_itportfolio_portfolios.id
GROUP BY ".$wpdb->prefix."huge_itportfolio_images.portfolio_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_itportfolio_portfolios.name AS par_name,".$wpdb->prefix."huge_itportfolio_portfolios.id FROM ".$wpdb->prefix."huge_itportfolio_portfolios) AS g
 ON a.sl_width=g.id WHERE a.name LIKE %s  group by a.id  ","%".$search_tag."%");

$rows = $wpdb->get_results($query);

$rows=open_cat_in_tree($rows);
	$query ="SELECT  ".$wpdb->prefix."huge_itportfolio_portfolios.ordering,".$wpdb->prefix."huge_itportfolio_portfolios.id, COUNT( ".$wpdb->prefix."huge_itportfolio_images.portfolio_id ) AS prod_count
FROM ".$wpdb->prefix."huge_itportfolio_images, ".$wpdb->prefix."huge_itportfolio_portfolios
WHERE ".$wpdb->prefix."huge_itportfolio_images.portfolio_id = ".$wpdb->prefix."huge_itportfolio_portfolios.id
GROUP BY ".$wpdb->prefix."huge_itportfolio_images.portfolio_id " ;
	$prod_rows = $wpdb->get_results($query);
		
foreach($rows as $row)
{
	foreach($prod_rows as $row_1)
	{
		if ($row->id == $row_1->id)
		{
			$row->ordering = $row_1->ordering;
			$row->prod_count = $row_1->prod_count;
		}
	}
}
	$cat_row=open_cat_in_tree($cat_row);
	$pageNav='';
	$sort='';
		html_showportfolios( $rows, $pageNav,$sort,$cat_row);
  }

function open_cat_in_tree($catt,$tree_problem='',$hihiih=1){

global $wpdb;
global $glob_ordering_in_cat;
static $trr_cat=array();
if(!isset($search_tag))
$search_tag='';
if($hihiih)
$trr_cat=array();
foreach($catt as $local_cat){
	$local_cat->name=$tree_problem.$local_cat->name;
	array_push($trr_cat,$local_cat);
}
return $trr_cat;

}

function editportfolio($id)
  {
	  
	  global $wpdb;
	  
	  				 @session_start();
		 if(isset($_POST['csrf_token_hugeit_gallery']) && (!isset($_SESSION["csrf_token_hugeit_gallery"]) || $_SESSION["csrf_token_hugeit_gallery"] != @$_POST['csrf_token_hugeit_gallery']))
		 { exit; }
	 
	  if(isset($_GET["removeslide"])){
	     if($_GET["removeslide"] != ''){
	
		$idfordelete = $_GET["removeslide"];
	  $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_itportfolio_images  WHERE id = %d ", $idfordelete));

	   }
	   }

	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id= %d",$id);
	   $row=$wpdb->get_row($query);
	   if(!isset($row->portfolio_list_effects_s))
	   return 'id not found';
       $images=explode(";;;",$row->portfolio_list_effects_s);
	   $par=explode('	',$row->param);
	   $count_ord=count($images);
	   $cat_row=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id!= %d and sl_width=0", $id));
       $cat_row=open_cat_in_tree($cat_row);
	   	  $query=$wpdb->prepare("SELECT name,ordering FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE sl_width=%d  ORDER BY `ordering` ",$row->sl_width);
	   $ord_elem=$wpdb->get_results($query);
	   
	    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %d order by ordering ASC  ",$row->id);
			   $rowim=$wpdb->get_results($query);
			   
			   if(isset($_GET["addslide"])){
			   if($_GET["addslide"] == 1){
	
$table_name = $wpdb->prefix . "huge_itportfolio_images";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `portfolio_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '".$row->id."', '', '', '', 'par_TV', 2, '1' )";

    $wpdb->query($sql_huge_itportfolio_images);
	

      $wpdb->query($sql_2);
	
	   }
	   }
	   $query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios order by id ASC";
			   $rowsld=$wpdb->get_results($query);
			  

        $paramssld = '';
	
	 $query="SELECT * FROM ".$wpdb->prefix."posts where post_type = 'post' and post_status = 'publish' order by id ASC";
			   $rowsposts=$wpdb->get_results($query);
	 
	 $rowsposts8 = '';
	 $postsbycat = '';
	 if(isset($_POST["iframecatid"])){
	 	  $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."term_relationships where term_taxonomy_id = %d order by object_id ASC",$_POST["iframecatid"]);
		$rowsposts8=$wpdb->get_results($query);
		
			   foreach($rowsposts8 as $rowsposts13){
	 $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."posts where post_type = 'post' and post_status = 'publish' and ID = %d  order by ID ASC",$rowsposts13->object_id);
			   $rowsposts1=$wpdb->get_results($query);
			   $postsbycat = $rowsposts1;
			   
	 }
	 }
	
    Html_editportfolio($ord_elem, $count_ord, $images, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat);
  }
  
function add_portfolio()
{
	global $wpdb;
	
	$table_name = $wpdb->prefix . "huge_itportfolio_portfolios";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `sl_height`, `sl_width`, `pause_on_hover`, `portfolio_list_effects_s`, `description`, `param`, `sl_position`, `ordering`, `published`, `categories`, `ht_show_sorting`, `ht_show_filtering`) VALUES
( 'New portfolio', '375', '600', 'on', 'cubeH', '4000', '1000', 'off', '1', '300', 'My First Category,My Second Category,My Third Category,', 'off', 'off')";

      $wpdb->query($sql_2);

   $query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios order by id ASC";
			   $rowsldcc=$wpdb->get_results($query);
			   $last_key = key( array_slice( $rowsldcc, -1, 1, TRUE ) );
			   
			   
	foreach($rowsldcc as $key=>$rowsldccs){
		if($last_key == $key){
			header('Location: admin.php?page=portfolios_huge_it_portfolio&id='.$rowsldccs->id.'&task=apply');
		}
	}	
}

/***<add>***/
function portfolio_video($id)
{
	global $wpdb;


	if(isset($_POST["huge_it_add_video_input"]) && $_POST["huge_it_add_video_input"] != '' ) {			
		if(!isset($_GET['thumb_parent']) || $_GET['thumb_parent'] == null) {
			
			$table_name = $wpdb->prefix . "huge_itportfolio_images";
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id= %d",$id);
			$row=$wpdb->get_row($query);
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %s ", $row->id);
			$rowplusorder=$wpdb->get_results($query);

			foreach ($rowplusorder as $key=>$rowplusorders){

				if($rowplusorders->ordering == 0){				
					$rowplusorderspl = 1;
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET ordering = '".$rowplusorderspl."' WHERE id = %s ", $rowplusorders->id));
				}
				else { 
					$rowplusorderspl=$rowplusorders->ordering+1;
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET ordering = '".$rowplusorderspl."' WHERE id = %s ", $rowplusorders->id));
				}

			}
			$_POST["huge_it_add_video_input"] .=";";
			$sql_video = "INSERT INTO 
			`" . $table_name . "` ( `name`, `portfolio_id`, `description`, `image_url`, `sl_url`, `sl_type`, `link_target`, `ordering`, `published`, `published_in_sl_width`,`category`) VALUES 
			( '".$_POST["show_title"]."', '".$id."', '".$_POST["show_description"]."', '".$_POST["huge_it_add_video_input"]."', '".$_POST["show_url"]."', 'video', 'on', '0', '1', '1','' )";
		   $wpdb->query($sql_video);
	    }
	  

		else {
		    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id= %d",$id);
		    $row=$wpdb->get_row($query);
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %s and id = %d", $row->id,$_GET['thumb_parent']);
			$get_proj_image=$wpdb->get_row($query);
			$get_proj_image->image_url .= $_POST["huge_it_add_video_input"].";";
			//$get_proj_image->image_url .= ";";
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET image_url = '".$get_proj_image->image_url."' where portfolio_id = %s and id = %d", $row->id,$_GET['thumb_parent']));
		}

	}
   Html_portfolio_video();
}
function  portfolio_video_edit($id) {
	global $wpdb;
	$thumb = $_GET["thumb"];
	$portfolio_id = $_GET["portfolio_id"];
	$id = $_GET["id"];
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %s and id = %d", $portfolio_id,$id);
			$get_proj_image=$wpdb->get_row($query);
		$input_edit_video = explode(";", $get_proj_image->image_url);//var_dump($input_edit_video );exit;
		$input_edit_video_thumb = $input_edit_video[$thumb];
		$video = youtube_or_vimeo($input_edit_video_thumb);

	if( isset($_POST["huge_it_add_video_input"]) && $_POST["huge_it_add_video_input"] != '') {
		$input_edit_video[$thumb] = $_POST["huge_it_add_video_input"];
		$new_url = implode(";", $input_edit_video);
		$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET image_url = '".$new_url."' where portfolio_id = %s and id = %d",$portfolio_id,$id));
	}

	if(isset($_POST["huge_it_edit_video_input"]) && $_POST["huge_it_edit_video_input"] != '')
		$edit = $_POST["huge_it_edit_video_input"];
	else  $edit = '';
		
	Html_portfolio_video_edit($thumb,$portfolio_id,$id,$input_edit_video_thumb,$video,$edit);

}

/***</add>***/

function removeportfolio($id)
{

	global $wpdb;
	 $sql_remov_tag=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id = %d", $id);
	 $sql_remov_image=$wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_itportfolio_images WHERE portfolio_id = %d", $id);
 if(!$wpdb->query($sql_remov_tag))
 {
	  ?>
	  <div id="message" class="error"><p>portfolio Not Deleted</p></div>
      <?php
	 
 }
 else{
	 $wpdb->query($sql_remov_image);
 ?>
 <div class="updated"><p><strong><?php _e('Item Deleted.' ); ?></strong></p></div>
 <?php
 }
}

function apply_cat($id)
{	
		 global $wpdb;
		 if(!is_numeric($id)){
			 echo 'insert numerc id';
		 	return '';
		 }
		 if(!(isset($_POST['sl_width']) && isset($_POST["name"]) ))
		 {
			 return '';
		 }
		 $cat_row=$wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id!= %d ", $id));
		 $corent_ord=$wpdb->get_var($wpdb->prepare('SELECT `ordering` FROM '.$wpdb->prefix.'huge_itportfolio_portfolios WHERE id = %d AND sl_width=%d',$id,$_POST['sl_width']));
		 $max_ord=$wpdb->get_var('SELECT MAX(ordering) FROM '.$wpdb->prefix.'huge_itportfolio_portfolios');
	 
            $query=$wpdb->prepare("SELECT sl_width FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id = %d", $id);
	        $id_bef=$wpdb->get_var($query);
			
							 @session_start();
		 if(isset($_POST['csrf_token_hugeit_gallery']) && (!isset($_SESSION["csrf_token_hugeit_gallery"]) || $_SESSION["csrf_token_hugeit_gallery"] != @$_POST['csrf_token_hugeit_gallery']))
		 { exit; }
      
	if(isset($_POST["content"])){
	$script_cat = preg_replace('#<script(.*?)>(.*?)</script>#is', '', stripslashes($_POST["content"]));
	}

	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  name = %s  WHERE id = %d ", $_POST["name"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  sl_width = %s  WHERE id = %d ", $_POST["sl_width"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  sl_height = %s  WHERE id = %d ", $_POST["sl_height"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  pause_on_hover = %s  WHERE id = %d ", $_POST["pause_on_hover"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  portfolio_list_effects_s = %s  WHERE id = %d ", $_POST["portfolio_effects_list"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  description = %s  WHERE id = %d ", $_POST["sl_pausetime"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  param = %s  WHERE id = %d ", $_POST["sl_changespeed"], $id));
	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  ordering = '1'  WHERE id = %d ", $id));
        $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  categories = '".$_POST["allCategories"]."'  WHERE id = '".$id."' ");
                        $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  ht_show_sorting = '".$_POST["ht_show_sorting"]."'  WHERE id = '".$id."' ");
//                        $wpdb->query("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  ht_show_filtering = '".$_POST["ht_show_filtering"]."'  WHERE id = '".$id."' ");

		
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios WHERE id = %d", $id);
	   $row=$wpdb->get_row($query);
				
				
				/***<image optimize>***/
				
		/*$query="SELECT * FROM ".$wpdb->prefix."huge_itportfolio_params";

	    $rowspar = $wpdb->get_results($query);
    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }

			$view0_width = $paramssld['ht_view0_block_width']; 
			$view1_width = $paramssld['ht_view1_block_width']; 
			$view2_width = $paramssld['ht_view2_element_width']; 
			$view3_width = $paramssld['ht_view3_mainimage_width']; 
			$view4_width = $paramssld["ht_view5_main_image_width"]; 
			$view6_width = $paramssld["ht_view6_width"];*/
		$cropwidth = 275;//max($view0_width,$view1_width,$view2_width ,$view3_width,$view4_width,$view6_width);
		$image_prefix = "_huge_it_small_portfolio";
		if(!function_exists('huge_it_copy_image_to_small')) {
			function huge_it_copy_image_to_small($imgurl,$image_prefix,$width1) {
				if(youtube_or_vimeo($imgurl) !== 'image')
					return;
				$pathinfo = pathinfo($imgurl);
				$extension = $pathinfo["extension"];
				$extension = strtolower($extension);
				$ext = array("png","jpg","jpeg","gif","psd","swf","bmp","wbmp","tiff_ll","tiff_mm","jpc","iff","ico");
				if((strlen($imgurl) < 3) || (!in_array($extension,$ext))){ 
					return false;
				}		
				if($width1 < 280) {
						$width1 = "280";
					}
					$pathinfo = pathinfo($imgurl);
					$filename = $pathinfo["filename"];//get image's name
					$extension = $pathinfo["extension"];//get image,s extension
					set_time_limit (0);
					$upload_dir = wp_upload_dir(); 
					$path = parse_url($imgurl, PHP_URL_PATH);
					//$path = substr($path,1);
					$url = $upload_dir["path"];//get upload path
					$copy_image = $url.'/'.$filename.$image_prefix.".".$extension;
					if(file_exists($copy_image)) {
						return;
					}
					$imgurl = $_SERVER['DOCUMENT_ROOT'].$path;
					
					if(function_exists("wp_get_image_editor")) {
						$size = wp_get_image_editor($imgurl);

					}
					else {
						return false;
					}
					if(method_exists($size,"get_size")) {
						$old_size = $size ->get_size();
					}
					else {
						return false;
					}

					$Width = $old_size['width'];//old image's width
					$Height =$old_size['height'];//old image's height
					if ($width1 < $Width) {
						$width = $width1;
						$height = (int)(($width * $Height)/$Width);//get new height
					}
					else {
						return false;
					}
					$img = wp_get_image_editor( $imgurl);

					$upload_dir = wp_upload_dir(); 
					if ( ! is_wp_error( $img ) ) {
						$img->resize( $width, $height, true );
						$url = $upload_dir["path"];//get upload path
						$copy_image = $url.'/'.$filename.$image_prefix.".".$extension;
						if(!file_exists($copy_image)) {
							$img->save($copy_image);//save new image if not exist

						}
					}
				return true;
			}
		}
	   
				/***</image optimize>***/			
				

			    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %d order by id ASC", $row->id);
			   $rowim=$wpdb->get_results($query);
			   
	if(isset($_POST['changedvalues']) && $_POST['changedvalues'] != '') {
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %d AND id in (".$_POST['changedvalues'].") order by id ASC",$row->id);
			$rowim=$wpdb->get_results($query);
			//   var_dump($_POST['changedvalues']);
			foreach ($rowim as $key=>$rowimages){

				$imgDescription = str_replace("%","%%",$_POST["im_description".$rowimages->id.""]);
				$imgTitle = str_replace("%","%%",$_POST["titleimage".$rowimages->id.""]);
				//var_dump($imgDescription);
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  ordering = '".$_POST["order_by_".$rowimages->id.""]."'  WHERE ID = %d ", $rowimages->id));
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  link_target = '".$_POST["sl_link_target".$rowimages->id.""]."'  WHERE ID = %d ", $rowimages->id));
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  sl_url = '".$_POST["sl_url".$rowimages->id.""]."' WHERE ID = %d ", $rowimages->id));
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  name = '".$imgTitle."'  WHERE ID = %d ", $rowimages->id));
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  description = '".$imgDescription."'  WHERE ID = %d ", $rowimages->id));
				$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  image_url = '".$_POST["imagess".$rowimages->id.""]."'  WHERE ID = %d ", $rowimages->id));
					/***<image optimize>***/
						$imagesuploader = explode(";", $_POST["imagess".$rowimages->id.""]);
						array_pop($imagesuploader);
						$count = count($imagesuploader);
						
						for($i = 0;$i < $count;$i++) {
							huge_it_copy_image_to_small($imagesuploader[$i],$image_prefix,$cropwidth);
						}
					
					/***</image optimize>***/
			}
	}

	
				   if($_POST["imagess"] != ''){
				   		   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = %d order by id ASC", $row->id);
			   $rowim=$wpdb->get_results($query);
	  foreach ($rowim as $key=>$rowimages){
	  $orderingplus = $rowimages->ordering+1;
	  $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_images SET  ordering = %d  WHERE ID = %d ", $orderingplus, $rowimages->id));
	  }
	
$table_name = $wpdb->prefix . "huge_itportfolio_images";
	$imagesnewuploader = explode(";;;", $_POST["imagess"]);
	
	array_pop($imagesnewuploader);

	foreach($imagesnewuploader as $imagesnewupload){

    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `portfolio_id`, `description`, `image_url`, `sl_url`, `ordering`, `published`, `published_in_sl_width`) VALUES
( '', '".$row->id."', '', '".$imagesnewupload.";', '', 'par_TV', 2, '1' )";

      $wpdb->query($sql_2);
		}
	   }
	   
	if(isset($_POST["posthuge-it-description-length"])){
	 $wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_itportfolio_portfolios SET  published = %d WHERE id = %d ", $_POST["posthuge-it-description-length"], $_GET['id']));
}
	?>
	<div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div>
	<?php
	
    return true;
	
}

?>