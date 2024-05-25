<?php

/**
 * Accepts an array or an object and sends request to create post with that data
 * @return bool|string|void
 */
function createPost(Cafe|Goodwin $data)
{
    global $wp_url;

    $data_array = [
        "title" => $data->title,
        "content" => $data->content,
        "status" => "pending",
        "categories" => $data->categories,
        "fifu_image_url" => $data->featured_image,
        "acf" => (object)[
            "cost" => $data->average_price,
            "place" => $data->address,
            "emotions" => $data->emotions,
            "date_start" => $data->date_start,
            "date_finish" => $data->date_finish,
            "time_start" => $data->time_start,
            "time_finish" => $data->time_finish
        ]
    ];
    $make_call = callAPI('POST', $wp_url, json_encode($data_array));
    return json_decode($make_call, true);
}