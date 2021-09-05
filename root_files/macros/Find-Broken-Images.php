<?php
/*
 * This macro will find the broken images in content html
 * 
 * This macro will not change the content of the html
 * It will only log the image paths with broken (404)
 *
 */

// Find all the images in the html
$imgs = $html->find('img');
$count = count($imgs);

if ($count) {
	$this->log("Found $count Images",'info');
	$i = 0;
	
	foreach($imgs as $img) {
		// test the image here 
		$src = $img->src;
		if ($src[0]==='/') {
			// begins with / so local
			$src = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
                "https" : "http") . "://" . $_SERVER['HTTP_HOST'].$src;
		}
		if (substr($src,0,4)==='http') {
			$ch = curl_init($src);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// Testing for 404 Error
			if($retcode != 200) {
				$i++;
				$this->log('Broken Image: '.$src,'important');
			}
			curl_close($ch);
		} else {
			$this->log('Unknown src '.$src,'important');
		}
	}
	// Log the messages
	if ($i) {
		$this->log("$i Broken Images found",'important');
	} else {
		$this->log('No broken Images found','success');
	}
} else {
	$this->log('No Images were found in the HTML','inverse');
}

?>