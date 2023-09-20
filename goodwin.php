<?php

require('./simple_html_dom.php');

$html = file_get_html('https://goodwincinema.ru/affiche/');

//foreach ($html->find('div[class=film-title]') as $element) {
//    echo $element->plaintext . '<br>';
//}
$inner = 0;
foreach ($html->find('div[class=filmdesc clear]') as $element) {
    if ($inner == 0) {
        $inner += 1;
        echo $element->find('div[class=film-title]', 0)->plaintext . '<br>';
        echo $element->find('div[class=story]', 0)->plaintext . '<br>';
        // сеансы

        $url_movie = 'https://goodwincinema.ru' . $element->find('div[class=film-title]', 0)->firstChild()->href;

        $movie_page = file_get_html($url_movie);
        $slider = $movie_page->find('div[class=nav-slider]', 0);
        var_dump(file_get_html('https://goodwincinema.ru/filmbase/3239/#date=20.09.2023')->find('.time'));
        foreach ($slider->find('a') as $time) {
//            $seans = file_get_html($url_movie . $time);
//            echo $seans;
        }
        echo '<hr>';
    }
}

//$months = [
//    'августа' =?
//    'сентября' => 9,
//    'октября' => 10,
//    'ноября' => 11,
//    'декабря' => 12
//];

//foreach ($html->find('td') as $element) {
//    if ($element->plaintext == 'В прокате') {
//        echo $element->next_sibling()->plaintext . '<br>';
//    }
//}

