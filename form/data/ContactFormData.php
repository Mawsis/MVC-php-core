<?php

namespace app\form\data;

use app\core\FormData;

class ContactFormData extends FormData
{
    protected array $labels = [
        'email' => 'Your Email',
        'subject' => 'Subject',
        'message' => 'Message'
    ];
}