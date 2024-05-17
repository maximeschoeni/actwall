<?php




class Actwall_Shows {

  /**
   * constructor
   */
  public function __construct() {

    add_action('init', array($this, 'init'));
    add_action('add_meta_boxes', array($this, 'meta_boxes'), 10, 2);
    // add_action('rest_api_init', array($this, 'rest_api_init'));

    add_action('karma_fields_init', array($this, 'karma_fields_init'));

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));

		}

    add_action('actwall_photo_shows', array($this, 'print_photo_shows'));
    add_action('actwall_member_shows', array($this, 'print_member_shows'));
    add_action('actwall_daterange', array($this, 'print_daterange'), 10, 2);
    // add_filter('actwall_filter_daterange', array($this, 'filter_daterange'), 10, 3);



  }

  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

		// register_rest_route('maxitype/v1', '/typefaces', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_typefaces'),
		// 	'permission_callback' => '__return_true'
		// ));
    //
    // register_rest_route('maxitype/v1', '/typeface', array(
    //   'methods' => 'GET',
    //   'callback' => array($this, 'rest_typeface'),
    //   'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'id' => array(
		// 			'required' => true
		// 		)
	  //   )
    // ));
    //
    // register_rest_route('maxitype/v1', '/info', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_info'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));
    //
    // register_rest_route('maxitype/v1', '/homefont/(?P<typeface_id>[^/]+)', array(
		// 	'methods' => 'GET',
		// 	'callback' => array($this, 'rest_homefont'),
		// 	'permission_callback' => '__return_true',
    //   'args' => array(
		// 		'typeface_id' => array(
		// 			'required' => true
		// 		)
	  //   )
		// ));

  }



  /**
	 * @hook init
	 */
	public function init() {

    register_post_type('show', array(
      'labels'             => array(
        'name' => 'Shows',
        'singular_name' => 'Show'
      ),
      'public'             => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => false,
      'publicly_queryable' => false,
      // 'rewrite'            => array(
      //   'slug' => 'member'
      // ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => true,
      // 'menu_position'      => null,
      'supports'           => array('title', 'editor'),
      'show_in_rest' => false
      // 'taxonomies' => array('module-category')
    ));



    // register_taxonomy('member-category', 'member', array(
    //   'label'        => 'Member Category',
    //   'public'       => false,
    //   'show_ui' => true,
    //   'show_admin_column' => true,
    //   'show_in_rest' => true,
    //   'rewrite'      => false,
    //   'hierarchical' => true
    // ));
    //
    // register_taxonomy('gender', 'member', array(
    //   'label'        => 'Gender',
    //   'public'       => false,
    //   'show_ui' => true,
    //   'show_admin_column' => true,
    //   'show_in_rest' => true,
    //   'rewrite'      => false,
    //   'hierarchical' => true
    // ));


  }




  /**
   * @hook add_meta_boxes
   */
  public function meta_boxes($post_type, $post) {

    add_meta_box(
      'details',
      'Details',
      array($this, 'details_meta_box'),
      array('show'),
      'normal',
      'default'
    );

  }


  /**
   * @callback add_meta_box
   */
  public function details_meta_box($post) {

    do_action('karma_fields_post_field', $post->ID, array(
      'type' => 'group',
      'children' => array(
        array(
          'type' => 'file',
          'label' => 'Image',
          'key' => 'image',
          'mimetype' => ['image'],
          'uploader' => 'wp'
        ),
        array(
          'type' => 'group',
          'display' => 'flex',
          'children' => array(
            array(
              'label' => 'Date start',
              'type' => 'date',
              'key' => 'date_start'
            ),
            array(
              'label' => 'Date end',
              'type' => 'date',
              'key' => 'date_end'
            )
          )
        ),
        array(
          'label' => 'Place',
          'type' => 'input',
          'key' => 'place'
        ),
        array(
          'label' => 'City',
          'type' => 'input',
          'key' => 'city'
        )
      )
    ));


  }

  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_submenu_page(
      'edit.php?post_type=member',
      'Shows',
			'Shows',
      'read',
			'shows',
			array($this, 'print_shows')
    );

	}

  public function print_shows() {


  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_menu_item('shows', '?post_type=member&page=shows');

		$karma_fields->register_table('shows', array(
			'header' => array(
				'title' => 'Shows'
			),
			'body' => array(
				'type' => 'grid',
				'driver' => 'posts',
				'params' => array(
          'post_type' => 'show',
          'post_status' => 'publish',
					'ppp' => 100
				),

        'children' => array(
          // array(
          //   'type' => 'index'
          // ),
          // array(
          //   'type' => 'hidden',
          //   'value' => 'show',
          //   'key' => 'post_type'
          // ),
          array(
            'type' => 'text',
            'label' => 'Title',
            'content' => array('getValue', 'post_title'),
            'width' => '1fr'
          ),
          array(
            'type' => 'text',
            'label' => 'Place',
            'content' => array('getValue', 'place')
          ),
          array(
            'type' => 'text',
            'label' => 'City',
            'content' => array('getValue', 'city')
          )

        ),
				'modal' => array(
          // 'display' => 'table',

          'width' => '40em',
					'children' => array(
            array(
              'label' => 'Title',
              'type' => 'input',
              'key' => 'post_title'
            ),
            array(
              'type' => 'file',
              'label' => 'Image',
              'key' => 'image',
              'mimetype' => ['image'],
              'uploader' => 'wp'
            ),
            array(
              'type' => 'group',
              'display' => 'flex',
              'children' => array(
                array(
                  'label' => 'Date start',
                  'type' => 'date',
                  'key' => 'date_start'
                ),
                array(
                  'label' => 'Date end',
                  'type' => 'date',
                  'key' => 'date_end'
                )
              )
            ),
            array(
              'label' => 'Place',
              'type' => 'input',
              'key' => 'place'
            ),
            array(
              'label' => 'City',
              'type' => 'input',
              'key' => 'city'
            )
					)
				)
			),
			// 'controls' => array(
			// 	'children' => array(
			// 		'save',
			// 		'upload',
			// 		// 'createFolder',
			// 		'delete',
			// 		'undo',
			// 		'redo',
			// 		'insert'
			// 	)
			// ),
			'filters' => array(
				'type' => 'group',
				'display' => 'flex',
				'children' => array(

					array(
						'type' => 'input',
						'label' => 'Recherche',
						'key' => 'search',
						'style' => 'flex:1'
					)
				)
			)


		));

  }


  /**
   * @hook actwall_photo_shows
   */
  public function print_photo_shows($photo_id) {

    $show_ids = get_post_meta($photo_id, 'show_id');

    if ($show_ids) {

      $show_query = new WP_Query(array(
        'post_type' => 'show',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'post__in' => $show_ids
      ));

      require_once get_stylesheet_directory() . '/class-image.php';

      Karma_Image::cache_image_query($show_query, array('image'));

      include get_stylesheet_directory().'/include/shows/relations.php';

    }

  }



  /**
   * @hook actwall_member_shows
   */
  public function print_member_shows($member_id) {
    global $wpdb;

    $member_id = intval($member_id);
    $photo_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND post_parent = $member_id");

    if ($photo_ids) {

      $photo_ids = array_map('intval', $photo_ids);
      $photo_ids = implode(',', $photo_ids);

      $show_ids = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'show_id' AND post_id IN ($photo_ids)");

      if ($show_ids) {

        $show_query = new WP_Query(array(
          'post_type' => 'show',
          'post_status' => 'publish',
          'posts_per_page' => 10,
          'post__in' => $show_ids
        ));

        require_once get_stylesheet_directory() . '/class-image.php';

        Karma_Image::cache_image_query($show_query, array('image'));

        include get_stylesheet_directory().'/include/shows/relations.php';

      }

    }

  }



  /**
   * @hook actwall_daterange
   */
  public function print_daterange($date1, $date2 = null) {

    echo Actwall_Shows::format_daterange($date1, $date2);

  }

  // /**
  //  * @filter actwall_filter_daterange
  //  */
  // public function filter_daterange($range, $date1, $date2 = null) {
  //
  //   $date1 = get_post_meta()
  //
  //   return $this->format_daterange($date1, $date2);
  //
  // }



  /**
   * @helper format_daterange
   */
  public static function format_daterange($date1, $date2 = null) {

    $t1 = strtotime($date1);

    if ($date2) {

      $t2 = strtotime($date2);

      $d1 = date('d', $t1);
  		$m1 = date('m', $t1);
  		$y1 = date('Y', $t1);
  		$d2 = date('d', $t2);
  		$m2 = date('m', $t2);
  		$y2 = date('Y', $t2);

  		if ($y1 === $y2) {

  			if ($m1 === $m2) {

  				if ($d1 === $d2) {

  					return date('d.m.Y', $t2);

  				} else {

  					return date('d', $t1) . ' — ' . date('d.m.Y', $t2);

  				}

  			} else {

  				return date('d.m', $t1) . ' — ' . date('d.m.Y', $t2);

  			}

  		} else {

  			return date('d.m.Y', $t1) . ' — ' . date('d.m.Y', $t2);

  		}

    } else {

      return date('d.m.Y', $t1);

    }

  }


  // /**
  //  * @rest 'wp-json/maxitype/v1/typefaces'
  //  */
  // public function rest_typefaces() {
  //
  //   $query = new WP_Query(array(
  //     'post_type' => 'typeface',
  //     'post_status' => 'publish',
  //     'orderby' => 'menu_order',
  //     'order' => 'ASC',
  //     'posts_per_page' => -1
  //     // 'meta_query' => array(
  //     //   array(
  //     //     'key'     => 'price',
  //     //     'value'   => '',
  //     //     'compare' => '!='
  //     //   )
  //     // )
  //   ));
  //
  //   $output = array();
  //
  //   foreach ($query->posts as $post) {
  //
  //     $price = get_post_meta($post->ID, 'price', true);
  //
  //     $output[] = array(
  //       'id' => (string) $post->ID,
  //       'permalink' => get_permalink($post->ID),
  //       'name' => $post->post_name,
  //       'title' => $post->post_title,
  //       'price' => $price
  //     );
  //
  //   }
  //
  //   return $output;
  //
  //
  // }


  //
  // /**
  //  * @rest 'wp-json/maxitype/v1/typeface'
  //  */
  // public function rest_typeface($request) {
  //
  //   $typeface_id = $request->get_param("id");
  //   $typeface_id = intval($typeface_id);
  //
  //   $typeface = get_post($typeface_id);
  //   $price = get_post_meta($typeface_id, 'price', true);
  //
  //   return array(
  //     'id' => (string) $typeface_id,
  //     'permalink' => get_permalink($typeface_id),
  //     'name' => $typeface->post_name,
  //     'title' => $typeface->post_title,
  //     'price' => $price ? $price : '50'
  //   );
  //
  // }







}


new Actwall_Shows;
