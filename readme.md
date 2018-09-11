# Pixelion
Responsive theme for MediaWiki  
v0.70 (beta)

A theme for MediaWiki that aims to look nice & be usable on a wide range of devices without sacrificing functionality~  
Code loosely derived from Monobook by Gabriel Wicke

Also restyles parts of the following extensions for niceness/responsiveness:
* Comments
* Semantic MediaWiki
* WikiEditor

## How to install
* Place the skin files in a directory called Pixelion in your skins folder
* Add the following line to your LocalSettings.php:  
`require_once "$IP/skins/Pixelion/Pixelion.php";`

## Usage notes
* This theme looks pretty good with a background image! A sample (derived from a public-domain photo) is provided as bg.jpg. \
  Images should be 16:9 aspect ratio to work with the default CSS, otherwise you will have to adjust `max-aspect-ratio` in the media queries.
* If you omit the background it will fall back to a solid pink colour by default
* Most background images/colours of your choice will work, but avoid anything too close to white as it will render the page action links invisible or hard to read
* Logos: unlike Monobook and Vector which are designed for a square-ish logo, this isn't! Try something like a 5:1 width:height ratio for good results. And make it as big as you like, it'll be scaled down for display
* A couple of fonts are used from Google Fonts, Jockey One and Archivo Narrow, with generic fallbacks if not available
* CSS is written as SCSS in lion.scss and compiled to lion.css. The compiled file is included for convenience; you can edit this directly if you just want to customise the theme for your own use and don't want to use SCSS. [Koala](http://koala-app.com/) is a nice standalone compiler if you *do* want to use it.
* Make sure you *don't* have any extension that redirects to a separate mobile version enabled on your wiki (eg MobileFrontend)! Kinda defeats the object of a responsive theme otherwise

## Known issues
* On mobile it generally looks better if you don't have too many top-level "sidebar" items - it depends on the device but 4 seems to be a good number.  If you have more it will drop down onto another line, which works, but doesn't look too great
* The user button in the top right is a little awkward on mobile since it's both a link and the hover trigger for the user menu; if you tap on it it will send you to the user page. To make the menu appear on mobile you generally have to hold it
* Since the aim is to squeeze all of mediawiki into mobile size, and mediawiki is a very complex piece of software with a ton of pages and elements, which additionally allows people to write basically any arbitrary HTML and CSS inside it..  there will undoubtedly be other things here and there which don't quite fit, or wrap oddly
* sorry bout that
* Also it pretty much falls apart in IE8 or earlier

## Release history
* v0.70 first public release!
* v0.69.1 various small tweaks/fixes
* v0.69 first numbered version
