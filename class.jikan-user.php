<?php

class JikanUser
{
    function __construct()
    {

    }

    public function greeting()
    {
        echo "Hello";
    }
    public function jikan_user_content() {
        $url_user = "https://api.jikan.moe/v4/users/fanalis93";
        $user_stat = "https://api.jikan.moe/v4/users/fanalis93/statistics";
        $arguments = array(
            'method' => 'GET',
        );
        $request = wp_remote_get($url_user, $arguments);
        $request_stat = wp_remote_get($user_stat, $arguments);
        $results = json_decode( wp_remote_retrieve_body( $request ) );
        $results_stat = json_decode( wp_remote_retrieve_body( $request_stat ) );
        $results = $results->data;
        $results_stat = $results_stat->data;
//    if(!is_array($results) || empty($results)) {
//    return false;
//    }
        ?>
        <div class="user_content">
            <div class="user_img img_container">
                <img src="<?php echo $results->images->jpg->image_url; ?>" alt="">
            </div>
            <div class="user_info">
                <h3>Username: <i><a href="<?php echo $results->url; ?>" target="_blank"><?php echo $results->username; ?></a></i></h3>
                <p><strong>User Location:</strong> <?php echo $results->location; ?></p>
                <p><strong>Member Since </strong> <?php echo date('d M, Y',strtotime($results->joined)); ?></p>
                <p><strong>Last Online: </strong> <?php echo date('d M, Y',strtotime($results->last_online)); ?></p>
                <div class="statistics">
                    <div class="anime_stat">
                        <h3>Anime Statistics</h3>
                        <?php
                        $anime = $results_stat->anime;
                        foreach ($anime as $key=>$stat) {
                            echo '<p>' . $key . ' - ' . $stat . '</p>';
                        }
                        ?>
                    </div>
                    <div class="manga_stat">
                        <h3>Manga Statistics</h3>
                        <?php
                        $manga = $results_stat->manga;
                        foreach ($manga as $key=>$stat) {
                            echo '<p>' . $key . ' - ' . $stat . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>


        </div>

        <?php

    }
}