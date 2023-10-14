<?php

class DeployTask extends Shell {
	public $tasks = array('Execute', 'RemoteExecute');
	private $config;

	public function setConfig($config) {
		$this->config = $config;
	}

	public function cloneRepo() {
		$this->Execute->setLocation($this->config['cloneDir']);
		$this->out('Cloning repository...');
		try {
			$this->Execute->gitClone($this->config['repository'], $this->config['branch'], $this->config['shallow']);
		}
		catch(OutOfBoundsException $e) {
			$this->out('Source location already exists. Attempting to update.');
			$remoteName = $this->Execute->gitRemoteName($this->config['repository']);
			if($remoteName === false) {
				$remoteName = sha1($this->config['repository']);
				$this->Execute->gitAddRemote($remoteName, $this->config['repository']);
			}

			$this->Execute->gitFetch($remoteName);
			$this->Execute->gitCheckout($remoteName . '/' . $this->config['branch']);
			$this->Execute->gitSubmodules();
		}
	}

	public function copyConfigFiles() {
		$this->Execute->setLocation($this->config['cloneDir']);

		$this->out("Copying config files...");
		foreach($this->config['config'] as $files) {
			$this->out("\t{$files[0]} to {$files[1]}");
			$this->Execute->copyFile($files[0], $files[1]);
		}
	}

	public function upload() {
		$this->out('Uploading files...');

		if(empty($this->config['excludes'])) {
			$this->config['excludes'] = array();
		}
		
		if(substr($this->config['cloneDir'], -1) !== '/') {
			$this->config['cloneDir'] .= '/';
		}

		if(substr($this->config['deployTo'], -1) !== '/') {
			$this->config['deployTo'] .= '/';
		}

		$this->Execute->rsync($this->config['excludes'], $this->config['cloneDir'], $this->config['deployTo']);
	}

	public function setPermissions($permissions) {
		$this->Execute->setLocation($this->config['cloneDir']);

		$this->out('Setting permissions...');
		foreach($permissions as $path => $permission) {
			$this->out("\t$path to $permission");
			$this->Execute->chmod($path, $permission);
		}
	}

	public function createDirectories($directories) {
		$this->Execute->setLocation($this->config['cloneDir']);

		$this->out('Creating directories...');
		foreach($directories as $directory) {
			$this->out("\t$directory");
			$this->Execute->mkdir($directory);
		}
	}

	public function deployDatabase() {
		$this->RemoteExecute->setServerConfig($this->config['user'], $this->config['server']);

		$dest = $this->config['database']['destination'];
		$source = $this->config['database']['source'];

		$this->out('Checking that the database exists on the remote server...');
		if($this->RemoteExecute->checkDbStatus($dest)) {
			$this->out('Creating a local database dump...');
			$filename = $this->Execute->dumpDB($source, $this->config['database']);

			$this->out('Uploading database dump to remote server...');
			$this->Execute->rsync(array(), $filename, $this->config['user'] .'@'. $this->config['server'].':/tmp/.');

			$this->out('Creating a backup of current remote database...');
			$dbBackup = $this->RemoteExecute->backupDB($dest);
			$this->out('Backup stored at: ' . $dbBackup . ' on remote server.');

			$this->out('Applying database dump to remote database server...');
			$this->RemoteExecute->applyDB($filename, $dest);

			return $filename;
		}
	}
	
	public function runCommands() {
		foreach($this->config['commands'] as $command) {
			if($this->config['localDeploy'] === false) {
				$this->RemoteExecute->setServerConfig($this->config['user'], $this->config['server']);
				$location = $this->config['location'];
				$this->RemoteExecute->runCommand("cd {$location} &&" . $command);
			}
			else {
				$this->Execute->runCommand($command);
			}
		}
	}
}