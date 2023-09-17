<?php

require('./Cafe.php');


function callAPI($method, $url, $data)
{
    $login = 'test-author';
    $password = 'nyLT ZAce XiF2 KCNQ rcLB xYHg';

    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERPWD, $login . ":" . $password);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Connection Failure");
    }
    curl_close($curl);

    return $result;
}

echo "лого, адрес, время работы, средний чек (это в поле примерно потратите), телефон, соцсети, сайт";


$get_data = callAPI('GET', 'https://catalog.api.2gis.ru/3.0/items?q=ресторан&key=55a2583f-3a47-4e77-9ecb-a9b97ed4442e&city_id=422835235324469&fields=items.ads.options,items.contact_groups,items.context,items,items.schedule', false);
//$get_data = callAPI('GET', 'https://catalog.api.2gis.ru/3.0/markers/clustered?key=rurbbn3446&map_width=1368&map_height=505&locale=ru_RU&rubric_id=164&allow_deleted=true&type=adm_div.city,adm_div.district,adm_div.district_area,adm_div.division,adm_div.living_area,adm_div.place,adm_div.region,adm_div.settlement,attraction,branch,building,crossroad,foreign_city,gate,parking,road,route,station,street,coordinates,kilometer_road_sign&search_device_type=desktop&search_user_hash=7090465036253147442&is_viewport_change=true&viewpoint1=84.71014902390445,56.51674022903702&viewpoint2=85.28438097609555,56.399521770962984&fields=items.ads,items.name,items.stop_factors,items.reviews,items.schedule,items.context,items.name_ex,items.timezone_offset,items.flags,items.has_exchange,items.temporary_unavailable_atm_services,search_attributes&stat[sid]=ae26e857-6192-430a-aeb1-bde5ae4c3d80&stat[user]=2122bdfb-4627-4180-ab44-0821089aa9a4&shv=2023-09-12-13&r=681537663', false);
$response = json_decode($get_data, true);


echo "<pre>";


foreach ($response['result']['items'] as $item) {
    $cafe = new Cafe($item);

    $get_data = callAPI('GET', "https://api.photo.2gis.com/3.0/objects/{$cafe->id}/albums/all/photos?locale=ru_RU&key=gYu1s9N1wP&preview_size=656x340&page_size=20", false);
    $response = json_decode($get_data, true);

    $data_array = [
        "title" => $cafe->title,
        "content" => "{$cafe->description}<br><br>{$cafe->work_hours}<br><br>{$cafe->contact}",
        "status" => "pending",
        "categories" => [
            10
        ],
        "fifu_image_url" => $response['items'][0]['url'],
        "acf" => (object)[
            "cost" => $cafe->average_price,
            "place" => $cafe->address,
            "emotions" => "Один\nС детьми\nС родителями\nС компанией\nС другом/подругой", //TODO: change
            "date_start" => "07/06/2023", //TODO: change
            "date_finish" => "28/06/2023", //TODO: change
            "time_start" => "13:00", //TODO: change
            "time_finish" => "16:00", //TODO: change
        ]
    ];
    $make_call = callAPI('POST', 'https://wddd.ru/wp/wp-json/wp/v2/posts/', json_encode($data_array));
    $response = json_decode($make_call, true);
    echo '<hr>';
    var_dump($response);
}

echo "</pre>";







