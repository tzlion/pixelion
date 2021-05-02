<?php
/**
 * Pixelion - a MediaWiki skin
 * Copyright © taizou 2016
 * Based on Monobook © 20XX Gabriel Wicke et al
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

$wgExtensionCredits['skin'][] = array (
    'path' 		=> __FILE__,
    'name' 		=> 'Pixelion',
    'namemsg' 	=> 'skinname-pixelion',
    'descriptionmsg' => 'pixelion-desc',
    'url' 		=> 'https://github.com/tzlion/pixelion',
    'author' 	=> array( 'a lion' ),
    'version'   => '0.80',
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
