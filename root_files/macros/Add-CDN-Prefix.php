<?php
/*
 * This macro will add the Cloudflare CDN url prefix to the image src.
 * 
 * You can change the CDNURL constant to use another CDN
 *
 * Example:	<img src="/site-assets/images/logo.jpg">
 * Becomes:	<img src="/cdn-cgi/image/quality=75,f=auto/site-assets/images/logo.jpg">
 *
 */

// Change this depending on the CDN you are using
const CDNURL="/cdn-cgi/image/quality=75,f=auto";

// Find all the images in the html
$imgs = $html->find('img');
$count = count($imgs);

if ($count) {
	$this->log("Found $count Images",'info');
	$i = 0;
	foreach($imgs as $img) {
		$file = substr($img->src,1);
		$parts = explode('/', $file);
		// check image is local before update.
		if ($parts[0]=='site-assets') {
			$i++;
			// Change the image src to include the CDN
			$img->src = CDNURL.$img->src;
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