<?php




class Actwall_Cadres {

  /**
   * constructor
   */
  public function __construct() {

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('init', array($this, 'create_tables'));
      // add_action('init', array($this, 'create_cadre_types_table'));


		}

    // add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));

  }

  // /**
	//  *	@hook 'rest_api_init'
	//  */
	// public function rest_api_init() {
  //
  //   register_rest_route('actwall/v1', '/collage/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_collage'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/verre/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_verre'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/tirage/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_tirage'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/papier/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_papier'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/cadre_type/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_cadre_type'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/cadre/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'rest_cadre'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //   register_rest_route('actwall/v1', '/format/(?P<id>\d+)/?', array(
	// 		'methods' => 'GET',
	// 		'callback' => array($this, 'format'),
	// 		'permission_callback' => '__return_true'
	// 	));
  //
  //
  //   //
  //   // register_rest_route('maxitype/v1', '/package', array(
	// 	// 	'methods' => 'GET',
	// 	// 	'callback' => array($this, 'rest_package'),
	// 	// 	'permission_callback' => '__return_true',
  //   //   'args' => array(
	// 	// 		'id' => array(
	// 	// 			'required' => true
	// 	// 		)
	//   //   )
	// 	// ));
  //
  // }

  /**
   * @rest 'wp-json/actwall/v1/product/{id}'
   */
	public function rest_collage($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadre_collages WHERE id = $id");

  }

  /**
   * @rest 'wp-json/actwall/v1/verre/{id}'
   */
	public function rest_verre($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadre_verres WHERE id = $id");

  }

  /**
   * @rest 'wp-json/actwall/v1/tirage/{id}'
   */
	public function rest_tirage($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadre_tirages WHERE id = $id");

  }

  /**
   * @rest 'wp-json/actwall/v1/papier/{id}'
   */
	public function rest_papier($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadre_papiers WHERE id = $id");

  }

  /**
   * @rest 'wp-json/actwall/v1/cadre_type/{id}'
   */
	public function rest_cadre_type($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadre_types WHERE id = $id");

  }

  /**
   * @rest 'wp-json/actwall/v1/cadre/{id}'
   */
	public function rest_cadre($request) {
    global $wpdb;

    $id = $request->get_param('id');
    $id = intval($id);

    return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cadres WHERE id = $id");

  }

  




  /**
	 *	create cadres table
	 */
	public function create_tables() {

    $table_version = '007';

    if ($table_version !== get_option('actwall_cadres_tables_version')) {

      $this->create_cadre_table();
      $this->create_cadre_types_table();
      $this->create_cadre_essences_table();
      $this->create_cadre_styles_table();
      $this->create_cadre_collages_table();
      $this->create_cadre_verres_table();
      $this->create_cadre_tirages_table();
      $this->create_cadre_papiers_table();
      $this->create_cadre_signatures_table();
      $this->create_cadre_certificates_table();


      update_option('actwall_cadres_table_version', $table_version);
    }



  }


  /**
	 *	create cadres table
	 */
	public function create_cadre_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadres (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `content` text NOT NULL,
      `image_id` bigint(12) NOT NULL,
      `type_id` bigint(12) NOT NULL,
      `essence_id` bigint(12) NOT NULL,
      `width` float(7, 2) NOT NULL,
      `textures` text NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

  }

  /**
	 *	create cadre types table
	 */
	public function create_cadre_types_table() {
		global $wpdb;


    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_types (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre types table
	 */
	public function create_cadre_essences_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_essences (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre types table
	 */
	public function create_cadre_styles_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_styles (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre types table
	 */
	public function create_cadre_collages_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_collages (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre types table
	 */
	public function create_cadre_verres_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_verres (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre tirages table
	 */
	public function create_cadre_tirages_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_tirages (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre tirages table
	 */
	public function create_cadre_papiers_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_papiers (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}

  /**
	 *	create cadre tirages table
	 */
	public function create_cadre_signatures_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_signatures (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}



  /**
	 *	create cadre tirages table
	 */
	public function create_cadre_certificates_table() {
		global $wpdb;

    $charset_collate = '';

    if (!empty($wpdb->charset)){
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }

    if (!empty($wpdb->collate)){
      $charset_collate .= " COLLATE $wpdb->collate";
    }

    $mysql = "CREATE TABLE {$wpdb->prefix}cadre_certificates (
      `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `name` varchar(255) NOT NULL,
      `_en_name` varchar(255) NOT NULL,
      `_fr_name` varchar(255) NOT NULL,
      `trash` tinyint(1) NOT NULL
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($mysql);

	}



  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    // add_submenu_page(
    //   'edit.php?post_type=member',
    //   'Cadres',
		// 	'Cadres',
    //   'read',
		// 	'cadres',
		// 	array($this, 'print_cadres')
    // );
    //
    // add_submenu_page(
    //   'edit.php?post_type=member',
    //   'Types Cadres',
		// 	'Types Cadres',
    //   'read',
		// 	'cadre_types',
		// 	array($this, 'print_cadres')
    // );

    add_menu_page(
  		'Cadres',
  		'Cadres',
  		'edit_posts',
  		'cadres',
  		array($this, 'print_cadres'),
      'dashicons-format-image',
  		40
  	);

    add_submenu_page(
      'cadres',
      'Types Cadres',
			'Types Cadres',
      'edit_posts',
			'cadre_types',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Essences',
			'Essences',
      'edit_posts',
			'cadre_essences',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Styles d’encadrement',
			'Styles d’encadrement',
      'edit_posts',
			'cadre_styles',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Collage',
			'Collage',
      'edit_posts',
			'cadre_collages',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Verres',
			'Verres',
      'edit_posts',
			'cadre_verres',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Technique de tirages',
			'Technique de tirages',
      'edit_posts',
			'cadre_tirages',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Papiers',
			'Papiers',
      'edit_posts',
			'cadre_papiers',
			array($this, 'print_cadres')
    );

    add_submenu_page(
      'cadres',
      'Signatures',
			'Signatures',
      'edit_posts',
			'cadre_signatures',
			array($this, 'print_signatures')
    );

    add_submenu_page(
      'cadres',
      'Certifacts d’authenticité',
			'Certifacts d’authenticité',
      'edit_posts',
			'cadre_certificates',
			array($this, 'print_certificates')
    );

	}

  public function print_certificates() {

  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_driver(
			'cadres',
			get_template_directory().'/drivers/driver-cadres.php',
			'Actwall_Driver_Cadres',
      array('textures'),
      array()
		);

    $karma_fields->register_driver(
			'cadre_types',
			get_template_directory().'/drivers/driver-cadre-types.php',
			'Actwall_Driver_Cadre_Types',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_essences',
			get_template_directory().'/drivers/driver-cadre-essences.php',
			'Actwall_Driver_Cadre_Essences',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_styles',
			get_template_directory().'/drivers/driver-cadre-styles.php',
			'Actwall_Driver_Cadre_Styles',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_collages',
			get_template_directory().'/drivers/driver-cadre-collages.php',
			'Actwall_Driver_Cadre_Collages',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_verres',
			get_template_directory().'/drivers/driver-cadre-verres.php',
			'Actwall_Driver_Cadre_Verres',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_papiers',
			get_template_directory().'/drivers/driver-cadre-papiers.php',
			'Actwall_Driver_Cadre_Papiers',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_tirages',
			get_template_directory().'/drivers/driver-cadre-tirages.php',
			'Actwall_Driver_Cadre_Tirages',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_signatures',
			get_template_directory().'/drivers/driver-cadre-signatures.php',
			'Actwall_Driver_Cadre_Signatures',
      array(),
      array()
		);

    $karma_fields->register_driver(
			'cadre_certificates',
			get_template_directory().'/drivers/driver-cadre-certificates.php',
			'Actwall_Driver_Cadre_Certificates',
      array(),
      array()
		);

    $karma_fields->register_menu_item('cadres', 'admin.php?page=cadres');
    // $karma_fields->register_menu_item('cadre_types', '?post_type=member&page=cadre_types');
    $karma_fields->register_menu_item('cadre_types', 'admin.php?page=cadre_types');
    $karma_fields->register_menu_item('cadre_essences', 'admin.php?page=cadre_essences');
    $karma_fields->register_menu_item('cadre_styles', 'admin.php?page=cadre_styles');
    $karma_fields->register_menu_item('cadre_collages', 'admin.php?page=cadre_collages');
    $karma_fields->register_menu_item('cadre_verres', 'admin.php?page=cadre_verres');
    $karma_fields->register_menu_item('cadre_papiers', 'admin.php?page=cadre_papiers');
    $karma_fields->register_menu_item('cadre_tirages', 'admin.php?page=cadre_tirages');
    $karma_fields->register_menu_item('cadre_signatures', 'admin.php?page=cadre_signatures');
    $karma_fields->register_menu_item('cadre_certificates', 'admin.php?page=cadre_certificates');


    $karma_fields->register_table('cadres', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Cadres'
			),
      'footer' => array(
        'translatable' => true,
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadres',
        'params' => array(
          // 'orderby' => 'name',
          // 'order' => 'asc',
					'ppp' => 100
				),
        'width' => '60em',
        'children' => array(
          array(
            'index'
          ),
          array(
            'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'text',
            'content' => array('getValue', 'name'),
            'translatable' => true
          )
        ),
        'modal' => array(
          'width' => '40em',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'name',
              'label' => 'Name',
              'translatable' => true
            ),
            array(
              'type' => 'textarea',
              'key' => 'content',
              'label' => 'Description',
            ),
            // array(
            //   'type' => 'files',
            //   'key' => 'image_id',
            //   'label' => 'Vignette',
            //   'uploader' => 'wp',
            //   'max' => 1
            //   // 'controls' => false
            // ),
            array(
              'type' => 'dropdown',
              'key' => 'type_id',
              'label' => 'Type de cadre',
              'options' => array(array('id' => '', 'name' => '–')),
              'driver' => 'cadre_types'
            ),
            array(
              'type' => 'dropdown',
              'key' => 'type_id',
              'label' => 'Essences',
              'options' => array(array('id' => '', 'name' => '–')),
              'driver' => 'cadre_essences'
            ),
            array(
              'label' => 'Epaisseur (cm)',
              'key' => 'width',
              'type' => 'input'
            ),
            array(
              'type' => 'array',
              'key' => 'textures',
              'label' => 'Textures',
              'children' => array(
                array(
                  'type' => 'file',
                  'max' => 1,
                  'label' => 'Image',
                  'key' => 'image',
                  'params' => array('parent' => '237')

                  // 'uploader' => 'wp'
                  // 'controls' => false
                ),
                array(
                  'type' => 'group',
                  'children' => array(
                    array(
                      'label' => 'Largeur (cm)',
                      'key' => 'width',
                      'type' => 'input'
                    ),
                    array(
                      'label' => 'Hauteur (cm)',
                      'key' => 'height',
                      'type' => 'input'
                    )
                  )
                )
              )
            )
            // array(
            //   'type' => 'array',
            //   'label' => 'Textures',
            //   'children' => array(
            //     array(
            //       'type' => 'file',
            //       'max' => 1,
            //       'label' => 'Image',
            //       'key' => 'textures',
            //       'uploader' => 'wp',
            //       'controls' => false
            //     ),
            //     array(
            //       'type' => 'group',
            //       'children' => array(
            //         array(
            //           'label' => 'Largeur (cm)',
            //           'key' => 'width',
            //           'type' => 'input'
            //         ),
            //         array(
            //           'label' => 'Hauteur (cm)',
            //           'key' => 'height',
            //           'type' => 'input'
            //         )
            //       )
            //     )
            //   )
            // )
          )
        )
      )
      // 'filters' => array(
      //   'children' => array(
      //     array(
      //       'label' => 'Search',
      //       'key' => 'search',
      //       'type' => 'input',
      //       'width' => '30em'
      //     )
      //   )
      // )
    ));





    $karma_fields->register_table('cadre_types', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Types de Cadre'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_types',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));


    $karma_fields->register_table('cadre_essences', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Essences'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_essences',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_collages', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Types de Collages'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_collages',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_styles', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Styles de Cadre'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_styles',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_verres', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Verres'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_verres',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_papiers', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Verres'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_papiers',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_tirages', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Verres'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_tirages',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_signatures', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Signatures'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_signatures',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));

    $karma_fields->register_table('cadre_certificates', array(
      'header' => array(
        'type' => 'header',
				'title' => 'Ceertificat d’authenticité'
			),
      'body' => array(
				'type' => 'grid',
				'driver' => 'cadre_certificates',
        'params' => array(
					'ppp' => 100
				),
        'width' => '50em',
        'children' => array(
          array(
            'index'
          ),
          array(
            // 'label' => 'Cadre',
            'width' => '1fr',
            'type' => 'input',
            'key' => 'name',
            'translatable' => true
          )
        )
      ),
      'footer' => array(
        'translatable' => true,
			)
    ));


  }

}


new Actwall_Cadres;
