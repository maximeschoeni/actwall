<?php


class Actwall_Pages {

  /**
   * constructor
   */
  public function __construct() {

    add_action('actwall_page_our_engagement_header', array($this, 'print_our_engagement_header'));
    add_action('actwall_page_our_engagement_body', array($this, 'print_our_engagement_body'));

    add_action('add_meta_boxes', array($this, 'meta_boxes'), 10, 2);

  }

  /**
   * @hook add_meta_boxes
   */
  public function meta_boxes($post_type, $post) {

    $template = get_page_template_slug();

    if ($template === 'template-ourengagement.php') {

      add_meta_box(
        'ourengagement',
        'Details',
        array($this, 'ourengagement_meta_box'),
        array('page'),
        'normal',
        'default'
      );

    }

  }

  /**
   * @callback add_meta_box
   */
  public function ourengagement_meta_box($post) {

    do_action('karma_fields_post_field', $post->ID, array(
      'type' => 'group',
      'children' => array(
        array(
          'label' => 'Tagline',
          'type' => 'textarea',
          'key' => 'tagline',
          'translatable' => true
        ),
        array(
          'label' => 'Address',
          'type' => 'input',
          'key' => 'address'
        ),
        array(
          'label' => 'Email',
          'type' => 'input',
          'key' => 'email'
        ),
        array(
          'label' => 'Phone',
          'type' => 'input',
          'key' => 'phone'
        ),
        array(
          'label' => 'Instagram',
          'type' => 'input',
          'key' => 'instagram'
        ),
        array(
          'label' => 'Equipe',
          'type' => 'array',
          'key' => 'team',
          'translatable' => true,
          'children' => array(
            array(
              'label' => 'Name',
              'type' => 'input',
              'key' => 'name'
            ),
            array(
              'label' => 'Department',
              'type' => 'input',
              'key' => 'department'
            )
          )
        )

      )
    ));

  }

  /**
	 *	@hook 'actwall_home_header'
	 */
	public function print_our_engagement_header() {
    global $post;

    include get_Stylesheet_directory() . '/include/page/header-our-engagement.php';

  }

  /**
	 *	@hook 'actwall_home_header'
	 */
	public function print_our_engagement_body() {
    global $post;

    include get_Stylesheet_directory() . '/include/page/body-our-engagement.php';

  }






}


new Actwall_Pages;
