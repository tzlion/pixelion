<?php

$wgExtensionCredits['skin'][] = array (
    'path' 		=> __FILE__,
    'name' 		=> 'Pixelion',
    'namemsg' 	=> 'skinname-pixelion',
    'descriptionmsg' => 'pixelion-desc',
    'url' 		=> 'http://lion.li',
    'author' 	=> array( 'a lion' ),
    // 'license_name' => 'idk yet'
);

$wgAutoloadClasses['SkinPixelion'] 		= __DIR__ . '/SkinPixelion.php';
$wgAutoloadClasses['PixelionTemplate'] 	= __DIR__ . '/PixelionTemplate.php';
$wgMessagesDirs['Pixelion'] 			= __DIR__ . 'i18n';

$wgValidSkinNames['pixelion'] = 'Pixelion';

$wgResourceModules['skins.pixelion.harrystyles'] = array(
    'styles' => array(
        'lion.css' => array( /* 'media' => 'screen'*/ ),
        // ^ specifying media in here seems to break nested media queries in IE
        // big fat sighs @ both IE and mediawiki for this one
        // 'printlion.css' => array( 'media' => 'print' ), // maybe later
    ),
    'remoteSkinPath' 	=> 'Pixelion',
    'localBasePath' 	=> __DIR__,
);