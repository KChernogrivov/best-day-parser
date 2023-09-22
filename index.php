<?php

require('./Cafe.php');
require('./Goodwin.php');
require('./call_api.php');
require('./create_wp_post.php');
require('./simple_html_dom.php');
require('./env.php');

echo '<pre>';


//Parse Goodwin
$html = file_get_html('https://goodwincinema.ru/affiche/');

foreach ($html->find('div[class=filmdesc clear]') as $film) {
    $cinema = new Goodwin($film);
    var_dump(createPost($cinema));
}

// Parse 2Gis
$get_data = callAPI('GET', 'https://catalog.api.2gis.ru/3.0/items?q=ресторан&key=55a2583f-3a47-4e77-9ecb-a9b97ed4442e&city_id=422835235324469&fields=items.ads.options,items.contact_groups,items.context,items,items.schedule', false);
$response = json_decode($get_data, true);

foreach ($response['result']['items'] as $item) {
    // Get image for every cafe by id
    $get_data = callAPI('GET', "https://api.photo.2gis.com/3.0/objects/{$item['id']}/albums/all/photos?locale=ru_RU&key=gYu1s9N1wP&preview_size=656x340&page_size=20", false);
    $response = json_decode($get_data, true);
    $fifu_image_url = $response['items'][0]['url'];

    $cafe = new Cafe($item, $fifu_image_url);
    var_dump(createPost($cafe));
}

echo '</pre>';








