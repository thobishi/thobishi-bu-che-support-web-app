<?php

class CreateTask extends Shell {
	private $project = null;
	private $config = null;
	private $projectPath = '/usr/local/bin/projects/';
	private $defaultRepository = 'git@redmine:';
	private $currentConfig = null;

	private function doConfigFiles($data) {
		$configFiles = !empty($data) ? $data : array();

		do {
			$this->nl(2);
			$this->hr();
			$this->out('Config files');
			$this->hr();
			foreach($configFiles as $key => $files) {
				$this->out(($key+1) . '. ' . $files[0] . ' -> ' . $files[1]);
			}
			$this->nl();
			$filename = $this->in('Select a item to remove, or enter a new source filename. blank to stop');
			if(empty($filename)) {
				break;
			}
			elseif(is_numeric($filename)) {
				unset($configFiles[$filename-1]);
			}
			else {
				$destFile = $this->in('Enter the destination filename.', false);
				$configFiles[] = array($filename, $destFile);
			}
		} while(true);

		return $configFiles;
	}

	private function addOptions($data, $prompt = 'Enter the paths/files to exclude.', $afterPrompt = 'Excluding: ') {
		$excludes = !empty($data) ? $data : array();

		do {
			$this->nl(2);
			$this->hr();
			$this->out($prompt . ' Blank line when finished.');
			$this->hr();
			foreach($excludes as $key => $exclude) {
				$this->out(($key+1) . '. ' . $exclude);
			}
			$this->nl();
			$exclude = $this->in('Select a item to remove, or enter a new item. blank to stop');
			if(empty($exclude)) {
				break;
			}
			elseif(is_numeric($exclude)) {
				unset($excludes[$exclude-1]);
			}
			else {
				$excludes[] = $exclude;
			}
		} while(true);

		$this->hr();
		$this->out($afterPrompt . implode(', ', $excludes));
		return $excludes;
	}

	private function dbInputs($defaults) {
		$dbconfig = array();

		$dbconfig['database'] = $this->in('What is the name of the database?', false, null, isset($defaults['database']) ? $defaults['database'] : null);
		$dbconfig['username'] = $this->in('What is the database username?', false, null, isset($defaults['username']) ? $defaults['username'] : null);
		$dbconfig['password'] = $this->in('What is the database password?', false, null, isset($defaults['password']) ? $defaults['password'] : null);

		return $dbconfig;
	}

	private function doDatabase($defaults) {
		$dbconfig = array();

		$customServer = $this->in('Is the database on a different server than the project files?', array('y', 'n'), isset($defaults['server']['name']) ? 'y' : 'n');
		if($customServer == 'y') {
				$dbconfig['server']['name'] = $this->in('What server is the database on?', false);
				$dbconfig['server']['username'] = $this->in('What user will be used to connect to the database server?', false);
		}

		$different = $this->in('Are the database details different between the source and destination databases?', array('y', 'n'), isset($defaults['source']) ? 'y' : 'n');
		if($different == 'y') {
			$this->hr();
			$this->out('Source database:');
			$dbconfig['source'] = $this->dbInputs(!empty($defaults['source']) ? $defaults['source'] : array());

			$this->hr();
			$this->out('Destination database:');
			$dbconfig['destination'] = $this->dbInputs(!empty($defaults['destination']) ? $defaults['destination'] : array());
		}
		else {
			$dbconfig += $this->dbInputs($defaults);
		}

		$tableType = $this->in('How should the tables be exported? [A]ll tables, [I]gnore tables, I[n]clude tables',
			array('a', 'i', 'n'),
			isset($defaults['ignore']) ? 'i' : (isset($defaults['include']) ? 'n' : 'a')
		);
		if($tableType == 'i') {
			$dbconfig['ignore'] = $this->addOptions(
				isset($defaults['ignore']) ? $defaults['ignore'] : array(),
				'Enter the tables to ignore.',
				'Ignoring: '
			);
		}
		elseif($tableType == 'n') {
			$dbconfig['include'] = $this->addOptions(
				isset($defaults['include']) ? $defaults['include'] : array(),
				'Enter the tables to include.',
				'Including: '
			);
		}

		return $dbconfig;
	}

	private function doBranch($defaults) {
		if(is_array($defaults)) {
			$defaultValue = 'p';
		}
		elseif($defaults === false) {
			$defaultValue = 'a';
		}
		else {
			$defaultValue = 's';
		}
		$this->hr();
		$this->out('How do you want to select the branch to deploy:');
		$this->out("\t[S]pecify a branch");
		$this->out("\tSelect a branch starting with a [p]refix");
		$this->out("\t[A]ny branch");	
		$branchType = strtolower($this->in('>', array('s', 'p', 'a'), $defaultValue));
	
		switch($branchType) {
			case 's':
				return $this->in('What branch should be deployed?', null, $defaults);
				break;
			case 'p':
				$default = isset($defaults[0]) ? $defaults[0] : false;
				$branch = array(
					'type' => 'prefix',
					$this->in('What prefix would the branch name have (e.g. release/)?', null, $default)
				);
				
				if($this->currentConfig['type'] == 'local') {
					if($this->in('Do you wish to append the branch name (without the prefix) to the path?', array('y', 'n')) == 'y') {
						$branch['append'] = true;
					}
				}
				
				return $branch;
				break;
			case 'a':
				return false;
				break;
		}
	}

	private function configEnvironment($local, $environment, $projectIdentifier) {
		$environmentDefaults = array(
			'local' => array(
				'branch' => 'dev',
				'repository' => $this->defaultRepository . $projectIdentifier . '.git',
				'location' => null,
				'database' => array(),
				'config' => array(),
				'commands' => array(),
			),
			'remote' => array(
				'branch' => 'master',
				'location' => '/var/www/sites/' . $projectIdentifier,
				'repository' => $this->defaultRepository . $projectIdentifier . '.git',
				'server' => null,
				'user' => null,
				'database' => array(),
				'config' => array(),
				'commands' => array(),
			)
		);

		$type = $local ? 'local' : 'remote';

		$defaults = isset($this->config['environments'][$environment]) ? array_merge($environmentDefaults[$type], $this->config['environments'][$environment]) : $environmentDefaults[$type];

		$this->currentConfig = array();

		$this->Dispatch->clear();
		$this->out("Configuring the $environment environment, a ".($local ? 'local' : 'remote')." environment for $projectIdentifier.");
		
		$this->hr();
		
		$this->currentConfig['type'] = $type;
		$this->currentConfig['repository'] = $this->in('What repository should be used?', null, $defaults['repository']);
		$this->currentConfig['branch'] = $this->doBranch($defaults['branch']);
		if($local) {
			$this->currentConfig['location'] = $this->in('Where should the project be cloned to by default? Leave blank to use the users directory (/var/www/html/usr)', null, $defaults['location']);
		}
		else {
			$this->currentConfig['server'] = $this->in('What server will the project be deployed too?', false, null, $defaults['server']);
			$this->currentConfig['user'] = $this->in('What user will be used to connect to the remote server? Leave blank to use the user who is running the script.', null, $defaults['user']);
			$this->currentConfig['location'] = $this->in('What location on the remote server will the project be deployed too?', null, $defaults['location']);

			if($this->in('Are there any files/paths that should be excluded when deploying the project?', array('y', 'n'), isset($defaults['excludes']) ? 'y' : 'n') == 'y') {
				$this->currentConfig['excludes'] = $this->addOptions(isset($defaults['excludes']) ? $defaults['excludes'] : array());
			}

			if($this->in('Does this project have an associated database that needs to be updated in this environment?', array('y', 'n'),!empty($defaults['database']) ? 'y' : 'n') == 'y') {
				$this->currentConfig['database'] = $this->doDatabase($defaults['database']);
			}
		}

		if($this->in('Does this environment have an special config files ?', array('y', 'n'), !empty($defaults['config']) ? 'y' : 'n') == 'y') {
			$this->currentConfig['config'] = $this->doConfigFiles($defaults['config']);
		}

		if($this->in('Are there any commands that need to be run after deployment?', array('y', 'n'), !empty($defaults['config']) ? 'y' : 'n') == 'y') {
			$this->currentConfig['commands'] = $this->addOptions($defaults['commands'], 'Enter the commands to run after deployment.', 'Commands to run:');
		}

		$this->hr();
		return $this->currentConfig;
	}

	private function getFilePermissions() {
		$filePermissions = isset($this->config['filePermissions']) ? $this->config['filePermissions'] : array();

		do {
			$this->hr();
			$this->out('File names/directories that need specific permissions. The permissions will be set recursively.');
			$this->hr();
			$count = 1;
			$fileNames = array_keys($filePermissions);
			foreach($fileNames as $key => $filename) {
				$this->out(($key+1) . '. ' . $filename . ' - ' . $filePermissions[$filename]);
			}
			$filename = $this->in('Select a file to remove, or enter a new file. blank to stop');

			if(empty($filename)) {
				break;
			}
			elseif(is_numeric($filename)) {
				unset($filePermissions[$fileNames[$filename-1]]);
			}
			else {
				$mode = $this->in('Mode/Permission', false);

				$filePermissions[$filename] = $mode;
			}

		} while(true);

		return $filePermissions;
	}

	private function formatConfigArray($config, $keys = array()) {
		$configString = '';

		foreach($config as $key => $data) {
			if(is_array($data)) {
				$keys[] = $key;
				$configString .= $this->formatConfigArray($data, $keys);
				array_pop($keys);
			}
			else {
				if(count($keys) > 0) {
					$configString .= '$config';
					foreach($keys as $thisKey) {
						$configString .= is_numeric($thisKey) ? '['.$thisKey.']' : '[\''.$thisKey.'\']';
					}
				}
				else {
					$configString .= '$config';
				}
				$keystring = is_numeric($key) ? '['.$key.']' : '[\''.$key.'\']';
				$configString .= $keystring . ' = ' . var_export($data, true) . ";\n";
			}
		}

		return $configString;
	}

	private function storeConfig() {
		$configString = "<?php \n\$config = array();\n" . $this->formatConfigArray($this->config);

		$this->Dispatch->clear();

		$this->out('Writing the following config data:');
		$this->hr();
		$this->out($configString);
		$this->hr();

		$response = strtolower($this->in("Continue?", array('y', 'n'), 'y'));
		if($response == 'n') {
			return $this->_stop();
		}
		file_put_contents($this->projectPath . $this->config['identifier'], $configString);
		$this->out('Config saved.');
	}

	private function environments() {
		do {
			$environment = '';
			$this->Dispatch->clear();
			$this->out('Environments for ' . $this->project);
			$this->hr();
			if(!empty($this->config['environments'])) {
				$environments = array_keys($this->config['environments']);
				foreach($environments as $key => $environmentName) {
					$this->out(($key+1) . '. ' . $environmentName);
				}

				$environment = $this->in('Select an environment to edit, a name for a new environment, or blank to stop');

				if(empty($environment)) {
					return;
				}
				elseif(is_numeric($environment)) {
					$environment = $environments[$environment-1];
				}
			}
			else {
				$this->config['environments'] = array();
				$environment = $this->in('Enter the name for the new environment.', false);
			}

			if(empty($this->config['environments'][$environment])) {
				$local = $this->in('Is this a [l]ocal or [r]emote environment', array('l', 'r', 'local', 'remote'), 'local');
				if($local === 'l' || $local === 'local') {
					$local = true;
				}
				else {
					$local = false;
				}
			}
			else {
				$local = $this->config['environments'][$environment]['localDeploy'];
			}

			$this->config['environments'][$environment] = $this->configEnvironment($local, $environment, $this->project);
			$this->config['environments'][$environment]['localDeploy'] = $local;
			$this->config['environments'][$environment]['shallow'] = !$local;
		} while(true);
	}

	public function execute() {
		$this->config = array();
		$this->out('Create a new deployment script for a project.');
		$this->hr();
		$this->out('This wizard will guide you through the process of creating a new project deployment script.');
		$this->hr();

		$this->config['identifier'] = $this->in('What is the project identifier for this project? This can be optained from redmine.', false);
		$this->project = $this->config['identifier'];

		$path = $this->projectPath . $this->config['identifier'];
		if(is_readable($path)) {
			$load = $this->in('A configuration file already exists for this project. Do you wish to [e]dit the file, or [o]verwrite it?', array('e', 'o', 'q'), 'e');
			if($load == 'e') {
				$this->loadProject();
			}
			elseif($load == 'q') {
				$this->_stop();
			}
		}

		$this->environments();

		$useDefaults = strtolower($this->in("Are there any directories that need to be created when deploying?", array('y', 'n'), isset($this->config['filePermissions']) ? 'y' : 'n'));
		if($useDefaults === 'y') {
			$this->config['creates'] = $this->addOptions(
				isset($this->config['creates']) ? $this->config['creates'] : array(),
				'Enter the directories to create. The directories will be created recursivly.',
				'Creating: '
			);
		}
		elseif(isset($this->config['creates'])) {
			unset($this->config['creates']);
		}

		$useDefaults = strtolower($this->in("Are there any special file/directory permissions that need to be set?", array('y', 'n'), isset($this->config['filePermissions']) ? 'y' : 'n'));
		if($useDefaults === 'y') {
			$this->config['filePermissions'] = $this->getFilePermissions();
		}
		elseif(isset($this->config['filePermissions'])) {
			unset($this->config['filePermissions']);
		}

		$this->config['identifier'] = $this->project;
		$this->storeConfig();
	}

	public function in($prompt, $allowEmpty = true, $options = null, $default = null) {
		if(is_array($allowEmpty) || $allowEmpty === null) {
			$default = $options;
			$options = $allowEmpty;
			$allowEmpty = true;
		}

		do {
			$result = trim(parent::in($prompt, $options, $default));
		} while(empty($result) && $allowEmpty === false);

		return $result;
	}

	private function loadProject() {
		$path = $this->projectPath . $this->project;
		if(!is_readable($path)) {
			$this->out("Invalid project config [$path]. Please ensure the project config exists, is readable and is a valid config file");
			$this->_stop();
		}
		include($path);

		if($config === false) {
			$this->out("Invalid project config [$path]. Please ensure the project config exists, is readable and is a valid php file with a \$config variable.");
			$this->_stop();
		}

		$this->config = $config;
	}
}
