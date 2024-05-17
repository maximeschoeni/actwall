<?php




class Actwall_Evenements {

  /**
   * constructor
   */
  public function __construct() {

    add_action('init', array($this, 'init'));
    add_action('add_meta_boxes', array($this, 'meta_boxes'), 10, 2);
    add_action('rest_api_init', array($this, 'rest_api_init'));

    // add_action('karma_fields_init', array($this, 'karma_fields_init'));

    if (is_admin()) {

			// add_action('admin_menu', array($this, 'admin_menu'));

		}

    add_action('actwall_page_evenements', array($this, 'print_page_evenements'));

  }

  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

    register_rest_route('actwall/v1', '/evenements', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_evenements'),
			'permission_callback' => '__return_true'
		));

  }



  /**
	 * @hook init
	 */
	public function init() {

    register_post_type('evenement', array(
      'labels'             => array(
        'name' => 'Evenements',
        'singular_name' => 'Evenement'
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



    register_taxonomy('event-category', 'evenement', array(
      'label'        => 'Event Category',
      'public'       => false,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

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
      array('evenement'),
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
          'label' => 'Location',
          'type' => 'input',
          'key' => 'city'
        )
      )
    ));


  }


  /**
	 *	@hook 'actwall_page_evenements'
	 */
	public function print_page_evenements() {

    $evenement_query = new WP_Query(array(
      'post_type' => array('evenement', 'show'),
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'meta_key' => 'date_start',
      'orderby' => array('meta_value' => 'desc', 'title' => 'asc')
    ));

    if ($evenement_query->have_posts()) {

      $categories = get_terms(array(
        'taxonomy'   => 'event-category',
        'hide_empty' => false
      ));

      $evenements = $this->prepare_evenements($evenement_query);

      include get_stylesheet_directory() . '/include/shows/page-evenements.php';

    }

  }

  /**
   * @helper prepare_evenements
   */
  public function prepare_evenements($query) {
    global $wpdb;

    // $locations_results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}locations WHERE trash = 0");
    // $locations = array();
    //
    // foreach ($locations_results as $result) {
    //
    //   $locations[$result->id] = $result->name;
    //
    // }

    $output = array();

    require_once get_stylesheet_directory() . '/class-image.php';

    Karma_Image::cache_image_query($query, array('image'));

    foreach ($query->posts as $evenement) {

      // $location_id = get_post_meta($evenement->ID, 'locations', true);
      $city = get_post_meta($evenement->ID, 'city', true);
      $date_start = get_post_meta($evenement->ID, 'date_start', true);
      $date_end = get_post_meta($evenement->ID, 'date_end', true);
      $image_id = get_post_meta($evenement->ID, 'image', true);

      if ($image_id) {

        $image_source = Karma_Image::get_image_source($image_id);

      }

      $output[] = array(
        'id' => $evenement->ID,
        'title' => get_the_title($evenement->ID),
        'content' => apply_filters('the_content', $evenement->post_content),
        // 'location_id' => $location_id,
        // 'location' => $location_id && isset($locations[$location_id]) ? $locations[$location_id] : '',
        'location' => $city,
        'date_range' => Actwall_Shows::format_daterange($date_start, $date_end),
        'image' => $image_source
      );

    }

    return $output;
  }


  /**
   * @rest 'wp-json/actwall/v1/evenements'
   */
  public function rest_evenements($request) {
    global $wpdb;

    $params = $request->get_params();

    $args = array(
      'post_type' => array('evenement', 'show'),
      'post_status' => 'publish',
      'posts_per_page' => 200,
      'meta_key' => 'date_start',
      'orderby' => array('meta_value' => 'desc', 'title' => 'asc')
    );

    foreach ($params as $key => $value) {

      switch ($key) {


        case 'event-category':
          $args['tax_query'][] = array(
            'taxonomy' => $key,
            'field' => 'term_id',
            'terms' => intval($value)
          );
          break;

        case 'location':
          $location = esc_sql($location);
          $ids = $wpdb->get_col("SELECT id FROM {$wpdb->prefix}locations WHERE name LIKE '%$location%'");
          if ($ids) {
            $args['meta_query'][] = array(
              'key' => 'location',
              'value' => array_map('intval', $ids),
              'compare' => 'IN'
            );
          }
          break;


        case 'search':
          $args['s'] = $value;
          break;

      }

    }


    $evenement_query = new WP_Query($args);

    return $this->prepare_evenements($evenement_query);

  }










}


new Actwall_Evenements;
