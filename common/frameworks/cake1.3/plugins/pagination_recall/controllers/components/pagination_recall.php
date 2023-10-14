<?php
/*
 * Pagination Recall CakePHP Component
 * Copyright (c) 2008 Matt Curry
 * www.PseudoCoder.com
 *
 * @author      mattc <matt@pseudocoder.com>
 * @version     1.0
 * @license     MIT
 *
 */

class PaginationRecallComponent extends Object {
  public $components = array('Session');
  private $Controller = null;
  private $options = array(
	  'vars' => array('page', 'sort', 'direction')
  );

  public function initialize(&$controller, $settings = array()) {  
	$this->Controller = & $controller;

	$this->options = Set::merge($this->options, $settings);
  }
  
  public function recallParams($redirect = false) {
	//recall previous options
	if ($this->Session->check("Pagination.{$this->Controller->modelClass}.options")) {
		$options = $this->Session->read("Pagination.{$this->Controller->modelClass}.options");
		
		$this->Controller->passedArgs = array_merge($this->Controller->passedArgs, $options);
		
		if($redirect == true) {
			$this->Session->delete("Pagination.{$this->Controller->modelClass}.options");
			$this->Controller->redirect($this->Controller->passedArgs);
		}
	}	  
  }
  
  public function saveParams() {
	extract($this->options);
	  
    $options = array_merge($this->Controller->params,
                           $this->Controller->params['url'],
                           $this->Controller->passedArgs
                          );

    $keys = array_keys($options);
    $count = count($keys);
    
    for ($i = 0; $i < $count; $i++) {
      if (!in_array($keys[$i], $vars) || is_numeric($keys[$i])) {
        unset($options[$keys[$i]]);
      }
    }
    
    //save the options into the session
    $this->Session->write("Pagination.{$this->Controller->modelClass}.options", $options);
  }
}