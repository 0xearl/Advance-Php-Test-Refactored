<?php 

namespace Classes\Formatters;

use Classes\Interfaces\FormatterInterface;

class CsvFormatter implements FormatterInterface
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;    
    }

    public function format()
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv";');
        if (!$this->data->count()) {
            return;
        }
        $csv = [];
        
        // extract headings
        // replace underscores with space & ucfirst each word for a decent headings
        $headings = collect($this->data->get(0))->keys();
        $headings = $headings->map(function($item, $key) {
            return collect(explode('_', $item))
                ->map(function($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
        $csv[] = $headings->join(',');

        // format data
        foreach ($this->data as $dataRow) {
            $csv[] = implode(',', array_values($dataRow));
        }
        return implode("\n", $csv);
    }
}