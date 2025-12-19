<?php 
/*
 * ezCMS Code written by mo.ahmed@hmi-tech.net & mosh.ahmed@gmail.com
 *
 * HMI Technologies Mumbai
 *
 * Class: ezCMS Includes Class 
 * 
 */

// **************** ezCMS CLASS ****************
require_once ("ezcms.class.php"); // CMS Class for database access

class ezIncludes extends ezCMS {

	public $filename = "include.php";
	public $homeclass = '';
	public $deletebtn = '';
	public $content = '';
	public $treehtml = '';
	// Stores Layout usage Details
	public $usage = ['log' => '', 'cnt' => 0];
	
	// Consturct the class
	public function __construct () {
	
		// call parent constuctor
		parent::__construct();
		
		// Create includes folder if not present
		if ( !is_dir('../includes') ) mkdir('../includes');
		
		// Create default include file if no present
		if (!file_exists('../includes/include.php')) file_put_contents('../includes/include.php', '<?php /* empty include file */ ?>' );
		
		// Check if file to display is set
		if (isset($_GET['show'])) $this->filename = $_GET['show'];
		if ($this->filename=="include.php") {
			$this->homeclass = 'label label-info';
		} else {
			$this->deletebtn = '<a href="?delfile='.$this->filename.'" class="btn btn-danger conf-del">Delete</a>';
		}
		
		// Check if file is to be deleted
		if (isset($_GET['delfile'])) $this->deleteFile();
		
		// Purge Revision
		if (isset($_GET['purgeRev'])) $this->delRevision();
		
		// Check if include file is present
		if (!file_exists('../includes/'.$this->filename)) {
			header("Location: ?flg=yell");
			exit;
		}
		
		// get the contents of the include file
		$this->content = htmlspecialchars(file_get_contents('../includes/'.$this->filename));
		
		// Update if Posted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') $this->update();
		
		//Build the HTML Treeview
		$this->buildTree();
		
		// Get the Usage of this include in layouts
		$this->getUsage();		
		
		// Get the Revisions
		$this->getRevisions();

	}
	
	// Function to Update the Defaults Settings
	private function delRevision() {

		$show = '';
		if ($this->filename != "include.php") $show = '&show='.$this->filename;	

		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms$show");
			exit;
		}
		
		// Get the revision ID to delete
		$revID = intval($_GET['purgeRev']);
		
		// Delete the revision
		if ( $this->delete('git_files',$revID) ) {
			header("Location: ?flg=revdeleted$show");
			exit;
		}
		
		header("Location: ?flg=revdelfailed$show");
		exit;
	
	}
	
	// Function to Build Treeview HTML
	private function buildTree() {

	    $groups = [];

	    // Scan files
	    foreach (glob("../includes/*.php") as $entry) {

	        $entry = basename($entry);
	        if ($entry == 'include.php') continue;

	        // Split filename by first dot
	        $parts = explode('.', $entry, 2);

	        // Group key is first part
	        $group = $parts[0];

	        // Store in group
	        $groups[$group][] = $entry;
	    }

	    ksort($groups);

	    $this->treehtml = '<ul>';

	    foreach ($groups as $group => $groupFiles) {

	        // Folder only if more than one file shares prefix
	        if (count($groupFiles) > 1) {

	            $this->treehtml .= '<li>';
	            $this->treehtml .= '<i class="icon-folder-open"></i> ';
	            $this->treehtml .= '<a href="#">'.$group.'</a>';
	            $this->treehtml .= '<ul>';

	            sort($groupFiles);
	            foreach ($groupFiles as $file) {

	                // Display: strip prefix only if more than one dot
	                if (substr_count($file, '.') > 1) {
	                    $display = substr($file, strlen($group) + 1);
	                } else {
	                    $display = $file;
	                }

	                $myclass = (basename($this->filename) === $file) ? 'label label-info' : '';

	                $this->treehtml .= '<li>';
	                $this->treehtml .= '<i class="icon-file"></i> ';
	                $this->treehtml .= '<a href="includes.php?show='.$file.'" class="'.$myclass.'">'.$display.'</a>';
	                $this->treehtml .= '</li>';
	            }

	            $this->treehtml .= '</ul></li>';

	        } else {
	            // Single file → show normally
	            $file = $groupFiles[0];
	            $display = $file;
	            $myclass = (basename($this->filename) === $file) ? 'label label-info' : '';

	            $this->treehtml .= '<li>';
	            $this->treehtml .= '<i class="icon-share-alt"></i> ';
	            $this->treehtml .= '<a href="includes.php?show='.$file.'" class="'.$myclass.'">'.$display.'</a>';
	            $this->treehtml .= '</li>';
	        }
	    }

	    $this->treehtml .= '</ul>';
	}

	// Function to fetch the revisions
	private function getRevisions() {
	
		$show = '';
		if ($this->filename != "include.php") $show = '&show='.$this->filename;	
		
		foreach ($this->query("SELECT git_files.*, users.username
				FROM users LEFT JOIN git_files ON users.id = git_files.createdby
				WHERE git_files.fullpath = 'includes/".$this->filename."'
				ORDER BY git_files.id DESC") as $entry) {

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
				<a href="?purgeRev='.$entry['id'].$show.'" class="conf-del">Purge</a>	
				</td></tr>';

			$this->revs['jsn'][$entry['id']] = $entry['content'];

			$this->revs['cnt']++;
		}
		$this->revs['cnt']--;
		
		if ($this->revs['log'] == '') 
			$this->revs['log'] = '<tr><td colspan="4">There are no revisions.</td></tr>';	
	}

	// Function to fetch the layouts this include is used in
	private function getUsage() {

		foreach (glob('../layout*.php') as $entry) {
			if (strpos(file_get_contents($entry), 'includes/'.$this->filename) !== false) {
				$entry = basename($entry);
				$editLnk = "layouts.php";
				if ($entry != 'layout.php') {
					$entry = substr($entry, 7 , strlen($entry)-7);
					$editLnk .= "?show=$entry";
				}
				$this->usage['log'] .= '<tr><td>'.($this->usage['cnt']+1).'</td>
					<td>'.$entry.'</td>	
					<td><a href="'.$editLnk.'" target="_blank">EDIT</a></td></tr>';
				$this->usage['cnt']++;
			}
		}
		
		if ($this->usage['log'] == '') 
			$this->usage['log'] = '<tr><td colspan="3">There are no layouts using this include file.</td></tr>';	
	}
	
	// Function to Delete the Layout
	private function deleteFile() {
	
		$filename = $_REQUEST['delfile'];
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms&show=$filename");
			exit;
		}
		
		// Default layout cannot be deleted and file must begin with 'layout.' and end with '.php'
		if (substr($filename,-4)!='.php') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// Check if layout is writeable
		if (!is_writable("../includes/$filename")) {
			header("Location: ?flg=unwriteable&show=$filename");
			exit;
		}		
		
		// Delete the file
		if (unlink("../includes/$filename")) {
			header("Location: ?flg=deleted");
			exit;
		}
		// Failed to delete the file	
		header("Location: ?flg=delfailed&show=$show");
		exit;	
	}
	
	// Function to Update the Layout
	private function update() {
	
		// Check all the variables are posted
		if ( (!isset($_POST['Submit'])) || (!isset($_POST['txtContents'])) || (!isset($_POST["txtName"])) ) {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request.');
		}
		
		$filename = $_POST["txtName"];
		$contents = $_POST["txtContents"];
		//$show = substr($filename, 7 , strlen($filename)-7);
		
		
		// Check permissions
		if (!$this->usr['editlayout']) {
			header("Location: ?flg=noperms&show=$filename");
			exit;
		}

		// Layout file must end with '.php'
		if (substr($filename,-4)!='.php') {
			header('HTTP/1.1 400 BAD REQUEST');
			die('Invalid Request');
		}

		// If file is missing then it is a copy 
		if (file_exists("../includes/$filename")) {
		
			// Check if writeable
			if (!is_writable("../includes/$filename")) {
				$this->flg = 'unwriteable';
				$this->filename = $filename;
				$this->content = htmlspecialchars($contents);
				return;
			}
			
			// Check if nothing has changed		
			$original = file_get_contents("../includes/$filename");
			if ($original == $contents) {
				header("Location: ?flg=nochange&show=$filename");
				exit;
			}
	
			// Create a revision
			$data = ['content' => $original, 
					'fullpath' => "includes/$filename",
					'revmsg' => $_POST['revmsg'],
					'createdby' => $this->usr['id']];
			if ( !$this->add('git_files', $data) ) {
				header("Location: ?flg=revfailed&show=$filename");
				exit;
			}
			
		}
		
		// Save the layout file
		if (file_put_contents("../includes/$filename", $contents ) !== false) {
			header("Location: ?flg=saved&show=$filename");
			exit;
		}
		
		// Failed to update layout
		$this->flg = 'failed';
		$this->filename = $filename;
		$this->content = htmlspecialchars($contents);

	}
	
}
?>