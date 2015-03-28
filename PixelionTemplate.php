<?php

class PixelionTemplate extends BaseTemplate
{

    /**
     * Template filter callback for Pixelion skin.
     * Takes an associative array of data set from a SkinTemplate-based
     * class, and a wrapper for MediaWiki's localization database, and
     * outputs a formatted page.
     *
     * @access private
     */
    function execute() {

        $this->remainingFooterLinks = $this->getFooterLinks( "flat" );

        $this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();

        $this->html( 'headelement' );
        ?>
        <link href='http://fonts.googleapis.com/css?family=Jockey+One|Archivo+Black|Archivo+Narrow:400,900italic,900,700,700italic,500italic,500,400italic,300italic,300,100italic,100|Skranji:400,700|Bowlby+One|Sarina|Emblema+One|Ranga:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        <div id="globalWrapper">
            <div id="column-one"<?php $this->html( 'userlangattributes' ) ?>>
                <h2><?php $this->msg( 'navigation-heading' ) ?></h2>
                <div id="topbar">
                    <?php $this->portletPersonal() ?>
                    <?php $this->portletsCustomSidebar(); ?>
                </div>
                <div id="headbar">
                    <?php $this->portletLogo() ?>
                    <?php $this->portletSearch();  ?>
                </div>
                <?php $this->portletContentActions(); ?>
            </div><!-- end of the left (by default at least) column -->

            <div id="column-content">
                <? $this->portletToolbox(); ?>
                <div id="content" class="mw-body" role="main">
                    <a id="top"></a>
                    <?php if ( $this->data('sitenotice') ) { ?>
                        <div id="siteNotice">
                            <?php $this->html( 'sitenotice' ) ?>
                        </div>
                    <?php } ?>

                    <h1 id="firstHeading" class="firstHeading" lang="<?php $this->text( 'pageLanguage' ); ?>">
                        <span dir="auto"><?php $this->html( 'title' ) ?></span>
                    </h1>

                    <div id="bodyContent" class="mw-body-content">
                        <div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
                        <div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>>
                            <?php $this->html( 'subtitle' )?>
                        </div>
                        <?php if ( $this->data('undelete') ) { ?>
                            <div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
                        <?php } ?>
                        <?php if ( $this->data('newtalk') ) { ?>
                            <div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
                        <?php } ?>
                        <div id="jump-to-nav" class="mw-jump">
                            <?php $this->msg( 'jumpto' )?>
                            <a href="#column-one"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' )?><a href="#searchInput"><?php $this->msg( 'jumptosearch' ) ?></a>
                        </div>

                        <!-- start content -->
                        <?php $this->html( 'bodytext' ) ?>
                        <?php
                        if ( $this->data('catlinks') ) {
                            $this->html( 'catlinks' );
                        }
                        ?>
                        <!-- end content -->
                        <?php
                        if ( $this->data('dataAfterContent') ) {
                            $this->html( 'dataAfterContent' );
                        }
                        ?>

                    </div>
                </div>
                <?php $this->portletLanguages(); // todo: deal ?>
                <? $this->popFooterLink( "lastmod" ) ?>
                <div class="sigh"></div>
            </div>
            <div class="sigh"></div>
            <?php $this->renderFooter() ?>
        </div>
        <?php
        $this->printTrail();
        echo Html::closeElement( 'body' );
        echo Html::closeElement( 'html' );

    } // end of execute() method

    /*************************************************************************************************/


    // ************ EVERYTHING AFTER THIS POINT SHOULD BE LICENSING SAFE ****************

    // <editor-fold desc="Portlets">

    // *****************************************************************************************************************
    //  PORTLETS
    // *****************************************************************************************************************

    /**
     * Render the wiki's logo or a text only link to the mainpage if none is defined
     */
    protected function portletLogo()
    {
        $linkAttrs = Linker::tooltipAndAccesskeyAttribs( 'p-logo' );
        $linkAttrs["href"] = $this->data( 'nav_urls', 'mainpage', 'href' );

        $content = Html::openElement( "a", $linkAttrs );
        if ( $this->data( "logopath" ) ) {
            $content .= '<img src="' . $this->data('logopath') . '" alt="' . $this->data('sitename') . '">';
        } else {
            $content .= $this->data('sitename');
        }
        $content .= Html::closeElement( "a" );

        $this->renderPortlet( "p-logo", "banner", null, [ "content" => $content, "no-tags" => true ] );
    }

    /**
     * Render the user's personal tools
     */
    protected function portletPersonal()
    {
        $content = $this->makeGenericList( $this->getPersonalTools(), $this->data( "userlangattributes" ) );

        $this->renderPortlet( "p-personal", "navigation", $this->getMsg( "personaltools" )->escaped(), $content );
    }

    /**
     * Render the content actions aka cactions aka usually tabs at the top of the page (page,discussion,edit..)
     */
    protected function portletContentActions()
    {
        $content = $this->makeGenericList( $this->data('content_actions') );
        $content .= $this->getPostPortletStuff( "cactions" );

        $header = $this->getMsg( "views" )->escaped();
        $this->renderPortlet( "p-cactions", "navigation", $header, $content );
    }

    /**
     * whack out a search box like
     */
    public function portletSearch()
    {
        if ( $this->data( 'sidebar', "SEARCH" ) === false ) {
            return;
        }

        ob_start();
        ?>
        <form id="searchform" action="<? $this->text( 'wgScript' ) ?>">
            <input type="hidden" name="title" value="<? $this->text( 'searchtitle' ) ?>"/>
            <?= $this->makeSearchInput( array( "id" => "searchInput" ) ) ?>
            <?= $this->makeSearchButton( "go", array( "id" => "searchGoButton", "class" => "searchButton" ) ) ?>
            <? if ( $this->config->get( 'UseTwoButtonsSearchForm' ) ) { ?>
                <?= $this->makeSearchButton( "fulltext", array( "id" => "mw-searchButton", "class" => "searchButton" ) ) ?>
            <? } else { ?>
                <a id="power-search-densetsu" href="<? $this->text( 'searchaction' ) ?>" rel="search"><? $this->msg( 'powersearch-legend' ) // The Legend of Power Search(tm) Coming Summer 1989 ?></a>
            <? } ?>
        </form>

        <?
        $content = ob_get_clean();
        $content .= $this->getPostPortletStuff( "search" );

        $header = '<label for="searchInput">' . $this->getMsg("personaltools")->escaped() . '</label>';
        $this->renderPortlet( "p-search", "search", $header, [ "attrs" => "id='searchBody'", "content" => $content ] );
    }

    /**
     * Render a bunch of random ass tools, most of which are related to the current page but some of which are not
     * (argh)
     */
    public function portletToolbox()
    {
        if ( $this->data( 'sidebar', "TOOLBOX" ) === false ) {
            return;
        }

        ob_start();
        wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
        $postHookContent = ob_get_clean();

        $content = '<a id="toolboxbutton" href="#" title="Toolbox">T</a>';
        $content .= $this->makeGenericList( $this->getToolbox(), "id='tools'", $postHookContent );
        $content .= $this->getPostPortletStuff( "tb" );

        $header = $this->getMsg( "toolbox" )->escaped();
        $this->renderPortlet( "p-tb", "navigation", $header, $content );
    }

    /**
     * Render alt languages if any are defined
     */
    public function portletLanguages()
    {
        if ( $this->data( 'sidebar', "LANGUAGES" ) === false || !$this->data('language_urls') ) {
            return;
        }

        $content = $this->makeGenericList( $this->data('language_urls') );
        $content .= $this->getPostPortletStuff( "lang" );

        $header = [ "content" => $this->getMsg( "otherlanguages" )->escaped(), "attrs" => $this->data( 'userlangattributes' ) ];
        $this->renderPortlet( "p-lang", "navigation", $header, $content );
    }

    /**
     * Render any custom "sidebar" sections as defined in MediaWiki:Sidebar
     */
    protected function portletsCustomSidebar()
    {
        foreach ( $this->data('sidebar') as $boxName => $content ) {

            // search, toolbox, languages = presets, being output elsewhere
            if ( $content === false || in_array( $boxName, [ "SEARCH", "TOOLBOX", "LANGUAGES" ] ) ) {
                continue;
            }

            if ( is_array( $content ) ) {
                $content = $this->makeGenericList( $content );
            }
            $content .= $this->getPostPortletStuff( $boxName );

            $nameMessage = wfMessage( $boxName );
            $header = htmlspecialchars( $nameMessage->exists() ? $nameMessage->text() : $boxName );

            $this->renderPortlet( Sanitizer::escapeId( "p-$boxName" ), "navigation", $header, $content, "generated-sidebar", Linker::titleAttrib( "p-$boxName" ) );

        }
    }

    /**
     * Render a portlet!
     *
     * @param string $id ID attribute
     * @param string $role Role attribute
     * @param string|array $header Header content. Can be a string of text/html or an array like this:
     *                             "content" => text/html content, "attrs" => additional attributes for the header tag
     * @param string|array $body Body content. Can be a string of html content or an array like this:
     *                           "content" => html content, "no-tags" => omits containing div if true,
     *                           "attrs" => additional attributes for the containing div
     * @param string|null $additionalClasses Any additional classes for the portlet's containing div
     * @param string|null $tooltip Something to go in the title tag
     */
    protected function renderPortlet( $id, $role, $header, $body, $additionalClasses = null, $tooltip = null )
    {
        if ( is_array( $body ) ) {
            $bodyContent = $body['content'];
            $showBodyTags = ( !isset( $body['no-tags'] ) || !$body['no-tags'] );
            $bodyAttrs = isset( $body['attrs'] ) ? $body['attrs'] : "";
        } else {
            $bodyContent = $body;
            $showBodyTags = true;
            $bodyAttrs = "";
        }

        if ( is_array( $header ) ) {
            $headerContent = $header['content'];
            $headerAttrs = isset( $header['attrs'] ) ? $header['attrs'] : "";
        } else {
            $headerContent = $header;
            $headerAttrs = "";
        }

        if ( $tooltip ) {
            $extraAttrs = "title = '$tooltip'";
        } else {
            $extraAttrs = "";
        }

        ?>
        <div class="portlet <?=$additionalClasses?>" id="<?=$id?>" role="<?=$role?>" <?=$extraAttrs?>>
            <? if ( $headerContent ) { ?>
                <h3 <?=$headerAttrs?>><?= $headerContent ?></h3>
            <? } ?>
            <? if ( $showBodyTags ) { ?>
                <div class="pBody" <?=$bodyAttrs?>>
                    <?=$bodyContent?>
                </div>
            <? } else { ?>
                <?=$bodyContent?>
            <? } ?>
        </div>
        <?
    }

    /**
     * Get anything hooked in after the given portlet
     *
     * @param $name
     * @return string
     */
    protected function getPostPortletStuff( $name )
    {
        ob_start();
        $this->renderAfterPortlet( $name );
        return ob_get_clean();
    }

    // </editor-fold>

    // <editor-fold desc="Footer">

    // *****************************************************************************************************************
    //  FOOTER
    // *****************************************************************************************************************

    /** @var array Should contain all the footer links that haven't been output yet */
    protected $remainingFooterLinks = array();

    /**
     * Output a footer link and remove it so it won't be output with the rest
     *
     * @param string $name e.g. lastmod, viewcount, copyright, privacy, about, disclaimer
     * @param string $wrapper HTML tag to wrap the link in
     */
    protected function popFooterLink( $name, $wrapper = "span" )
    {
        $key = array_search( $name, $this->remainingFooterLinks );

        if ( $key !== false ) {
            echo "<$wrapper id='$name'>";
            $this->html( $name );
            echo "</$wrapper>";
            unset( $this->remainingFooterLinks[$key] );
        }
    }

    /**
     * Output the actual footer
     */
    protected function renderFooter()
    {
        $footerIcons = $this->getFooterIcons( "icononly" );
        $footerLinks = $this->remainingFooterLinks; // Any footer links that were not already output using popFooterLink

        ?>
        <div id="footer" role="contentinfo" <?php $this->html( 'userlangattributes' ) ?>>

            <? foreach( (array)$footerIcons as $iconSetName => $iconSet ) { ?>
                <ul id="f-<?= $iconSetName ?>ico">
                    <? foreach( $iconSet as $icon ) { ?>
                        <li>
                            <?= $this->getSkin()->makeFooterIcon( $icon ); ?>
                        </li>
                    <? } ?>
                </ul>
            <? } ?>

            <? if ( $footerLinks ) { ?>
                <ul id="f-list">
                    <? foreach( $footerLinks as $linkName ) { ?>
                        <? $this->popFooterLink( $linkName, "li" )?>
                    <? } ?>
                </ul>
            <? } ?>

            <div id="self-aggrandisement">Theme by <a href="http://lion.li">LION STUDIO</a>©</div>

        </div>
    <?
    }

    // </editor-fold>

    // <editor-fold desc="Misc">

    // *****************************************************************************************************************
    //  UTILITY STUFF ETC
    // *****************************************************************************************************************

    /**
     * Return html for an unordered list of the sort of items that could be passed into makeListItem
     * Or just strings, whatev
     *
     * @param $items
     * @param string|null $ulAttrs
     * @param string $postListContent
     * @return string
     */
    protected function makeGenericList( $items, $ulAttrs = null, $postListContent = "" )
    {
        ob_start();
        ?>
        <ul <?=$ulAttrs?>>
            <? foreach ( $items as $key => $item ) {
                if ( is_array( $item ) ) {
                    echo $this->makeListItem( $key, $item );
                } else {
                    echo $item;
                }
            } ?>
            <?= $postListContent ?>
        </ul>
        <?
        return ob_get_clean();
    }

    /**
     * Get a value or subvalue from the data array if set
     *
     * @param string $keys,... Array keys and subkeys
     * @return mixed The value if found, NULL if not
     */
    protected function data( $keys )
    {
        $arg_list = func_get_args();
        $val = $this->data;
        for ($i = 0; $i < func_num_args(); $i++) {
            $arg = $arg_list[$i];
            if ( isset( $val[$arg])) {
                $val = $val[$arg];
            } else {
                return null;
            }
        }

        return $val;
    }

    /**
     * Echo a value from the data array if set
     *
     * @param string $key
     * @return string|void
     */
    public function html( $key )
    {
        echo $this->data( $key );
    }

    /**
     * Echo an escaped value from the data array if set
     *
     * @param string $key
     * @return string|void
     */
    public function text( $key )
    {
        echo htmlspecialchars( $this->data( $key ) );
    }

    // </editor-fold>
}