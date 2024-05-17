<?php


Class Actwall_Driver_Cadres {

  /**
	 * update
	 */
  public function update($item, $id) {
    global $wpdb;

    if (!is_user_logged_in()) {
      return;
    }

    $table = "{$wpdb->prefix}cadres";

    $values = array();
    $value_types = array();

    foreach ($item as $key => $value) {

      switch ($key) {

        case 'name':
        case '_fr_name':
        case 'content':
          $values[$key] = $value[0];
          $value_types[] = '%s';
          break;

        case 'image_id':
        case 'type_id':
          $values[$key] = intval($value[0]);
          $value_types[] = '%d';
          break;

        case 'width':
          $values[$key] = floatval($value[0]);
          $value_types[] = '%s';
          break;

        case 'textures':
          $values[$key] = json_encode($value);
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

    $table = "{$wpdb->prefix}cadres";

    $wpdb->insert($table, array('trash' => 1), array('%d'));

    return $wpdb->insert_id;

  }

	/**
	 * query
	 */
  public function query($params) {
    global $wpdb;

    $table = "{$wpdb->prefix}cadres";

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

    if (isset($params['search'])) {

      $search = esc_sql($params['search']);
      $where_clauses[] = "(name LIKE '%$search%' OR content LIKE '%$search%')";

    }

    if (isset($params['type_id'])) {

      $type_id = intval($params['type_id']);
      $where_clauses[] = "type_id = $type_id";

    }

    if (isset($params['ids']) ) {

      $ids = explode(',', $params['ids']);

      if ($ids) {

        $ids = array_map('intval', $ids);
        $ids = implode(',', $ids);

        $where_clauses[] = "id IN ($ids)";

      }

    }

    if ($where_clauses) {

      $where = "WHERE " .implode(' AND ', $where_clauses);

    } else {

      $where = '';
    }

    $sql = "SELECT
      id,
      name,
      _fr_name,
      content,
      type_id,
      image_id,
      width
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

    $table = "{$wpdb->prefix}cadres";

    // where

    $where_clauses = array();

    $where_clauses[] = "trash = 0";

    if (isset($params['search'])) {

      $search = esc_sql($params['search']);
      $where_clauses[] = "(name LIKE '%$search%' OR content LIKE '%$search%')";

    }

    if (isset($params['type_id'])) {

      $type_id = intval($params['type_id']);
      $where_clauses[] = "type_id = $type_id";

    }

    if (isset($params['ids']) ) {

      $ids = explode(',', $params['ids']);

      if ($ids) {

        $ids = array_map('intval', $ids);
        $ids = implode(',', $ids);

        $where_clauses[] = "id IN ($ids)";

      }

    }

    if ($where_clauses) {

      $where = "WHERE " .implode(' AND ', $where_clauses);

    } else {

      $where = '';
    }

    return $wpdb->get_var("SELECT COUNT(id) FROM $table $where");

  }


  public function textures($params) {
    global $wpdb;

    $ids = explode(',', $params['ids']);
    $ids = array_map('intval', $ids);

    if ($ids) {

      $ids = implode(',', $ids);

    }

    $output = array();

    $cadres = $wpdb->get_results("SELECT id, textures FROM {$wpdb->prefix}cadres WHERE trash = 0 AND id IN ($ids)");

    foreach ($cadres as $cadre) {

      if ($cadre->textures) {

        $textures = json_decode($cadre->textures);

        foreach ($textures as $texture) {

          $output[] = array(
            'id' => $cadre->id,
            'key' => 'textures',
            'value' => $texture
          );

        }

      }

    }

    return $output;

  }


}
