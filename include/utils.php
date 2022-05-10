<?php

require_once('../vendor/autoload.php');
use Illuminate\Support;

// global connection object
// global $mysqli_db;
// $mysqli_db = new mysqli('localhost', 'root', '', $database ?: 'employees');

/**
 * Execute a query & return the resulting data as an array of assoc arrays
 * @param string $sql query to execute
 * @return boolean|array array of associative arrays - query results for select
 *     otherwise true or false for insert/update/delete success
 */
function query($sql) {
    global $mysqli_db;
    $result = $mysqli_db->query($sql);
    if (!is_object($result)) {
        return $result;
    }
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

/**
 * Debug method - dumps a print_r of any passed variables and exits
 * @param mixed any number of variables you wish to inspect
 */
function e() {
    $args = func_get_args();
    global $noexit;
    foreach ($args as $arg) {
        $out = print_r($arg, 1);
        echo '<pre>' . $out . '</pre><hr />';
    }
    if (!$noexit) {
        $bt = debug_backtrace();
        exit('<i>Called from: ' . $bt[0]['file'] . ' (' . ($bt[1]['class'] ? $bt[1]['class'] . ':' : '') . $bt[1]['function'] . ')</i>');
    }
}

/**
 * Fail method - used in testing to output a decent failure message
 * @param string message the message to output
 */
function fail($message) {
    exit('<div style="display: inline-block; color: #a94442; background: #f2dede; border: solid 1px #ebccd1; font-family: Helvetica, Arial; size: 16px; padding: 15px;">Test failed: ' . $message . '</div>');
}

/**
 * Pass method - used in testing to output a decent pass message
 * @param string message the message to output
 */
function pass($message) {
    exit('<div style="display: inline-block; color: #3c763d; background: #dff0d8; border: solid 1px #d6e9c6; font-family: Helvetica, Arial; size: 16px; padding: 15px;">Test failed: ' . $message . '</div>');
}

/**
 * Display an array of assoc arrays (query resultset) as an HTML table
 * Depends on Laravel collections class being available
 * @param array an array of assoc arrays
 * @return string HTML table
 */
function asTable($data) {
    if (!$data || !is_array($data) || !count($data)) {
        return 'Sorry, no matching data was found';
    }
    $data = collect($data);
    $styles = '<style type="text/css">body{font:16px Roboto,Arial,Helvetica,Sans-serif}td,th{padding:4px 8px}th{background:#eee;font-weight:500}tr:nth-child(odd){background:#f4f4f4}</style>';

    // extract headings
    // replace underscores with space & ucfirst each word for a decent heading
    $headings = collect($data->get(0))->keys();
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
    foreach ($data as $dataRow) {
        $row = '<tr>';
        foreach ($dataRow as $key => $value) {
            $row .= '<td>' . $value . '</td>';
        }
        $row .= '</tr>';
        $rows[] = $row;
    }
    $rows = implode('', $rows);
    return $styles . '<table>' . $headings . $rows . '</table>';
}