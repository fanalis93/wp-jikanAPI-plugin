<?php
require_once "class.jikan-genre-table.php";
class JikanGenre  {
    function __construct(){

    }
    public function greeting(){
        echo "Hello";
    }
    public function get_genre_data() {
        $url_genre = "https://api.jikan.moe/v4/genres/anime";
        $arguments = array(
            'method' => 'GET',
        );
        $request = wp_remote_get($url_genre, $arguments);
        $results = json_decode( wp_remote_retrieve_body( $request ) );
        $results = $results->data;
        if(!is_array($results) || empty($results)) {
            return false;
        }

        return $results;
    }
    function datatable_search_by_name( $item ) {
        $name        = strtolower( $item['name'] );
        $search_name = sanitize_text_field( $_GET['s'] );
        if ( strpos( $name, $search_name ) !== false ) {
            return true;
        }

        return false;
    }
    public function jikan_genres_content()
    {
        $genredata = $this->get_genre_data();
        $data = json_decode(json_encode($genredata), true);
        $table = new GenreTable();


        $orderby = $_REQUEST['orderby'] ?? '';
        $order   = $_REQUEST['order'] ?? '';

        if ( 'mal_id' == $orderby ) {
            if ( 'asc' == $order ) {
                usort( $data, function ( $item1, $item2 ) {
                    return $item2['mal_id'] <=> $item1['mal_id'];
                } );
            } else {
                usort( $data, function ( $item1, $item2 ) {
                    return $item1['mal_id'] <=> $item2['mal_id'];
                } );
            }
        } else if ( 'name' == $orderby ) {
            if ( 'asc' == $order ) {
                usort( $data, function ( $item1, $item2 ) {
                    return $item2['name'] <=> $item1['name'];
                } );
            } else {
                usort( $data, function ( $item1, $item2 ) {
                    return $item1['name'] <=> $item2['name'];
                } );
            }
        } else if ( 'count' == $orderby ) {
            if ( 'asc' == $order ) {
                usort( $data, function ( $item1, $item2 ) {
                    return $item2['count'] <=> $item1['count'];
                } );
            } else {
                usort( $data, function ( $item1, $item2 ) {
                    return $item1['count'] <=> $item2['count'];
                } );
            }
        }
        if ( isset( $_GET['s'] ) && !empty($_GET['s']) ) {
            $data = array_filter( $data, 'datatable_search_by_name' );
        }

        $table->set_data( $data );

        $table->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php _e( "Genres", "jikan" ); ?></h2>
            <form method="GET">
                <?php
                $table->search_box( 'search', 'search_id' );
                $table->display();
                ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
        <?php
    }


}
