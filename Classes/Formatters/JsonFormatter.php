<?php 

namespace Classes\Formatters;

use Classes\Interfaces\FormatterInterface;

class JsonFormatter implements FormatterInterface
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;    
    }

    public function format()
    {
        header('Content-type: application/json');
        return json_encode($this->data->all());
    }
}