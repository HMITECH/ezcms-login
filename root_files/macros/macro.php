<?php
/*
 * Empty Macro File - Copy to make new .
 *
 * Macros are an easy way to automate common tasks on
 * the html blocks in ezCMS: 
 * Content (maincontent), 
 * Aside 1 (sidecontent),
 * Assde 2 (sidercontent)
 *
 * Variables: The following variables will be exposed to the macro code
 * $html - php class of HTML DOM : 
 * 		Example: $html->find('img') 
 *    		will return array of all the images in the content
 *
 * 		Making changes to the $html will update the page
 * 		Example $html->find('img')[0]->height = '500px'
 *    		will add or change the height attribute of the html element
 *
 * 		Full Documentation Link:
 * 		https://simplehtmldom.sourceforge.io/manual.htm
 * 		https://github.com/jkrrv/php-dom-parser
 *
 * Macro Actions will create revision which can be revet back if needed.
 *
 * Logging Macro Actions: 
 * You can log the actions in your macro to be displayed as follows:
	$this->log( 'Default messages appear in Grey background'    ,'notice'   );
	$this->log( 'Success messages appear in Green background'   ,'success'  );
	$this->log( 'Warning messages appear in Orange background'  ,'warning'  );
	$this->log( 'Important messages appear in Red background'   ,'important');
	$this->log( 'Info messages appear in Blue background'       ,'info'     );
	$this->log( 'Inverse messages appear in Black background '  ,'inverse'  );
 *
 * The macros provided are just examples.
 */
?>

