<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Pages Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

// Handles Web Pages in ezCMS
class ezPages extends ezCMS {

	public $id = 1;
	public $treehtml = '';
	public $ddOptions = '';
	public $slOptions = '';	
	public $createdText = '';
	public $addNewBtn;
	public $page;
	public $btns;
	private $childIDS = array(); // id of seclect page + its children.
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Check if file to display is set
		if (isset($_GET['id'])) $this->id = $_GET['id'];
		
		// Re order the pages
		if (isset($_GET['redorderids'])) $this->reorderPages();		
		
		// Delete Page
		if (isset($_GET['delid'])) $this->deletePage();
		
		// Copy page
		if (isset($_GET['copyid'])) $this->copyPage();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		if ($this->id <> 'new' ) {
			$this->page = $this->query('SELECT * FROM `pages` WHERE `id` = '.intval($this->id).' LIMIT 1')
				 ->fetch(PDO::FETCH_ASSOC);
				 
			// check if user is present.
			if (!isset($this->page['id'])) {
				header("Location: ?flg=yell");
				exit;
			}

			$this->setupCheckboxes();

			// Get Created on String
			$this->createdText = '<div class="clearfix"></div><p><em>Created on '.
				$this->page['createdon'].'</em> and last edited by <strong>'.
				$this->chkTableForVal('users', 'id', 'username', $this->page['createdby'])
				.'</strong></p>';
		}
		
		// Load childern ids
		$this->getChildIDS($this->id);
		
		// Update the Controller of Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		// Get the Revisions
		if ($this->id != 'new') $this->getRevisions();
		
		//Build the Menu to show
		$this->buildMenu();

		// Get the layouts for select options
		$this->buildlayoutOpts();

		//Build the HTML Treeview
		$this->buildTree();

		//Disable parent page drop down
		if ( ($this->id > 2) || ($this->id == 'new') ) $this->ddOptions = 
			'<select id="slparentid" name="parentid" class="input-block-level">'.$this->ddOptions.'</select>';
		else $this->ddOptions = '<div class="alert alert-info slRootMsg">Root</div>';
		
		// process variable for html display
		if ($this->id != 'new') $this->setPageVariables();
		
		// Get the Message to display if any
		$this->getMessage();
		$this->msg = str_replace('File','Page',$this->msg);

	}
	
	// Function to reorder pages
	private function reorderPages() {
		$place = 1;
		$stmt = $this->prepare("UPDATE `pages` SET `place` = ? WHERE `id` = ?");
		foreach (explode(',',$_GET['redorderids']) as $id) {
			if (!$stmt->execute(array($place,$id))) die('Reorder SQL Failed!');
			$place++;
		}
		die('0');
	}
	
	// Function to setup the checkboxes
	private function setupCheckboxes() {
		$this->setOptions('nositemap', '', '');
		$this->setOptions('useheader', 'Page will display this custom HEADER', 'Page will display the default HEADER');
		$this->setOptions('useside'  , 'Page will display this custom ASIDE1', 'Page will display the default ASIDE1');
		$this->setOptions('usesider' , 'Page will display this custom ASIDE2', 'Page will display the default ASIDE2');
		$this->setOptions('usefooter', 'Page will display this custom FOOTER', 'Page will display the default FOOTER');
		$this->setOptions('published', 'Page is published','Unpublished page is only visible when logged in');
	}

	// Function to build the menu to display
	private function buildlayoutOpts() {
		$isSel = '';
		if (($this->page['layout'] =='') || ($this->page['layout']=='layout.php')) $isSel = 'selected';
		$this->slOptions .= '<option value="layout.php" '.$isSel.'>Default - layout.php</option>';
		foreach (glob("../layout.*.php") as $entry) {
			$entry = substr($entry, 3 , strlen($entry)-3);
			$isSel = '';
			if ($this->page['layout'] == $entry) $isSel = 'selected';
			$this->slOptions .= "<option $isSel>$entry</option>";
		}
	}
	
	// Function to build the menu to display
	private function buildMenu() {
	
		$this->btns = '';
		if ($this->id == 'new') { 
			$this->btns .= '<input type="submit" name="Submit" class="btn btn-primary" value="Add New">';
			return ;
		}
		$this->btns .= '<input type="submit" name="Submit" class="btn btn-primary" value="Save Changes">';
		$myclass = ''; // 
		if ( !$this->page['published'] ) $myclass = 'nopubmsg';
		$viewURL = $this->page['url'];
		if ( $this->page['id']==2 ) $viewURL = '/'.time().time().time();
		$this->btns .= '<a href="..'.$viewURL.'" target="_blank"  class="btn btn-success '.$myclass.' ">View</a>';
		$this->btns .= '<a href="?id=new" class="btn btn-info">New</a>';
		$this->btns .= '<a href="?copyid='.$this->id.'" class="btn btn-warning">Copy</a>';
		if ($this->id > 2)
			$this->btns .= '<a href="?delid='.$this->id.'" class="btn btn-danger conf-del">Delete</a>';
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showrevs" href="#" class="btn btn-secondary">Revisions <sup>'.$this->revs['cnt'].'</sup></a>';
		if ($_SESSION['EDITORTYPE'] == 3)
			$this->btns .= '<a id="showdiff" href="#" class="btn btn-inverted btn-danger">Review DIFF</a>';	
	}
	
	// Function to Copy a Page 
	private function copyPage() {
	
		// Get ID of the page to copy
		$id = intval($_GET['copyid']);

		// Check permissions
		if (!$this->usr['editpage']) {
			header("Location: ?flg=noperms&id=$id");
			exit;
		}
		
		if ($this->query("INSERT INTO `pages` ( `pagename`, `title`, `url`, `nositemap`, `keywords`, `description`, `maincontent`, 
					`useheader`, `headercontent`, `head`, `notes`, `layout`, `usefooter`, `footercontent`, `useside`, `sidecontent`, 
					`usesider`, `sidercontent`, `published`, `parentid`) 
				SELECT `pagename` , `title`, CONCAT(`url`,'-', UNIX_TIMESTAMP()), `nositemap`, 
					`keywords`, `description`, `maincontent`, `useheader`, `headercontent` , `head`, `notes`, `layout`, `usefooter`, 
					`footercontent`, `useside`, `sidecontent` , `usesider` , `sidercontent` , 0, IF(`parentid`=0 , 1 , `parentid`) 
					FROM `pages` WHERE id=$id")) {
			$id = $this->lastInsertId();
			// update name, url and title
			$this->query("UPDATE `pages` SET `place` = `id`,
				`pagename` = concat( `pagename` , '-copy', `id` ),
				`title` = concat( `title` , '-copy', `id` ) WHERE id =$id");
			header("Location: ?flg=copied&id=$id");
			exit;
		}

		header("Location: ?id=$id&flg=copyfailed");	// failed
		exit;
	
	}
	
	// Function to Update the Defaults Settings
	private function deletePage() {

		// Get ID of the page to delete
		$id = intval($_GET['delid']);

		// Check permissions
		if (!$this->usr['delpage']) {
			header("Location: ?flg=noperms&id=".$id);
			exit;
		}
		
		// TODO - DELETE REVISIONS
		if (!$this->query("DELETE FROM `git_pages` WHERE `page_id` = $id")) {
			$this->flg = 'revdelfailed';
			return;
		} 		
		
		// Set all childern of deleted page to its parent
		$parent = $this->query("SELECT `parentid` FROM `pages` WHERE `id` = $id")->fetch(PDO::FETCH_ASSOC);
		if (!$this->query("Update `pages` SET `parentid` = ".$parent['parentid']." WHERE `parentid` = $id")) {
			$this->flg = 'failed';
			return;
		} 
		
		// Delete the Pge
		if ( $this->delete('pages',$id) ) {
			// Re build the sitemap again
			$this->rebuildSitemap();
			header("Location: ?flg=deleted");
			exit;
		}
		
		// Failed to update
		$this->flg = 'failed';
	
	}
	// Function to Update the Defaults Settings
	private function delRevision() {

		// Check permissions
		if (!$this->usr['delpage']) {
			header("Location: ?flg=noperms&id=".$this->id);
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);

		// Delete the revision
		if ( $this->delete('git_pages',$revID) ) {
			header("Location: ?flg=revdeleted&id=".$this->id);
			exit;
		}

		header("Location: ?flg=revdelfailed&id=".$this->id);
		exit;

	}

	// Function to fetch the revisions
	private function getRevisions() {

		$results = $this->query("SELECT git_pages.*,users.username
				FROM git_pages LEFT JOIN users ON git_pages.createdby = users.id
				WHERE git_pages.page_id = ".intval($this->id)." ORDER BY git_pages.id DESC")->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($results as $entry) {

			$this->revs['opt'] .= '<option value="'.$entry['id'].'">#'.
				$this->revs['cnt'].' '.$entry['createdon'].' ('.$entry['username'].')</option>';

			$this->revs['log'] .= '<tr>
				<td>'.$this->revs['cnt'].'</td>
				<td>'.$entry['username'].'</td>
				<td>'.$entry['revmsg'].'</td>
				<td>'.$entry['createdon'].'</td>
			  	<td data-rev-id="'.$entry['id'].'">
				<a href="#">Fetch</a> &nbsp;|&nbsp; 
				<a href="#">Diff</a> &nbsp;|&nbsp;
				<a href="?id='.$this->id.'&purgeRev='.$entry['id'].'" class="conf-del">Purge</a></td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry;

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;

		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}

	// Function to Setup page variable and checkboxes
	private function setPageVariables() {
		$this->page['keywords'] = htmlspecialchars($this->page["keywords"]);
		$this->page['description'] = htmlspecialchars($this->page["description"]);
		$this->page['maincontent'] = htmlspecialchars($this->page["maincontent"]);		
		$this->page['headercontent'] = htmlspecialchars($this->page["headercontent"]);
		$this->page['sidecontent'] = htmlspecialchars($this->page["sidecontent"]);
		$this->page['sidercontent'] = htmlspecialchars($this->page["sidercontent"]);		
		$this->page['footercontent'] = htmlspecialchars($this->page["footercontent"]);		
		$this->page['head'] = htmlspecialchars($this->page["head"]);
		$this->page['notes'] = htmlspecialchars($this->page["notes"]);
	}

	private function setOptions($itm, $msgOn, $mgsOff) {
		$this->page[$itm.'Check'] = '';
		$this->page[$itm.'Msg'] = '<span class="label label-important">'.$mgsOff.'</span>';	
		if ($this->page[$itm]) {
			$this->page[$itm.'Check'] = 'checked';
			$this->page[$itm.'Msg'] = '<span class="label label-info">'.$msgOn.'</span>';
		}
	}
	
	// Function to Build Treeview HTML
	private function buildTree($parentid = 0) {

		static $nestCount;

		$treeSQL = $this->prepare(
			"SELECT `id`, `title`, `pagename`, `url`, `published`, `description` 
			FROM  `pages` WHERE `parentid` = ? order by place");
		$treeSQL->execute( array($parentid) );

		if ($treeSQL->rowCount()) {

			$nestCount += 1;
			if ($nestCount == 1) $this->treehtml .= '<ul id="left-tree">'; else $this->treehtml .=  '<ul>';
			$cnt = 0;
			
			while ($entry = $treeSQL->fetch()) {
				$cnt++;
				
				$action = '<i class="icon-file"></i>';
				if ($entry['id']==1) $action = '<i class="icon-home"></i>';
				if ($entry['id']==2) $action = '<i class="icon-question-sign"></i> ';
				
				$myclass = ($entry["id"] == $this->id) ? 'label label-info' : '';
				$myPub   = ($entry["published"]) ? '' : ' <i class="icon-ban-circle" title="Page is not published"></i>';
				$this->treehtml .= '<li data-id="'.$entry['id'].'">'.$action.' <a href="pages.php?id='.$entry['id'].
							'" class="'.$myclass.'" title="'.$entry["title"].'">'.$entry["pagename"].'</a>'.$myPub;
				$isSel = '';
				if  ( ($entry['id'] != 2) && !(in_array($entry['id'], $this->childIDS)) ){
					if ($this->page['parentid'] == $entry['id']) $isSel = 'selected';
					$this->ddOptions .= '<option value="' . $entry['id'] . '" '.$isSel.'>'.
						str_repeat(' - ',$nestCount - 1) . $entry['pagename'].'</option>';				
				}
				$this->buildTree($entry['id']);
				$this->treehtml .= '</li>';
			}
			$this->treehtml .= '</ul>';
			$nestCount -= 1;
		}

	}
	
	// Function to rebuild the sitemap
	private function rebuildSitemap() {	
	
		$sitemapXML  = '<?xml version="1.0" encoding="UTF-8"?>
			<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>
			<!-- generator="ezCMS" -->
			<!-- sitemap-generator-url="http://www.hmi-tech.net" sitemap-generator-version="2.0" -->
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			<url><loc>http://' . $_SERVER['SERVER_NAME'] .  '/</loc></url>';
		foreach ($this->query("SELECT `url` FROM `pages` WHERE `id`>2 AND `published`=1 AND `nositemap`=0") as $entry)
				$sitemapXML  .= '<url><loc>http://'.$_SERVER['SERVER_NAME'].$entry['url'].'</loc></url>';
		$sitemapXML  .= '</urlset>';
		// save XML Site Map
		file_put_contents('../sitemap.xml', $sitemapXML);
	}
	
	// Function to get valid URL Stub
	private function Slug($string) {
    	return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', 
			html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', 
			htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
	}
	
	// function to collect array with selected page and all its children
	private function getChildIDS($id) {
		$id = intval($id);
		array_push($this->childIDS, $id);
		foreach ($this->query("SELECT `id` FROM `pages` WHERE `parentid` = '$id'") as $entry)
			if ($id >2) $this->getChildIDS($entry['id']);
	}
	
	// Function to auto generate URL 
	private function getAutoURL($parentid, $pagename) {
	
		$url = '';
		$papa['parentid'] = intval($parentid);
		while ($papa['parentid'] > 2) {
			$papa = $this
				->query("SELECT `parentid`, `pagename` FROM `pages` WHERE id = ".$papa['parentid'])
				->fetch(PDO::FETCH_ASSOC);
			$url = $this->Slug($papa['pagename']).'/'.$url;
		}
		$url = '/'.$url.$this->Slug($pagename);
		
		// make sure this URL is unique
		$dupCheckID = $this->chkTableForVal('pages', 'url', 'id', $url);
		if ($this->id == 'new') {
			if ($dupCheckID) $url = $url.'-'.time();
		} else {
			if (($dupCheckID) && ($dupCheckID != $this->id))  $url = $url.'-'.time();
		}
	
		return $url;

	}

	// Function to Update the Controller
	private function update() {

		// Check permissions
		if (!$this->usr['editpage']) {
			header("Location: ?flg=noperms&id=".$this->id);
			exit;
		}
		
		// array to hold the data
		$data = array();
		
		// get the required post varables 
		$txtFlds = array('pagename', 'title', 'keywords', 'description', 'maincontent', 'headercontent',
			 'sidecontent', 'sidercontent', 'sidercontent', 'footercontent','head', 'notes', 'layout' );
		if ( ($this->id != 1) && ($this->id != 2) )
			array_push($txtFlds, 'parentid', 'url');
		$this->fetchPOSTData($txtFlds, $data);

		// get the required post checkboxes 
		$cksFlds = array('published','useheader','useside','usesider','usefooter','nositemap');
		$this->fetchPOSTCheck($cksFlds, $data);
		
		
		// Validate here ...
		if (strlen(trim($data['pagename'])) < 2) die('Page Name must min 2 chars!');
		if (strlen(trim($data['title'])) < 2) die('Page Title must min 2 chars!');
		if (isset($data['parentid']))
			if  (in_array($data['parentid'], $this->childIDS))
				die('Parent cannot be same page or its child');
				
		// if URL is empty ... auto generate it from path.
		if (isset($data['url'])) {
			if (trim($data['url'])=='')
				$data['url'] = $this->getAutoURL($data['parentid'], $data['pagename']);
			// check duplication of URL
			$dupCheckID = $this->chkTableForVal('pages', 'url', 'id', $data['url']);
		} else $dupCheckID = false;
						

		if ($this->id == 'new') {
			// add new
			$data['createdby'] = $_SESSION['EZUSERID'];
			
			// Test for URL Duplicatoin
			if ($dupCheckID) {
				$this->flg = 'urlduplicate';
				$this->page = $data;
				$this->setupCheckboxes();
				return;
			}
			
			$newID = $this->add( 'pages' , $data);
			if ($newID) {
				$this->rebuildSitemap();
				header("Location: ?id=".$newID."&flg=added");	// added
				exit; 
			} 
		} else {
		
			// Test if nothing has changed 
			$isChanged = false;
			foreach (array_merge($txtFlds, $cksFlds) as $fld)
				if ($data[$fld] != $this->page[$fld]) $isChanged = true;
			if (!$isChanged) {
				header("Location: ?flg=nochange&id=".$this->id);
				exit;
			}

			// url address should not be duplicated.
			if (($dupCheckID) && ($dupCheckID != $this->id)) {
				$this->flg = 'urlduplicate';
				$this->page = $data;
				$this->setupCheckboxes();
				return;
			}

			// Create a revision			
			if (!$this->query("INSERT INTO `git_pages` ( 
				  `page_id`, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` , `useside` ,
				   `sidecontent` , `published` , `parentid` , `url` , `revmsg`,
				   `sidercontent` , `usesider` ,`head` , `notes`, `layout` , `nositemap` , `createdby` )
				SELECT 
				  `id` AS page_id, `pagename`, `title`, `keywords`, `description`, `maincontent`,
				  `useheader` , `headercontent` , `usefooter` , `footercontent` ,
				  `useside` , `sidecontent` , `published` , `parentid` , `url` , ".$this->quote($_POST['revmsg']).",
				  `sidercontent` , `usesider` ,`head` , `notes`, `layout` , `nositemap` , 
				  '".$_SESSION['EZUSERID']."' as `createdby`  FROM `pages` WHERE `id` = ".$this->id)) {
				header("Location: ?flg=revfailed&id=".$this->id);
				exit;
			}

			// update
			if ($this->edit( 'pages' , $this->id , $data )) {
				$this->rebuildSitemap();
				header("Location: ?id=".$this->id."&flg=saved");	// added
				exit; 
			}
		}

	}
	
	// Function to Set the Display Message
	private function getMessage() {

		// Set the HTML to display for this flag
		switch ($this->flg) {
			case "urlduplicate":
				$this->setMsgHTML('error','SAVE FAILED','This URL is already in use by another page.');
				break;
			case "copied":
				$this->setMsgHTML('success','PAGE COPIED','The page was successfully copied. CHANGE TITLE, NAME AND URL AS NEEDED');
				break;
			case "copyfailed":
				$this->setMsgHTML('error','COPY FAILED','The page was not copied.');
				break;
		}

	}

}
?>
