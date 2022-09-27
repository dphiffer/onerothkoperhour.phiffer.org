<?php

$json = file_get_contents('paintings/index.json');
$index = json_decode($json);
usort($index, function($a, $b) {
	$size_a = max($a->width, $a->height);
	$size_b = max($b->width, $b->height);
	return $size_a > $size_b ? -1 : 1;
});

foreach ($index as $num => $painting) {
	preg_match('/\.\w+$/', $painting->file, $matches);
	$ext = $matches[0];
	copy("paintings/$painting->file", "paintings/$num$ext");
	if ($num > 23) {
		break;
	}
}

file_put_contents('paintings/index.json', json_encode($index, JSON_PRETTY_PRINT));
