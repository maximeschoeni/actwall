<?php




class Actwall_Members {

  /**
   * constructor
   */
  public function __construct() {

    add_action('actwall_member_header', array($this, 'print_header'));
    add_action('actwall_member_body', array($this, 'print_body'));

    add_action('init', array($this, 'init'));
    add_action('add_meta_boxes', array($this, 'meta_boxes'), 10, 2);
    add_action('rest_api_init', array($this, 'rest_api_init'));
    add_action('karma_fields_init', array($this, 'karma_fields_init'));

    add_action('actwall_portfolio', array($this, 'print_portfolio'));
    add_action('actwall_page_photographers', array($this, 'print_page_photographers'));

    add_filter('karma_fields_posts_driver_meta_results', array($this, 'meta_product'), 10, 2);
    add_filter('karma_fields_posts_driver_update', array($this, 'update_product'), 10, 6);

    // add_filter('deepl4sublanguage_source_texts', array($this, 'translate_source_text'), 10, 7);
    // add_action('deepl4sublanguage_translate_post', array($this, 'translate_post'), 10, 6);

    add_filter('deepl4sublanguage_export_grid', array($this, 'deepl4sublanguage_export_grid'), 10, 5);
    add_action('deepl4sublanguage_import_grid', array($this, 'deepl4sublanguage_import_grid'), 10, 7);

    // add_action('deepl4sublanguage_import_grid', $grid, $current_grid, $post, $language, $force, $sublanguage, $this);


  }

  // apply_filters('deepl4sublanguage_export_grid', $grid, $language, $post, $sublanguage, $this);

  /**
	 *	@hook 'deepl4sublanguage_export_grid'
	 */
  public function deepl4sublanguage_export_grid($grid, $language, $post, $sublanguage, $d4s) {

    if ($post->post_type === 'member') {

      $sublanguage->set_language();

      $series_query = new WP_Query(array(
        'post_type' => 'serie',
        'post_status' => array('publish', 'draft'),
        'post_parent' => $post->ID,
        'posts_per_page' => -1,
        'language' => false
      ));

      if ($sublanguage->is_sub($language)) {

        $prefix = $sublanguage->get_prefix();

        foreach ($series_query->posts as $serie) {

          $value = get_post_meta($serie->ID, "{$prefix}post_content", true);

          if ($value) {

            $grid['serie'][$serie->ID] = $value;

          }

        }

      } else {

        foreach ($series_query->posts as $serie) {

          $value = $serie->post_content;

          if ($value) {

            $grid['serie'][$serie->ID] = $value;

          }

        }

      }

      $product_query = new WP_Query(array(
        'post_type' => 'product',
        'post_status' => array('publish', 'draft'),
        'post_parent' => $post->ID,
        'posts_per_page' => -1,
        'language' => false
      ));

      if ($sublanguage->is_sub($language)) {

        $prefix = $sublanguage->get_prefix();

        foreach ($product_query->posts as $product) {

          $value = get_post_meta($product->ID, "{$prefix}post_content", true);

          if ($value) {

            $grid['product_description'][$product->ID] = $value;

          }

        }

      } else {

        foreach ($product_query->posts as $product) {

          $value = $product->post_content;

          if ($value) {

            $grid['product_description'][$product->ID] = $value;

          }

        }

      }

    }

    return $grid;

  }

  /**
	 *	@hook 'deepl4sublanguage_import_grid'
	 */
  public function deepl4sublanguage_import_grid($grid, $current_grid, $post, $language, $force, $sublanguage, $d4s) {



    if ($post->post_type === 'member') {

      if (isset($grid['serie'])) {

        if ($sublanguage->is_sub($language)) {

  				$prefix = $sublanguage->get_prefix($language);

  				foreach ($grid['serie'] as $serie_id => $text) {

  					if ($force || empty($current_grid['serie'][$serie_id])) {

  						update_post_meta($serie_id, "{$prefix}post_content", $text);

  					}

  				}

  			} else {

  				foreach ($grid['serie'] as $serie_id => $value) {



            $args = array();

  					if ($force || empty($current_grid['serie'][$serie_id])) {

  						$args['post_content'] = $value;

  					}



            if ($args) {

    					$args['ID'] = $serie_id;

              // echo '<pre>'; var_dump($args); die();

    					wp_update_post($args);

              // die();

    				}

  				}

  			}

      }

      if (isset($grid['product_description'])) {

        if ($sublanguage->is_sub($language)) {

  				$prefix = $sublanguage->get_prefix($language);

  				foreach ($grid['product_description'] as $product_id => $text) {

  					if ($force || empty($current_grid['product_description'][$product_id])) {

  						update_post_meta($product_id, "{$prefix}post_content", $text);

  					}

  				}

  			} else {

  				foreach ($grid['product_description'] as $product_id => $value) {

            $args = array();

  					if ($force || empty($current_grid['product_description'][$product_id])) {

  						$args['post_content'] = $value;

  					}

            if ($args) {

    					$args['ID'] = $product_id;

    					wp_update_post($args);

    				}

  				}

  			}

      }



		}

  }

  // /**
	//  *	@hook 'deepl4sublanguage_translate_post'
	//  */
  // public function translate_post($post, $target_language, $source_language, $force_translate, $sublanguage, $d4s) {
  //
  //   if ($post->post_type === 'member') {
  //
  //     $series_query = new WP_Query(array(
  //       'post_type' => 'serie',
  //       'post_status' => array('publish', 'draft'),
  //       'post_parent' => $post->ID,
  //       'posts_per_page' => -1
  //     ));
  //
  //     foreach ($series_query->posts as $serie) {
  //
  //       $d4s->translate_post($serie, $target_language->post_name, $source_language->post_name, $force_translate);
  //
  //     }
  //
  //     $product_query = new WP_Query(array(
  //       'post_type' => 'product',
  //       'post_status' => array('publish', 'draft'),
  //       'post_parent' => $post->ID,
  //       'posts_per_page' => -1
  //     ));
  //
  //     foreach ($product_query->posts as $product) {
  //
  //       $d4s->translate_post($product, $target_language->post_name, $source_language->post_name, $force_translate);
  //
  //     }
  //
  //   }
  //
  // }

  // /**
	//  *	@filter 'deepl4sublanguage_source_texts'
	//  */
  // public function translate_source_text($source_texts, $post, $target_language, $source_language, $force_translate, $sublanguage, $d4s) {
  //
  //   if ($post->post_type === 'member') {
  //
  //     if ($force_translate || !$d4s->get_meta_value($post, 'awards', $target_language, false)) {
  //
  //       $awards = $d4s->get_meta_value($post, 'awards', $source_language, false);
  //
  //       foreach ($awards as $i => $award) {
  //
  //         $award = (array) $award;
  //
  //         $source_texts["awards{$i}"] = $award['description'];
  //
  //       }
  //
  //       add_action('deepl4sublanguage_parse_texts', array($this, 'translate_parse_text'), 10, 8);
  //
  //     }
  //
  //   }
  //
  //
  //   return $source_texts;
  //
  // }
  //
  // /**
	//  *	@hook 'deepl4sublanguage_parse_texts'
	//  */
  // public function translate_parse_text($target_texts, $source_texts, $post, $target_language, $source_language, $force_translate, $sublanguage, $d4s) {
  //
  //   if ($post->post_type === 'member') {
  //
  //     $awards = $d4s->get_meta_value($post, 'awards', $source_language, false);
  //
  //     foreach ($awards as $i => $award) {
  //
  //       if (isset($target_texts["awards{$i}"])) {
  //
  //         update_post_meta($post->ID, 'awards', array(
  //           'year' => $award['year'],
  //           'description' => $target_texts["awards{$i}"],
  //         ), $award);
  //
  //       }
  //
  //     }
  //
  //   }
  //
  // }



  /**
	 *	@filter 'karma_fields_posts_driver_meta_results'
	 */
  public function update_product($null, $value, $key, $id, $args, $data) {
    global $wpdb;

    $member = get_post($id);



    if ($key === 'product' && $member && $member->post_type === 'member') {

      if ($value) {

        $product_ids = array_map('intval', $value);
        $product_ids_string = implode(',', $product_ids);
        $id = intval($id);

        $wpdb->query("UPDATE $wpdb->posts SET post_parent = 0 WHERE post_type = 'product' AND post_parent = $id AND ID NOT IN ($product_ids_string)");
        $wpdb->query("UPDATE $wpdb->posts SET post_parent = $id WHERE post_type = 'product' AND post_parent != $id AND ID IN ($product_ids_string)");

      } else {

        $wpdb->query("UPDATE $wpdb->posts SET post_parent = 0 WHERE post_type = 'product' AND post_parent = $id");

      }

      return true;
    }

  }

  /**
	 *	@filter 'karma_fields_posts_driver_meta_results'
	 */
  public function meta_product($results, $ids) {
    global $wpdb;

    if (is_string($ids)) return $results; // compat

    $ids_string = implode(',', array_map('intval', $ids));

    $post_type = $wpdb->get_var("SELECT post_type FROM $wpdb->posts WHERE ID in ($ids_string) LIMIT 1");

    if ($post_type === 'member') {

      $sql = "SELECT
        ID AS 'value',
        'product' AS 'key',
        post_parent AS 'id'
        FROM $wpdb->posts
        WHERE post_type = 'product' AND post_parent IN ($ids_string) ";



			$results = array_merge(
        (array) $results,
        $wpdb->get_results($sql, ARRAY_A)
      );

    }

    return $results;
  }



  /**
	 *	@hook 'rest_api_init'
	 */
	public function rest_api_init() {

    register_rest_route('actwall/v1', '/photographers', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_photographers'),
			'permission_callback' => '__return_true'
		));


    register_rest_route('actwall/v1', '/bio/(?P<member_id>\d+)/?', array(
			'methods' => 'GET',
			'callback' => array($this, 'rest_bio'),
			'permission_callback' => '__return_true'
		));

  }



  /**
	 * @hook init
	 */
	public function init() {

    register_post_type('member', array(
      'labels'             => array(
        'name' => 'Members',
        'singular_name' => 'Member'
      ),
      'public'             => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'publicly_queryable' => true,
      'rewrite'            => array(
        'slug' => 'member'
      ),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => true,
      // 'menu_position'      => null,
      'supports'           => array('title', 'editor'),
      'show_in_rest' => false
      // 'taxonomies' => array('module-category')
    ));



    register_taxonomy('member-category', 'member', array(
      'label'        => 'Member Category',
      'public'       => false,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));

    register_taxonomy('gender', 'member', array(
      'label'        => 'Gender',
      'public'       => false,
      'show_ui' => true,
      'show_admin_column' => true,
      'show_in_rest' => true,
      'rewrite'      => false,
      'hierarchical' => true
    ));


  }

  /**
   * @hook karma_fields_init
   */
  public function karma_fields_init($karma_fields) {

    // $karma_fields->register_driver(
		// 	'members',
		// 	get_template_directory().'/drivers/driver-members.php',
		// 	'Actwall_Driver_Members',
    //   array('meta', 'product', 'content'),
    //   array()
		// );

    // $karma_fields->register_menu_item('members', '?post_type=member');
    //
    // $karma_fields->register_table('members', array());


  }

  /**
   * @hook add_meta_boxes
   */
  public function meta_boxes($post_type, $post) {



    add_meta_box(
      'details',
      'Details',
      array($this, 'details_meta_box'),
      array('member'),
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
          'type' => 'group',
          'display' => 'flex',
          'children' => array(
            array(
              'type' => 'file',
              'key' => 'portrait_id',
              'label' => 'Portrait',
              'params' => array('parent' => array('getValue', 'id')),
              'max' => 1
            ),
            array(
              'type' => 'input',
              'key' => 'portrait_caption',
              'label' => 'Crédits portrait'
            )
          )
        ),



        array(
          'label' => 'Location',
          'type' => 'tags',
          'table' => 'locations',
          'driver' => 'locations',
          'key' => 'locations'
        ),
        array(
          'type' => 'input',
          'key' => 'specialisation',
          'label' => 'Type of photography'
        ),
        array(
          'type' => 'group',
          'display' => 'flex',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'firstname',
              'label' => 'Firstname'
            ),
            array(
              'type' => 'input',
              'key' => 'lastname',
              'label' => 'Lastname'
            )
          )
        ),
        array(
          'type' => 'input',
          'key' => 'representation',
          'label' => 'Represented by'
        ),
        array(
          'type' => 'group',
          'display' => 'flex',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'website_name',
              'label' => 'Website (Name)'
            ),
            array(
              'type' => 'input',
              'key' => 'website',
              'label' => 'Website (URL)',
              'style' => 'flex-grow: 1'
            )
          )
        ),
        array(
          'type' => 'input',
          'key' => 'email',
          'label' => 'Email'
        ),
        array(
          'type' => 'input',
          'key' => 'instagram',
          'label' => 'Instagram'
        ),
        array(
          'type' => 'array',
          'key' => 'awards',
          // 'translatable' => true,
          'label' => 'Awards',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'name',
              'label' => 'Année',
              'width' => '10em'
            ),
            array(
              'type' => 'input',
              'key' => 'description',
              'label' => 'Nom'
            )
          )
        ),
        array(
          'type' => 'array',
          'key' => 'publications',
          'label' => 'Publications',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'name',
              'label' => 'Année',
              'width' => '10em'
            ),
            array(
              'type' => 'input',
              'key' => 'description',
              'label' => 'Nom'
            )
          )
        ),
        array(
          'type' => 'array',
          'key' => 'exhibitions',
          'label' => 'Exhibitions',
          'children' => array(
            array(
              'type' => 'input',
              'key' => 'name',
              'label' => 'Année',
              'width' => '10em'
            ),
            array(
              'type' => 'input',
              'key' => 'description',
              'label' => 'Nom'
            )
          )
        ),
        // array(
        //   'label' => 'Publications',
        //   'type' => 'tinymce',
        //   'header' => array(
        //     'children' => array('bold', 'italic')
        //   ),
        //   'key' => 'publications'
        // ),
        // array(
        //   'label' => 'Exhibitions',
        //   'type' => 'tinymce',
        //   'key' => 'exhibitions',
        //   'header' => array(
        //     'children' => array('bold', 'italic')
        //   )
        // ),

        // array(
        //   'label' => 'Exhibitions',
        //   'type' => 'tags',
        //   'table' => 'shows',
        //   'driver' => 'posts',
        //   'params' => array(
        //     'post_type' => 'show'
  			// 	),
        //   'key' => 'shows'
        // ),
        // array(
        //   'label' => 'Books',
        //   'type' => 'tags',
        //   'table' => 'publications',
        //   'driver' => 'posts',
        //   'params' => array(
        //     'post_type' => 'publication'
  			// 	),
        //   'key' => 'publications'
        // ),



        array(
          'label' => 'Séries',
          'type' => 'gridField',
          'driver' => 'posts',
          'params' => array(
            'post_type' => 'serie',
            'post_parent' => array('getValue', 'id'),
            'post_status' => 'publish'
          ),
          'defaults' => array(
            'post_type' => 'serie',
            'post_parent' => array('getValue', 'id'),
            'post_status' => 'publish'
          ),
          'position' => array('count', array('request', 'getQuery')),
          'children' => array(
            array(
              'type' => 'links',
              'label' => 'Name',
              'table' => 'serie',
              'params' => array('id' => array('getValue', 'id')),
              'content' => array('||', array('getValue', 'post_title'), '[sans nom]')
            )

            // array(
            //   'type' => 'input',
            //   'key' => 'name',
            //   'label' => 'Nom',
            //   'width' => '1fr'
            // ),
            // array(
            //   'type' => 'input',
            //   'key' => 'date',
            //   'label' => 'Date',
            //   'width' => 'auto'
            // ),
            // array(
            //   'type' => 'input',
            //   'key' => 'lieu',
            //   'label' => 'Lieu',
            //   'width' => 'auto'
            // ),
            // array(
            //   'type' => 'dropdown',
            //   'key' => 'mode',
            //   'label' => 'Mode',
            //   'options' => array(
            //     array('id' => '', 'name' => 'Tout format confondu'),
            //     array('id' => 'format', 'name' => 'Par format')
            //   ),
            //   'width' => 'auto'
            // ),
            // array(
            //   'type' => 'dropdown',
            //   'key' => 'type',
            //   'label' => 'Type',
            //   'options' => array(
            //     array('id' => '', 'name' => '-'),
            //     array('id' => 'type1', 'name' => 'Type 1'),
            //     array('id' => 'type2', 'name' => 'Type 2'),
            //     array('id' => 'type3', 'name' => 'Type 3')
            //   ),
            //   'width' => 'auto'
            // )
          )
        ),

        // array(
        //   'label' => 'Formats',
        //   'type' => 'gridField',
        //   'driver' => 'formats',
        //   'params' => array(
        //     'member_id' => array('getValue', 'id')
        //   ),
        //   'position' => array('count', array('request', 'getQuery')),
        //   'children' => array(
        //     array(
        //       'type' => 'input',
        //       'key' => 'name',
        //       'label' => 'Nom',
        //       'width' => '1fr'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'image_width',
        //       'label' => 'Largeur image',
        //       'width' => '6em'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'image_height',
        //       'label' => 'Hauteur image',
        //       'width' => '6em'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'paper_width',
        //       'label' => 'Largeur papier',
        //       'width' => '6em'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'paper_height',
        //       'label' => 'Hauteur papier',
        //       'width' => '6em'
        //     ),
        //
        //     array(
        //       'type' => 'dropdown',
        //       'key' => 'style',
        //       'label' => 'Style',
        //       'options' => array(
        //         array('id' => '', 'name' => '-'),
        //         array('id' => '1', 'name' => 'Passe-partout'),
        //         array('id' => '2', 'name' => 'Bord blanc'),
        //         array('id' => '3', 'name' => 'Franc-bord')
        //       ),
        //       'width' => '7em'
        //     ),
        //     array(
        //       'type' => 'dropdown',
        //       'key' => 'collage',
        //       'label' => 'Collage',
        //       'options' => array(
        //         array('id' => '', 'name' => '-'),
        //         array('id' => '1', 'name' => 'Aluminium'),
        //         array('id' => '2', 'name' => 'Dibond 2 mm'),
        //         array('id' => '3', 'name' => 'Dibond 3 mm')
        //       ),
        //       'width' => '7em'
        //     ),
        //     array(
        //       'type' => 'dropdown',
        //       'key' => 'frame',
        //       'label' => 'Cadre',
        //       'options' => array(
        //         array('id' => '', 'name' => '-'),
        //         array('id' => '1', 'name' => 'Bois'),
        //         array('id' => '2', 'name' => 'Aluminium'),
        //         array('id' => '3', 'name' => 'Sans')
        //       ),
        //       'width' => '7em'
        //     ),
        //
        //     array(
        //       'type' => 'dropdown',
        //       'key' => 'bois',
        //       'label' => 'Essence',
        //       'options' => array(
        //         array('id' => '', 'name' => '-'),
        //         array('id' => '1', 'name' => 'Chêne'),
        //         array('id' => '2', 'name' => 'Pommier'),
        //         array('id' => '3', 'name' => 'Sapin')
        //       ),
        //       'width' => '7em'
        //     ),
        //
        //     array(
        //       'type' => 'input',
        //       'key' => 'prix_tirage',
        //       'label' => 'Prix tirage',
        //       'width' => '6em'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'prix_encadre',
        //       'label' => 'Prix encadré',
        //       'width' => '6em'
        //     )
        //   )
        // ),
        //
        // array(
        //   'label' => 'Images',
        //   'type' => 'gridField',
        //   'driver' => 'posts',
        //   'params' => array(
        //     'post_type' => 'product',
        //     'post_parent' => array('getValue', 'id'),
        //     'post_status' => 'publish'
        //   ),
        //   'position' => array('count', array('request', 'getQuery')),
        //   'children' => array(
        //     array(
        //       'type' => 'file',
        //       'key' => 'attachments',
        //       'max' => 1,
        //       'label' => 'File',
        //       // 'uploader' => 'wp',
        //       'width' => 'auto'
        //     ),
        //     array(
        //       'type' => 'input',
        //       'key' => 'name',
        //       'label' => 'Titre',
        //       'placeholder' => array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie'), 'post_title'),
        //       'width' => '15em'
        //     ),
        //     array(
        //       'type' => 'dropdown',
        //       'label' => 'Série',
        //       'key' => 'serie',
        //       'options' => array(array('id' => '', 'name' => '-')),
        //       'driver' => 'posts',
        //       'params' => array(
        //         'post_type' => 'serie',
        //         'post_parent' => array('queryValue', 'posts', array('getValue', 'id'), 'post_parent'),
        //         'post_status' => 'publish'
        //       ),
        //       'width' => '7em'
        //     ),
        //     array(
        //       'key' => 'nb',
        //       'type' => 'input',
        //       'label' => 'Nombre',
        //       'width' => '6em',
        //       'disabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('getValue', 'id'), 'serie'), 'mode'), "format")
        //     ),
        //     // array(
        //     //   'type' => 'dropdown',
        //     //   'label' => 'Format',
        //     //   'key' => 'format',
        //     //   'options' => array(array('id' => '', 'name' => '-')),
        //     //   'driver' => 'formats',
        //     //   'params' => array(
        //     //     'member_id' => array('queryValue', 'posts', array('getValue', 'id'), 'post_parent')
        //     //   ),
        //     //   'width' => '7em'
        //     // ),
        //     array(
        //       'type' => 'array',
        //
        //       // 'key' => 'editions',
        //       'label' => 'Editions',
        //       'children' => array(
        //         array(
        //           'type' => 'dropdown',
        //           'label' => 'Format',
        //           'key' => 'format',
        //           'options' => array(array('id' => '', 'name' => '-')),
        //           'driver' => 'formats',
        //           'params' => array(
        //             'member_id' => array('queryValue', 'posts', array('getValue', 'id'), 'post_parent')
        //           )
        //         ),
        //         // array(
        //         //   'type' => 'text',
        //         //   'content' => array('request', 'getId')
        //         // ),
        //         array(
        //           'key' => 'nb_edition',
        //           'type' => 'input',
        //           'label' => 'Nombre',
        //           'width' => '6em',
        //           'enabled' => array("=", array('queryValue', 'posts', array('queryValue', 'posts', array('request', 'getId'), 'serie'), 'mode'), "format")
        //         ),
        //         array(
        //           'key' => 'nb_produit',
        //           'type' => 'input',
        //           'label' => 'Nombre produits',
        //           'width' => '6em'
        //         ),
        //         array(
        //           'key' => 'nb_vendu',
        //           'type' => 'input',
        //           'label' => 'Nombre vendu',
        //           'width' => '6em',
        //           'placeholder' => array('count', array('query', 'ventes', array('format_id' => array('getValue', 'format'))))
        //         ),
        //         array(
        //           'key' => 'prix_tirage',
        //           'type' => 'input',
        //           'label' => 'Prix tirage',
        //           'width' => '6em',
        //           'placeholder' => array('queryValue', 'formats', array('getValue', 'format'), 'prix_tirage')
        //         ),
        //         array(
        //           'key' => 'prix_encadre',
        //           'type' => 'input',
        //           'label' => 'Prix encadré',
        //           'width' => '6em',
        //           'placeholder' => array('queryValue', 'formats', array('getValue', 'format'), 'prix_encadre')
        //         )
        //       ),
        //       'width' => '1fr'
        //     )
        //
        //   )
        // ),



        array(
          'label' => 'Ventes',
          'type' => 'gridField',
          'driver' => 'ventes',
          'params' => array(
            'member_id' => array('getValue', 'id')
          ),
          'children' => array(
            array(
              'type' => "media",
              'id' => array('queryValue', 'posts', array('getValue', 'product_id'), 'attachments'),
              'driver' => 'medias',
              'width' => '10em',
              'display' => 'thumb'
            ),
            array(
              'type' => 'date',
              'key' => 'time',
              'label' => 'Date',
              'width' => 'auto'
            ),

            array(
              'type' => 'dropdown',
              'label' => 'Image',
              'key' => 'product_id',
              'options' => array(array('id' => '', 'name' => '-')),
              'driver' => 'posts',
              'params' => array(
                'post_type' => 'product',
                'post_parent' => array('getValue', 'member_id'),
                'post_status' => 'publish'
              ),
              'width' => '10em'
            ),
            array(
              'type' => 'dropdown',
              'label' => 'Format',
              'key' => 'format_id',
              'options' => array(array('id' => '', 'name' => '-')),
              'driver' => 'formats',
              'params' => array(
                // 'member_id' => array('queryValue', 'ventes', array('getValue', 'id'), 'member_id'),
                'ids' => array('join', array('queryValue', 'posts', array('getValue', 'product_id'), 'format_id'), ',')
              ),
              'width' => '10em'
            ),
            // array(
            //   'type' => 'text',
            //   'content' => array('join', array('queryValue', 'posts', array('getValue', 'product_id'), 'format_id'), ', ')
            // ),
            array(
              'type' => 'text',
              'label' => 'Prix',
              'content' => array('getValue', 'prix'),
              'width' => '8em'
            ),
            // array(
            //   'type' => 'text',
            //   'label' => 'Prix',
            //   'content' => array('||',
            //     array('getAt', array('queryValue', 'posts', array('getValue', 'product_id'), 'prix_encadre'), array('indexOf', array('queryValue', 'posts', array('getValue', 'product_id'), 'format_id'), array('getValue', 'format_id'))),
            //     array('queryValue', 'formats', array('getValue', 'format_id'), 'prix_encadre')
            //   ),
            //   'width' => '8em'
            // ),
            // array(
            //   'type' => 'checkbox',
            //   'key' => 'produit',
            //   'label' => 'Produit',
            //   'width' => '1fr'
            // )


          )
        ),




      )
    ));


  }


  /**
   * @hook maxitype_typeface
   */
  public function print_page_member() {
    global $wp_query;

    require_once get_stylesheet_directory() . '/classes/class-image.php';

    Karma_Image::cache_image_query($wp_query, array('images'));


    include get_stylesheet_directory().'/include/page-member.php';

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


  /**
	 *	@hook 'actwall_member_header'
	 */
	public function print_header() {
    global $post;

    $member = $post;

    include get_Stylesheet_directory() . '/include/members/single-header.php';

  }

  /**
	 *	@hook 'actwall_member_body'
	 */
	public function print_body() {
    global $post;


    require_once get_stylesheet_directory() . '/class-image.php';

    $portrait_id = get_post_meta($post->ID, 'portrait_id', true);

    if ($portrait_id) {

      $portrait = Karma_Image::get_image_source($portrait_id);

      // $portrait_credit = wp_get_attachment_caption($portrait_id);
      $portrait_caption = get_post_meta($post->ID, 'portrait_caption', true);

    }

    $representation = get_post_meta($post->ID, 'representation', true);
    $email = get_post_meta($post->ID, 'email', true);
    $website = get_post_meta($post->ID, 'website', true);
    $website_name = get_post_meta($post->ID, 'website_name', true);
    $instagram = get_post_meta($post->ID, 'instagram', true);
    $awards = get_post_meta($post->ID, 'awards');

    $exhibitions = get_post_meta($post->ID, 'exhibitions');
    $publications = get_post_meta($post->ID, 'publications');

    // compat
    if ($exhibitions === array('')) $exhibitions = array();
    if ($publications === array('')) $publications = array();



    include get_stylesheet_directory() . '/include/members/single-body.php';

  }

  /**
   * @rest 'wp-json/actwall/v1/bio'
   */
  public function rest_bio($request) {
    global $wpdb;

    $member_id = $request->get_param("member_id");

    $post = get_post($member_id);

    require_once get_stylesheet_directory() . '/class-image.php';

    $portrait_id = get_post_meta($post->ID, 'portrait_id', true);

    if ($portrait_id) {

      $portrait = Karma_Image::get_image_source($portrait_id);

    }

    $portrait_caption = get_post_meta($post->ID, 'portrait_caption', true);

    $representation = get_post_meta($post->ID, 'representation', true);
    $email = get_post_meta($post->ID, 'email', true);
    $website = get_post_meta($post->ID, 'website', true);
    $website_name = get_post_meta($post->ID, 'website_name', true);
    $instagram = get_post_meta($post->ID, 'instagram', true);
    $awards = get_post_meta($post->ID, 'awards');
    $exhibitions = get_post_meta($post->ID, 'exhibitions');
    $publications = get_post_meta($post->ID, 'publications');

    // compat
    if ($exhibitions === array('')) $exhibitions = array();
    if ($publications === array('')) $publications = array();

    return array(
      'id' => $post->ID,
      'title' => $post->post_title,
      'content' =>  apply_filters('the_content', apply_filters('sublanguage_translate_post_field', $post->post_content, $post, 'post_content')),
      'portrait' => isset($portrait) ? $portrait : null,
      'portrait_caption' => $portrait_caption,
      'representation' => $representation,
      'email' => $email,
      'website' => $website,
      'website_name' => $website_name,
      'instagram' => $instagram,
      'awards' => $awards,
      'exhibitions' => $exhibitions,
      'publications' => $publications
    );

  }



  /**
	 *	@hook 'actwall_portfolio'
	 */
	public function print_portfolio() {
    global $wpdb, $post;

    if (isset($_GET['m'])) {

      $post_name = $_GET['m'];

      $member_query = new WP_Query(array(
        'post_type' => 'member',
        'post_status' => 'publish',
        'name' => $post_name,
        'posts_per_page' => 1
      ));


      if ($member_query->have_posts()) {

        $member_id = intval($member_query->posts[0]->ID);



        $serie_ids = $wpdb->get_col(
          "SELECT serie.ID FROM $wpdb->posts AS serie
          INNER JOIN $wpdb->postmeta AS pm ON (pm.meta_value = serie.ID)
          INNER JOIN $wpdb->posts AS product ON (product.ID = pm.post_id)
          WHERE serie.post_parent = $member_id AND serie.post_type = 'serie' AND product.post_type = 'product' AND pm.meta_key = 'serie'"
        );

        if ($serie_ids) {

          $series_query = new WP_Query(array(
            'post_type' => 'serie',
            'post_status' => 'publish',
            'post_parent' => $member_id,
            'posts_per_page' => -1,
            'post__in' => $serie_ids
          ));

          while ($member_query->have_posts()) {

            $member_query->the_post();

            $member = $member_query->post;

            include get_stylesheet_directory() . '/include/members/single-header.php';
            include get_stylesheet_directory() . '/include/members/portfolio-body.php';

            break;

          }

          wp_reset_postdata();

        }

      }

    }

  }



  /**
	 *	@hook 'actwall_page_photographers'
	 */
	public function print_page_photographers() {
    global $wpdb, $post, $wpdb;

    $member_query = new WP_Query(array(
      'post_type' => 'member',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      // 'meta_key' => 'lastname',
      // 'orderby' => array('meta_value' => 'asc', 'title' => 'asc')
      'orderby' => array('title' => 'asc')
    ));





    // $locations_results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}locations WHERE trash = 0");
    // $locations = array();
    //
    // foreach ($locations_results as $result) {
    //
    //   $locations[$result->id] = $result->name;
    //
    // }

    if ($member_query->have_posts()) {

      $categories = get_terms(array(
        'taxonomy'   => 'member-category',
        'hide_empty' => false
      ));

      $members = $this->prepare_members($member_query->posts);

      include get_stylesheet_directory() . '/include/members/photographers.php';

    }

  }



  /**
   * @rest 'wp-json/actwall/v1/photographers'
   */
  public function rest_photographers($request) {
    global $wpdb;

    $params = $request->get_params();

    $args = array(
      'post_type' => 'member',
      'post_status' => 'publish',
      'posts_per_page' => 200,
      // 'meta_key' => 'lastname',
      // 'orderby' => array('meta_value' => 'asc', 'title' => 'asc')

      'orderby' => array('title' => 'asc')
    );

    foreach ($params as $key => $value) {

      switch ($key) {

        case "photo-type":
          // ??
          break;

        case "gender":
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

        case 'letter';
          $args['letter'] = $value;
          break;

      }

    }

    add_filter('posts_where', array($this, 'posts_where'), 10, 2);

    $member_query = new WP_Query($args);

    return $this->prepare_members($member_query->posts);

  }

  /**
   * filter 'posts_where'
   */
  public function posts_where($where, $wp_query) {

    if (isset($wp_query->query_vars['letter'])) {

      $letter = esc_sql($wp_query->query_vars['letter']);
      $where .= "AND wp_posts.post_title LIKE '$letter%'";

    }

    return $where;

  }



  /**
   * @helper prepare_members
   */
  public function prepare_members($members) {
    global $wpdb;

    $locations_results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}locations WHERE trash = 0");
    $locations = array();

    foreach ($locations_results as $result) {

      $locations[$result->id] = $result->name;

    }

    $output = array();

    foreach ($members as $member) {

      $location_id = get_post_meta($member->ID, 'locations', true);
      $specialisation = get_post_meta($member->ID, 'specialisation', true);

      $output[] = array(
        'id' => $member->ID,
        'title' => get_the_title($member->ID),
        'content' => apply_filters('the_content', $member->post_content),
        'location_id' => $location_id,
        'location' => $location_id && isset($locations[$location_id]) ? $locations[$location_id] : '',
        'specialisation' => $specialisation,
        'permalink' => get_permalink($member->ID)
      );

    }

    return $output;
  }

}


new Actwall_Members;
