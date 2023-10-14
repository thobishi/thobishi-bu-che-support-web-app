<?php

class DeployShell extends Shell {
	public $tasks = array('Deploy', 'Execute', 'Create');

	private $defaultRepository = 'git@redmine:';
	private $tmpPath = '/tmp/';
	private $projectPath = '/usr/local/bin/projects/';
	private $project = null;
	private $config = array();
	private $environment = 'dev';
	private $deployDatabase = false;
	private $defaults = array(
		'branch' => 'dev',
		'shallow' => false,
		'database' => array(
			'config' => array(
				'destination' => 'config/database.php',
				'source' => 'config/database.#.php'
			),
			'ignore' => array()
		),
		'excludes' => array(),
		'localDeploy' => true
	);
	private $filePermissions = array();


	private function getProjectList() {
		$projects = array();

		$handle = opendir($this->projectPath);
		
		if($handle === false)  {
			throw new Exception($this->projectPath . ' could not be found.');
		}			
		
		while (false !== ($file = readdir($handle))) {
			if(strpos($file, '.') !== 0) {
				$projects[] = $file;
			}
		}
		closedir($handle);

		return $projects;
	}

	private function selectProject() {
		$projects = $this->getProjectList();

		$this->out('Available projects:');
		foreach($projects as $key => $project) {
			$this->out("\t[".($key+1)."] $project");
		}
		while(true) {
			$projectKey = $this->in('Please choose the project you wish to deploy or [q]uit');
			if(strtolower($projectKey) == 'q') {
				return $this->_stop();
			}
			elseif (isset($projects[$projectKey-1])) {
				$this->project = $projects[$projectKey-1];
				break;
			}
		}
	}

	private function loadProject() {
		if(isset($this->args[0])) {
			$this->project = $this->args[0];
		}
		elseif(empty($this->project)) {
			$this->selectProject();
		}

		$path = $this->projectPath . $this->project;
		if(!is_readable($path)) {
			$this->out("Invalid project config [$path]. Please ensure the project config exists, is readable and is a valid ini file");
			$this->_stop();
		}
		include($path);

		if($config === false) {
			$this->out("Invalid project config [$path]. Please ensure the project config exists, is readable and is a valid php file with a \$config variable.");
			$this->_stop();
		}

		$this->config = $config;

		$this->supportedEnvironments = array_keys($this->config['environments']);
	}

	private function deploymentDetails() {
			$this->hr();
			$this->out("Project name:\t\t{$this->project}");
			$this->out("Project repository:\t{$this->environmentConfig['repository']}");
			$this->out("Branch:\t\t\t{$this->environmentConfig['branch']}");
			$this->out("Deploying to:\t\t{$this->environmentConfig['deployTo']}");
			if(isset($this->environmentConfig['config'])) {
				$this->out("Config files:");
				foreach($this->environmentConfig['config'] as $config) {
					$this->out("\t{$config[0]} -> {$config[1]}");
				}
			}

			if($this->deployDatabase === false) {
				$this->out("Database will NOT be deployed.");
			}
			elseif(isset($this->environmentConfig['database']['source']) && isset($this->environmentConfig['database']['source'])) {
				$this->out("Database will be deployed");
				$this->out("\tServer:\t\t{$this->environmentConfig['database']['server']['username']}@{$this->environmentConfig['database']['server']['name']}\t\t");
				$this->out("\tSource DB:\t{$this->environmentConfig['database']['source']['username']}:{$this->environmentConfig['database']['source']['database']}");
				$this->out("\tDestination DB:\t{$this->environmentConfig['database']['destination']['username']}:{$this->environmentConfig['database']['destination']['database']}");
				if(isset($this->environmentConfig['database']['ignore'])) {
					$this->out("\tThe following tables will be ignored:");
					foreach($this->environmentConfig['database']['ignore'] as $ignore) {
						$this->out("\t\t$ignore");
					}
				}
				elseif(isset($this->environmentConfig['database']['include'])) {
					$this->out("\tThe following tables will be included:");
					foreach($this->environmentConfig['database']['include'] as $ignore) {
						$this->out("\t\t$ignore");
					}
				}
				else {
					$this->nl(5);
					$this->hr();
					$this->out("WARNING: All tables will be transfered. This can result in data loss in the remote environment");
					$this->hr();
					$this->nl(5);
				}
			}

			if(isset($this->config['creates'])) {
				$this->nl(2);
				$this->out('The following directories will be created:');
				foreach($this->config['creates'] as $file) {
					$this->out("\t{$file}");
				}
			}

			if(isset($this->config['filePermissions'])) {
				$this->nl(2);
				$this->out('The following permissions will be set:');
				foreach($this->config['filePermissions'] as $file => $permission) {
					$this->out("\t{$file} -> {$permission}");
				}
			}

			if(isset($this->environmentConfig['commands'])) {
				$this->nl(2);
				$this->out('The following commands will be run:');
				foreach($this->environmentConfig['commands'] as $command) {
					$this->out("\t{$command}");
				}
			}

			$this->hr();
	}

	private function buildSourceRepo() {
		if(!empty($this->params['repo'])) {
			if($this->params['repo'] === true) {
				$repo = '/var/www/html/usr/' . exec('whoami') . '/' . $this->project . '/';
			}
			elseif(strpos($this->params['repo'], '/') !== 0 && strpos($this->params['repo'], '@') === false) {
				$repo = '/var/www/html/usr/' . exec('whoami') . '/' . $this->params['repo'] . '/';
			}
			else {
				$repo = $this->params['repo'];
			}

			$tmpConfig = $this->environmentConfig;
			$tmpConfig['repository'] = $repo;
			
			try {
				$this->Execute->gitExists($tmpConfig);
				$this->environmentConfig['repository'] = $repo;
			}
			catch(Exception $e) {
				$this->out($e->getMessage());
				$this->_stop();
			}
		}
	}

	private function deploy() {
		try {
			$this->Dispatch->clear();
			$this->buildDeployTo();

			$this->out("Ready to deploy {$this->project}");

			if($this->deployDatabase) {
				$this->buildDBConfig();
			}

			$this->deploymentDetails();

			$response = strtolower($this->in("Continue?", array('y', 'n'), 'y'));
			if($response == 'n') {
				return $this->_stop();
			}

			if($this->environmentConfig['localDeploy'] === false) {
				$this->environmentConfig['cloneDir'] = $this->tmpPath . $this->project . '-' . time() . rand(0, 1000);
			}
			else {
				$this->environmentConfig['cloneDir'] = $this->environmentConfig['deployTo'];
			}

			$this->Deploy->setConfig($this->environmentConfig);

			$this->Deploy->cloneRepo();

			if(!empty($this->config['creates'])) {
				$this->Deploy->createDirectories($this->config['creates']);
			}

			if(isset($this->environmentConfig['config'])) {
				$this->Deploy->copyConfigFiles();
			}

			if(!empty($this->config['filePermissions'])) {
				$this->Deploy->setPermissions($this->config['filePermissions']);
			}

			if($this->environmentConfig['localDeploy'] === false) {
				if($this->deployDatabase) {
					$dbFile = $this->Deploy->deployDatabase();
				}

				$this->Deploy->upload();
				$this->Execute->rm($this->environmentConfig['cloneDir']);
			}

			if(isset($this->environmentConfig['commands'])) {
				$this->Deploy->runCommands();
			}
			
			$this->hr();
			$date = date('d F Y H:i');
			$this->out("{$this->project} has been successfully deployed to {$this->environment} at {$date}.");
		}
		catch(Exception $e) {
			if($this->environmentConfig['localDeploy'] === false) {
				$this->Execute->rm($this->environmentConfig['cloneDir']);
				if(isset($dbFile)) {
					$this->Execute->rm($dbFile);
				}
			}
			$this->out('ERROR: ' . $e->getMessage());
			$this->_stop();
		}
	}

	private function buildDBConfig() {
		if(empty($this->environmentConfig['database']['server']['username'])) {
			$this->environmentConfig['database']['server']['username'] = $this->environmentConfig['user'];
		}
		if(empty($this->environmentConfig['database']['server']['name'])) {
			$this->environmentConfig['database']['server']['name'] = $this->environmentConfig['server'];
		}
		if(empty($this->environmentConfig['database']['source']) && !empty($this->environmentConfig['database']['destination'])) {
			$this->environmentConfig['database']['source'] = $this->environmentConfig['database']['destination'];
		}
		if(empty($this->environmentConfig['database']['destination']) && !empty($this->environmentConfig['database']['source'])) {
			$this->environmentConfig['database']['destination'] = $this->environmentConfig['database']['source'];
		}
		if(empty($this->environmentConfig['database']['destination']) && empty($this->environmentConfig['database']['source']) && !empty($this->environmentConfig['database']['database'])) {
			$this->environmentConfig['database']['destination']['database'] = $this->environmentConfig['database']['source']['database'] = $this->environmentConfig['database']['database'];
			$this->environmentConfig['database']['destination']['username'] = $this->environmentConfig['database']['source']['username'] = $this->environmentConfig['database']['username'];
			$this->environmentConfig['database']['destination']['password'] = $this->environmentConfig['database']['source']['password'] = $this->environmentConfig['database']['password'];
		}

		if(isset($this->params['allDb'])) {
			$allDb = $this->in('Are you sure you want to transfer all tables? This will overwrite all tables in the remote environment.', array('y', 'n'), 'n');
			if($allDb == 'y') {
				if(isset($this->environmentConfig['database']['ignore'])) {
					unset($this->environmentConfig['database']['ignore']);
				}
				if(isset($this->environmentConfig['database']['include'])) {
					unset($this->environmentConfig['database']['include']);
				}
			}
		}
	}

	private function buildDeployTo() {
		if($this->environmentConfig['localDeploy'] === false) {
			if(empty($this->environmentConfig['user'])) {
				$this->environmentConfig['user'] = exec('whoami');
			}

			$this->environmentConfig['deployTo'] = $this->environmentConfig['user'] . '@' . $this->environmentConfig['server'] . ':' . $this->environmentConfig['location'];
		}
	}

	public function startup() {
		$this->_welcome();
		$this->out('Octoplus deploy shell');
		$this->hr();

		if(isset($this->params['db'])) {
			$this->deployDatabase = true;
		}
	}

	public function help() {
		$help = <<<HELP
The project deployer for Octoplus
---------------------------------------------------------------
Usage for deployment: deploy <parms> [project_identifier] [environment]
---------------------------------------------------------------
Params:
	-db
		Include a database dump in the deployment (Only applicable with remote environments)
	-allDb
		Ignore ignore/include tables and transfer the entire database
	-location
		Local location to deploy the project too (Only applicable with local environments)
	-branch
		Branch to deploy
	-repo
		Source repository for the deployment. There are a number of ways to use this:
			`deploy <project> <environment> -repo` will use /var/www/html/usr/<your_user_name>/<project>
			`deploy <project> <environment> -repo foobar` will use /var/www/html/usr/<your_user_name>/foobar
			`deploy <project> <environment> -repo /var/www/foobar` will use /var/www/foobar
			`deploy <project> <environemnt> -repo username@server.com:repo` will use the remote repository located at server.com
---------------------------------------------------------------
Available projects:
HELP;

		$this->out($help);
		$this->out("\t* " . implode("\n\t* ", $this->getProjectList()));

		$this->hr();
		$this->out('To create a new project enter deploy create');
		$this->_stop();
	}

	private function localDeploy() {
		$defaultLocation = empty($this->environmentConfig['location']) ? '/var/www/html/usr/' . exec('whoami') . '/' . $this->project . '/' : $this->environmentConfig['location'];
		$this->out("Almost ready to deploy {$this->project} to a local development environment.");
		if(empty($this->params['location'])) {
			$this->environmentConfig['deployTo'] = $this->in("Please enter the location you wish the project to be deployed to.", null, $defaultLocation);
		}
		else {
			$this->environmentConfig['deployTo'] = $this->params['location'];
		}

		$this->buildSourceRepo();
		$this->environmentConfig['branch'] = $this->selectBranch();

		$this->deploy();
	}

	private function remoteDeploy() {
		$this->buildSourceRepo();
		$this->environmentConfig['branch'] = $this->selectBranch();
		
		$this->deploy();
	}

	private function selectBranch() {
		if(!empty($this->params['branch'])) {
			return $this->params['branch'];
		}
		
		if(is_string($this->environmentConfig['branch'])) {
			return $this->environmentConfig['branch'];
		}
		
		if(is_array($this->environmentConfig['branch'])) {
			$branches = $this->Execute->gitBranches($this->environmentConfig, $this->environmentConfig['branch'][0]);
			$message = 'No branches with the prefix "'.$this->environmentConfig['branch'][0].'" exist in the repository.';
		}
		else {
			$branches = $this->Execute->gitBranches($this->environmentConfig);
			$message = 'No branches exist in the repository.';
		}
		
		if(empty($branches)) {
			$this->out($message);
			$this->_stop();
		}
		
		$this->out('Please select which branch you wish to deploy');
		$this->hr();
		foreach($branches as $key => $branch) {
			$this->out("\t[".($key+1)."] $branch");
		}
		
		while(true) {
			$branchKey = $this->in('Please choose the branch you wish to deploy or [q]uit');
			if(strtolower($branchKey) == 'q') {
				return $this->_stop();
			}
			elseif (isset($branches[$branchKey-1])) {
				if(isset($this->environmentConfig['branch']['append'])) {
					$this->environmentConfig['deployTo'] .= DS . str_replace($this->environmentConfig['branch'][0], '', $branches[$branchKey-1]);
				}
				return $branches[$branchKey-1];
				break;
			}
		}
	}
	
	public function main() {
		$this->loadProject();

		if(!isset($this->args[1])) {
			$this->environment = $this->in('What environment do you wish to deploy too', $this->supportedEnvironments);
		}
		else {
			$this->environment = $this->args[1];
		}

		if(!in_array($this->environment, $this->supportedEnvironments)) {
			$this->out("{$this->project} does not support the {$this->environment}");
			$this->_stop();
		}

		$this->environmentConfig = $this->config['environments'][$this->environment];

		if($this->environmentConfig['localDeploy']) {
			$this->localDeploy();
		}
		else {
			$this->remoteDeploy();
		}
	}
}