<?php
use ybourque\Wikparser\Wikparser;

require dirname(__DIR__) . "/vendor/autoload.php";

$wik = new WikParser();
$queries = ['def', 'pos', 'syn', 'hyper', 'gender'];
$parsed = $wik->getWordDefiniton('bon', $queries, 'fr');
var_dump($parsed);

/*
requires a separate call for each element. modify to make one call and return
all parsed elements?
*/
