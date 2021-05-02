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

class SkinPixelion extends SkinTemplate
{
    public $skinname  = "pixelion";
    public $stylename = "Pixelion";
    public $template  = "PixelionTemplate";

    public function setupSkinUserCss( OutputPage $output )
    {
        $output->addModuleStyles(
            array (
                // Default MW stuff
                // "mediawiki.skinning.interface",
                // "mediawiki.skinning.content.externallinks",
                "skins.pixelion.harrystyles",
            )
        );

        $output->addModules( 'jquery.mw-jump' );

        parent::setupSkinUserCss( $output );
    }
}
