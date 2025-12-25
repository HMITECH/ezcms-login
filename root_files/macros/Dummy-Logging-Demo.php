<?php
/*
 * This macro is a dummy macro to show how to log messages
 * It does not do anything to the content and can be used understand how macros works.
 * 
 * Messages logged during the execution of the Macro are displayed as show:
 *
	Default		GREY		<span class="label">Default</span>
	Success		GREEN		<span class="label label-success">Success</span>
	Warning		ORANGE		<span class="label label-warning">Warning</span>
	Important	RED			<span class="label label-important">Important</span>
	Info		BLUE		<span class="label label-info">Info</span>
	Inverse		BLACK		<span class="label label-inverse">Inverse</span>
 *
 * Run this Macro on a page to see the logged messages
 *
 */

// Uncomment the line below if you want to log to file.
// Location site-assets/logs/macro/
// $this->logFile = 'dummy-macro.csv';

$this->log( 'Default messages appear in Grey background'    ,'notice'   );
$this->log( 'Success messages appear in Green background'   ,'success'  );
$this->log( 'Warning messages appear in Orange background'  ,'warning'  );
$this->log( 'Important messages appear in Red background'   ,'important');
$this->log( 'Info messages appear in Blue background'       ,'info'     );
$this->log( 'Inverse messages appear in Black background '  ,'inverse'  );

?>