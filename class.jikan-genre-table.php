<?php

if (!class_exists("WP_List_Table")) {
    require_once(ABSPATH . "wp-admin/includes/class-wp-list-table.php");
}
class GenreTable extends WP_List_Table
{
    private $_items;
    function __construct($args = array())
    {
        parent::__construct($args);
    }
    function set_data($result)
    {
        $this->_items = $result;
    }
    function get_columns() {
        return [
            'mal_id' =>__('Mal ID','jikan'),
            'name'  => __( 'Name', 'jikan' ),
            'count'  => __( 'Anime Count', 'jikan' ),
            'url'  => __( 'Genre URL', 'jikan' ),
        ];
    }
    function get_sortable_columns() {
        return [
            'mal_id'  => [ 'mal_id', true ],
            'name' => [ 'name', true ],
            'count' => [ 'count', true ],
        ];
    }
    function column_url($item)
    {
        return "<a href=''>{$item['url']}</a>";
    }
    function prepare_items()
    {
        $paged = $_REQUEST['paged'] ?? 1;
        $per_page = 10;
        $total_items = count($this->_items);

        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $data_chunks = array_chunk($this->_items, $per_page);
        $this->items = $data_chunks[$paged - 1];


        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil(count($this->_items) / $per_page)
        ]);
    }
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }
}
