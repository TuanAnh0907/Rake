<?php
require __DIR__ . './../vendor/autoload.php';

use TuanAnh\Rake\Rake;

$path = __DIR__ . '/../StopWords/stopword.json';
$filename =  __DIR__ . '/../Documents/text.txt';

$rake = new Rake($filename, $path);
$phrases = $rake->extract();

print_r($phrases);

