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
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

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
                <?php $this->cactions(); ?>
            </div><!-- end of the left (by default at least) column -->

            <div id="column-content">
                <? $this->conditionalRenderSidebarPart( "TOOLBOX" ); ?>
                <div id="content" class="mw-body" role="main">
                    <a id="top"></a>
                    <?php if ( $this->data['sitenotice'] ) { ?>
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
                        <?php if ( $this->data['undelete'] ) { ?>
                            <div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
                        <?php } ?>
                        <?php if ( $this->data['newtalk'] ) { ?>
                            <div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
                        <?php } ?>
                        <div id="jump-to-nav" class="mw-jump">
                            <?php $this->msg( 'jumpto' )?>
                            <a href="#column-one"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' )?><a href="#searchInput"><?php $this->msg( 'jumptosearch' ) ?></a>
                        </div>

                        <!-- start content -->
                        <?php $this->html( 'bodytext' ) ?>
                        <?php
                        if ( $this->data['catlinks'] ) {
                            $this->html( 'catlinks' );
                        }
                        ?>
                        <!-- end content -->
                        <?php
                        if ( $this->data['dataAfterContent'] ) {
                            $this->html( 'dataAfterContent' );
                        }
                        ?>

                    </div>
                </div>
                <?php $this->conditionalRenderSidebarPart( "LANGUAGES" ); // todo: deal ?>
                <? $this->popFooterLink( "lastmod" ) ?>
                <div class="visualClear"></div>
            </div>
            <div class="visualClear"></div>
            <?php $this->renderFooter() ?>
		</div>
		<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/


    protected function portletPersonal()
    {
        ?>
        <div class="portlet" id="p-personal" role="navigation">
            <h3><?php $this->msg( 'personaltools' ) ?></h3>

            <div class="pBody">
                <ul<?php $this->html( 'userlangattributes' ) ?>>
                    <?php foreach ( $this->getPersonalTools() as $key => $item ) { ?>
                        <?php echo $this->makeListItem( $key, $item ); ?>

                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    <?php
    }

    protected function portletLogo()
    {
        ?>
        <div class="portlet" id="p-logo" role="banner">
            <?php
            echo Html::openElement( 'a', array(
                    'href' => $this->data['nav_urls']['mainpage']['href'],
                     )
                + Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ); ?>
            <? if ( $this->data['logopath'] ): ?>
                <img src="<?=$this->data['logopath']?>" alt="<?=$this->data['sitename']?>">
            <? else: ?>
                <?=$this->data['sitename']?>
            <? endif ?>
            <? echo Html::closeElement( 'a' ) ?>
        </div>
    <?
    }

	/**
	 * @param array $sidebar
	 */
	protected function renderCustomPortals() {

        //var_dump($this->data['sidebar']);

        // Currently the only custom one seems to be "navigation"

		foreach ( $this->data['sidebar'] as $boxName => $content ) {

            // search, toolbox, languages = presets, being output elsewhere
            if ( $content === false || in_array( $boxName, [ "SEARCH", "TOOLBOX", "LANGUAGES" ] ) ) {
                continue;
            }

			$this->customBox( $boxName, $content );

		}
	}

    protected function conditionalRenderSidebarPart($boxName)
    {
        if ( !isset( $this->data['sidebar'][$boxName] ) || $this->data['sidebar'][$boxName]  !== false ) {

            if ( $boxName == 'SEARCH' ) {
                $this->searchBox();
            } elseif ( $boxName == 'TOOLBOX' ) {
                $this->toolbox();
            } elseif ( $boxName == 'LANGUAGES' ) {
                $this->languageBox();
            } else {
                $this->customBox( $boxName, $this->data['sidebar'][$boxName] );
            }

        }
    }


	function searchBox() {
		?>
		<div id="p-search" class="portlet" role="search">
			<h3><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h3>

			<div id="searchBody" class="pBody">
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
			</div>
		</div>
	<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between Pixelion and Modern
	 */
	function cactions() {
		?>
		<div id="p-cactions" class="portlet" role="navigation">
			<h3><?php $this->msg( 'views' ) ?></h3>

			<div class="pBody">
				<ul><?php
					foreach ( $this->data['content_actions'] as $key => $tab ) {
						echo '
				' . $this->makeListItem( $key, $tab );
					} ?>

				</ul>
				<?php $this->renderAfterPortlet( 'cactions' ); ?>
			</div>
		</div>
	<?php
	}

	/*************************************************************************************************/
	function toolbox() {

		?>
		<div class="portlet" id="p-tb" role="navigation">
			<h3><?php $this->msg( 'toolbox' ) ?></h3>

			<div class="pBody">
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
			</div>
		</div>
	<?php
	}

	/*************************************************************************************************/
	function languageBox() {
        $langurls = $this->data['language_urls'];
		if ( $langurls !== false ) {
			?>
			<div id="p-lang" class="portlet" role="navigation">
				<h3<?php $this->html( 'userlangattributes' ) ?>><?php $this->msg( 'otherlanguages' ) ?></h3>

				<div class="pBody">
					<ul>
						<?php foreach ( $langurls as $key => $langlink ) { ?>
							<?php echo $this->makeListItem( $key, $langlink ); ?>

						<?php
}
						?>
					</ul>

					<?php $this->renderAfterPortlet( 'lang' ); ?>
				</div>
			</div>
		<?php
		}
	}

	/*************************************************************************************************/
	/**
	 * @param string $bar
	 * @param array|string $cont
	 */
	function customBox( $bar, $cont ) {
		$portletAttribs = array(
			'class' => 'generated-sidebar portlet',
			'id' => Sanitizer::escapeId( "p-$bar" ),
			'role' => 'navigation'
		);

		$tooltip = Linker::titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$portletAttribs['title'] = $tooltip;
		}
		echo '	' . Html::openElement( 'div', $portletAttribs );
		$msgObj = wfMessage( $bar );
		?>

		<h3><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $bar ); ?></h3>
		<div class='pBody'>
			<?php
			if ( is_array( $cont ) ) {
				?>
				<ul>
					<?php
					foreach ( $cont as $key => $val ) {
						?>
						<?php echo $this->makeListItem( $key, $val ); ?>

					<?php
					}
					?>
				</ul>
			<?php
			} else {
				# allow raw HTML block to be defined by extensions
				print $cont;
			}

			$this->renderAfterPortlet( $bar );
			?>
		</div>
		</div>
	<?php
	}

    // ************ EVERYTHING AFTER THIS POINT SHOULD BE LICENSING SAFE ****************

    // *****************************************************************************************************************
    //  FOOTER START
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

}