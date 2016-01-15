<?php
/*
Controller name: Core
Controller description: Basic introspection methods
*/

class GB_JSON_API_Core_Controller {
  
  public function info() {
    global $gb_json_api;
    $php = '';
    if (!empty($gb_json_api->query->controller)) {
      return $gb_json_api->controller_info($gb_json_api->query->controller);
    } else {
      $dir = gb_json_api_dir();
      if (file_exists("$dir/gb_json-api.php")) {
        $php = file_get_contents("$dir/gb_json-api.php");
      } else {
        // Check one directory up, in case json-api.php was moved
        $dir = dirname($dir);
        if (file_exists("$dir/gb_json-api.php")) {
          $php = file_get_contents("$dir/gb_json-api.php");
        }
      }
      if (preg_match('/^\s*Version:\s*(.+)$/m', $php, $matches)) {
        $version = $matches[1];
      } else {
        $version = '(Unknown)';
      }
      $active_controllers = explode(',', get_option('gb_json_api_controllers', 'core'));
      $controllers = array_intersect($gb_json_api->get_controllers(), $active_controllers);
      return array(
        'gb_json_api_version' => $version,
        'controllers' => array_values($controllers)
      );
    }
  }
 
   
  public function get_recent_posts() {
    global $gb_json_api;
    $posts = $gb_json_api->introspector->get_posts();
    return $this->posts_result($posts);
  }
  
  public function get_post() {
    global $gb_json_api, $post;
    extract($gb_json_api->query->get(array('id', 'slug', 'post_id', 'post_slug')));
    if ($id || $post_id) {
      if (!$id) {
        $id = $post_id;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        'p' => $id
      ), true);
    } else if ($slug || $post_slug) {
      if (!$slug) {
        $slug = $post_slug;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        'name' => $slug
      ), true);
    } else {
      $gb_json_api->error("Include 'id' or 'slug' var in your request.");
    }
    if (count($posts) == 1) {
      $post = $posts[0];
      $previous = get_adjacent_post(false, '', true);
      $next = get_adjacent_post(false, '', false);
      $post = new GB_JSON_API_Post($post);
      $response = array(
        'post' => $post
      );
      if ($previous) {
        $response['previous_url'] = get_permalink($previous->ID);
      }
      if ($next) {
        $response['next_url'] = get_permalink($next->ID);
      }
      return $response;
    } else {
      $gb_json_api->error("Not found.");
    }
  }

  public function get_post_by_url(){
     global $gb_json_api ;
     global $wpdb;
     $requested_post = $gb_json_api->query->get('url') ;
     $requested_post = str_replace("\\","",$requested_post) ;
     $gbid = url_to_postid($requested_post ) ;
     if ($gbid == 0 ) $gb_json_api->error("Not found.") ;
     $posts = $gb_json_api->introspector->get_posts(array('p' => $gbid), true);
     $post = new GB_JSON_API_Post($posts[0]);
       $response = array(
         'post' => $post
       ); 	
     return $response; 
  }  

  public function get_page() {
    global $gb_json_api;
    extract($gb_json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        'page_id' => $id
      ));
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
    } else {
      $gb_json_api->error("Include 'id' or 'slug' var in your request.");
    }
    
    // Workaround for https://core.trac.wordpress.org/ticket/12647
    if (empty($posts)) {
      $url = $_SERVER['REQUEST_URI'];
      $parsed_url = parse_url($url);
      $path = $parsed_url['path'];
      if (preg_match('#^http://[^/]+(/.+)$#', get_bloginfo('url'), $matches)) {
        $blog_root = $matches[1];
        $path = preg_replace("#^$blog_root#", '', $path);
      }
      if (substr($path, 0, 1) == '/') {
        $path = substr($path, 1);
      }
      $posts = $gb_json_api->introspector->get_posts(array('pagename' => $path));
    }
    
    if (count($posts) == 1) {
      if (!empty($children)) {
        $gb_json_api->introspector->attach_child_posts($posts[0]);
      }
      return array(
        'page' => $posts[0]
      );
    } else {
      $gb_json_api->error("Not found.");
    }
  }

  public function gb_auth(){
	global $gb_json_api;
	$api_key =  get_option('gb_api_key', null) ;
	$query_api_key = $gb_json_api->query->api_key ;
	
	if ($api_key == null || $api_key==$query_api_key)
		return array('response'=> 'True', 'title'=> get_bloginfo('name'), 'url' => get_bloginfo('url')) ;
	else return array('response'=> 'False') ;
 }
  
  public function get_popular_posts() {
	global $gb_json_api;
	if ($gb_json_api->query->date) {
      		$date = preg_replace('/\D/', '', $gb_json_api->query->date);
      		if (!preg_match('/^\d{4}(\d{2})?(\d{2})?$/', $date)) {
			$wp_posts = $gb_json_api->introspector->get_posts(array(
                		'post_type' => 'post',
                		'order' => 'DESC',
                		'orderby' => 'comment_count',
                		'numberposts' => $gb_json_api->query->count,
				'page' => $gb_json_api->query->page
                		));	
      		}
		if (strlen($date) == 4) {
			$wp_posts = $gb_json_api->introspector->get_posts(array(
                                'post_type' => 'post',
                                'order' => 'DESC',
                                'orderby' => 'comment_count',
                                'year' => substr($date, 0, 4),
				'numberposts' => $gb_json_api->query->count,
                                'page' => $gb_json_api->query->page
                                ));
		}
     		if (strlen($date) > 4) {
			$wp_posts = $gb_json_api->introspector->get_posts(array(
                                'post_type' => 'post',
                                'order' => 'DESC',
                                'orderby' => 'comment_count',
                                'year' => substr($date, 0, 4),
				'monthnum'=> (int) substr($date, 4, 2),
				'numberposts' => $gb_json_api->query->count,
                                'page' => $gb_json_api->query->page
                                ));
      		}   
      		if (strlen($date) > 6) {
			$wp_posts = $gb_json_api->introspector->get_posts(array(
                                'post_type' => 'post',
                                'order' => 'DESC',
                                'orderby' => 'comment_count',
                                'year' => substr($date, 0, 4),
                                'monthnum'=> (int) substr($date, 4, 2),
				'day' => (int) substr($date, 6, 2),
				'numberposts' => $gb_json_api->query->count,
                                'page' => $gb_json_api->query->page
                                ));
      		}   
    	} else {
      		$wp_posts = $gb_json_api->introspector->get_posts(array(
                                'post_type' => 'post',
                                'order' => 'DESC',
                                'orderby' => 'comment_count',
				'numberposts' => $gb_json_api->query->count,
                                'page' => $gb_json_api->query->page
                                ));
    	}   
 
	return $this->posts_result($wp_posts);
  } 
 
  public function get_date_posts() {
    global $gb_json_api;
    if ($gb_json_api->query->date) {
      $date = preg_replace('/\D/', '', $gb_json_api->query->date);
      if (!preg_match('/^\d{4}(\d{2})?(\d{2})?$/', $date)) {
        $gb_json_api->error("Specify a date var in one of 'YYYY' or 'YYYY-MM' or 'YYYY-MM-DD' formats.");
      }
      $request = array('year' => substr($date, 0, 4));
      if (strlen($date) > 4) {
        $request['monthnum'] = (int) substr($date, 4, 2);
      }
      if (strlen($date) > 6) {
        $request['day'] = (int) substr($date, 6, 2);
      }
      $posts = $gb_json_api->introspector->get_posts($request);
    } else {
      $gb_json_api->error("Include 'date' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_category_posts() {
    global $gb_json_api;
    $category = $gb_json_api->introspector->get_current_category();
    if (!$category) {
      $gb_json_api->error("Not found.");
    }
    $posts = $gb_json_api->introspector->get_posts(array(
      'cat' => $category->id
    ));
    return $this->posts_object_result($posts, $category);
  }
  
  public function get_tag_posts() {
    global $gb_json_api;
    $tag = $gb_json_api->introspector->get_current_tag();
    if (!$tag) {
      $gb_json_api->error("Not found.");
    }
    $posts = $gb_json_api->introspector->get_posts(array(
      'tag' => $tag->slug
    ));
    return $this->posts_object_result($posts, $tag);
  }
  
  public function get_author_posts() {
    global $gb_json_api;
    $author = $gb_json_api->introspector->get_current_author();
    if (!$author) {
      $gb_json_api->error("Not found.");
    }
    $posts = $gb_json_api->introspector->get_posts(array(
      'author' => $author->id
    ));
    return $this->posts_object_result($posts, $author);
  }
  
  public function get_search_results() {
    global $gb_json_api;
    if ($gb_json_api->query->search) {
      $posts = $gb_json_api->introspector->get_posts(array(
        's' => $gb_json_api->query->search
      ));
    } else {
      $gb_json_api->error("Include 'search' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_date_index() {
    global $gb_json_api;
    $permalinks = $gb_json_api->introspector->get_date_archive_permalinks();
    $tree = $gb_json_api->introspector->get_date_archive_tree($permalinks);
    return array(
      'permalinks' => $permalinks,
      'tree' => $tree
    );
  }
  
  public function get_category_index() {
    global $gb_json_api;
    $categories = $gb_json_api->introspector->get_categories();
    return array(
      'count' => count($categories),
      'categories' => $categories
    );
  }
  
  public function get_tag_index() {
    global $gb_json_api;
    $tags = $gb_json_api->introspector->get_tags();
    return array(
      'count' => count($tags),
      'tags' => $tags
    );
  }
  
  public function get_author_index() {
    global $gb_json_api;
    $authors = $gb_json_api->introspector->get_authors();
    return array(
      'count' => count($authors),
      'authors' => array_values($authors)
    );
  }
  
  public function get_page_index() {
    global $gb_json_api;
    $pages = array();
    // Thanks to blinder for the fix!
    $numberposts = empty($gb_json_api->query->count) ? -1 : $gb_json_api->query->count;
    $wp_posts = get_posts(array(
      'post_type' => 'page',
      'post_parent' => 0,
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'numberposts' => $numberposts
    ));
    foreach ($wp_posts as $wp_post) {
      $pages[] = new GB_JSON_API_Post($wp_post);
    }
    foreach ($pages as $page) {
      $gb_json_api->introspector->attach_child_posts($page);
    }
    return array(
      'pages' => $pages
    );
  }
  
  public function get_nonce() {
    global $gb_json_api;
    extract($gb_json_api->query->get(array('controller', 'method')));
    if ($controller && $method) {
      $controller = strtolower($controller);
      if (!in_array($controller, $gb_json_api->get_controllers())) {
        $gb_json_api->error("Unknown controller '$controller'.");
      }
      require_once $gb_json_api->controller_path($controller);
      if (!method_exists($gb_json_api->controller_class($controller), $method)) {
        $gb_json_api->error("Unknown method '$method'.");
      }
      $nonce_id = $gb_json_api->get_nonce_id($controller, $method);
      return array(
        'controller' => $controller,
        'method' => $method,
        'nonce' => wp_create_nonce($nonce_id)
      );
    } else {
      $gb_json_api->error("Include 'controller' and 'method' vars in your request.");
    }
  }
  
  protected function get_object_posts($object, $id_var, $slug_var) {
    global $gb_json_api;
    $object_id = "{$type}_id";
    $object_slug = "{$type}_slug";
    extract($gb_json_api->query->get(array('id', 'slug', $object_id, $object_slug)));
    if ($id || $$object_id) {
      if (!$id) {
        $id = $$object_id;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        $id_var => $id
      ));
    } else if ($slug || $$object_slug) {
      if (!$slug) {
        $slug = $$object_slug;
      }
      $posts = $gb_json_api->introspector->get_posts(array(
        $slug_var => $slug
      ));
    } else {
      $gb_json_api->error("No $type specified. Include 'id' or 'slug' var in your request.");
    }
    return $posts;
  }
  
  protected function posts_result($posts) {
    global $wp_query;
    return array(
      'count' => count($posts),
      'count_total' => (int) $wp_query->found_posts,
      'pages' => $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
  
  protected function posts_object_result($posts, $object) {
    global $wp_query;
    // Convert something like "JSON_API_Category" into "category"
    $object_key = strtolower(substr(get_class($object), 9));
    return array(
      'count' => count($posts),
      'pages' => (int) $wp_query->max_num_pages,
      $object_key => $object,
      'posts' => $posts
    );
  }
  
}

?>
