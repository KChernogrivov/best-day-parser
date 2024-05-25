<?php

class Goodwin
{
    public string $title;
    public string $address = 'г. Томск, пр. Комсомольский 13б, ТРЦ «Изумрудный город», 3 этаж.';
    public int $average_price = 350;
    public string $content;
    public string $date_start;

    public string $date_finish;
    public string $time_start = '00:00';
    public string $time_finish = '00:00';
    public string $emotions = 'Один\nС детьми\nС родителями\nС компанией\nС другом/подругой';
    public array $categories = [9];
    public string $featured_image = '';

    function __construct($html_element)
    {
        $url_movie = 'https://goodwincinema.ru' . $html_element->find('div[class=film-title]', 0)->firstChild()->href;

        $this->title = $html_element->find('div[class=film-title]', 0)->plaintext;
        $this->content = $html_element->find('div[class=story]', 0)->plaintext . '<br><br>';

        //searches for date start and end
        $this->featured_image = str_replace('mid', 'big', 'https://goodwincinema.ru' . $html_element->find('div[class=left]', 0)->find('img', 0)->src);
        foreach ($html_element->find('td[class=label]') as $element) {
            if ($element->plaintext == 'В прокате') {
                $this->content .= 'В прокате: ' . $element->next_sibling()->plaintext;
                $dates = $this->parseDate($element->next_sibling()->plaintext);
                $this->date_start = $dates[0];
                $this->date_finish = $dates[1];
            }
        }

        $this->content .= '<br><br>' . "<a href='{$url_movie}'>Купить билет</a>" . '<br>';
    }

    private function parseDate(string $text_date): array
    {
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

        // Удалить лишние слова
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

}