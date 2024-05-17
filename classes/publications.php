<?php




class Actwall_Publications {

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

    add_action('actwall_member_publications', array($this, 'print_member_publications'));

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

    register_post_type('publication', array(
      'labels'             => array(
        'name' => 'Publications',
        'singular_name' => 'Publication'
      ),
      'public'             => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      // 'rewrite'            => array(
      //   'slug' => 'member'
      // ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => true,
      // 'menu_position'      => null,
      'supports'           => array('title'),
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
      array('publication'),
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
          'label' => 'Member',
          'type' => 'dropdown',
          'key' => 'member_id',
          'options' => array(array('id' => '', 'name' => '-')),
          'driver' => 'posts',
          'params' => array('post_type' => 'member')
        ),
        array(
          'type' => 'file',
          'label' => 'Image',
          'key' => 'image',
          'mimetype' => ['image'],
          'uploader' => 'wp'
        ),
        array(
          'label' => 'Date',
          'type' => 'input',
          'key' => 'date'
        ),
        array(
          'label' => 'Publisher',
          'type' => 'input',
          'key' => 'publisher'
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

    // add_submenu_page(
    //   'edit.php?post_type=member',
    //   'Publications',
		// 	'Publications',
    //   'read',
		// 	'publications',
		// 	array($this, 'print_publications')
    // );

	}



  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    // $karma_fields->register_menu_item('shows', '?post_type=member&page=shows');

		$karma_fields->register_table('publications', array(
			'header' => array(
				'title' => 'Publications'
			),
			'body' => array(
				'type' => 'grid',
				'driver' => 'posts',
				'params' => array(
          'post_type' => 'publications',
          'post_status' => 'publish',
					'ppp' => 100
				),
        'children' => array(
          // array(
          //   'type' => 'index'
          // ),
          array(
            'type' => 'text',
            'label' => 'Title',
            'content' => array('getValue', 'post_title'),
            'width' => '1fr'
          ),
          array(
            'type' => 'text',
            'label' => 'Publisher',
            'content' => array('getValue', 'publisher')
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
              'label' => 'Année',
              'type' => 'input',
              'key' => 'date'
            ),
            array(
              'label' => 'Publisher',
              'type' => 'input',
              'key' => 'publisher'
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
   * @hook actwall_member_publications
   */
  public function print_member_publications($member_id) {
    global $wp_query;

    $publication_query = new WP_Query(array(
      'post_type' => 'publication',
      'post_status' => 'publish',
      'posts_per_page' => 10,
      'meta_query' => array(
        array(
          'key' => 'member_id',
          'value' => $member_id
        )
      )
    ));

    if ($publication_query->posts) {

      require_once get_stylesheet_directory() . '/class-image.php';

      Karma_Image::cache_image_query($publication_query, array('image'));

      include get_stylesheet_directory().'/include/members/relation-publications.php';

    }


  }







}


new Actwall_Publications;
