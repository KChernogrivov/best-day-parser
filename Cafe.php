<?php

class Cafe
{
    public string $title;
    public string $address;
    public string $work_hours = '';
    public int $average_price;
    public string $content;
    public string $date_start;

    public string $date_finish;
    public string $time_start = '10:00';
    public string $time_finish = '19:00';
    public string $emotions = 'Один\nС детьми\nС родителями\nС компанией\nС другом/подругой';
    public array $categories = [10];
    public string $featured_image;

    public function __construct(array $item, string $fifu_image_url)
    {
        $this->title = $item['name'];
        $this->address = $item['address_name'] . (array_key_exists('address_comment', $item) ? ', ' . $item['address_comment'] : '');
        $this->average_price = preg_replace('/[^0-9]/', '', $item['context']['stop_factors'][0]['name']);
        $this->setWorkHours($item['schedule']);
        $this->content = "{$item['ads']['article']}<br><br>{$this->work_hours}<br><br>{$this->setContact($item['ads']['options']['actions'][0])}";
        $this->date_start = date("d/m/Y");
        $this->date_finish = date("d/m/Y"); //TODO: add a year
        $this->featured_image = $fifu_image_url;
    }

    /**
     * Returns a string with phone url or site url
     */
    private function setContact(array $contact): string
    {
        $link = $contact['type'] == 'phone' ? "tel:{$contact['value']}" : (string)$contact['value'];

        return "<a href='{$link}'>{$contact['caption']}</a>";
    }

    private function setWorkHours(array $shedule): void
    {
        $week = [
            'Mon' => 'Понедельник',
            'Tue' => 'Вторник',
            'Wed' => 'Среда',
            'Thu' => 'Четверг',
            'Fri' => 'Пятница',
            'Sat' => 'Суббота',
            'Sun' => 'Воскресенье'
        ];

        foreach ($week as $key => $day) {
            $this->work_hours .= "<p>{$day}: {$shedule[$key]['working_hours'][0]['from']} - {$shedule[$key]['working_hours'][0]['to']}</p>";
        }
    }
}