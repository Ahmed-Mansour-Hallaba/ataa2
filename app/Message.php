<?php

namespace App;
    
class Message
{
    public $content;
    public function __construct($content)
    {
        $this->content=$content;
    }
}