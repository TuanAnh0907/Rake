<?php
include('Rake.php');

$path = './src/StopWords/stopword.json';
//$string = file_get_contents($path);
//$json_a = json_decode($string, true); // mang stop word

$filename = './src/Documents/text.txt';
//$fp = fopen($filename, "r+");//mở file ở chế độ đọc
//$contents = fread($fp, filesize($filename));//đọc file

$rake = new Rake($filename, $path);
$phrases = $rake->extract();

print_r($phrases);

