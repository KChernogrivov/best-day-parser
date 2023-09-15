<?php

class Cafe
{
    private int $id;
    private string $name;
    private string $description;
    private string $address;
    private string $work_hours = '';
    private string $average_price;
    private string $contact;

    public function __construct(array $item)
    {
        $this->id = $item['id'];
        $this->description = $item['ads']['article'];
        $this->name = $item['name'];
        $this->address = $item['address_name'] . (array_key_exists('address_comment', $item) ? ', ' . $item['address_comment'] : '');
        $this->average_price = $item['context']['stop_factors'][0]['name'];
        $this->setContact($item['ads']['options']['actions'][0]);
        $this->setWorkHours($item['schedule']);
    }

    private function setContact(array $contact): bool
    {
        $link = $contact['type'] === 'phone' ? "tel:{$contact['value']}" : (string)$contact['value'];

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
            $this->work_hours .= "{$day}: {$shedule[$key]['working_hours'][0]['from']} - {$shedule[$key]['working_hours'][0]['to']} ";
        }
    }
}