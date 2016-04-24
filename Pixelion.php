<?php

$wgExtensionCredits['skin'][] = array (
    'path' 		=> __FILE__,
    'name' 		=> 'Pixelion',
    'namemsg' 	=> 'skinname-pixelion',
    'descriptionmsg' => 'pixelion-desc',
    'url' 		=> 'http://lion.li',
    'author' 	=> array( 'a lion' ),
    'version'   => '0.7',
    'license-name' => 'GPLv2+',
);

$wgAutoloadClasses['SkinPixelion'] 		= __DIR__ . '/SkinPixelion.php';
$wgAutoloadClasses['PixelionTemplate'] 	= __DIR__ . '/PixelionTemplate.php';
$wgMessagesDirs['Pixelion'] 			= __DIR__ . '/i18n';

$wgValidSkinNames['pixelion'] = 'Pixelion';

$wgResourceModules['skins.pixelion.harrystyles'] = array(
    'styles' => array(
        'lion.css' => array( /* 'media' => 'screen'*/ ),
        // ^ specifying media in here seems to break nested media queries in IE
        // 'printlion.css' => array( 'media' => 'print' ), // maybe later
    ),
    'remoteSkinPath' 	=> 'Pixelion',
    'localBasePath' 	=> __DIR__,
);