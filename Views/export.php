<?php 

use Classes\Models\PlayerModel;
use Classes\Formatters\CsvFormatter;
use Classes\Formatters\XmlFormatter;
use Classes\Formatters\HtmlFormatter;
use Classes\Formatters\JsonFormatter;

$player_model = new PlayerModel();
$data = [];
$args = collect($_REQUEST);
$format = $args->pull('format') ?: 'html';
$type = $args->pull('type');
if (!$type) {
    die('Please Select a Type');
}

switch ($type) {
    case 'playerstats':
        $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
        $search = $args->filter(function($value, $key) use ($searchArgs) {
            return in_array($key, $searchArgs);
        });
        $data = $player_model->getPlayerStats($search);
        break;
    case 'players':
        $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
        $search = $args->filter(function($value, $key) use ($searchArgs) {
            return in_array($key, $searchArgs);
        });
        $data = $player_model->getPlayers($search);
        break;
}
if(!$data) {
    die('Error: No data found!');
}

switch($format) {
    case 'xml':
        $formatter = new XmlFormatter($data);
        echo $formatter->format();
        break;
    case 'json':
        $formatter = new JsonFormatter($data);
        echo $formatter->format();
        break;
    case 'csv':
        $formatter = new CsvFormatter($data);
        echo $formatter->format();
        break;
    default: 
        $formatter = new HtmlFormatter($data);
        echo $formatter->format();
        break;
}