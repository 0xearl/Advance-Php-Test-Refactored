<?php 

namespace Classes\Formatters;

use \LSS\Array2XML;
use Classes\Interfaces\FormatterInterface;

class XmlFormatter implements FormatterInterface
{
    private $data;


    function __construct($data)
    {
        $this->data = $data;    
    }

    public function format()
    {
        header('Content-type: text/xml, application/xml');
                
        // fix any keys starting with numbers
        $keyMap = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        $xmlData = [];
        foreach ($this->data->all() as $row) {
            $xmlRow = [];
            foreach ($row as $key => $value) {
                $key = preg_replace_callback('(\d)', function($matches) use ($keyMap) {
                    return $keyMap[$matches[0]] . '_';
                }, $key);
                $xmlRow[$key] = $value;
            }
            $xmlData[] = $xmlRow;
        }
        $xml = Array2XML::createXML('data', [
            'entry' => $xmlData
        ]);
        return $xml->saveXML();
    }
}