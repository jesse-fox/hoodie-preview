<?php

//rgb(mt_rand(0,255),mt_rand(0,255),mt_rand(0,255))

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Expecting path such as http://localhost/hoodie-preview/image.php?fe8101/3c3c3c
$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $_SERVER['QUERY_STRING']);


$background = "#000";

$main_color = "#" . $segments[0];
$second_color = "#" . $segments[1];

//Random hex colors
//$main_color = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";
//$second_color = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";


// Set up for putting our image together
$sleeves_img = new Imagick('img/sleeves.jpg');
$body_img = new Imagick('img/body.jpg');
$ghosts = new Imagick('img/over.png');

$height = $sleeves_img->getImageHeight();
$width = $sleeves_img->getImageWidth();

$canvas = new Imagick();
$canvas->newImage($width, $height, $background, "jpg");


// Composite the images with colors, and combine them
try{


	// Sleeve image - Generate and color
	$sleeve_color = new Imagick();
	$sleeve_color->newPseudoImage($width,$height,"canvas:" . $second_color);
	$sleeve_color->compositeImage($sleeves_img, Imagick::COMPOSITE_COPYOPACITY, 0, 0);

	// Body image - Other composite options may be required
	$body_color = new Imagick();
	$body_color->newPseudoImage($width,$height,"canvas:". $main_color);
	$body_color->compositeImage($body_img, Imagick::COMPOSITE_COPYOPACITY, 0, 0);


	// Put all the peices  we made together.
	$canvas->compositeImage($body_color, imagick::ALPHACHANNEL_COPY, 0, 0 );
	$canvas->compositeImage($sleeve_color, imagick::ALPHACHANNEL_COPY, 0, 0 );
	$canvas->compositeImage($ghosts, imagick::COMPOSITE_DEFAULT, 0, 0 );


	// Output as an image. If you need to print debug info, disable this
	header( "Content-Type: image/jpg" );
	echo $canvas;
	die;

// so imagick can let you know when it's unhappy
}catch(Exception $e){
	echo 'Error: ',  $e->getMessage(), "";
}

?>
