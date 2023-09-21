<?php

require('./simple_html_dom.php');

$months = [
    'января' => 1,
    'февраля' => 2,
    'марта' => 3,
    'апреля' => 4,
    'мая' => 5,
    'июня' => 6,
    'июль' => 7,
    'августа' => 8,
    'сентября' => 9,
    'октября' => 10,
    'ноября' => 11,
    'декабря' => 12
];

function parseDate(string $text_date): array
{
    global $months;
    //remove words
    $text_date = str_replace(array_keys($months), array_values($months), $text_date);

    $subject = $text_date;
    $pattern = '/\d+\s\d+/';
    $matches = array();
    preg_match_all($pattern, $subject, $matches);


    $startDay = (int)explode(' ', $matches[0][0])[0];
    $startMonth = (int)explode(' ', $matches[0][0])[1];
    $endDay = (int)explode(' ', $matches[0][1])[0];
    $endMonth = (int)explode(' ', $matches[0][1])[1];

    // Получить текущую дату
    $currentDate = new DateTime();
    $currentYear = (int)$currentDate->format('Y');

    // Определить год начала события
    if ($startMonth < $currentDate->format('m') || ($startMonth == $currentDate->format('m') && $startDay <= $currentDate->format('d'))) {
        $startYear = $currentYear;
    } else {
        $startYear = $currentYear - 1;
    }

    // Определить год окончания события
    if ($endMonth > $currentDate->format('m') || ($endMonth == $currentDate->format('m') && $endDay >= $currentDate->format('d'))) {
        $endYear = $currentYear;
    } else {
        $endYear = $currentYear + 1;
    }

    // Сформировать массив дат события
    $startDate = "{$startDay}/{$startMonth}/{$startYear}";
    $endDate = "{$endDay}/{$endMonth}/{$endYear}";
    $eventDates = array($startDate, $endDate);

    return $eventDates;

}


$html = file_get_html('https://goodwincinema.ru/affiche/');

$inner = 0;
foreach ($html->find('div[class=filmdesc clear]') as $element) {
    if ($inner == 0) {

        $url_movie = 'https://goodwincinema.ru' . $element->find('div[class=film-title]', 0)->firstChild()->href;

        echo $element->find('div[class=film-title]', 0)->plaintext . '<br>';
        echo $element->find('div[class=story]', 0)->plaintext . '<br>';
//        echo $element->find('div[class=film-table]', 0)->find('tr', 11)->lastChild() . '<br>';
        echo 'г. Томск, пр. Комсомольский 13б, ТРЦ «Изумрудный город», 3 этаж.' . '<br>';
        echo 350 . '<br>';
        echo "<a href='{$url_movie}'>Купить билет</a>" . '<br>';
        // получаем текущую дату
        foreach ($element->find('td[class=label]') as $element) {
            if ($element->plaintext == 'В прокате') {
                var_dump(parseDate($element->next_sibling()->plaintext));
            }
        }
//        var_dump(parseDate($element->find('div[class=film-table]', 0)->find('tr', 11)->lastChild()));

//        сеансы
//        $movie_page = file_get_html($url_movie);
//        $slider = $movie_page->find('div[class=nav-slider]', 0);
//        var_dump(file_get_html('https://goodwincinema.ru/filmbase/3239/#date=20.09.2023')->find('.time'));

        echo '<hr>';


    }
}



//foreach ($html->find('td') as $element) {
//    if ($element->plaintext == 'В прокате') {
//        echo $element->next_sibling()->plaintext . '<br>';
//    }
//}

