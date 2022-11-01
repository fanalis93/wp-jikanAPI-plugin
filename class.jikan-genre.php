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

//        $ids = [];
//        $names = [];
//        $counts = [];
//        $urls = [];
//        foreach($results as $data) {
//            $ids[] = $data->mal_id;
//            $names[] = $data->name;
//            $counts[] = $data->count;
//            $urls[] = $data->url;
//        }
//        $genredata = [];
//        array_push($genredata, $ids,$names,$urls,$counts);
        return $results;
    }
    public function jikan_genres_content()
    {
        $genredata = $this->get_genre_data();
        $array = json_decode(json_encode($genredata), true);
        $table = new GenreTable();
        $table->set_data( $array );

        $table->prepare_items();
        ?>
        <div class="wrap">
            <h2><?php _e( "Genres", "jikan" ); ?></h2>
            <form method="GET">
                <?php
                $table->display();
                ?>
                <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
            </form>
        </div>
        <?php
    }

}
