<div id="loading">
<?php
	define('URL', 'http://%1$s:%2$s@redmine/');
	require_once 'simple/ActiveResource.php';
	require_once 'simple/classTextile.php';

	$textile = new Textile();

	function login() {
		header('HTTP/1.1 401 Unauthorized');
	   	header('WWW-Authenticate: Basic realm="Login with your redmine details');

	    	die('You need to login to continue');
	}

	if (empty($_SERVER['PHP_AUTH_USER']) && empty($_COOKIE['RedmineLogin'])) {
		login();
	}
	elseif(empty($_COOKIE['RedmineLogin'])) {
		$userDetails = array(
			'user' => $_SERVER['PHP_AUTH_USER'],
			'pass' => $_SERVER['PHP_AUTH_PW']
		);
		if(checkRedmine($userDetails)) {
			setcookie('RedmineLogin', base64_encode(json_encode($userDetails)), strtotime('+1 year'));
		}
		else {
			setcookie('RedmineLogin', '', time()-10);
			login();
		}
	}
	else {
		$userDetails = json_decode(base64_decode($_COOKIE['RedmineLogin']), true);
		if(!checkRedmine($userDetails)) {
			setcookie('RedmineLogin', '', time()-10);
			login();
		}
	}

	function checkRedmine($details) {
		$contents = @file_get_contents(sprintf(URL . '/projects.xml', $details['user'], $details['pass']));
		return $contents === false ? false : true;
	}

	class Project extends ActiveResource {
		var $site = URL;
		var $request_format = 'xml';

		public function __construct($userDetails) {
			$this->site = sprintf(URL, urlencode($userDetails['user']), urlencode($userDetails['pass']));

			parent::__construct();
		}
	}
	
	class Issue extends ActiveResource {
		var $site = URL;
		var $request_format = 'xml';

		public function __construct($userDetails = null) {
			$this->site = sprintf(URL, urlencode($userDetails['user']), urlencode($userDetails['pass']));

			parent::__construct();
		}
	}

	class Stories {
		private $userDetails, $projectId, $issues, $project, $stories;
		
		public function __construct($userDetails, $projectId) {
			$this->userDetails = $userDetails;
			$this->projectId = $projectId;
			$this->textile = new Textile();
			
			@apache_setenv('no-gzip', 1);
			@ini_set('zlib.output_compression', 0);
			@ini_set('implicit_flush', 1);
			for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
			ob_implicit_flush(1);
			
			echo(str_repeat(' ',1024));
			$this->output('Loading...');
			
			$this->getProject();
			$this->getIssues();
			$this->findStories();
		}
		
		private function output($text) {
			echo $text;
			echo '<br>';
			flush();
		}
		
		public function projectInfo($attribute = null) {
			if($attribute) {
				return $this->project->$attribute;
			}
			
			return $this->project;
		}
		
		private function getIssues() {
			$this->output('Fetching all issues...');
			$Issue = new Issue($this->userDetails);
			
			$issues = $Issue->find('all', array('project_id' => $this->project->id, 'limit' => 1000));
			
			if(!empty($issues->error)) {
				throw new Exception($issues->error);
			}
			else {
				$this->output('Issues loaded.');
				$this->issues = $issues;
			}
		}
		
		private function getProject() {
			$this->output('Fetching project information...');
			$Project = new Project($this->userDetails);
			$project = $Project->find($this->projectId);
			
			if(!empty($project->error)) {
				throw new Exception($project->error);
			}
			else {
				$this->output('Project loaded.');
				$this->project = $project;
			}
		}
		
		private function findStories() {
			$storyDir = opendir('.');

			$stories = array();

			$this->output('Finding available stories...');
			while($storyId = readdir($storyDir)) {
				if(strpos($storyId, 'story-') !== false) {
					$storyId = str_replace('story-', '', $storyId);

					$this->output('Fetching story #' . $storyId . '...');
					$Story = new Issue($this->userDetails);		

					$story = $Story->find($storyId, array('include' => 'children,relations'));

					if(empty($story->error)) {
						$stories[$story->id] = $story;	
					}
				}
			}
			$this->output(count($stories) . ' stories found.');

			closeDir($storyDir);
			ksort($stories);

			$this->stories = $stories;		
		}
		
		private function findIssue($issueId) {
			foreach($this->issues as $issue) {
				if($issue->id == $issueId) {
					return $issue;
				}
			}
			return false;
		}

		public function renderStories() {
			$output = '';
			foreach($this->stories as $story) {
				$output .= "<h4><a href=\"#\">Story #{$story->id} - {$story->subject} ({$story->status->attributes()->name})</a></h4>";
				$output .= '<div>';
				$output .= '<div class="buttons">';
					$output .= "<a href=\"story-{$story->id}\" target=\"_blank\">Test this story</a>";
					$output .= "<a href=\"http://redmine.octoplus.co.za/issues/{$story->id}\" target=\"_blank\">View this story on redmine</a>";
					$output .= "<a href=\"http://redmine.octoplus.co.za/projects/".$this->projectId."/issues/new\" target=\"_blank\">Create new issue</a>";
				$output .= '</div>';

				//Story description
				$output .= '<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;">';
					$output .= '<p>'.$this->textile->TextileThis($story->description).'</p>';
				$output .= '</div>';	

				//Child stories
				if(!empty($story->children->issue)) {
					$output .= '<div>';
					$output .= '<h3>Tasks</h3>';
					$output .= '<ul>';
					foreach($story->children->issue as $child) {
						$child = $this->findIssue($child->attributes()->id);
						if($child !== false) {
							$output .= "<li><a class=\"task-id\" href=\"http://redmine.octoplus.co.za/issues/{$child->id}\" target=\"_blank\">{$child->id}</a><span class=\"task-subject\">{$child->subject}</span><span class=\"task-status\">{$child->status->attributes()->name}</span></li>";
						}
					}
					$output .= '</ul>';
					$output .= '</div>';
				}

				if(!empty($story->_data['relations'])) {
					$output .= '<div>';
					$output .= '<h3>Related issues</h3>';
					$output .= '<ul>';
					foreach($story->relations->relation as $relation) {
						$relation = $this->findIssue($relation->attributes()->issue_id);
						if($relation !== false) {
							$output .= "<li><a class=\"task-id\" href=\"http://redmine.octoplus.co.za/issues/{$relation->id}\" target=\"_blank\">{$relation->id}</a><span class=\"task-subject\">{$relation->subject}</span><span class=\"task-status\">{$relation->status->attributes()->name}</span></li>";
						}
					}
					$output .= '</ul>';
					$output .= '</div>';			
				}

				$output .= '</div>';
			}
			
			return $output;
		}
	}
	
	try {
		$Stories = new Stories($userDetails, PROJECTID);
	}
	catch(Exception $e) {
		die($e->getMessage());
	}
?>
</div>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $Stories->projectInfo('name'); ?> - Stories available for testing</title>
		<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/redmond/jquery-ui.css" type="text/css" rel="Stylesheet" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(function() {
				$('#stories').accordion({
					collapsible: true,
					autoHeight: false,
					active: false
				});

				$('.buttons').buttonset();
				
				$('#loading').remove();
			});
		</script>
		<style type="text/css">
			.task-id {
				margin-right: 10px;
			}
			
			.task-status {
				font-size: 90%;
				color: #a0a0a0;
				margin-left: 10px;
			}
		</style>
	</head>
	<body>
		<h2><?php echo $Stories->projectInfo('name'); ?> - Stories for testing</h2>
		<p><?php echo $textile->TextileThis($Stories->projectInfo('description')); ?></p>
		<div id="stories">
			<?php
				echo $Stories->renderStories();
			?>
		</div>
	</body>
</html>
