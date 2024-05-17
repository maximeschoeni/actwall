<?php

require_once KARMA_FIELDS_ALPHA_PATH.'/drivers/driver-posts.php';

class Actwall_Driver_Members extends Karma_Fields_Alpha_Driver_Posts {

  /**
	 * update
	 */
  public function update($data, $id) {
    global $wpdb;

    $data = (array) $data;

    if (isset($data['product'])) {

      $product_ids = array_map('intval', $data['product']);

      if ($product_ids) {

        $product_ids_string = implode(',', $product_ids);
        $id = intval($id);

        $wpdb->query("UPDATE $wpdb->posts SET post_parent = 0 WHERE post_type = 'product' AND post_parent = $id AND ID NOT IN ($product_ids_string)");
        $wpdb->query("UPDATE $wpdb->posts SET post_parent = $id WHERE post_type = 'product' AND post_parent != $id AND ID IN ($product_ids_string)");

      } else {

        $wpdb->query("UPDATE post_parent = 0 WHERE post_type = 'product' AND post_parent = $id");

      }

      unset($data['product']);

    }

    return parent::update($data, $id);

  }


  /**
	 * meta relations
	 */
  public function product($params) {
    global $wpdb;

    $ids = explode(',', $params['ids']);
    $ids = array_filter($ids);

    if ($ids) {

      $ids = array_map('intval', $ids);
      $ids = implode(',', $ids);

      $sql = "SELECT
        ID AS 'value',
        'product' AS 'key',
        post_parent AS 'id'
        FROM $wpdb->posts
        WHERE post_type = 'product' AND post_parent IN ($ids) ";

			$results = $wpdb->get_results($sql, ARRAY_A);

      return $results;

    } else {

      return array();

    }

  }



}
