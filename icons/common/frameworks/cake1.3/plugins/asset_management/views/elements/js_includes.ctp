<?php
    if (!isset($this->Asset)) {
        return;
    }

    $inclusionRules = Configure::read('JsIncludes');
    $settings = array(
        'type' => 'js',
        'packaging' => Configure::read('Assets.packaging'),
		'minify' => Configure::read('Assets.minify'),
		'cacheBuster' => Configure::read() > 0,
		'js' => array(
			'minification' => array(
				'method' => 'jsmin'
			)
		)
    );

    // IE sometimes has problems with minifications.
    // Better turn minification off for IE.
    $isIe = isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false;
    if ($isIe) {
        $settings['minify'] = false;
    }
    $this->Asset->includeFiles($inclusionRules, $settings);