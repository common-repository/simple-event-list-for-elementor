<?php

global $wpdb;
defined("TABLE_NAME_MIGA_SIMPLE_EVENTS") or
  define("TABLE_NAME_MIGA_SIMPLE_EVENTS", $wpdb->prefix . "miga_simple_events");


class My_List_Table extends WP_List_Table
{
    private $table_data;

    public function prepare_items()
    {
        $this->process_bulk_action();
        $this->table_data = $this->get_table_data();

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $primary  = 'name';
        $this->_column_headers = array($columns, $hidden, $sortable, $primary);
        usort($this->table_data, array(&$this, 'usort_reorder'));
        $this->items = $this->table_data;
    }

    public function process_bulk_action()
    {
        // security check!
        if (isset($_POST['_wpnonce']) && ! empty($_POST['_wpnonce'])) {
            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_UNSAFE_RAW);
            $action = 'bulk-' . $this->_args['plural'];

            if (! wp_verify_nonce($nonce, $action)) {
                wp_die('Nope! Security check failed!');
            }
        }

        $table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
        if ($this->current_action() === "delete_all") {
          $ids = esc_sql($_POST['element']);
          foreach ($ids as $id) {
              global $wpdb;
              $wpdb->delete($table_name, [
                  "id" => (int)$id
              ]);
          }
        } elseif ($this->current_action() === "toggle_all") {
            $ids = esc_sql($_POST['element']);
            foreach ($ids as $id) {
                global $wpdb;
                $results = $wpdb->get_results("UPDATE $table_name SET visible = NOT visible WHERE id=".(int)$id);
            }
        }
    }


      private function get_table_data()
      {
          global $wpdb;

          $table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
          $data = $wpdb->get_results(
              "SELECT * from {$table_name} ORDER BY dateFrom ASC",
              ARRAY_A
          );

          $posts = get_posts();
          $postOptions = array();
          foreach ($posts as $post) {
              $postOptions[$post->ID] = $post->post_title;
          }

          foreach ($data as &$value) {
              if (isset($value["link"])) {
                  if (isset($postOptions[(int)$value["link"]])) {
                      $value["link"] = $postOptions[(int)$value["link"]];
                  } else {
                      $value["link"] = "-";
                  }
              } else {
                  $value["link"] = "-";
              }

              $value["dateFrom"] = wp_date(get_option('date_format'), strtotime($value["dateFrom"]. " 12:00"));
              if (isset($value["dateTo"])) {
                  $value["dateTo"] = wp_date(get_option('date_format'), strtotime($value["dateTo"]. " 12:00"));
              } else {
                  $value["dateTo"] = "-";
              }

              if (!isset($value["timeTo"])) {
                  $value["timeTo"] ="-";
              }
              if (!isset($value["timeFrom"])) {
                  $value["timeFrom"] ="-";
              }

              if ($value["visible"]==1) {
                  $value["visible"] = '<i class="dashicons dashicons-visibility"></i>';
              } else {
                  $value["visible"] = '<i class="dashicons dashicons-hidden" style="opacity:0.5"></i>';
              }
          }

          return $data;
      }


      public function get_columns()
      {
          $columns = array(
                  'cb'            => '<input type="checkbox" />',
                  'dateFrom'          => __('From (date)', 'simple-event-list-for-elementor'),
                  'timeFrom'          => __('From (time)', 'simple-event-list-for-elementor'),
                  'dateTo'         => __('To (date)', 'simple-event-list-for-elementor'),
                  'timeTo'         => __('To (time)', 'simple-event-list-for-elementor'),
                  'text'   => __('Text', 'simple-event-list-for-elementor'),
                  'link'        => __('Link', 'simple-event-list-for-elementor'),
                  'visible'        => __('Visible', 'simple-event-list-for-elementor')
          );
          return $columns;
      }

      public function column_default($item, $column_name)
      {
          switch ($column_name) {
              case 'id':
              case 'dateFrom':
              case 'timeFrom':
              case 'dateTo':
              case 'timeTo':
              case 'text':
              case 'link':
              case 'visible':
              default:
                  return $item[$column_name];
          }
      }

   public function column_cb($item)
   {
       return sprintf(
           '<input type="checkbox" name="element[]" value="%s" />',
           $item['id']
       );
   }

    // protected function get_sortable_columns()
    // {
    //     $sortable_columns = array(
    //           'dateFrom' => array('dateFrom', true),
    //           'dateTo'   => array('dateTo', false)
    //     );
    //     return $sortable_columns;
    // }

    public function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'dateFrom';

        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    // Adding action links to column
    public function column_dateFrom($item)
    {
        $actions = array(
                'edit'      => sprintf('<a href="?page=%s&action=%s&element=%s">' . __('Edit', 'simple-event-list-for-elementor') . '</a>', $_REQUEST['page'], 'edit', $item['id']),
                'delete'    => sprintf('<a href="?page=%s&action=%s&element=%s">' . __('Delete', 'simple-event-list-for-elementor') . '</a>', $_REQUEST['page'], 'delete', $item['id']),
                'toggle_visibility'    => sprintf('<a href="?page=%s&action=%s&element=%s">' . __('Toggle visibility', 'simple-event-list-for-elementor') . '</a>', $_REQUEST['page'], 'toggle_visibility', $item['id']),
        );

        return sprintf('%1$s %2$s', $item['dateFrom'], $this->row_actions($actions));
    }

    // To show bulk action dropdown
    public function get_bulk_actions()
    {
        $actions = array(
                'delete_all'    => __('Delete', 'simple-event-list-for-elementor'),
                'toggle_all' => __('Toggle visibility', 'simple-event-list-for-elementor')
        );
        return $actions;
    }
}
