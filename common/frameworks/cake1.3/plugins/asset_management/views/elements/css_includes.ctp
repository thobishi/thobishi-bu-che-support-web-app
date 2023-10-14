<?php
    if (!isset($this->Asset)) {
        return;
    }

	if(empty($settings)) { 
		$settings = array();
	}
	
    $inclusionRules = Configure::read('CssIncludes');
    $defaults = array(
        'type' => 'css',
        'packaging' => Configure::read('Assets.packaging'),
		'minify' => Configure::read('Assets.minify'),
		'cacheBuster' => Configure::read() > 0 ? true : false,
		'css' => array(
			'preprocessor' => false
		)
    );
	
	$settings = Set::merge($settings, $defaults);
	
    $this->Asset->includeFiles($inclusionRules, $settings);