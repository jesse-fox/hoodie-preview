<?php

//rgb(mt_rand(0,255),mt_rand(0,255),mt_rand(0,255))

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $_SERVER['REQUEST_URI_PATH']);

//print_r($segments);

$background = "#000";

$main_color = "#" . $segments[1];
$second_color = "#" . $segments[2];

//$main_color = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";
//$second_color = "rgb(".mt_rand(0,255).",".mt_rand(0,255).",".mt_rand(0,255).")";



$sleeves_img = new Imagick('sleeves.jpg');
$body_img = new Imagick('body.jpg');
$ghosts = new Imagick('over.png');

$height = $sleeves_img->getImageHeight();
$width = $sleeves_img->getImageWidth();



try{


	$canvas = new Imagick();
	$canvas->newImage($width, $height, $background, "jpg");
	
	
	$sleeve_color = new Imagick();
	$sleeve_color->newPseudoImage($width,$height,"canvas:" . $second_color);
	
	$sleeve_color->compositeImage($sleeves_img, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
	
	
	$body_color = new Imagick();
	$body_color->newPseudoImage($width,$height,"canvas:". $main_color);

	$body_color->compositeImage($body_img, Imagick::COMPOSITE_COPYOPACITY, 0, 0);
	
	

	$canvas->compositeImage($body_color, imagick::ALPHACHANNEL_COPY, 0, 0 );
	$canvas->compositeImage($sleeve_color,imagick::ALPHACHANNEL_COPY, 0, 0 );
	$canvas->compositeImage($ghosts,imagick::COMPOSITE_DEFAULT, 0, 0 );
	
	
	
	
	header( "Content-Type: image/jpg" );
	echo $canvas;
	die;

}catch(Exception $e){
	echo 'Error: ',  $e->getMessage(), "";
}

?>