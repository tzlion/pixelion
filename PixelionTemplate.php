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
                    <?php $this->renderCustomPortals(); ?>
                </div>
                <div id="headbar">
                    <?php $this->portletLogo() ?>
                    <?php $this->conditionalRenderSidebarPart( "SEARCH" );  ?>
                </div>
                <?php $this->portletContentActions(); ?>
            </div><!-- end of the left (by default at least) column -->

            <div id="column-content">
                <? $this->conditionalRenderSidebarPart( "TOOLBOX" ); ?>
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
                <?php $this->conditionalRenderSidebarPart( "LANGUAGES" ); // todo: deal ?>
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



	/**
	 * @param array $sidebar
	 */
	protected function renderCustomPortals() {

        // Currently the only custom one seems to be "navigation"

		foreach ( $this->data('sidebar') as $boxName => $content ) {

            // search, toolbox, languages = presets, being output elsewhere
            if ( $content === false || in_array( $boxName, [ "SEARCH", "TOOLBOX", "LANGUAGES" ] ) ) {
                continue;
            }

			$this->customBox( $boxName, $content );

		}
	}

    protected function conditionalRenderSidebarPart($boxName)
    {
        if ( $this->data( 'sidebar', $boxName ) !== false ) {

            if ( $boxName == 'SEARCH' ) {
                $this->searchBox();
            } elseif ( $boxName == 'TOOLBOX' ) {
                $this->toolbox();
            } elseif ( $boxName == 'LANGUAGES' ) {
                $this->languageBox();
            } else {
                $this->customBox( $boxName, $this->data( 'sidebar', $boxName ) );
            }

        }
    }


	function searchBox() {
        ob_start();
		?>

				<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
					<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
					<?php echo $this->makeSearchInput( array( "id" => "searchInput" ) ); ?>

					<?php
					echo $this->makeSearchButton(
						"go",
						array( "id" => "searchGoButton", "class" => "searchButton" )
					);

					if ( $this->config->get( 'UseTwoButtonsSearchForm' ) ) {
						?>&#160;
						<?php echo $this->makeSearchButton(
							"fulltext",
							array( "id" => "mw-searchButton", "class" => "searchButton" )
						);
					} else {
						?>

						<div><a href="<?php
						$this->text( 'searchaction' )
						?>" rel="search"><?php $this->msg( 'powersearch-legend' ) ?></a></div><?php
					} ?>

				</form>

				<?php $this->renderAfterPortlet( 'search' ); ?>

	<?php
        $header = '<label for="searchInput">' . $this->getMsg("personaltools")->escaped() . '</label>';
        $this->renderPortlet( "p-search", "search", $header, [ "attrs" => "id='searchBody'", "content" => ob_get_clean() ] );
	}




	/*************************************************************************************************/
	function toolbox() {
        ob_start();
		?>
                <a id="toolboxbutton" href="#" title="Toolbox">T</a>
				<ul id="tools">
					<?php
					foreach ( $this->getToolbox() as $key => $tbitem ) {
						?>
						<?php echo $this->makeListItem( $key, $tbitem ); ?>

					<?php
					}
					wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
					?>
				</ul>
				<?php $this->renderAfterPortlet( 'tb' ); ?>
	<?php
        $header = $this->getMsg( "toolbox" )->escaped();
        $this->renderPortlet( "p-tb", "navigation", $header, ob_get_clean() );
	}

	/*************************************************************************************************/
	function languageBox() {

        ob_start();

        $langurls = $this->data('language_urls');
		if ( $langurls !== false ) {
            echo $this->makeGenericList( $langurls );
			?>

					<?php $this->renderAfterPortlet( 'lang' ); ?>

		<?php
		}

        $header = [ "content" => $this->getMsg("otherlanguages")->escaped(), "attrs" => $this->data( 'userlangattributes' ) ];
        $this->renderPortlet( "p-lang", "navigation", $header, ob_get_clean() );

    }

	/*************************************************************************************************/
	/**
	 * @param string $bar
	 * @param array|string $cont
	 */
	function customBox( $bar, $cont ) {

		$tooltip = Linker::titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$titleattr = $tooltip;
		} else {
            $titleattr=null;
        }

		$msgObj = wfMessage( $bar );

        $header = htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $bar );
        ob_start();
        ?>
        <?php
            if ( is_array( $cont ) ) {
                echo $this->makeGenericList( $cont );
            } else {
                # allow raw HTML block to be defined by extensions
                print $cont;
            }

            $this->renderAfterPortlet( $bar );
        ?>
        <?
        $body = ob_get_clean();
        $this->renderPortlet( Sanitizer::escapeId( "p-$bar" ), "navigation", $header, $body, "generated-sidebar", $titleattr );
	}

    // ************ EVERYTHING AFTER THIS POINT SHOULD BE LICENSING SAFE ****************

    // <editor-fold desc="Portlets">

    // *****************************************************************************************************************
    //  PORTLETS
    // *****************************************************************************************************************

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

    protected function portletPersonal()
    {
        $content = $this->makeGenericList( $this->getPersonalTools(), $this->data( "userlangattributes" ) );
        $this->renderPortlet( "p-personal", "navigation", $this->getMsg( "personaltools" )->escaped(), $content );
    }

    protected function portletContentActions()
    {
        $content = $this->makeGenericList( $this->data('content_actions') );
        $content .= $this->getPostPortletStuff( "cactions" );
        $header = $this->getMsg( "views" )->escaped();
        $this->renderPortlet( "p-cactions", "navigation", $header, $content );
    }

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
     * @return string
     */
    protected function makeGenericList( $items, $ulAttrs = null )
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