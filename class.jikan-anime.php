<?php

require_once "class.jikan-genre.php";
class JikanAnime  {
    function __construct(){

    }
    public function greeting(){
        echo "Hello";
    }
    public function jikan_settings_content() {

        $animeName = '';
        $searchgenre = '';
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['search_anime']){
            $animeName = sanitize_text_field($_POST['anime']);
            $searchgenre =  sanitize_text_field($_POST['list']);
        }

//        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['search_genre']){
//            if(!empty($_POST['list'])){
//                $searchgenre =  sanitize_text_field($_POST['list']);
//            }
//        }

        $url_name = "https://api.jikan.moe/v4/anime?q=$animeName&genres=$searchgenre";
        $arguments = array(
            'method' => 'GET',
        );
        $request = wp_remote_get($url_name, $arguments);
        $results = json_decode( wp_remote_retrieve_body( $request ) );
        $results = $results->data;
        if(!is_array($results) || empty($results)) {
            return false;
        }

        // GET GENRE DATA
        $get_genre = new JikanGenre();
        $genredata = $get_genre->get_genre_data();

        $ids = [];
        $names = [];
        $counts = [];
        $urls = [];
        foreach($genredata as $data) {
            $ids[] = $data->mal_id;
            $names[] = $data->name;
            $counts[] = $data->count;
            $urls[] = $data->url;
        }
        $genredata = [];
        array_push($genredata, $ids,$names,$urls,$counts);


        $carray = array_combine($genredata[0] , $genredata[1]);
        ?>

        <div class="query">
            <form action="" method="post">
                <label for="name">Insert Anime Name</label>
                <input type="text" name="anime" id="name">

                <label for="list">Search By Genre </label>
                <select name="list" id="list">
                    <!--                <option value="1">Action</option>-->
                    <!--                <option value="14">Horror</option>-->
                    <!--                <option value="2">Adventure</option>-->
                    <!--                <option value="4">Comedy</option>-->
                    <option value="">Select</option>
                    <?php
                    foreach($carray as $key => $value)
                    {
                        ?>
                        <option value="<?php echo $key; ?>"><?php
                            echo $value;

                            ?></option>
                        <?php
                    }

                    ?>

                </select>
                <input class="button button-primary" type="submit" name="search_anime" value="Submit">

            </form>
            <?php
            echo '<h2>Showing Results for: <span  style="color: royalblue">' . $animeName  .'</span></h2>';
?>
        </div>

        <?php
        foreach ($results as $data) {
            ?>
            <div class="container">
                <div class="anime_body">
                    <div class="img_container">
                        <img src="<?php echo $data->images->jpg->image_url; ?>" alt="">
                    </div>
                    <div class="info_container">
                        <h3>Anime Title: <i><a href="<?php echo $data->url; ?>" target="_blank"><?php echo $data->title; ?></a></i></h3>
                        <p><strong>Studios:</strong> <?php
                            foreach($data->studios as $studios){
                                ?>
                                <a href="<?php echo $studios->url; ?>" target="_blank"><?php echo $studios->name; ?></a>
                                <?php
                            }
                            ?></p>
                        <p><strong>Producers:</strong> <?php
                            foreach($data->producers as $producers){
                                ?>
                                <a href="<?php echo $producers->url; ?>" target="_blank"><?php echo $producers->name; ?></a>
                                <?php
                            }
                            ?></p>

                        <?php
                        $genres = $data->genres;
                        $sgenre = "";
                        foreach($genres as $genre) {
                            $sgenre .= $genre->name . ', ';
                        }
                        ?>
                        <p><strong>Genres:</strong> <?php echo rtrim($sgenre, ', '); ?></p>


                        <p><strong>Score (MAL):</strong> <?php echo $data->score; ?></p>
                        <p><strong>Episodes:</strong> <?php echo $data->episodes; ?></p>
                        <p><strong>Status:</strong> <?php echo $data->status; ?></p>
                        <p><strong>Rating:</strong> <?php echo $data->rating; ?></p>
                        <p><strong>Synopsis:</strong> <?php echo $data->synopsis; ?></p>
                    </div>

                </div>
                <div class="trailer_container">
                    <!--                <iframe width="560" height="333" src="--><?php //echo $data->trailer->embed_url; ?><!--" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"  allowfullscreen></iframe>-->

                </div>
            </div>
            <div class="customHr">.</div>

        <?php } ?>
        <?php
    }
}
