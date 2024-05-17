<?php


class Actwall_Home {

  /**
   * constructor
   */
  public function __construct() {

    // add_action('actwall_home_header', array($this, 'print_header'));
    add_action('actwall_home', array($this, 'print_body'));

    add_action('actwall_photo_body', array($this, 'print_product_body')); // -> moved from products.php

    add_action('rest_api_init', array($this, 'rest_api_init'));


    add_action('actwall_member_photos', array($this, 'print_member_photos'), 10, 2);
    add_action('actwall_serie', array($this, 'print_serie'));
    add_action('actwall_page_worktosale', array($this, 'print_page_worktosale'));





  }

  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

		register_rest_route('actwall/v1', '/taxonomy/(?P<taxonomy>.+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_taxonomy'),
			'permission_callback' => '__return_true'
		));

    register_rest_route('actwall/v1', '/photographies', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_photographies'),
			'permission_callback' => '__return_true'
		));

    register_rest_route('actwall/v1', '/member_photos/(?P<member_id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_member_photos'),
			'permission_callback' => '__return_true'
		));

    register_rest_route('actwall/v1', '/member_products/(?P<member_id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_member_products'),
			'permission_callback' => '__return_true'
		));

    register_rest_route('actwall/v1', '/product/(?P<id>\d+)/?', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_product'),
			'permission_callback' => '__return_true'
		));




    // register_rest_route('maxitype/v1', '/package', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_package'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));

  }


  /**
	 *	@hook 'actwall_home_header'
	 */
	// public function print_header() {
  //
  //
  //   $image_types = get_terms(array(
  //     'taxonomy'   => 'image-type',
  //     'hide_empty' => false,
  //     'orderby' => 'description',
  //     'order' => 'asc'
  //   ));
  //
  //   include get_Stylesheet_directory() . '/include/home/header.php';
  //
  // }

  /**
	 *	@hook 'actwall_home_body'
	 */
	public function print_body() {
    global $wpdb, $post;


    // $options = get_option('actwall', array());
    //
    // $photography_term_id = isset($options['photography_term']) ? $options['photography_term'][0] : '0';



      $product_query = new WP_Query(array(

        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 200,
        // 'post_parent__in' => $member_ids,
        'orderby' => 'rand',
        'meta_query' => array(
          array(
            'key' => 'serie_id',
            'compare' => 'EXISTS'
          )
        )
        // 'tax_query' => array(
        //   array(
        //     'taxonomy' => 'image-type',
        //     'field' => 'term_id',
        //     'terms' => $photography_term_id
        //   )
        // )
      ));

      $options = get_option('actwall');

      $scale = isset($options['home_image_size']) ? (float) $options['home_image_size'] : 0.33;
      $min_image_height = isset($options['home_min_image_height']) ? (int) $options['home_min_image_height'] : 40;
      $max_image_height = isset($options['home_max_image_height']) ? (int) $options['home_max_image_height'] : 80;

      $photos = $this->prepare_photos($product_query->posts, $scale, $min_image_height, $max_image_height);


      $image_types = get_terms(array(
        'taxonomy'   => 'image-type',
        'hide_empty' => false,
        'orderby' => 'description',
        'order' => 'asc'
      ));

      // $home_margin_h = isset($options['home_margin_h']) ? (int) $options['home_margin_h'] : 20;
      // $home_margin_v = isset($options['home_margin_v']) ? (int) $options['home_margin_v'] : 20;
      //
      // $margin = array(
      //   'top' => $home_margin_v*$scale,
      //   'right' => $home_margin_h*$scale,
      //   'bottom' => $home_margin_v*$scale,
      //   'left' => $home_margin_h*$scale,
      // );

      include get_stylesheet_directory() . '/include/home/body.php';


  }


  /**
   * @rest 'wp-json/actwall/v1/taxonomy/{taxonomy}'
   */
  public function rest_taxonomy($request) {
    global $wpdb;

    $taxonomy = $request->get_param('taxonomy');

    $output = array();

    $terms = get_terms(array(
      'taxonomy'   => $taxonomy,
      'hide_empty' => false,
      'orderby' => 'description',
      'order' => 'asc'
    ));

    if ($terms && !is_wp_error($terms)) {

      foreach ($terms as $term) {

        $output[] = array(
          'id' => $term->term_id,
          'name' => $term->name
        );

      }

    }

    return $output;
  }


  /**
   * @rest 'wp-json/actwall/v1/photographies'
   */
  public function rest_photographies($request) {
    global $wpdb;

    $params = $request->get_params();

    $args = array(
      // 'post_type' => 'attachment',
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => 200,
      'orderby' => 'random'
    );

    // if (empty($params['image-type'])) {
    //
    //   $options = get_option('actwall', array());
    //
    //   $photography_term_id = isset($options['photography_term']) ? $options['photography_term'][0] : '0';
    //
    //   $params['image-type'] = $photography_term_id;
    //
    //
    // }

    if (isset($params['member-category']) || isset($params['gender']) || isset($params['location'])) {

      $member_args = array(
        'post_type' => 'member',
        'post_status' => 'publish',
        'posts_per_page' => 200,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'fields' => 'ids'
      );

      foreach ($params as $key => $value) {

        switch ($key) {

          case "member-category":
          case "gender":
            $member_args['tax_query'][] = array(
              'taxonomy' => $key,
              'field' => 'term_id',
              'terms' => intval($value)
            );
            break;

          case 'location':
            $location = esc_sql($location);
            $ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}locations WHERE name LIKE '%$location%'");
            if ($ids) {
              $member_args['meta_query'][] = array(
                'key' => 'location',
                'value' => array_map('intval', $ids),
                'compare' => 'IN'
              );
            }
            break;

        }

      }

      $member_query = new WP_Query($member_args);

      if ($member_query->posts) {

        $args['post_parent__in'] = array_map('intval', $member_query->posts);

      }

    }




    foreach ($params as $key => $value) {

      switch ($key) {

        case "orderby":

          switch($value) {

            case "newest":
              // $args['orderby'] = array('meta_value' => 'DESC', 'title' => 'ASC');
              // $args['meta_key'] = 'date';

              // $args['orderby'] = array('meta_value' => 'DESC', 'title' => 'ASC');
              // $args['meta_key'] = 'date';

              $args['orderby'] = 'none';
              $args['serie']['orderby'] = 'date';
              $args['serie']['order'] = 'desc';


              break;

            case "low-price":
              $args['orderby'] = array('meta_value_num' => 'ASC', 'title' => 'ASC');
              $args['meta_key'] = '_min_price';
              break;

            case "high-price":
              $args['orderby'] = array('meta_value_num' => 'DESC', 'title' => 'ASC');
              $args['meta_key'] = '_max_price';
              break;

            default:
              $args['orderby'] = 'rand';
              break;

          }

          break;

        case 'member_id':
          $args['post_parent__in'] = array_map('intval', explode(',', $value));
          break;

        // case "image-type":
        case "photo-type":
        case "print":
          $args['tax_query'][] = array(
            'taxonomy' => $key,
            'field' => 'term_id',
            'terms' => intval($value)
          );
          break;

        case 'availability':
          $args['meta_query'][] = array(
            'key' => $key,
            'value' => intval($value),
            'type' => 'numeric'
          );
          break;

        case 'min-price':
          $args['meta_query'][] = array(
            // 'key' => 'price',
            'key' => '_min_price',
            'value' => intval($value),
            'compare' => '>=',
            'type' => 'numeric'
          );
          break;

        case 'max-price':
          $args['meta_query'][] = array(
            // 'key' => 'price',
            'key' => '_max_price',
            'value' => intval($value),
            'compare' => '<',
            'type' => 'numeric'
          );
          break;

        case 'size':

          if ((int) $value === 1) {
            // $args['meta_query'][] = array(
            //   'key' => 'size-width',
            //   'value' => '40',
            //   'compare' => '<',
            //   'type' => 'numeric'
            // );
            // $args['format']['max-width'] = 40;

            $format_ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}formats WHERE trash = 0 AND paper_width <= 40");

            $args['meta_query'][] = array(
              'key' => 'format_id',
              'value' => $format_ids,
              'compare' => 'IN'
            );



          } else if ((int) $value === 2) {

            $format_ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}formats WHERE trash = 0 AND paper_width > 40 AND paper_width <= 100");

            $args['meta_query'][] = array(
              'key' => 'format_id',
              'value' => $format_ids,
              'compare' => 'IN'
            );

            // $args['meta_query']['relation'] = 'AND';
            // $args['meta_query'][] = array(
            //   'key' => 'size-width',
            //   'value' => '40',
            //   'compare' => '>=',
            //   'type' => 'numeric'
            // );
            // $args['meta_query'][] = array(
            //   'key' => 'size-width',
            //   'value' => '100',
            //   'compare' => '<',
            //   'type' => 'numeric'
            // );
            // $args['format']['min-width'] = 40;
            // $args['format']['max-width'] = 100;
          } else if ((int) $value === 3) {
            // $args['meta_query'][] = array(
            //   'key' => 'size-width',
            //   'value' => '100',
            //   'compare' => '>=',
            //   'type' => 'numeric'
            // );
            // $args['format']['min-width'] = 100;

            $format_ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}formats WHERE trash = 0 AND paper_width > 100");

            $args['meta_query'][] = array(
              'key' => 'format_id',
              'value' => $format_ids,
              'compare' => 'IN'
            );
          }
          break;

        case 'color':
          $args['meta_query'][] = array(
            'key' => $key,
            'value' => $value
          );
          break;

        case 'search':
          $words = explode(' ', $value);
          $words = array_filter($words);
          $words = array_map('esc_sql', $words);
          $words_string = implode("','", $words);
          // $ids = $wpdb->get_col("SELECT doc FROM {$wpdb->prefix}relevanssi WHERE term IN ('$string')");

          $product_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts AS p
            LEFT JOIN {$wpdb->prefix}relevanssi AS rel ON (rel.doc = p.ID)
            WHERE p.post_type = 'product' AND rel.term IN ('$words_string')");

          $serie_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts AS p
            LEFT JOIN {$wpdb->prefix}relevanssi AS rel ON (rel.doc = p.ID)
            WHERE p.post_type = 'serie' AND rel.term IN ('$words_string')");

          if ($serie_ids) {

            $serie_ids = array_map('intval', $serie_ids);
            $serie_ids_string = implode(',', $serie_ids);

            $serie_product_ids = $wpdb->get_col(
              "SELECT p.ID FROM $wpdb->posts AS p
              LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = p.ID)
              WHERE p.post_type = 'product' AND pm.meta_key = 'serie_id' AND pm.meta_value IN ($serie_ids_string)");

            if ($serie_product_ids) {

              $product_ids = array_merge($product_ids, $serie_product_ids);
              $product_ids = array_unique($product_ids);

            }

            // $product_ids = $wpdb->get_results(
            //   "SELECT p.ID FROM $wpdb->posts AS p
            //   LEFT JOIN $wpdb->postmeta AS pm ON (pm.post_id = p.ID)
            //   LEFT JOIN {$wpdb->prefix}relevanssi AS rel ON (rel.doc = p.ID)
            //   WHERE p.post_type = 'product' AND (rel.term IN ('$words_string') OR pm.meta_key = 'serie_id' AND pm.meta_value IN ($serie_ids_string))");

          }

          if ($product_ids) {

            $args['post__in'] = $product_ids;

          } else {

            $args['s'] = $value;

          }


          // $args['s'] = $value;
          // $args['relevanssi'] = true;
          break;



      }

    }


    // $args['orderby'] = array('title' => 'asc');
    // unset($args['meta_key']);

    // if ($params['format']) {}

    add_filter('posts_where', array($this, 'posts_where'), 10, 2);
    add_filter('posts_join', array($this, 'posts_join'), 10, 2);
    add_filter('posts_orderby', array($this, 'posts_orderby'), 10, 2);

    // add_filter('query', function($query) {
    //   var_dump($query);
    //   return $query;
    // });

    // var_dump($args);
    //
    // add_filter('query', function($query) {
    //   var_dump($query);
    //   return $query;
    // });

    $photo_query = new WP_Query($args);

    // die();

    // foreach ($photo_query->posts as $post) {
    //
    //   var_dump(get_post_meta($post->ID, '_min_price', true));
    // }
    //
    // die();

    // die();

    // var_dump($photo_query->posts);
    //
    // die();

    require_once get_stylesheet_directory() . '/class-image.php';

    $options = get_option('actwall');

    $scale = isset($options['home_image_size']) ? (float) $options['home_image_size'] : 0.33;
    $min_image_height = isset($options['home_min_image_height']) ? (int) $options['home_min_image_height'] : 40;
    $max_image_height = isset($options['home_max_image_height']) ? (int) $options['home_max_image_height'] : 80;

    return array(
      'results' => $this->prepare_photos($photo_query->posts, $scale, $min_image_height, $max_image_height),
      'length' => $photo_query->found_posts
    );

  }

  /**
   * filter 'posts_where'
   */
  public function posts_where($where, $wp_query) {

    // var_dump($where);

    if (isset($wp_query->query_vars['format'])) {

      // var_dump($where);

    }

    if (isset($wp_query->query_vars['serie']['orderby']) && $wp_query->query_vars['serie']['orderby'] === 'date') {

      $where .= " AND pm_serie.meta_key = 'serie_id' AND pm_serie_date.meta_key = 'date'";

    }

    if (isset($wp_query->query_vars['format'])) {

      if (isset($wp_query->query_vars['format']['max-width'])) {

        $max_width = (int) $wp_query->query_vars['format']['max-width'];

        $where .= " AND formats.paper_width <= $max_width";

      }

      if (isset($wp_query->query_vars['format']['min-width'])) {

        $min_width = (int) $wp_query->query_vars['format']['min-width'];

        $where .= " AND formats.paper_width >= $min_width";

      }

    }

    if (isset($wp_query->query_vars['availability'])) {

      if (isset($wp_query->query_vars['availability']['produced'])) {

        $where .= " AND SUM(pm_availability.meta_value) < COUNT(ventes.id)"; // --> need group by ?

      }

    }

    return $where;

  }

  /**
   * filter 'posts_join'
   */
  public function posts_join($join, $wp_query) {
    global $wpdb;

    if (isset($wp_query->query_vars['format'])) {

      // var_dump($where);

    }

    if (isset($wp_query->query_vars['serie']['orderby']) && $wp_query->query_vars['serie']['orderby'] === 'date') {

      $join .= "INNER JOIN $wpdb->postmeta AS pm_serie ON (pm_serie.post_id = wp_posts.ID)
        INNER JOIN $wpdb->postmeta AS pm_serie_date ON (pm_serie_date.post_id = pm_serie.meta_value)";

    }

    if (isset($wp_query->query_vars['format'])) {

      $join .= "INNER JOIN $wpdb->postmeta AS pm_format ON (pm_format.post_id = wp_posts.ID)
        INNER JOIN {$wpdb->prefix}formats AS formats ON (formats.id = pm_format.meta_value)";

    }

    if (isset($wp_query->query_vars['availability'])) {

      $join .= "LEFT JOIN $wpdb->postmeta AS pm_availability ON (pm_availability.post_id = wp_posts.ID)
        LEFT JOIN {$wpdb->prefix}ventes AS ventes ON (ventes.product_id = wp_posts.ID)";

    }


    return $join;
  }

  /**
   * filter 'posts_orderby'
   */
  public function posts_orderby($orderby, $wp_query) {

    if (isset($wp_query->query_vars['serie']['orderby']) && $wp_query->query_vars['serie']['orderby'] === 'date') {

      $order = isset($wp_query->query_vars['serie']['order']) && strtolower($wp_query->query_vars['serie']['order']) === 'asc' ? 'ASC' : 'DESC';
      $orderby = "pm_serie_date.meta_value $order";

    }


    return $orderby;
  }





  // /**
	//  * prepare photo
	//  */
	// public function prepare_photos($products) {
  //   global $wpdb;
  //
  //   $members = $wpdb->get_results("SELECT $wpdb->posts.* FROM $wpdb->posts WHERE post_type = 'member' AND post_status = 'publish'");
  //
  //   update_post_caches($members, 'any', false, true);
  //
  //   $cadre_results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0");
  //
  //   foreach ($cadre_results as $cadre) {
  //
  //     if ($cadre->textures) {
  //
  //       $textures = json_decode($cadre->textures);
  //
  //       foreach ($textures as $texture) {
  //
  //         $cadre_image = $texture->image;
  //
  //         if ($texture->image) {
  //
  //           $texture_image_ids[] = $texture->image[0];
  //
  //         }
  //
  //       }
  //
  //       $cadres[$cadre->id] = array(
  //         'width' => $cadre->width,
  //         'textures' => $textures,
  //         'image_id' => $cadre->image_id
  //       );
  //
  //     }
  //
  //   }
  //
  //   require_once get_stylesheet_directory() . '/class-image.php';
  //
  //   $texture_image_ids = array_unique($texture_image_ids);
  //
  //   if ($texture_image_ids) {
  //
  //     Karma_Image::cache_images($texture_image_ids);
  //
  //   }
  //
  //   Karma_Image::cache_posts_images($products, array('attachments'));
  //
  //   $photos = array();
  //
  //   $scale = 0.33;
  //   $default_cadre_id = 1;
  //   $default_padding = 0;
  //   $default_photo_width = 80;
  //
  //   foreach ($products as $product) {
  //
  //     $default_margin_vertical = rand(0, 1);
  //     $default_margin_horizontal = rand(0, 40);
  //
  //
  //     $photo = array();
  //
  //     $attachment_id = get_post_meta($product->ID, 'attachments', true);
  //
  //     $photo['id'] = $product->ID;
  //     $photo['attachment_id'] = $attachment_id;
  //     $photo['title'] = get_the_title($product->ID);
  //     $photo['permalink'] = get_permalink($product->ID);
  //     $photo['member_name'] = get_the_title($product->post_parent);
  //     $photo['price'] = get_post_meta($product->ID, 'price', true);
  //
  //     $image = Karma_Image::get_image_source($attachment_id);
  //
  //     $photo['src'] = $image['src'];
  //     $photo['sizes'] = $image['sizes'];
  //
  //     $photo_width = get_post_meta($product->ID, 'size-width', true);
  //     $photo_height = get_post_meta($product->ID, 'size-height', true);
  //
  //     if (!$photo_width) {
  //
  //       $photo_width = $default_photo_width;
  //
  //     }
  //
  //     $photo['width'] = intval($photo_width)*$scale;
  //     $photo['height'] = intval($photo_height)*$scale;
  //
  //
  //     $padding_top = get_post_meta($product->ID, 'padding-top', true);
  //     $padding_right = get_post_meta($product->ID, 'padding-right', true);
  //     $padding_bottom = get_post_meta($product->ID, 'padding-bottom', true);
  //     $padding_left = get_post_meta($product->ID, 'padding-left', true);
  //
  //     if (!$padding_top) {
  //
  //       $padding_top = $default_padding;
  //
  //     }
  //
  //     if (!$padding_right) {
  //
  //       $padding_right = $padding_top;
  //
  //     }
  //
  //     if (!$padding_bottom) {
  //
  //       $padding_bottom = $padding_top;
  //
  //     }
  //
  //     if (!$padding_left) {
  //
  //       $padding_left = $padding_right;
  //
  //     }
  //
  //     $photo['padding'] = array(
  //       'top' => $padding_top*$scale,
  //       'right' => $padding_right*$scale,
  //       'bottom' => $padding_bottom*$scale,
  //       'left' => $padding_left*$scale,
  //     );
  //
  //     // $margin_top = get_post_meta($product->ID, 'padding-top', true);
  //     // $margin_right = get_post_meta($product->ID, 'padding-right', true);
  //     // $margin_bottom = get_post_meta($product->ID, 'padding-bottom', true);
  //     // $margin_left = get_post_meta($product->ID, 'padding-left', true);
  //     //
  //     // if (!$margin_top) {
  //     //
  //     //   $margin_top = $default_margin_vertical;
  //     //
  //     // }
  //     //
  //     // if (!$margin_right) {
  //     //
  //     //   $margin_right = $default_margin_horizontal;
  //     //
  //     // }
  //     //
  //     // if (!$margin_bottom) {
  //     //
  //     //   $margin_bottom = $margin_top;
  //     //
  //     // }
  //     //
  //     // if (!$margin_left) {
  //     //
  //     //   $margin_left = $margin_right;
  //     //
  //     // }
  //
  //     $photo['margin'] = array(
  //       'top' => 20*$scale,
  //       'right' => 20*$scale,
  //       'bottom' => 20*$scale,
  //       'left' => 20*$scale,
  //     );
  //
  //     $cadre_id = get_post_meta($product->ID, 'cadre_id', true);
  //
  //
  //
  //
  //
  //
  //     $border_style_width = 0;
  //     $border_style_image = 'none';
  //
  //
  //
  //     if ($cadre_id && isset($cadres[$cadre_id])) {
  //
  //       $cadre = $cadres[$cadre_id];
  //
  //       if ($cadre['textures']) {
  //
  //         $texture = $cadre['textures'][0];
  //
  //         $cadre_width = floatval($cadre['width']);
  //         $texture_width = floatval($texture->width);
  //         $texture_img_src = wp_get_attachment_url($texture->image[0]);
  //
  //         $texture_img = Karma_Image::get_image_source($texture->image[0]);
  //         $texture_img_width = $texture_img['width'];
  //
  //         $border_width = $cadre_width*intval($texture_img_width)/$texture_width;
  //
  //         // $border_style_image = "url({$texture_img_src}) ${border_width} stretch";
  //         // $border_style_width = floatval($cadre_width*$scale);
  //
  //
  //         $photo['border_width'] = floatval($cadre_width*$scale);
  //         $photo['border_slice'] = $border_width * 0.95;
  //         $photo['border_img'] = $texture_img_src;
  //
  //         $photo['has_frame'] = true;
  //
  //       }
  //
  //     }
  //
  //     $photos[] = $photo;
  //
  //   }
  //
  //
  //   return $photos;
  //
  // }


  public function get_specifications($product_id, $key = 'specifications') {

    $output = array();
    $values = get_post_meta($product_id, $key);
    $_values = get_post_meta($product_id, "_$key");

    foreach ($values as $i => $value) {

      if (!$value && isset($_values[$i])) {

        $value = $_values[$i];

      }

      $ul = '';

      if ($value) {

        $items = explode("\n", $value);

        $ul .= '<ul>';

        foreach ($items as $item) {

          $ul .= '<li>'.$item.'</li>';

        }

        $ul .= '</ul>';

      }

      $output[] = $ul;

    }

    return $output;

  }


  /**
   * prepare photo
   */
  public function prepare_photos($products, $scale, $min_image_height, $max_image_height) {
    global $wpdb;

    if (!$products) {

      return array();

    }

    $product_ids = array();
    $attachment_ids = array();

    foreach ($products as $product) {

      $product_ids[] = intval($product->ID);
      $attachment_ids[] = get_post_meta($product->ID, 'attachments', true);

    }


    $product_ids_string = implode(',', $product_ids);

    $ventes = $wpdb->get_results(
      "SELECT product_id, format_id, COUNT(id) AS 'nb' FROM {$wpdb->prefix}ventes
      WHERE product_id IN ($product_ids_string)
      GROUP BY product_id, format_id",
      ARRAY_A
    );






    $vente_set = array();

    foreach ($ventes as $vente) {

      $vente_set[$vente['product_id']][$vente['format_id']] = $vente['nb'];

    }




    $format_ids = array();

    foreach ($product_ids as $product_id) {

      $product_format_ids = get_post_meta($product_id, 'format_id');

      $format_ids = array_merge($format_ids, $product_format_ids);

    }

    $format_ids = array_unique($format_ids);
    $format_ids = array_map('intval', $format_ids);
    $format_set = array();


    if ($format_ids) {

      $format_ids_string = implode(',', $format_ids);

      $formats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}formats WHERE id IN ($format_ids_string)", ARRAY_A);

      foreach ($formats as $format) {

        $format_set[$format['id']] = $format;

      }

    } else {

      $formats = array();

    }

    $cadre_ids = array();

    foreach ($formats as $format) {

      $cadre_ids[] = $format['cadre_id'];

    }

    $cadre_ids = array_unique($cadre_ids);
    $cadre_ids = array_map('intval', $cadre_ids);
    $cadre_set = array();

    if ($cadre_ids) {

      $cadre_ids_string = implode(',', $cadre_ids);
      $cadres = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cadres WHERE id IN ($cadre_ids_string)", ARRAY_A);

      foreach ($cadres as $cadre) {
        if (isset($cadre['textures'])) {
          $cadre['textures'] = json_decode($cadre['textures']);
          if (isset($cadre['textures']) && is_array($cadre['textures'])) {
            $cadre['textures'] = array_map(function($obj) {return (array) $obj;}, $cadre['textures']);
          }
        }
        $cadre_set[$cadre['id']] = $cadre;
      }

    }




    $texture_ids = array();

    foreach ($cadre_set as $cadre) {

      foreach ($cadre['textures'] as $texture) {

        $cadre_image = $texture['image'];

        if (isset($texture['image'])) {

          $texture_ids[] = $texture['image'][0];

        }

      }

    }

    $texture_ids = array_unique($texture_ids);


    $serie_ids = array();

    foreach ($product_ids as $product_id) {

      $serie_ids[] = get_post_meta($product_id, 'serie_id', true);

    }

    $serie_ids = array_unique($serie_ids);
    $serie_ids = array_map('intval', $serie_ids);

    if ($serie_ids) {

      $serie_ids_string = implode(',', $serie_ids);

      $series = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID IN ($serie_ids_string)");

      update_post_caches($series, 'any', false, true);

    } else {

      $series = array();

    }




    $member_ids = array();

    foreach ($series as $serie) {

      $member_ids[] = $serie->post_parent;

    }

    $member_ids = array_unique($member_ids);
    $member_ids = array_map('intval', $member_ids);

    if ($member_ids) {

      $member_ids_string = implode(',', $member_ids);

      $members = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE ID in ($member_ids_string)");

      update_post_caches($members, 'any', false, true);

    } else {

      $members = array();

    }



    // $cadre_results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0");
    //
    // foreach ($cadre_results as $cadre) {
    //
    //   if ($cadre->textures) {
    //
    //     $textures = json_decode($cadre->textures);
    //
    //     foreach ($textures as $texture) {
    //
    //       $cadre_image = $texture->image;
    //
    //       if ($texture->image) {
    //
    //         $texture_image_ids[] = $texture->image[0];
    //
    //       }
    //
    //     }
    //
    //     $cadres[$cadre->id] = array(
    //       'width' => $cadre->width,
    //       'textures' => $textures,
    //       'image_id' => $cadre->image_id
    //     );
    //
    //   }
    //
    // }

    require_once get_stylesheet_directory() . '/class-image.php';










    $attachments = array_merge($attachment_ids, $texture_ids);

    if ($attachments) {

      Karma_Image::cache_images($attachments);

    }


    // Karma_Image::cache_posts_images($products, array('attachments'));

    $photos = array();


    // var_dump($scale, $min_image_height, $max_image_height);
    // die();


    foreach ($products as $product) {

      // $default_margin_vertical = rand(0, 1);
      // $default_margin_horizontal = rand(0, 40);

      $zoom = 1;


      $photo = array();

      $attachment_id = get_post_meta($product->ID, 'attachments', true);

      if (!$attachment_id) {

        continue;

      }

      $member = get_post($product->post_parent);

      if (!$member) {

        continue;

      }

      $serie_id = get_post_meta($product->ID, 'serie_id', true);
      $serie = get_post($serie_id);

      $mode = get_post_meta($serie_id, 'mode', true);
      $nb_tfc = get_post_meta($serie_id, 'nb_tfc', true);

      $photo['id'] = $product->ID; // compat
      $photo['product_id'] = $product->ID;
      $photo['mode'] = $mode;
      $photo['attachment_id'] = $attachment_id;
      $photo['title'] = $product->post_title;

      // $photo['signature'] = get_post_meta($serie_id, 'signature', true);
      $photo['selected_exhibitions'] = get_post_meta($serie_id, 'selected_exhibitions');
      $photo['acquired'] = get_post_meta($serie_id, 'acquired');

      if (!$photo['title']) {

        $photo['title'] = $serie->post_title;

      }

      if ($product->post_content) {

        $photo['content'] = apply_filters('sublanguage_translate_post_field', $product->post_content, $product, 'post_content');

      } else if (isset($serie->post_content) && $serie->post_content) {

        $photo['content'] = apply_filters('sublanguage_translate_post_field', $serie->post_content, $serie, 'post_content');

      } else {

        $photo['content'] = '';

      }

      $photo['nb_tfc'] = get_post_meta($product->ID, 'nb_tfc', true);

      $photo['year'] = get_post_meta($serie_id, 'year', true);
      $photo['date'] = get_post_meta($serie_id, 'date', true);
      $photo['nice_date'] = get_post_meta($serie_id, 'nice_date', true);
      $photo['lieu'] = get_post_meta($serie_id, 'lieu', true);

      $photo['permalink'] = get_permalink($product->ID);



      $photo['member_name'] = $member->post_title;



      // $photo['specifications'] = get_post_meta($product->ID, 'specifications', true);
      //
      // if (!$photo['specifications']) {
      //
      //   $photo['specifications'] = get_post_meta($product->ID, '_specifications', true);
      //
      // }
      //
      // if ($photo['specifications']) {
      //
      //   $items = explode("\n", $photo['specifications']);
      //
      //   $ul = '<ul>';
      //
      //   foreach ($items as $item) {
      //
      //     $ul .= '<li>'.$item.'</li>';
      //
      //   }
      //
      //   $ul .= '</ul>';
      //
      //   $photo['specifications'] = $ul;
      //
      // }


      // $photo['specifications'] = $this->get_specifications($product->ID, 'specifications');

      // $photo['framing'] = get_post_meta($product->ID, 'framing', true);
      //
      // if (!$photo['framing']) {
      //
      //   $photo['framing'] = get_post_meta($product->ID, '_framing', true);
      //
      // }
      //
      // if ($photo['framing']) {
      //
      //   $items = explode("\n", $photo['framing']);
      //
      //   $ul = '<ul>';
      //
      //   foreach ($items as $item) {
      //
      //     $ul .= '<li>'.$item.'</li>';
      //
      //   }
      //
      //   $ul .= '</ul>';
      //
      //   $photo['framing'] = $ul;
      //
      // }

      // $photo['framing'] = $this->get_specifications($product->ID, 'framing');



      // var_dump( get_post_meta($product->ID, '_framing'));
      // var_dump( get_post_meta($product->ID, 'framing'));


      $framings = get_post_meta($product->ID, 'framing');
      $specifications = get_post_meta($product->ID, 'specifications');
      $_framings = get_post_meta($product->ID, '_framing');
      $_specifications = get_post_meta($product->ID, '_specifications');

      // var_dump( get_post_meta($product->ID, '_framing', true));





      $photo['history'] = get_post_meta($product->ID, 'history', true);
      $photo['shipping'] = get_post_meta($product->ID, 'shipping', true);


      // if ($mode === 'format') {
      //
      //   $nb = 0;
      //
      // } else { // tout format confondu
      //
      //   // $nb = (int) get_post_meta($product->ID, 'nb_tfc', true);
      //
      //
      //
      // }

      if (!$nb_tfc) {

        $nb_tfc = get_post_meta($product->ID, 'nb_tfc', true);

      }



      $total_vente = 0;

      // $photo['price'] = get_post_meta($product->ID, 'price', true);

      $image = Karma_Image::get_image_source($attachment_id);

      $photo['src'] = $image['src'];
      $photo['sizes'] = $image['sizes'];

      // echo '<pre>'; var_dump($image); die();

      $file_width = (int) $image['width'];
      $file_height = (int) $image['height'];
      $file_ratio = $file_width/$file_height;
      $file_panorama = $file_width > $file_height;
      $file_portrait = $file_width < $file_height;

      $photo['ratio'] = $file_ratio;
      $photo['panorama'] = $file_panorama;
      $photo['portrait'] = $file_portrait;


      // var_dump($product->ID);

      $product_format_ids = get_post_meta($product->ID, 'format_id');
      $product_prix_tirage = get_post_meta($product->ID, 'prix_tirage');
      $product_prix_encadre = get_post_meta($product->ID, 'prix_encadre');
      $product_nb_edition = get_post_meta($product->ID, 'nb');




      $photo['_min_price'] = get_post_meta($product->ID, '_min_price', true);
      // $photo['min_price'] = get_post_meta($product->ID, '_min_price', true);
      // $min_price = min(get_post_meta($product->ID, 'prix_encadre'));

      $photo['min_price'] = 99999999;


      $total_vendu = 0;

      if (isset($vente_set[$product->ID])) {

        foreach ($vente_set[$product->ID] as $num) {

          $total_vendu += $num;

        }

      }

      // var_dump($product_format_ids);



      foreach ($product_format_ids as $i => $format_id) {

        if (empty($format_set[$format_id])) {

          continue;

        }

        $format = $format_set[$format_id];

        $image_width = (float) $format['image_width'];
        $image_height = (float) $format['image_height'];
        $paper_width = (float) $format['paper_width'];
        $paper_height = (float) $format['paper_height'];

        if (!$image_width && !$image_height) {

          $image_height = $max_image_height;

        }

        if (!$image_height) {

          $image_height = $image_width*($file_height/$file_width);

        } else if (!$image_width) {

          $image_width = $image_height*($file_width/$file_height);

        }


        if ($paper_width < $image_width) {

          $paper_width = $image_width;

        }

        if ($paper_height < $image_height) {

          $paper_height = $image_height;

        }

        // -> fix ratio
        if ($file_panorama && $paper_height > $paper_width || $file_portrait && $paper_height < $paper_width) {

          $paper_height_temp = $paper_height;
          $image_height_temp = $image_height;

          $paper_height = $paper_width;
          $paper_width = $paper_height_temp;
          $image_height = $image_width;
          $image_width = $image_height_temp;
        }


        // cadre

        $cadre_id = (int) $format['cadre_id'];

        if ($cadre_id && isset($cadre_set[$cadre_id], $cadre_set[$cadre_id]['textures'][0])) {

          $has_frame = true;

          $cadre = $cadre_set[$cadre_id];

          $texture = $cadre['textures'][0];

          $cadre_width = (float) $cadre['width'];
          $texture_width = (float) $texture['width'];

          if (!$texture_width) {

            $texture_width = 10;

          }

          $texture_img_src = wp_get_attachment_url($texture['image'][0]);

          $texture_img = Karma_Image::get_image_source($texture['image'][0]);
          $texture_img_width = $texture_img['width'];

          $border_width = $cadre_width*intval($texture_img_width)/$texture_width;

        } else {

          $has_frame = false;

        }

        // nb editions

        if ($mode === 'format') {

          $nb_edition = (int) $product_nb_edition[$i] ? (int) $product_nb_edition[$i] : (int) $format['nb'];

        } else {

          $nb_edition = (int) $nb_tfc;

        }

        // prix

        if (isset($product_prix_encadre[$i]) && $product_prix_encadre[$i]) {

          $prix_encadre = (int) $product_prix_encadre[$i];

        } else if (isset($format['prix_encadre'])) {

          $prix_encadre = (int) $format['prix_encadre'];

        } else {

          $prix_encadre = 0;

        }




        if (isset($product_prix_tirage[$i]) && $product_prix_tirage[$i]) {

          $prix_tirage = (int) $product_prix_tirage[$i];

        } else if (isset($format['prix_tirage']) && intval($format['prix_tirage'])) {

          $prix_tirage = (int) $format['prix_tirage'];

        } else {

          $prix_tirage = $prix_encadre;

        }



        // ventes

        $nb_vendu = isset($vente_set[$product->ID][$format_id]) ? (int) $vente_set[$product->ID][$format_id] : 0;


        // specifications/framing

        $specifications = isset($specifications[$i]) ? $specifications[$i] : '';
        $framing = isset($framings[$i]) ? $framings[$i] : '';

        $_specifications = isset($_specifications[$i]) ? $_specifications[$i] : '';
        $_framing = isset($_framings[$i]) ? $_framings[$i] : '';

        if (isset($photo['framing'][$i])) {

          $framing = $photo['framing'][$i];

        }



        $format_with_cadre = isset($format['with_cadre']) ? (string) $format['with_cadre'] : '';

        foreach (array(true, false) as $with_frame) {

          if ($with_frame && ($format_with_cadre === '2' || !$has_frame) || !$with_frame && ($format_with_cadre === '1')) {

            continue;

          }

          $edition = array();

          $edition['has_frame'] = $with_frame;

          $edition['specifications'] = $specifications;
          $edition['framing'] = $framing;
          $edition['_specifications'] = $specifications;
          $edition['_framing'] = $framing;


          $edition['nb_edition'] = $nb_edition;
          $edition['nb_vendu'] = $nb_vendu;
          $edition['format'] = $format;



          $edition['paper_width'] = $paper_width;
          $edition['paper_height'] = $paper_height;
          $edition['image_width'] = $image_width;
          $edition['image_height'] = $image_height;


          $edition['bord_blanc_h'] = ($paper_width - $image_width)/2;
          $edition['bord_blanc_v'] = ($paper_height - $image_height)/2;

          // if ($paper_height < $min_image_height) {
          //
          //   $zoom = $min_image_height/$paper_height;
          //
          // }
          //
          // if ($paper_height > $max_image_height) {
          //
          //   $zoom = $max_image_height/$paper_height;
          //
          // }
          //
          // $edition['zoom_h'] = $max_image_height/$paper_width; // -> zoom to apply to have standard picture with
          // $edition['zoom'] = $zoom; // -> zoom to apply to have standard picture height
          // $edition['width'] = $scale*$zoom*$image_width;
          // $edition['height'] = $scale*$zoom*$image_height;
          // $edition['padding_top'] = $scale*$zoom*($paper_height - $image_height)/2;
          // $edition['padding_right'] = $scale*$zoom*($paper_width - $image_width)/2;
          // $edition['padding_bottom'] = $scale*$zoom*($paper_height - $image_height)/2;
          // $edition['padding_left'] = $scale*$zoom*($paper_width - $image_width)/2;

          if ($with_frame) {

            $prix = $prix_encadre;

          } else {

            $prix = $prix_tirage;

          }



          $photo['min_price'] = min($prix, $photo['min_price']);


          // $edition['prix_encadre'] = $prix; // compat
          // $edition['prix_tirage'] = $prix; // compat
          $edition['prix'] = $prix;





          // if ($cadre_id && isset($cadre_set[$cadre_id], $cadre_set[$cadre_id]['textures'][0])) {
          //
          //   $cadre = $cadre_set[$cadre_id];
          //
          //   $texture = $cadre['textures'][0];
          //
          //   $cadre_width = (float) $cadre['width'];
          //   $texture_width = (float) $texture['width'];
          //
          //   if (!$texture_width) {
          //
          //     $texture_width = 10;
          //
          //   }
          //
          //   $texture_img_src = wp_get_attachment_url($texture['image'][0]);
          //
          //   $texture_img = Karma_Image::get_image_source($texture['image'][0]);
          //   $texture_img_width = $texture_img['width'];
          //
          //   $border_width = $cadre_width*intval($texture_img_width)/$texture_width;

          if ($with_frame) {

            $edition['cadre_width'] = $cadre_width;
            $edition['texture_img_width'] = $texture_img_width;
            $edition['texture_width'] = $texture_width;


            $edition['border_width'] = floatval($cadre_width*$zoom*$scale);
            $edition['border_slice'] = $border_width * 0.66;
            $edition['border_img'] = $texture_img_src;
            // $edition['has_frame'] = true;

          }

          $photo['editions'][] = $edition;

        }








      }

      $photos[] = $photo;

    }

    return $photos;

  }





  public function print_member_photos($member_id, $num = 10, $except_id = null) {
    global $post;

    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => $num,
      'post_parent' => $member_id,
      'meta_query' => array(
        array(
          'key' => 'attachments',
          'compare' => 'EXISTS'
        )
      )
    );

    if ($except_id) {

      $args['post__not_in'] = array($except_id);

    }


    $photo_query = new WP_Query($args);


    if ($photo_query->posts) {

      $options = get_option('actwall');

      $scale = isset($options['serie_image_size']) ? (float) $options['serie_image_size'] : 0.33;
      $min_image_height = isset($options['serie_min_image_height']) ? (int) $options['serie_min_image_height'] : 40;
      $max_image_height = isset($options['serie_max_image_height']) ? (int) $options['serie_max_image_height'] : 80;


      $photos = $this->prepare_photos($photo_query->posts, $scale, $min_image_height, $max_image_height);

      include get_stylesheet_directory().'/include/members/relation-photos.php';

    }

  }

  /**
   * @rest 'wp-json/actwall/v1/member_photos/{member_id}'
   */
  public function rest_member_photos($request) {

    $member_id = $request->get_param('member_id');
    $num = $request->get_param('num');
    $except_id = $request->get_param('except_id');

    // ob_start();

    $this->print_member_photos($member_id, $num, $except_id);

    // return ob_get_clean();

  }

  /**
   * @rest 'wp-json/actwall/v1/member_products/{member_id}'
   */
  public function rest_member_products($request) {

    $member_id = $request->get_param('member_id');
    $num = $request->get_param('num');
    $except_id = $request->get_param('except_id');

    // ob_start();

    // $this->print_member_photos($member_id, $num, $except_id);

    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => $num,
      'post_parent' => $member_id,
      'meta_query' => array(
        array(
          'key' => 'attachments',
          'compare' => 'EXISTS'
        )
      )
    );

    if ($except_id) {

      $args['post__not_in'] = array($except_id);

    }

    $photo_query = new WP_Query($args);

    if ($photo_query->posts) {

      $options = get_option('actwall');

      $scale = isset($options['serie_image_size']) ? (float) $options['serie_image_size'] : 0.33;
      $min_image_height = isset($options['serie_min_image_height']) ? (int) $options['serie_min_image_height'] : 40;
      $max_image_height = isset($options['serie_max_image_height']) ? (int) $options['serie_max_image_height'] : 80;

      return $this->prepare_photos($photo_query->posts, $scale, $min_image_height, $max_image_height);

      // include get_stylesheet_directory().'/include/members/relation-photos.php';

    } else {

      return array();
    }

  }

  public function print_serie($serie_id) {
    global $post;

    $photo_query = new WP_Query(array(
      // 'post_type' => 'attachment',
      // 'post_status' => 'inherit',
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'meta_query' => array(
        array(
          'key' => 'serie',
          'value' => $serie_id
        )
      )
    ));

    if ($photo_query->posts) {

      $scale = isset($options['serie_image_size']) ? (float) $options['serie_image_size'] : 0.33;
      $min_image_height = isset($options['serie_min_image_height']) ? (int) $options['serie_min_image_height'] : 40;
      $max_image_height = isset($options['serie_max_image_height']) ? (int) $options['serie_max_image_height'] : 80;

      $photos = $this->prepare_photos($photo_query->posts, $scale, $min_image_height, $max_image_height);

      include get_stylesheet_directory().'/include/members/relation-photos.php';

    }

  }


  /**
	 *	@hook 'actwall_page_worktosale'
	 */
	public function print_page_worktosale() {
    global $wpdb, $post;

    if (isset($_GET['photographer'])) {

      // $options = get_option('actwall', array());
      //
      // $photography_term_id = isset($options['photography_term']) ? $options['photography_term'][0] : '0';


      $post_name = $_GET['photographer'];

      $member_query = new WP_Query(array(
        'post_type' => 'member',
        'post_status' => 'publish',
        'name' => $post_name,
        'posts_per_page' => 1

      ));

      if ($member_query->posts) {

        $member = $member_query->posts[0];

        $photo_query = new WP_Query(array(
          // 'post_type' => 'attachment',
          // 'post_status' => 'inherit',
          'post_type' => 'product',
          'post_status' => 'publish',
          'posts_per_page' => 200,
          'post_parent' => $member->ID
          // 'tax_query' => array(
          //   array(
          //     'taxonomy' => 'image-type',
          //     'field' => 'term_id',
          //     'terms' => (int) $photography_term_id
          //   )
          // )
        ));

        if ($photo_query->posts) {

          $scale = isset($options['home_image_size']) ? (float) $options['home_image_size'] : 0.33;
          $min_image_height = isset($options['home_min_image_height']) ? (int) $options['home_min_image_height'] : 40;
          $max_image_height = isset($options['home_max_image_height']) ? (int) $options['home_max_image_height'] : 80;
          // $home_margin_h = isset($options['home_margin_h']) ? (int) $options['home_margin_h'] : 20;
          // $home_margin_v = isset($options['home_margin_v']) ? (int) $options['home_margin_v'] : 20;
          //
          // $margin = array(
          //   'top' => $home_margin_v*$scale,
          //   'right' => $home_margin_h*$scale,
          //   'bottom' => $home_margin_v*$scale,
          //   'left' => $home_margin_h*$scale,
          // );

          $photos = $this->prepare_photos($photo_query->posts, $scale, $min_image_height, $max_image_height);

          $image_types = get_terms(array(
            'taxonomy'   => 'image-type',
            'hide_empty' => false,
            'orderby' => 'description',
            'order' => 'asc'
          ));


          include get_stylesheet_directory().'/include/members/worktosale.php';

        }

      }

    }

  }



  /**
   *  moved from class products.php
   *
	 *	@hook 'actwall_photo_body'
	 */
	public function print_product_body() {
    // global $post, $wpdb;
    //
    // $member = get_post($post->post_parent);
    //
    // $options = get_option('actwall');
    //
    // $scale = isset($options['produit_image_size']) ? (float) $options['produit_image_size'] : 0.33;
    // $min_image_height = isset($options['produit_min_image_height']) ? (int) $options['produit_min_image_height'] : 40;
    // $max_image_height = isset($options['produit_max_image_height']) ? (int) $options['produit_max_image_height'] : 80;
    //
    //
    // $photos = $this->prepare_photos(array($post), $scale, $min_image_height, $max_image_height);
    //
    // if ($photos) {
    //
    //   $photo = $photos[0];
    //
    //   if (isset($photo['editions'])) {
    //
    //
    //     include get_Stylesheet_directory() . '/include/product/single.php';
    //
    //   }
    //
    // }

    global $post, $wpdb;

    $member = get_post($post->post_parent);

    $options = get_option('actwall');

    $scale = isset($options['produit_image_size']) ? (float) $options['produit_image_size'] : 0.33;
    $min_image_height = isset($options['produit_min_image_height']) ? (int) $options['produit_min_image_height'] : 40;
    $max_image_height = isset($options['produit_max_image_height']) ? (int) $options['produit_max_image_height'] : 80;


    $results = array();
    $results['member_id'] = $member->ID;
    $results['member_title'] = get_the_title($member->ID);
    $results['member_link'] = get_permalink($member->ID);
    $results['work_to_sale_link'] = home_url('work-to-sale').'?photographer='.$member->post_name;


    $photos = $this->prepare_photos(array($post), $scale, $min_image_height, $max_image_height);

    if ($photos && isset($photos[0]['editions'])) {

      $results['photo'] = $photos[0];

      // if (isset($photo['editions'])) {
      //
      //   $results['photo'] = $photo;
      //
      //
      //   include get_Stylesheet_directory() . '/include/product/single.php';
      //
      // }

    }

    $photo = $results['photo'];

    include get_stylesheet_directory() . '/include/product/single.php';

  }


  /**
   * @rest 'wp-json/actwall/v1/product/{id}'
   */
	public function rest_product($request) {
    global $wpdb;

    $id = $request->get_param('id');

    $post = get_post($id);

    $output = array();

    if ($post) {

      $member = get_post($post->post_parent);

      if ($member) {

        $output['member_id'] = $member->ID;
        $output['member_title'] = get_the_title($member->ID);
        $output['member_link'] = get_permalink($member->ID);
        $output['work_to_sale_link'] = home_url('work-to-sale').'?photographer='.$member->post_name;

      }

      $options = get_option('actwall');

      $scale = isset($options['produit_image_size']) ? (float) $options['produit_image_size'] : 0.33;
      $min_image_height = isset($options['produit_min_image_height']) ? (int) $options['produit_min_image_height'] : 40;
      $max_image_height = isset($options['produit_max_image_height']) ? (int) $options['produit_max_image_height'] : 80;


      $photos = $this->prepare_photos(array($post), $scale, $min_image_height, $max_image_height);

      if ($photos) {

        $output['photo'] = $photos[0];

      }

    }

    return $output;

  }



}


new Actwall_Home;
