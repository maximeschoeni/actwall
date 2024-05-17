<?php




class Actwall_Photos {

  /**
   * constructor
   */
  public function __construct() {

    // add_action('actwall_photo_body', array($this, 'print_photo_body'));

    add_action('init', array($this, 'register'));

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));

		}

    add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));



    add_filter('attachment_link', array($this, 'attachment_link'), 10, 2);


    // add_action('template_redirect', array($this, 'custom_redirects') );

    add_filter('karma_fields_medias_driver_folder_post_types', array($this, 'filter_folders_post_type'));


    // add_filter('request', function($query_vars) {
    //
    //   var_dump($query_vars);
    //   die();
    //
    // });

    // add_action('pre_get_posts', function($wp_query) {
    //
    //   var_dump($wp_query->query_vars);
    //   die();
    //
    // });



    // add_filter('posts_request', function($request, $wp_query) {
    //
    //   var_dump($request);
    //   die();
    //
    // }, 10, 2);


    // add_filter('posts_results', function($posts, $wp_query) {
    //
    //   var_dump($posts);
    //   // var_dump(get_permalink($posts[0]->ID));
    //   die();
    //
    // }, 10, 2);



  // add_filter('pre_handle_404', function($false, $wp_query) {
  //
  //   var_dump(is_singular());
  //   die();
  //
  // }, 10, 2);



    // add_filter('template_include', function($template) {
    //
    //   var_dump($template);
    //   die();
    //
    // });



  }

  /**
   *	@filter 'karma_fields_medias_driver_folder_post_types'
   */
  public function filter_folders_post_type($post_types) {

    return array_merge($post_types, array('member'));

  }



  // public function custom_redirects() {
  //   global $wp_query;
  //
  //   // -> redirect images that have no image type
  //
  //
  //
  //   if (is_attachment()) {
  //
  //     $post = get_queried_object();
  //
  //     $terms = get_the_terms($post, 'image-type');
  //
  //     if (empty($terms)) {
  //
  //       if ($post->post_parent) {
  //
  //         $member = get_post($post->post_parent);
  //
  //         if ($member && $member->post_type === 'member') {
  //
  //           $permalink = get_permalink($member);
  //           wp_redirect($permalink);
  //           exit;
  //
  //         }
  //
  //       }
  //
  //
  //       // -> 404
  //
  //       $wp_query->set_404();
  //       status_header( 404 );
  //       get_template_part( 404 );
  //       exit();
  //
  //     }
  //
  //   }
  //
  //
  //
  //
  //
  // 	// if ( is_front_page() ) {
  // 	// 	wp_redirect( home_url( '/dashboard/' ) );
  // 	// 	die;
  // 	// }
  //   //
  // 	// if ( is_page('contact') ) {
  // 	// 	wp_redirect( home_url( '/new-contact/' ) );
  // 	// 	die;
  // 	// }
  //
  // }





  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

		// register_rest_route('maxitype/v1', '/packages', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_packages'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'typeface_id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));
    //
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
	 * @hook init
	 */
	public function register() {

    // register_post_type('artwork', array(
    //   'labels'             => array(
    //     'name' => 'Artworks',
    //     'singular_name' => 'Artwork'
    //   ),
    //   'public'             => true,
    //   'show_ui'            => true,
    //   'show_in_menu'       => true,
    //   'query_var'          => true,
    //   'publicly_queryable' => true,
    //   // 'rewrite'            => array(
    //   //   'slug' => 'member'
    //   // ),
    //   'capability_type'    => 'post',
    //   'has_archive'        => false,
    //   'hierarchical'       => false,
    //   // 'menu_position'      => null,
    //   'supports'           => array('title', 'editor'),
    //   'show_in_rest' => false
    //   // 'taxonomies' => array('module-category')
    // ));

    register_taxonomy('image-type', array('attachment', 'product'), array(
      'label'        => 'Image type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

    register_taxonomy('photo-type', array('attachment', 'product'), array(
      'label'        => 'Photo type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

    register_taxonomy('print', array('attachment', 'product'), array(
      'label'        => 'Print type',
      'public'       => true,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));


  }

  /**
	 *	create font table
	 */
	public function create_table() {
		global $wpdb, $karma;

		// $table_version = '001';
    //
		// if ($table_version !== get_option('actwall_locations_table_version')) {
    //
		// 	$charset_collate = '';
    //
		// 	if (!empty($wpdb->charset)){
		// 		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		// 	}
    //
		// 	if (!empty($wpdb->collate)){
		// 		$charset_collate .= " COLLATE $wpdb->collate";
		// 	}
    //
		// 	$mysql = "CREATE TABLE {$wpdb->prefix}locations (
		// 		`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    //     `name` varchar(255) NOT NULL,
    //     `caption` varchar(255) NOT NULL,
    //     `price` int(9) NOT NULL,
    //     `member_id` bigint(12) NOT NULL,
    //     `type_id` bigint(12) NOT NULL,
    //     `print_id` bigint(12) NOT NULL,
    //     -- `country` varchar(255) NOT NULL,
		// 		`trash` tinyint(1) NOT NULL
		// 	) $charset_collate;";
    //
		// 	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		// 	dbDelta($mysql);
    //
		// 	update_option('actwall_locations_table_version', $table_version);
		// }

	}


  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_submenu_page(
      'edit.php?post_type=member',
      'Photo',
			'Photo',
      'read',
			'photo',
			array($this, 'print_photo')
    );

    // add_submenu_page(
    //   'edit.php?post_type=member',
    //   'Artworks',
		// 	'Artworks',
    //   'read',
		// 	'artworks',
		// 	array($this, 'print_artworks')
    // );

	}

  public function print_photo() {

  }

  public function print_artworks() {

  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    // $karma_fields->register_menu_item('medias', 'upload.php');

		$karma_fields->register_table('medias', array(
			'header' => array(
        'type' => 'header',
				'title' => array('||', array('queryValue', 'posts', array('getParam', 'parent'), 'post_title'), 'Medias')
			),
			// 'modal' => array(
			// 	'width' => '25em'
			// ),
			'body' => array(
				'type' => 'medias',
				'driver' => 'medias',
				'params' => array(
					'ppp' => 100,
          'parent' => '0'
				),
        // 'children' => array(
        //   array(
        //     'type' => 'text',
        //     'content' => 'hello world'
        //   )
        // ),

				'modal' => array(
          'width' => '40em',
					'children' => array(
						array(
							'type' => 'description'
						),
            array(
            	'type' => 'input',
            	'label' => 'Name',
            	'key' => 'post_title'
            ),

					)
				)
			),
      'footer' => array(
				'children' => array(
					'save',
					'upload',
					'createFolder',
					'delete',
          'separator',
					'undo',
					'redo',
					'insert'
				)
			)



			// 'filters' => array(
			// 	'type' => 'group',
			// 	'display' => 'flex',
			// 	'children' => array(
      //
			// 		array(
			// 			'type' => 'input',
			// 			'label' => 'Recherche',
			// 			'key' => 'search',
			// 			'style' => 'flex:1',
      //       'width' => '20em'
			// 		)
			// 	)
			// )


		));

    // $karma_fields->register_table('photos', array(
    //   'header' => array(
		// 		'title' => 'Photographies'
		// 	),
    //   'body' => array(
		// 		'type' => 'grid',
		// 		'driver' => 'medias',
    //     'params' => array(
    //       'orderby' => 'name',
    //       'order' => 'asc',
		// 			'ppp' => 100
		// 		),
    //     'width' => '30em',
    //     'children' => array(
    //       array(
    //         'index'
    //       ),
    //       array(
    //         'type' => 'input',
    //         'label' => 'Price',
    //         'key' => 'price'
    //       ),
    //       array(
    //         'type' => 'group',
    //         'children' => array(
    //           array(
    //             'type' => 'input',
    //             'label' => 'Width',
    //             'key' => 'width'
    //           ),
    //           array(
    //             'type' => 'input',
    //             'label' => 'Height',
    //             'key' => 'height'
    //           )
    //         )
    //       ),
    //       array(
    //         'type' => 'tags',
    //         'key' => 'photo-type',
    //         'driver' => 'taxonomy',
    //         'params' => array('taxonomy' => 'photo-type'),
    //         'table' => 'taxonomy'
    //       ),
    //       array(
    //         'type' => 'tags',
    //         'key' => 'print',
    //         'driver' => 'taxonomy',
    //         'params' => array('taxonomy' => 'print'),
    //         'table' => 'taxonomy'
    //       ),
    //       array(
    //         'label' => 'City',
    //         'width' => '1fr',
    //         'type' => 'input',
    //         'key' => 'name'
    //       )
    //     )
    //   ),
    //   'filters' => array(
    //     'children' => array(
    //       array(
    //         'label' => 'Search',
    //         'key' => 'search',
    //         'type' => 'input',
    //         'width' => '30em'
    //       )
    //     )
    //   )
    // ));


  }

  // /**
  //  * @rest 'wp-json/maxitype/v1/packages'
  //  */
  // public function rest_packages($request) {
  //   global $wpdb;
  //
  //   $typeface_id = $request->get_param('typeface_id');
  //   $typeface_id = intval($typeface_id);
  //
  //   $results = $wpdb->get_results(
  //     "SELECT pk.id, pk.name, pk.price, count(pr.id) AS 'num', pk.description, p.post_title AS 'typeface' FROM {$wpdb->prefix}packages AS pk
  //     LEFT JOIN {$wpdb->prefix}pack_rel AS pr ON (pr.pack_id = pk.id)
  //     LEFT JOIN $wpdb->posts AS p ON (p.ID = pk.typeface_id)
  //     WHERE pk.trash = 0 AND pk.typeface_id = $typeface_id
  //     GROUP BY pk.id
  //     ORDER BY pk.position ASC, pk.name ASC"
  //   );
  //
  //   return $results;
  // }
  //
  // /**
  //  * @rest 'wp-json/maxitype/v1/package'
  //  */
  // public function rest_package($request) {
  //   global $wpdb;
  //
  //   $id = $request->get_param('id');
  //   $id = intval($id);
  //
  //   $results = $wpdb->get_row(
  //     "SELECT pk.id, pk.name, pk.price, pk.description, p.post_title AS 'typeface' FROM {$wpdb->prefix}packages AS pk
  //     LEFT JOIN $wpdb->posts AS p ON (p.ID = pk.typeface_id)
  //     WHERE pk.id = $id
  //     GROUP BY pk.id
  //     LIMIT 1"
  //   );
  //
  //   return $results;
  // }


  /**
	 *	@hook 'actwall_home_header'
	 */
	// public function print_photo_header() {
  //   global $post;
  //
  //   $member = get_post($post->post_parent);
  //
  //   include get_Stylesheet_directory() . '/include/members/single-header.php';
  //
  // }

  /**
	 *	@hook 'actwall_home_header'
	 */
	public function print_photo_body() {
    global $post, $wpdb;

    require_once get_stylesheet_directory() . '/class-image.php';

    $scale = 0.4;

    $member = get_post($post->post_parent);
    $title = get_the_title($post->ID);
    $member_name = get_the_title($post->post_parent);
    $price = get_post_meta($post->ID, 'price', true);
    $edition_total = intval(get_post_meta($post->ID, 'edition_total', true));
    $edition_sold = intval(get_post_meta($post->ID, 'edition_sold', true));
    $edition_num = $edition_total - $edition_sold;

    $attachments = get_post_meta($post->ID, 'attachments');

    if ($attachments) {

      $image = Karma_Image::get_image_source($attachments[0]);

      $src = $image['src'];
      $sizes = $image['sizes'];

      $photo_width = get_post_meta($post->ID, 'size-width', true);
      // $photo_height = get_post_meta($post->ID, 'size-height', true);

      if (!$photo_width) {

        $photo_width = 80;

      }

      $photo_height = $photo_width*$image['height']/$image['width'];

      $scale = $scale*80/$photo_height;

      $width = intval($photo_width)*$scale;
      $height = intval($photo_height)*$scale;

      $padding_top = get_post_meta($post->ID, 'padding-top', true);
      $padding_right = get_post_meta($post->ID, 'padding-right', true);
      $padding_bottom = get_post_meta($post->ID, 'padding-bottom', true);
      $padding_left = get_post_meta($post->ID, 'padding-left', true);

      if (!$padding_top) {

        $padding_top = 0;

      }

      if (!$padding_right) {

        $padding_right = $padding_top;

      }

      if (!$padding_bottom) {

        $padding_bottom = $padding_top;

      }

      if (!$padding_left) {

        $padding_left = $padding_right;

      }

      $padding_top = $padding_top*$scale;
      $padding_right = $padding_right*$scale;
      $padding_bottom = $padding_bottom*$scale;
      $padding_left = $padding_left*$scale;



      $border_width = 0;
      $border_image = 'none';
      $cadre = false;

      $cadre_id = get_post_meta($post->ID, 'cadre_id', true);

      if ($cadre_id) {

        $cadre_id = intval($cadre_id);

        $cadre = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0 AND id = $cadre_id");

      }

      // if (!$cadre) { // -> use any!
      //
      //   $cadre = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadres WHERE trash = 0 LIMIT 1");
      //
      // }

      if ($cadre) {

        $textures = json_decode($cadre->textures);
        $texture = $textures[0];
        $cadre_width = floatval($cadre->width);
        $texture_width = floatval($texture->width);
        // $texture_img_src = wp_get_attachment_url($texture->image[0]);
        $texture_img = Karma_Image::get_image_source($texture->image[0]);
        $texture_img_width = $texture_img['width'];

        $border_slice = 0.95*$cadre_width*intval($texture_img_width)/$texture_width;

        $border_width = floatval($cadre_width*$scale);
        $border_img = $texture_img['src'];

      }

      include get_Stylesheet_directory() . '/include/members/single-header.php';
      include get_Stylesheet_directory() . '/include/attachment/single.php';

    } else {

      echo 'Image not found!';

    }

  }

  /**
	 *	@filter 'attachment_link'
	 */
	public function attachment_link($link, $post_id) {

    $post = get_post($post_id);

    return home_url().'/attachment/'.$post->post_name;

  }




}


new Actwall_Photos;
