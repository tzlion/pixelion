<?php

$wgExtensionCredits['skin'][] = array (
    'path' 		=> __FILE__,
    'name' 		=> 'LionBook',
    'namemsg' 	=> 'skinname-lionbook',
    'descriptionmsg' => 'lionbook-desc',
    'url' 		=> 'http://lion.li',
    'author' 	=> array( 'a lion' ),
    // 'license_name' => 'idk yet'
);

$wgAutoloadClasses['SkinLionBook'] 		= __DIR__ . '/SkinLionBook.php';
$wgAutoloadClasses['LionBookTemplate'] 	= __DIR__ . '/LionBookTemplate.php';
$wgMessagesDirs['LionBook'] 			= __DIR__ . 'i18n';

$wgValidSkinNames['lionbook'] = 'LionBook';

$wgResourceModules['skins.lionbook.harrystyles'] = array(
    'styles' => array(
        'lion.css' => array( 'media' => 'screen' ),
        // 'printlion.css' => array( 'media' => 'print' ), // maybe later
    ),
    'remoteSkinPath' 	=> 'LionBook',
    'localBasePath' 	=> __DIR__,
);