<?php

class SkinPixelion extends SkinTemplate
{
    public $skinname  = "pixelion";
    public $stylename = "Pixelion";
    public $template  = "PixelionTemplate";

    public function setupSkinUserCss( OutputPage $output )
    {
        parent::setupSkinUserCss( $output );

        $output->addModuleStyles(
            array (
                // Default MW stuff, maybe bring some of it back
                // "mediawiki.skinning.interface",
                // "mediawiki.skinning.content.externallinks",
                "skins.pixelion.harrystyles",
            )
        );
    }
}