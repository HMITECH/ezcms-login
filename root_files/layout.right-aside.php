<?php
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net and mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * View: Default Front end layout
 *
 * The layout is an example to show  with only right sidebar
 * without the aside content.
 * Change the layout of any page to this for full width content.
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
			<td><main><?php echo $maincontent;?></main></td>
			<td class="asidebar"><aside><?php echo $siderbar;?></aside></td>
		</tr>
	</table>

	<footer><?php echo $footer; ?></footer>

</body>
</html>
