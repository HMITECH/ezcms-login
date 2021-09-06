<?php
/*
 * This macro will add Height and Width attributes to image elements.
 *
 * It will read the image size from the source image and update the content
 * using the php function getimagesize();
 *
 * Example:	<img src="/site-assets/images/logo.jpg">
 * Becomes:	<img src="/site-assets/images/logo.jpg" height="100px" width="200px">
 *
 * 100px and 200px will be replaced with the actual image dimensions.
 *
 */

// Find all the images in the html
$imgs = $html->find('img');
$count = count($imgs);

if ($count) {
	
	$this->log("Found $count Images",'info');
	$i = 0;
	foreach($imgs as $img) {
		$src = $img->src;
		if ($src[0]==='/') {
			// begins with / so local
			$src = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'].$src;
		}
		if (substr($src,0,4)==='http') {
			list($width, $height) = @getimagesize($src);
			if ($width) {
				$i++;
				if (!$img->height) $img->height = $height.'px';
				if (!$img->width) $img->width = $width.'px';
			} else {
				$this->log('Failed to get Dimension for '.$src,'important');
			}
		} else {
			$this->log('Unknown image source '.$src,'important');
		}
	}
	// Log the messages
	if ($i) {
		$this->log("$i Images were updated",'success');
	} else {
		$this->log('No Images need to be changed','inverse');
	} 
} else {
	$this->log('No Images were found in the HTML','inverse');
}

?>