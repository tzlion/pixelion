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
        'lion.css' => array( 'media' => 'screen' ),
        // 'printlion.css' => array( 'media' => 'print' ), // maybe later
    ),
    'remoteSkinPath' 	=> 'Pixelion',
    'localBasePath' 	=> __DIR__,
);