<?php

// Load HTML with paintings on it then save the biggest version of the image
// possible. Save an index JSON file while we're at it.

require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$html = file_get_contents('https://www.markrothko.org/paintings/');
$dom = new Dom();
$dom->loadStr($html);
$items = $dom->find('.thumbnail');
$index = [];

foreach ($items as $item) {
	$img = $item->find('img');
	$src = preg_replace('/\?.+$/', '', "$img->src");
	$src = preg_replace('/-\d+x\d+(\.jpe?g)$/', '$1', $src);
	$filename = basename($src);
	$filename = str_replace(' ', '-', $filename);
	$url = 'https://www.markrothko.org' . str_replace(' ', '%20', $src);
	$data = file_get_contents($url);
	file_put_contents("paintings/$filename", $data);
	$caption = $item->find('.caption');
	echo trim($caption->innerText);
	echo "\n    $src\n";
	list($width, $height) = getimagesize("paintings/$filename");
	$index[] = [
		'file' => $filename,
		'caption' => trim($caption->innerText),
		'width' => $width,
		'height' => $height
	];
}

file_put_contents("paintings/index.json", json_encode($index, JSON_PRETTY_PRINT));
