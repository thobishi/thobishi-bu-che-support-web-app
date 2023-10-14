<?php

class ExecuteTask extends Shell {
	private $location = '';

	private function delete() {
		$dir = $this->location;
		if(is_dir($dir)) {
			$objects = scandir($dir);
			foreach($objects as $object) {
				if($object != "." && $object != "..") {
					if(filetype($dir . "/" . $object) == "dir")
						rrmdir($dir . "/" . $object); else
						unlink($dir . "/" . $object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	public function runCommand($command, $location = true, $redirectOutput = false, &$rOutput = false, $showCommand = true) {
		if($location) {
			$command = "cd {$this->location} && " . $command;
		}
		if($showCommand) {
			$this->out('Running: ' . $command);
		}
		
		if($redirectOutput === true) {
			$command .= ' 1>&2';
		}
		
		$output = array();
		exec($command, $output, $return);
		
		if(is_array($rOutput)) {
			$rOutput = $output;
		}
		
		if($showCommand) {
			$this->out("\n");
		}
		return $return;
	}

	public function setLocation($location) {
		$this->location = $location;
		if(substr($this->location, -1, 1) !== '/') {
			$this->location .= '/';
		}
	}

	public function gitClone($repository, $branch, $shallow = false) {
		$command = "git clone --recursive --branch $branch";
		if($shallow) {
			$command .= " --depth 1";
		}
		$command .= " {$repository} {$this->location}";

		if(file_exists($this->location)) {
			throw new OutOfBoundsException("{$this->location} already exists, cannot deploy.");
		}

		if($this->runCommand($command, false) !== 0) {
			throw new Exception("Could not clone repository with $command.");
		}
	}

	public function gitCheckoutRemote($branch) {
		$command = "git checkout -b $branch origin/$branch";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not checkout remote branch with $command.");
		}
	}

	public function gitCheckout($branch) {
		$command = "git checkout $branch";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not checkout branch with $command");
		}
	}

	public function gitPull($branch) {
		$command = "git pull";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not pull latest commits with $command");
		}
	}

	public function gitFetch($remote = 'origin') {
		$command = "git fetch $remote";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not fetch latest commits with $command");
		}
	}

	public function gitSubmodules() {
		$command = "git submodule update --init --recursive";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not update submodules with $command");
		}
	}

	public function gitAddRemote($remoteName, $remoteUrl) {
		$command = "git remote add $remoteName $remoteUrl";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could not add remote with $command");
		}
	}

	public function gitRemoteName($remoteUrl) {
		$command = "git remote -v";

		$output = array();
		if($this->runCommand($command, true, false, $output, false) !== 0) {
			throw new Exception("Could not read remotes with $command");
		}

		$remoteName = false;
		foreach($output as $remote) {
			if(strpos($remote, $remoteUrl) !== false) {
				$remoteName = trim(preg_replace('/(\(fetch\)|\(push\))/', '', str_replace($remoteUrl, '', $remote)));
				break;
			}
		}

		return $remoteName;
	}

	public function gitExists($config) {
		$command = "git ls-remote " . $config['repository'];

		if($this->runCommand($command) !== 0) {
			throw new Exception("No valid git repository found at " . $config['repository']);
		}

		return true;
	}

	public function gitBranches($config, $prefix = false) {
		$command = "git ls-remote --heads " . $config['repository'];
		
		$output = array();
		if($this->runCommand($command, false, false, $output, false) !== 0) {
			throw new Exception("Could not read branch list with $command");
		}
		
		$branches = array();
		foreach($output as $branch) {
			$branchName = preg_replace('/[0-9a-z]{40}[\s]+[a-z]+\/[a-z]+\/([\w\/]+)/', '\1', $branch);
			
			if($prefix !== false && strpos($branchName, $prefix) === false) {
				continue;
			}
			
			$branches[] = $branchName;
		}

		return $branches;
	}

	public function copyFile($source, $destination) {
		return copy($this->location . $source, $this->location . $destination);
	}

	public function rsync($excludes, $source, $destination) {
		if(!empty($excludes)) {
			$excludes = '--exclude /' . implode(' --exclude /', $excludes);
		}
		else {
			$excludes = '';
		}

		$command = "rsync -azvrPe ssh $excludes --exclude \".git\" $source $destination";

		if($this->runCommand($command, false, true) !== 0) {
			throw new Exception("Could not upload files with $command.");
		}
	}

	public function chmod($path, $permission) {
		$command = "chmod $permission -R $path";

		$this->runCommand($command);
	}

	public function mkdir($dir) {
		$command = "mkdir -p $dir";

		if(!file_exists($this->location . $dir)) {
			$this->runCommand($command);
		}
	}

	public function rm($path) {
		$command = "rm $path -rf";

		$this->runCommand($command, false);
	}

	public function dumpDB($db, $options = array()) {
		if(!empty($options['ignore'])) {
			$options = "--ignore-table={$db['database']}" . implode(" --ignore-table={$db['database']}", $ignoreTables);
		}
		elseif(!empty($options['include'])) {
			$options = implode(' ', $options['include']);
		}
		else {
			$options = '';
		}

		$filename = '/tmp/' .sha1(time() . rand(0,time())) . '.sql';

		$command = "mysqldump -u {$db['username']} -p{$db['password']} {$db['database']} $options > $filename";

		$output = true;
		if($this->runCommand($command, false) !== 0) {
			throw new Exception("Could make a sql dump with $command.");
		}

		return $filename;
	}
}