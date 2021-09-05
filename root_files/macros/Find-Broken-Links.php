<?php
/*
 * This macro will find the broken links in content html
 * 
 * This macro will not change the content of the html
 * It will only log the URL which are broken (404)
 * 
 */

// Find all the images in the html
$links = $html->find('a');
$count = count($links);

if ($count) {
	$this->log("Found $count Links",'info');
	$i = 0;
	// &$imgs instead of $imgs to pass by referance
	foreach($links as &$link) {
		$src = $link->href; 
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
				$this->log('Broken Link: '.$src,'important');
			}
			curl_close($ch);
		} else {
			$this->log('Unknown Link '.$src,'important');
		}
	}
	// Log the messages
	if ($i) {
		$this->log("$i broken links found",'important');
	} else {
		$this->log('No broken links found','success');
	}
} else {
	$this->log('No Links were found in the HTML','inverse');
}
?>