<?php
function showPublishedportfolios_1($id)
{
 global $wpdb;
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_images where portfolio_id = '%d' order by ordering ASC",$id);
	$images=$wpdb->get_results($query);
			/***<title display>***  free has not this option  /
					$title = array();
					$number = 0;
			    foreach($images as $key=>$row) { 
				   	$imagesuploader = explode(";", $row->image_url);
					array_pop($imagesuploader);
					$count = count($imagesuploader);
					for($i = 0;$i < $count;$i++) {				
						$pathinfo = pathinfo($imagesuploader[$i]);
						$filename = $pathinfo["filename"];
						$filename = strtolower($filename);
						$query=$wpdb->prepare("SELECT post_title FROM ".$wpdb->prefix."posts where post_name = '%s'",$filename);
						$post_result = $wpdb->get_var($query);
						$concat = $post_result."_-_-_".$imagesuploader[$i];
						if(in_array($concat,$title)) {
							continue;
						}
						$title[$number] = $concat;				
						$number++;
					}
				}
			/***</title display>***/	
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_itportfolio_portfolios where id = '%d' order by id ASC",$id);
	$portfolio=$wpdb->get_results($query);
        $paramssld = '';
	return front_end_portfolio($images, $paramssld, $portfolio);
}
?>






