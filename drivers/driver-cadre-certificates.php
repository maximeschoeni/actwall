<?php


class Actwall_Driver_Cadre_Certificates {

  /**
	 * update
	 */
  public function update($item, $id) {
    global $wpdb;

    if (!is_user_logged_in()) {
      return;
    }

    $table = "{$wpdb->prefix}cadre_certificates";

    $values = array();
    $value_types = array();

    foreach ($item as $key => $value) {

      switch ($key) {

        case 'name':
        case '_fr_name':
          $values[$key] = $value[0];
          $value_types[] = '%s';
          break;

        case 'trash':
          $values[$key] = $value[0];
          $value_types[] = '%d';
          break;

      }

    }

    if ($values && $value_types) {

      $wpdb->update($table, $values, array('id' => $id), $value_types, array('%d'));

    }

    return true;

  }


  /**
	 * add
	 */
  public function add() {
    global $wpdb;

    $table = "{$wpdb->prefix}cadre_certificates";

    $wpdb->insert($table, array('trash' => 1), array('%d'));

    return $wpdb->insert_id;

  }

	/**
	 * query
	 */
  public function query($params) {
    global $wpdb;

    $table = "{$wpdb->prefix}cadre_certificates";

    // order

    $order = '';

    if (isset($params['orderby'])) {

      $dir = isset($params['order']) && $params['order'] === 'desc' ? 'DESC' : 'ASC';

      switch ($params['orderby']) {

        case 'name':
        default:
          $order = "ORDER BY name $dir";
          break;

      }

    }


    // limit

    $limit = '';

    if (isset($params['ppp'])) {

      $ppp = intval($params['ppp']);

      if ($ppp > 0) {

        if (isset($params['page'])) {

          $page = intval($params['page']);

        } else {

          $page = 1;

        }

        $offset = $ppp*($page-1);

        $limit = "LIMIT $offset, $ppp";

      }

    }


    // where

    $where_clauses = array();

    $where_clauses[] = "trash = 0";

    if ($where_clauses) {

      $where = "WHERE " .implode(' AND ', $where_clauses);

    } else {

      $where = '';
    }

    $sql = "SELECT
      id,
      name,
      _fr_name
      FROM $table
      $where
      $order
      $limit";

    return $wpdb->get_results($sql);

  }

  /**
	 * count
	 */
  public function count($params) {
    global $wpdb;

    $table = "{$wpdb->prefix}cadre_certificates";

    // where

    $where_clauses = array();

    $where_clauses[] = "trash = 0";

    if ($where_clauses) {

      $where = "WHERE " .implode(' AND ', $where_clauses);

    } else {

      $where = '';
    }

    return $wpdb->get_var("SELECT COUNT(id) FROM $table $where");

  }


}
