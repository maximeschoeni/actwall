<?php




class Actwall_Formats {

  /**
   * constructor
   */
  public function __construct() {

    if (is_admin()) {

			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('init', array($this, 'create_table'));
      // add_action('init', array($this, 'create_cadre_types_table'));


		}

    add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));

  }

  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

    register_rest_route('actwall/v1', '/format/(?P<id>\d+)/?', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_format'),
			'permission_callback' => '__return_true'
		));

  }

  /**
   * @rest 'wp-json/actwall/v1/format/{id}'
   */
	public function rest_format($request) {
    global $wpdb, $sublanguage;

    $format_id = $request->get_param('id');
    $format_id = intval($format_id);

    $result = $wpdb->get_row("SELECT
      f.id,
      f.image_width,
      f.image_height,
      f.paper_width,
      f.paper_height,
      ct.name AS 'tirage',
      cp.name AS 'papier',
      cs.name AS 'signature',
      cc.name AS 'collage',
      c.name AS 'cadre',
      cv.name AS 'verre',
      ct._fr_name AS '_fr_tirage',
      cp._fr_name AS '_fr_papier',
      cs._fr_name AS '_fr_signature',
      cc._fr_name AS '_fr_collage',
      c._fr_name AS '_fr_cadre',
      cv._fr_name AS '_fr_verre'

      FROM {$wpdb->prefix}formats AS f
      LEFT JOIN {$wpdb->prefix}cadre_tirages AS ct ON (ct.id = f.tirage_id)
      LEFT JOIN {$wpdb->prefix}cadre_papiers AS cp ON (cp.id = f.papier_id)
      -- LEFT JOIN {$wpdb->prefix}cadre_types AS ctype ON (ctype.id = f.type_id)
      LEFT JOIN {$wpdb->prefix}cadre_signatures AS cs ON (cs.id = f.signature_id)

      LEFT JOIN {$wpdb->prefix}cadre_collages AS cc ON (cc.id = f.collage_id)
      LEFT JOIN {$wpdb->prefix}cadres AS c ON (c.id = f.cadre_id)
      LEFT JOIN {$wpdb->prefix}cadre_verres AS cv ON (cv.id = f.verre_id)
      WHERE f.id = $format_id
    ");

    $tirage = $result->tirage;
    $papier = $result->papier;
    $signature = $result->signature;
    $collage = $result->collage;
    $cadre = $result->cadre;
    $verre = $result->verre;

    if (isset($sublanguage) && $sublanguage->get_language()->post_name === 'fr') {

      $language = $sublanguage->get_language();

      if ($language->post_name === 'fr') {

        if ($result->_fr_tirage) {
          $tirage = $result->_fr_tirage;
        }
        if ($result->_fr_papier) {
          $papier = $result->_fr_papier;
        }
        if ($result->_fr_signature) {
          $signature = $result->_fr_signature;
        }
        if ($result->_fr_collage) {
          $collage = $result->_fr_collage;
        }
        if ($result->_fr_cadre) {
          $cadre = $result->_fr_cadre;
        }
        if ($result->_fr_verre) {
          $verre = $result->_fr_verre;
        }

      }

    }

    $image_width = (float) $result->image_width;
    $image_height = (float) $result->image_height;
    $paper_width = (float) $result->paper_width;
    $paper_height = (float) $result->paper_height;

    if (!$paper_width) {
      $paper_width = $image_width;
    }
    if (!$paper_height) {
      $paper_height = $image_height;
    }

    $image_width = str_replace('.00', '', number_format($image_width, 2));
    $image_height = str_replace('.00', '', number_format($image_height, 2));

    $paper_width = str_replace('.00', '', number_format($paper_width, 2));
    $paper_height = str_replace('.00', '', number_format($paper_height, 2));



    $specifications = array(
      $tirage,
      $papier,
      sprintf(__('Dimension de l’image %sx%s', 'actwall'), $image_width, $image_height),
      sprintf(__('Dimension du papier %sx%s', 'actwall'), $paper_width, $paper_height),
      $signature,
    );

    $framing = array(
      $collage,
      $cadre,
      $verre
    );

    return array(
      'framing' => $framing,
      'specifications' => $specifications
    );

  }


  /**
	 *	create formats table
	 */
	public function create_table() {
		global $wpdb;

		$table_version = '015';

		if ($table_version !== get_option('actwall_formats_table_version')) {

			$charset_collate = '';

			if (!empty($wpdb->charset)){
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			}

			if (!empty($wpdb->collate)){
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$mysql = "CREATE TABLE {$wpdb->prefix}formats (
				`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(255) NOT NULL,
        `member_id` bigint(12) NOT NULL,
        `serie_id` bigint(12) NOT NULL,
        `image_width` float(7, 2) NOT NULL,
        `image_height` float(7, 2) NOT NULL,
        `paper_width` float(7, 2) NOT NULL,
        `paper_height` float(7, 2) NOT NULL,
        `style_id` bigint(12) NOT NULL, -- Passe-partout ou Bord blanc ou franc-bord
        `collage_id` bigint(12) NOT NULL, -- Choix :aluminium/ dibond 2 mm / dibond 3 mm
        `type_id` bigint(12) NOT NULL, -- Choix défilant : Bois // aluminium // sans
        `essence_id` bigint(12) NOT NULL,
        `cadre_id` bigint(12) NOT NULL,
        `tirage_id` bigint(12) NOT NULL,
        `papier_id` bigint(12) NOT NULL,
        `verre_id` bigint(12) NOT NULL,
        `signature_id` bigint(12) NOT NULL,
        `certificate_id` bigint(12) NOT NULL,
        `prix_tirage` float(7, 2) NOT NULL,
        `prix_encadre` float(7, 2) NOT NULL,
        `nb` int(9) NOT NULL,



				`trash` tinyint(1) NOT NULL,
        KEY member_id (member_id)
			) $charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($mysql);

			update_option('actwall_formats_table_version', $table_version);
		}

  }

  // /**
	//  *	create cadre types table
	//  */
	// public function create_cadre_types_table() {
	// 	global $wpdb;
  //
  //
  //   $table_version = '001';
  //
	// 	if ($table_version !== get_option('actwall_cadre_types_table_version')) {
  //
	// 		$charset_collate = '';
  //
	// 		if (!empty($wpdb->charset)){
	// 			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	// 		}
  //
	// 		if (!empty($wpdb->collate)){
	// 			$charset_collate .= " COLLATE $wpdb->collate";
	// 		}
  //
	// 		$mysql = "CREATE TABLE {$wpdb->prefix}cadre_types (
	// 			`id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  //       `name` varchar(255) NOT NULL,
	// 			`trash` tinyint(1) NOT NULL
	// 		) $charset_collate;";
  //
	// 		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	// 		dbDelta($mysql);
  //
	// 		update_option('actwall_cadre_types_table_version', $table_version);
	// 	}
  //
	// }


  /**
	 *	Create admin menu
	 */
	public function admin_menu() {

    add_submenu_page(
      'edit.php?post_type=member',
      'Formats',
			'Formats',
      'read',
			'formats',
			array($this, 'print_formats')
    );

	}

  public function print_formats() {

  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    $karma_fields->register_driver(
			'formats',
			get_template_directory().'/drivers/driver-formats.php',
			'Actwall_Driver_Formats',
      array(),
      array()
		);
    //
    //
    // $karma_fields->register_menu_item('cadres', '?post_type=member&page=cadres');
    // $karma_fields->register_menu_item('cadre_types', '?post_type=member&page=cadre_types');
    //
    //
    // $karma_fields->register_table('cadres', array(
    //   'header' => array(
		// 		'title' => 'Cadres'
		// 	),
    //   'body' => array(
		// 		'type' => 'grid',
		// 		'driver' => 'cadres',
    //     'params' => array(
    //       // 'orderby' => 'name',
    //       // 'order' => 'asc',
		// 			'ppp' => 100
		// 		),
    //     'width' => '60em',
    //     'children' => array(
    //       array(
    //         'index'
    //       ),
    //       array(
    //         'label' => 'Cadre',
    //         'width' => '1fr',
    //         'type' => 'text',
    //         'content' => array('getValue', 'name')
    //       )
    //     ),
    //     'modal' => array(
    //       'width' => '40em',
    //       'children' => array(
    //         array(
    //           'type' => 'input',
    //           'key' => 'name',
    //           'label' => 'Name',
    //         ),
    //         array(
    //           'type' => 'textarea',
    //           'key' => 'content',
    //           'label' => 'Description',
    //         ),
    //         array(
    //           'type' => 'files',
    //           'key' => 'image_id',
    //           'label' => 'Vignette',
    //           'uploader' => 'wp',
    //           'max' => 1,
    //           'controls' => false
    //         ),
    //         array(
    //           'type' => 'dropdown',
    //           'key' => 'type_id',
    //           'label' => 'Type de cadre',
    //           'options' => array(array('id' => '', 'name' => '–')),
    //           'driver' => 'cadre_types'
    //         ),
    //         array(
    //           'label' => 'Epaisseur (cm)',
    //           'key' => 'width',
    //           'type' => 'input'
    //         ),
    //         array(
    //           'type' => 'array',
    //           'key' => 'textures',
    //           'label' => 'Textures',
    //           'children' => array(
    //             array(
    //               'type' => 'file',
    //               'max' => 1,
    //               'label' => 'Image',
    //               'key' => 'image',
    //               'uploader' => 'wp',
    //               'controls' => false
    //             ),
    //             array(
    //               'type' => 'group',
    //               'children' => array(
    //                 array(
    //                   'label' => 'Largeur (cm)',
    //                   'key' => 'width',
    //                   'type' => 'input'
    //                 ),
    //                 array(
    //                   'label' => 'Hauteur (cm)',
    //                   'key' => 'height',
    //                   'type' => 'input'
    //                 )
    //               )
    //             )
    //           )
    //         )
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
    //
    //
    //



  }

}


new Actwall_Formats;
