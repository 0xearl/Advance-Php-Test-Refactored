<?php 

namespace Classes\Formatters;

use Classes\Interfaces\FormatterInterface;

class HtmlFormatter implements FormatterInterface
{
    private $data;

    function __construct($data)
    {
        $this->data = $data;    
    }

    public function format()
    {
        if (!$this->data->count()) {
            return $this->htmlTemplate('Sorry, no matching data was found');
        }
        
        // extract headings
        // replace underscores with space & ucfirst each word for a decent heading
        $headings = collect($this->data->get(0))->keys();
        $headings = $headings->map(function($item, $key) {
            return collect(explode('_', $item))
                ->map(function($item, $key) {
                    return ucfirst($item);
                })
                ->join(' ');
        });
        $headings = '<tr><th>' . $headings->join('</th><th>') . '</th></tr>';

        // output data
        $rows = [];
        foreach ($this->data as $dataRow) {
            $row = '<tr>';
            foreach ($dataRow as $key => $value) {
                $row .= '<td>' . $value . '</td>';
            }
            $row .= '</tr>';
            $rows[] = $row;
        }
        $rows = implode('', $rows);
        return $this->htmlTemplate("<table>{$headings} {$rows} </table>'");
    }
    // wrap html in a standard template
    public function htmlTemplate($html) {
        return "
            <html>
            <head>
            <style type='text/css'>
                body {
                    font: 16px Roboto, Arial, Helvetica, Sans-serif;
                }
                td, th {
                    padding: 4px 8px;
                }
                th {
                    background: #eee;
                    font-weight: 500;
                }
                tr:nth-child(odd) {
                    background: #f4f4f4;
                }
            </style>
            </head>
            <body>
                {$html}
            </body>
            </html>";
    }
}