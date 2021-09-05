<?php
/*
 * This macro will remove the Cloudflare CDN url prefix to the image src.
 * 
 * You can change the CDNURL constant to use another CDN
 *
 * Example:	<img src="/cdn-cgi/image/quality=75,f=auto/site-assets/images/logo.jpg">
 * Becomes:	<img src="/site-assets/images/logo.jpg">
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
	// &$imgs instead of $imgs to pass by referance
	foreach($imgs as &$img) {
		$file = substr($img->src,1);
		$parts = explode('/', $file);
		// check image is from CDN before update.
		if ($parts[0]=='cdn-cgi') {
			$og = explode('site-assets',$img->src,2)[1];
			if ($og) {
				$i++;
				// Change the image src to include the CDN
				$img->src = '/site-assets'.$og;
			} else {
				$this->log("Could not find local path for CDN image ".$img->src,'error');
			}
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