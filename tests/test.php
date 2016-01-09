<?php
use ybourque\Wikparser\Wikparser;

require dirname(__DIR__) . "/vendor/autoload.php";

$wik = new WikParser();
$queries = ['def', 'pos', 'syn', 'hyper', 'gender'];
$parsed = [];
foreach ($queries as $query) {
    $parsed[$query] = $wik->getWordDefiniton('simplement', $query, 'fr');
}
var_dump($parsed);

/*
requires a separate call for each element. modify to make one call and return
all parsed elements?
*/
