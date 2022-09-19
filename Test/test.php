<?php
require __DIR__ . './../vendor/autoload.php';

use TuanAnh0907\Rake\Rake;

$path = __DIR__ . '/../asset/StopWords/stopword.json';
$filename = __DIR__ . '/../asset/Documents/text.txt';

function loadStopwords($stopwords_file)
{
    $string = file_get_contents($stopwords_file);
    return json_decode($string, true);
}

function loadFile($document_file)
{
    $fp = fopen($document_file, 'rb+'); //mở file ở chế độ đọc
    return fread($fp, filesize($document_file));
}

$document = loadFile($filename);
$stopwords = loadStopwords($path);

$rake = new Rake($document, $stopwords);
$phrases = $rake->extract();

print_r($phrases);