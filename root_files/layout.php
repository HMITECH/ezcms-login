<?php
/*
 * EZCMSA Code written by mo.ahmed@hmi-tech.net and mosh.ahmed@gmail.com
 *
 * HMI Tech Mumbai
 *
 * View: Default Front end layout - layout.php
 * HMI Tech Mumbai
 * The layout is a basic structure of the page to be rendered
 * you can copy this layout and create your own custom layouts
 * and then use then in the pages of the site.
 */

?><!DOCTYPE html>
<html lang="en">
<head>

<?php include ( "includes/head.php" ); ?>

</head>
<body>

	<header><?php echo $header; ?></header>

	<table>
		<tr>
			<td class="asidebar"><aside><?php echo $sidebar;?></aside></td>
			<td><main><?php echo $maincontent;?></main></td>
			<td class="asidebar"><aside><?php echo $siderbar;?></aside></td>
		</tr>
	</table>

	<footer><?php echo $footer; ?></footer>

</body>
</html>
