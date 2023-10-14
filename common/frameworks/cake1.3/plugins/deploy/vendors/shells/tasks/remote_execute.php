<?php
class RemoteExecuteTask extends Shell {
	private $username = null;
	private $server = null;

	public function setServerConfig($username, $server) {
		$this->username = $username;
		$this->server = $server;
	}

	public function runCommand($command, &$rOutput = array()){
		$command = "ssh {$this->username}@{$this->server} \"$command\"";

		$this->out('Running: ' . $command);
		$output = array();
		echo exec($command, $output, $return);
		$rOutput = $output;
		
		$this->out("\n");
		return $return;
	}

	private function runMysqlCommand($command, $uname, $passwd) {
		$command = "mysql -u $uname -p$passwd -e '$command'";

		$this->runCommand($command, $output);

		return $output;
	}

	public function checkDbStatus($db) {
		$command = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = \\\"{$db['database']}\\\";";

		$output = $this->runMysqlCommand($command, $db['username'], $db['password']);
		if(!empty($output[1]) && $output[1] == $db['database']) {
			return true;
		}
		else {
			throw new Exception("The remote database ({$db['database']}) does not exists, or is not readable. Please ensure the database exists and that the database user ({$db['username']}) has permissions to view the database.");
		}
	}

	public function applyDB($dbDump, $db) {
		$command = "mysql -u {$db['username']} -p{$db['password']} -D {$db['database']} < $dbDump";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could make update remote database with $command.");
		}
	}

	public function backupDB($db) {
		$filename = '/tmp/' . $db['database'] . '_backup_' . date('Y-m-d_H:i:s') . '.sql';
		$command = "mysqldump -u {$db['username']} -p{$db['password']} {$db['database']} > $filename";

		if($this->runCommand($command) !== 0) {
			throw new Exception("Could make a sql dump with $command.");
		}

		return $filename;
	}
}