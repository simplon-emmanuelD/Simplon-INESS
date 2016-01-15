<?php

/**
 * Ajax sorting di options
 */
add_action('wp_ajax_frs_list_update_order', 'frs_list_update_order' ); /* for logged in user */

function frs_list_update_order() {
    global $wpdb; // this is how you get access to the database

    unset($_POST['action']);

    $list_item = $_POST['list_item'];

    foreach ($list_item as $key => $value) 
    {
    	update_post_meta($value, 'tonjoo_frs_order_number', $key);
    }

    die();
}


/**
 * Ajax delete di options
 */
add_action('wp_ajax_frs_delete', 'frs_delete' ); /* for logged in user */

function frs_delete() {
    global $wpdb; // this is how you get access to the database

    unset($_POST['action']);

    $id = htmlspecialchars($_POST['post_id']);

    wp_delete_post($id);

    echo json_encode(array('success'=>true));

    die();
}

/**
 * Ajax Show Modal
 */
add_action('wp_ajax_frs_show_modal', 'frs_show_modal' ); /* for logged in user */

function frs_show_modal() {
    global $wpdb; // this is how you get access to the database

    unset($_POST['action']);

    $id = htmlspecialchars($_POST['post_id']);

    ob_start();

    if($id=='false'){
        $content='';
        $title = '';
    }
    else{
        $post = get_post($id);
        $content = $post->post_content;
        $title = $post->post_title;
    }
    frs_modal($id);
    $modal = ob_get_clean();

    $modal = htmlspecialchars($modal);


    /* right content */
    $img_default =  plugin_dir_url( __FILE__ ).'/assets/slideshow_empty.png';
    $post_thumbnail_id = 'false';
    $scr = $img_default;

    if($id!='false'&& has_post_thumbnail($id))
    {
        $post_thumbnail_id = get_post_thumbnail_id($id);
        $scr = wp_get_attachment_image_src($post_thumbnail_id,'original');
        $scr = $scr[0];
    }

    $return = array(
    	'success'=>true,
    	'modal'=>$modal,
    	'content'=>$content,
        'post_thumbnail_id'=>$post_thumbnail_id,
        'scr' => $scr,
        'id' => $id,
        'img_default'=>$img_default,
        'title' =>$title
    	);

    echo json_encode($return);

    die();
}


/**
 * Ajax Show Category Modal
 */
add_action('wp_ajax_frs_add_slidetype', 'frs_add_slidetype' ); /* for logged in user */

function frs_add_slidetype() {

    $cat_name = htmlspecialchars($_POST['name']);

    $catarr = array('cat_name' => $cat_name, 'taxonomy' => 'slide_type' );

    $new_cat_id = wp_insert_category( $catarr );
    $new_cat = get_term_by('id', $new_cat_id, 'slide_type');

    $return = array(
        'success'=>true,
        'slug'=>$new_cat->slug
        );

    echo json_encode($return);

    die();
}

/**
 * Ajax Delete Category Modal
 */
add_action('wp_ajax_frs_delete_slidetype', 'frs_delete_slidetype' ); /* for logged in user */

function frs_delete_slidetype() {

    if(! isset($_POST['id']) || $_POST['id'] <= 0)
    {
        $return = array(
            'success'=>false
        );

        echo json_encode($return);

        die();
    }

    $taxonomy_id = htmlspecialchars($_POST['id']);

    // delete posts
    $args = array(
            'post_type' => 'pjc_slideshow',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'slide_type',
                    'field'    => 'id',
                    'terms'    => $taxonomy_id,
                ),
            ));

    $the_query = new WP_Query( $args );

    if($the_query->have_posts()): while($the_query->have_posts()): $the_query->the_post();
        wp_delete_post( get_the_ID(), true );

    endwhile; 
    endif;

    wp_reset_postdata();

    // delete taxonomy
    wp_delete_term($taxonomy_id, 'slide_type');

    $return = array(
        'success'=>true
    );

    echo json_encode($return);

    die();
}


/**
 * Ajax Save, Edit / Create new is the same, depend on the post id (null/not null)
 */
add_action('wp_ajax_frs_save', 'frs_save' ); /* for logged in user */

function frs_save() {
    global $wpdb; // this is how you get access to the database

    unset($_POST['action']);

    $id = htmlspecialchars($_POST['post_id']);

    //new Data
    if($id=='false'){

	    $slide_type = htmlspecialchars($_POST['slide_type']);

	    $title = htmlspecialchars($_POST['title']);
	    
	    $content = $_POST['content'];

        $password = md5(mt_rand(5, 50));
        $password = substr($password, 0,10);

	    // Create post object
		$my_post = array(
		  'post_title'    => $title,
		  'post_content'  => $content,
		  'post_status'   => 'publish',          
          'post_password' => $password,
          'post_author'   => get_current_user_id(),
          'post_type'     =>"pjc_slideshow"      
		);

		// Insert the post into the database
		$save_id = wp_insert_post($my_post, true);

		wp_set_object_terms($save_id,array((int)$slide_type),'slide_type');

        $thumbnailId  = (int) htmlspecialchars($_POST['featured_image']);

        //set featured image
        if($thumbnailId) {

            set_post_thumbnail( $save_id,$thumbnailId);
 
        }//remove featured image
        else{
            delete_post_thumbnail($save_id);
        }

		$return = array('success'=>true,'id'=>$save_id);

	    echo json_encode($return);

	    die();

	}
    else
    {
        //edit post
        $slide_type = htmlspecialchars($_POST['slide_type']);

        $title = htmlspecialchars($_POST['title']);
        
        $content = $_POST['content'];

        // Create post object
        $my_post = array(
          'post_title'    => $title,
          'post_content'  => $content,
          'ID'=>$id
         
        );

        // Insert the post into the database
        wp_update_post( $my_post );

        $thumbnailId  = (int) htmlspecialchars($_POST['featured_image']);;

        //set featured image
        if($thumbnailId) {

            set_post_thumbnail( $id,$thumbnailId);

        }//remove featured image
        else{
            delete_post_thumbnail($id);
        }

        
        //Change post_ID
        $_POST['post_ID'] = $id;

        tonjoo_slideshow_save_postdata($id);

        $return = array('success'=>true,'id'=>$id);

        echo json_encode($return);

        die();
    }
}

/**
 * Render Row After Edit 
 */
add_action('wp_ajax_frs_render_row', 'frs_render_row' ); /* for logged in user */
function frs_render_row(){

	unset($_POST['action']);

    $id = htmlspecialchars($_POST['post_id']);

    global $post;

    $post = get_post($id);

	setup_postdata( $post );

	
    ob_start();
    $edit = admin_url()."post.php?post={$id}&action=edit";
    include 'ajax-row-template.php';

    $row = ob_get_clean();

    $row = htmlspecialchars($row);

	$return = array('success'=>true,'row'=>$row);

    echo json_encode($return);

    die();

}



