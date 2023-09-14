<?php

class Cafe
{
    public int $id;
    public string $name;
    public string $address;
    public array $work_hours;
    public string $average_price;
    public string $contact;

    public function __construct(array $item)
    {
        $this->id = $item['id'];
        $this->name = $item['name'];
        $this->address = $item['address_name'] . (array_key_exists('address_comment', $item) ? ', ' . $item['address_comment'] : '');
        $this->setContact($item['ads']['options']['actions'][0]);
        $this->work_hours = $item['ads']["schedule"];
    }

    private function setContact(array $contact): bool
    {
        $link = $contact['type'] === 'phone' ? "tel:{$contact['value']}" : (string)$contact['value'];
        return $this->contact = "<a href='{$link}'>{$contact['caption']}</a>";
    }
}