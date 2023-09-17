<?php

class Cafe
{
    public int $id;
    public string $title;
    public string $description;
    public string $address;
    public string $work_hours = '';
    public int $average_price;
    public string $contact;

    public function __construct(array $item)
    {
        $this->id = $item['id'];
        $this->description = $item['ads']['article'];
        $this->title = $item['name'];
        $this->address = $item['address_name'] . (array_key_exists('address_comment', $item) ? ', ' . $item['address_comment'] : '');
        $this->average_price = preg_replace('/[^0-9]/', '', $item['context']['stop_factors'][0]['name']);
        $this->setContact($item['ads']['options']['actions'][0]);
        $this->setWorkHours($item['schedule']);
    }

    private function setContact(array $contact): bool
    {
        $link = $contact['type'] == 'phone' ? "tel:{$contact['value']}" : (string)$contact['value'];

        return $this->contact = "<a href='{$link}'>{$contact['caption']}</a>";
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