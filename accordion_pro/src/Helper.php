<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src;

defined('C5_EXECUTE') or die('Access Denied.');

class Helper
{

    /**
     * function xml2array
     *
     * This function is part of the PHP manual.
     *
     * The PHP manual text and comments are covered by the Creative Commons
     * Attribution 3.0 License, copyright (c) the PHP Documentation Group
     *
     * @author  k dot antczak at livedata dot pl
     * @date    2011-04-22 06:08 UTC
     * @link    http://www.php.net/manual/en/ref.simplexml.php#103617
     * @license http://www.php.net/license/index.php#doc-lic
     * @license http://creativecommons.org/licenses/by/3.0/
     * @license CC-BY-3.0 <http://spdx.org/licenses/CC-BY-3.0>
     */
    public static function xml2array($xmlObject, $out = array())
    {
        foreach ((array) $xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? self::xml2array($node) : $node;
        }
        
        return $out;
    }
    
    /**
     * @param \SimpleXMLElement $xml
     *
     * @return string
     */
    public static function convertSimpleXmlObjectToText($xml)
    {
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        return $dom->saveXML();
    }
    
    /**
     *
     * @param string $color
     * @return boolean
     */
    public static function isValidColor($color)
    {
        $allColors = array('transparent', 'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');

        if (in_array(strtolower($color), $allColors)) {
            return true;
        } elseif (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return true;
        } elseif (preg_match('/^[a-f0-9]{6}$/i', $color)) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param string $fontWeight
     * @return boolean
     */
    public static function isValidFontWeight($fontWeight)
    {
        return (in_array(strtolower($fontWeight), array("normal", "bold", "bolder", "lighter", "initial", "inherit"))) || is_numeric($fontWeight);
    }

    /**
     *
     * @param string $textDecoration
     * @return boolean
     */
    public static function isValidTextDecoration($textDecoration)
    {
        return in_array(strtolower($textDecoration), array("none", "underline", "overline", "line-through", "initial", "inherit"));
    }

    /**
     *
     * @param string $fontSize
     * @return boolean
     */
    public static function isValidFontSize($fontSize)
    {
        return (in_array(strtolower($fontSize), array("initial", "inherit", "medium", "xx-small", "x-small", "small", "large", "x-large", "xx-large", "smaller", "larger"))) || self::isValidUnit($fontSize);
    }

    /**
     *
     * @return array
     */
    public static function getFontAwesomeIcons()
    {
        return array(
            "f000" => "fa-glass",
            "f001" => "fa-music",
            "f002" => "fa-search",
            "f003" => "fa-envelope-o",
            "f004" => "fa-heart",
            "f005" => "fa-star",
            "f006" => "fa-star-o",
            "f007" => "fa-user",
            "f008" => "fa-film",
            "f009" => "fa-th-large",
            "f00a" => "fa-th",
            "f00b" => "fa-th-list",
            "f00c" => "fa-check",
            "f00d" => "fa-times",
            "f00e" => "fa-search-plus",
            "f010" => "fa-search-minus",
            "f011" => "fa-power-off",
            "f012" => "fa-signal",
            "f013" => "fa-cog",
            "f014" => "fa-trash-o",
            "f015" => "fa-home",
            "f016" => "fa-file-o",
            "f017" => "fa-clock-o",
            "f018" => "fa-road",
            "f019" => "fa-download",
            "f01a" => "fa-arrow-circle-o-down",
            "f01b" => "fa-arrow-circle-o-up",
            "f01c" => "fa-inbox",
            "f01d" => "fa-play-circle-o",
            "f01e" => "fa-repeat",
            "f021" => "fa-refresh",
            "f022" => "fa-list-alt",
            "f023" => "fa-lock",
            "f024" => "fa-flag",
            "f025" => "fa-headphones",
            "f026" => "fa-volume-off",
            "f027" => "fa-volume-down",
            "f028" => "fa-volume-up",
            "f029" => "fa-qrcode",
            "f02a" => "fa-barcode",
            "f02b" => "fa-tag",
            "f02c" => "fa-tags",
            "f02d" => "fa-book",
            "f02e" => "fa-bookmark",
            "f02f" => "fa-print",
            "f030" => "fa-camera",
            "f031" => "fa-font",
            "f032" => "fa-bold",
            "f033" => "fa-italic",
            "f034" => "fa-text-height",
            "f035" => "fa-text-width",
            "f036" => "fa-align-left",
            "f037" => "fa-align-center",
            "f038" => "fa-align-right",
            "f039" => "fa-align-justify",
            "f03a" => "fa-list",
            "f03b" => "fa-outdent",
            "f03c" => "fa-indent",
            "f03d" => "fa-video-camera",
            "f03e" => "fa-picture-o",
            "f040" => "fa-pencil",
            "f041" => "fa-map-marker",
            "f042" => "fa-adjust",
            "f043" => "fa-tint",
            "f044" => "fa-pencil-square-o",
            "f045" => "fa-share-square-o",
            "f046" => "fa-check-square-o",
            "f047" => "fa-arrows",
            "f048" => "fa-step-backward",
            "f049" => "fa-fast-backward",
            "f04a" => "fa-backward",
            "f04b" => "fa-play",
            "f04c" => "fa-pause",
            "f04d" => "fa-stop",
            "f04e" => "fa-forward",
            "f050" => "fa-fast-forward",
            "f051" => "fa-step-forward",
            "f052" => "fa-eject",
            "f053" => "fa-chevron-left",
            "f054" => "fa-chevron-right",
            "f055" => "fa-plus-circle",
            "f056" => "fa-minus-circle",
            "f057" => "fa-times-circle",
            "f058" => "fa-check-circle",
            "f059" => "fa-question-circle",
            "f05a" => "fa-info-circle",
            "f05b" => "fa-crosshairs",
            "f05c" => "fa-times-circle-o",
            "f05d" => "fa-check-circle-o",
            "f05e" => "fa-ban",
            "f060" => "fa-arrow-left",
            "f061" => "fa-arrow-right",
            "f062" => "fa-arrow-up",
            "f063" => "fa-arrow-down",
            "f064" => "fa-share",
            "f065" => "fa-expand",
            "f066" => "fa-compress",
            "f067" => "fa-plus",
            "f068" => "fa-minus",
            "f069" => "fa-asterisk",
            "f06a" => "fa-exclamation-circle",
            "f06b" => "fa-gift",
            "f06c" => "fa-leaf",
            "f06d" => "fa-fire",
            "f06e" => "fa-eye",
            "f070" => "fa-eye-slash",
            "f071" => "fa-exclamation-triangle",
            "f072" => "fa-plane",
            "f073" => "fa-calendar",
            "f074" => "fa-random",
            "f075" => "fa-comment",
            "f076" => "fa-magnet",
            "f077" => "fa-chevron-up",
            "f078" => "fa-chevron-down",
            "f079" => "fa-retweet",
            "f07a" => "fa-shopping-cart",
            "f07b" => "fa-folder",
            "f07c" => "fa-folder-open",
            "f07d" => "fa-arrows-v",
            "f07e" => "fa-arrows-h",
            "f080" => "fa-bar-chart",
            "f081" => "fa-twitter-square",
            "f082" => "fa-facebook-square",
            "f083" => "fa-camera-retro",
            "f084" => "fa-key",
            "f085" => "fa-cogs",
            "f086" => "fa-comments",
            "f087" => "fa-thumbs-o-up",
            "f088" => "fa-thumbs-o-down",
            "f089" => "fa-star-half",
            "f08a" => "fa-heart-o",
            "f08b" => "fa-sign-out",
            "f08c" => "fa-linkedin-square",
            "f08d" => "fa-thumb-tack",
            "f08e" => "fa-external-link",
            "f090" => "fa-sign-in",
            "f091" => "fa-trophy",
            "f092" => "fa-github-square",
            "f093" => "fa-upload",
            "f094" => "fa-lemon-o",
            "f095" => "fa-phone",
            "f096" => "fa-square-o",
            "f097" => "fa-bookmark-o",
            "f098" => "fa-phone-square",
            "f099" => "fa-twitter",
            "f09a" => "fa-facebook",
            "f09b" => "fa-github",
            "f09c" => "fa-unlock",
            "f09d" => "fa-credit-card",
            "f09e" => "fa-rss",
            "f0a0" => "fa-hdd-o",
            "f0a1" => "fa-bullhorn",
            "f0f3" => "fa-bell",
            "f0a3" => "fa-certificate",
            "f0a4" => "fa-hand-o-right",
            "f0a5" => "fa-hand-o-left",
            "f0a6" => "fa-hand-o-up",
            "f0a7" => "fa-hand-o-down",
            "f0a8" => "fa-arrow-circle-left",
            "f0a9" => "fa-arrow-circle-right",
            "f0aa" => "fa-arrow-circle-up",
            "f0ab" => "fa-arrow-circle-down",
            "f0ac" => "fa-globe",
            "f0ad" => "fa-wrench",
            "f0ae" => "fa-tasks",
            "f0b0" => "fa-filter",
            "f0b1" => "fa-briefcase",
            "f0b2" => "fa-arrows-alt",
            "f0c0" => "fa-users",
            "f0c1" => "fa-link",
            "f0c2" => "fa-cloud",
            "f0c3" => "fa-flask",
            "f0c4" => "fa-scissors",
            "f0c5" => "fa-files-o",
            "f0c6" => "fa-paperclip",
            "f0c7" => "fa-floppy-o",
            "f0c8" => "fa-square",
            "f0c9" => "fa-bars",
            "f0ca" => "fa-list-ul",
            "f0cb" => "fa-list-ol",
            "f0cc" => "fa-strikethrough",
            "f0cd" => "fa-underline",
            "f0ce" => "fa-table",
            "f0d0" => "fa-magic",
            "f0d1" => "fa-truck",
            "f0d2" => "fa-pinterest",
            "f0d3" => "fa-pinterest-square",
            "f0d4" => "fa-google-plus-square",
            "f0d5" => "fa-google-plus",
            "f0d6" => "fa-money",
            "f0d7" => "fa-caret-down",
            "f0d8" => "fa-caret-up",
            "f0d9" => "fa-caret-left",
            "f0da" => "fa-caret-right",
            "f0db" => "fa-columns",
            "f0dc" => "fa-sort",
            "f0dd" => "fa-sort-desc",
            "f0de" => "fa-sort-asc",
            "f0e0" => "fa-envelope",
            "f0e1" => "fa-linkedin",
            "f0e2" => "fa-undo",
            "f0e3" => "fa-gavel",
            "f0e4" => "fa-tachometer",
            "f0e5" => "fa-comment-o",
            "f0e6" => "fa-comments-o",
            "f0e7" => "fa-bolt",
            "f0e8" => "fa-sitemap",
            "f0e9" => "fa-umbrella",
            "f0ea" => "fa-clipboard",
            "f0eb" => "fa-lightbulb-o",
            "f0ec" => "fa-exchange",
            "f0ed" => "fa-cloud-download",
            "f0ee" => "fa-cloud-upload",
            "f0f0" => "fa-user-md",
            "f0f1" => "fa-stethoscope",
            "f0f2" => "fa-suitcase",
            "f0a2" => "fa-bell-o",
            "f0f4" => "fa-coffee",
            "f0f5" => "fa-cutlery",
            "f0f6" => "fa-file-text-o",
            "f0f7" => "fa-building-o",
            "f0f8" => "fa-hospital-o",
            "f0f9" => "fa-ambulance",
            "f0fa" => "fa-medkit",
            "f0fb" => "fa-fighter-jet",
            "f0fc" => "fa-beer",
            "f0fd" => "fa-h-square",
            "f0fe" => "fa-plus-square",
            "f100" => "fa-angle-double-left",
            "f101" => "fa-angle-double-right",
            "f102" => "fa-angle-double-up",
            "f103" => "fa-angle-double-down",
            "f104" => "fa-angle-left",
            "f105" => "fa-angle-right",
            "f106" => "fa-angle-up",
            "f107" => "fa-angle-down",
            "f108" => "fa-desktop",
            "f109" => "fa-laptop",
            "f10a" => "fa-tablet",
            "f10b" => "fa-mobile",
            "f10c" => "fa-circle-o",
            "f10d" => "fa-quote-left",
            "f10e" => "fa-quote-right",
            "f110" => "fa-spinner",
            "f111" => "fa-circle",
            "f112" => "fa-reply",
            "f113" => "fa-github-alt",
            "f114" => "fa-folder-o",
            "f115" => "fa-folder-open-o",
            "f118" => "fa-smile-o",
            "f119" => "fa-frown-o",
            "f11a" => "fa-meh-o",
            "f11b" => "fa-gamepad",
            "f11c" => "fa-keyboard-o",
            "f11d" => "fa-flag-o",
            "f11e" => "fa-flag-checkered",
            "f120" => "fa-terminal",
            "f121" => "fa-code",
            "f122" => "fa-reply-all",
            "f123" => "fa-star-half-o",
            "f124" => "fa-location-arrow",
            "f125" => "fa-crop",
            "f126" => "fa-code-fork",
            "f127" => "fa-chain-broken",
            "f128" => "fa-question",
            "f129" => "fa-info",
            "f12a" => "fa-exclamation",
            "f12b" => "fa-superscript",
            "f12c" => "fa-subscript",
            "f12d" => "fa-eraser",
            "f12e" => "fa-puzzle-piece",
            "f130" => "fa-microphone",
            "f131" => "fa-microphone-slash",
            "f132" => "fa-shield",
            "f133" => "fa-calendar-o",
            "f134" => "fa-fire-extinguisher",
            "f135" => "fa-rocket",
            "f136" => "fa-maxcdn",
            "f137" => "fa-chevron-circle-left",
            "f138" => "fa-chevron-circle-right",
            "f139" => "fa-chevron-circle-up",
            "f13a" => "fa-chevron-circle-down",
            "f13b" => "fa-html5",
            "f13c" => "fa-css3",
            "f13d" => "fa-anchor",
            "f13e" => "fa-unlock-alt",
            "f140" => "fa-bullseye",
            "f141" => "fa-ellipsis-h",
            "f142" => "fa-ellipsis-v",
            "f143" => "fa-rss-square",
            "f144" => "fa-play-circle",
            "f145" => "fa-ticket",
            "f146" => "fa-minus-square",
            "f147" => "fa-minus-square-o",
            "f148" => "fa-level-up",
            "f149" => "fa-level-down",
            "f14a" => "fa-check-square",
            "f14b" => "fa-pencil-square",
            "f14c" => "fa-external-link-square",
            "f14d" => "fa-share-square",
            "f14e" => "fa-compass",
            "f150" => "fa-caret-square-o-down",
            "f151" => "fa-caret-square-o-up",
            "f152" => "fa-caret-square-o-right",
            "f153" => "fa-eur",
            "f154" => "fa-gbp",
            "f155" => "fa-usd",
            "f156" => "fa-inr",
            "f157" => "fa-jpy",
            "f158" => "fa-rub",
            "f159" => "fa-krw",
            "f15a" => "fa-btc",
            "f15b" => "fa-file",
            "f15c" => "fa-file-text",
            "f15d" => "fa-sort-alpha-asc",
            "f15e" => "fa-sort-alpha-desc",
            "f160" => "fa-sort-amount-asc",
            "f161" => "fa-sort-amount-desc",
            "f162" => "fa-sort-numeric-asc",
            "f163" => "fa-sort-numeric-desc",
            "f164" => "fa-thumbs-up",
            "f165" => "fa-thumbs-down",
            "f166" => "fa-youtube-square",
            "f167" => "fa-youtube",
            "f168" => "fa-xing",
            "f169" => "fa-xing-square",
            "f16a" => "fa-youtube-play",
            "f16b" => "fa-dropbox",
            "f16c" => "fa-stack-overflow",
            "f16d" => "fa-instagram",
            "f16e" => "fa-flickr",
            "f170" => "fa-adn",
            "f171" => "fa-bitbucket",
            "f172" => "fa-bitbucket-square",
            "f173" => "fa-tumblr",
            "f174" => "fa-tumblr-square",
            "f175" => "fa-long-arrow-down",
            "f176" => "fa-long-arrow-up",
            "f177" => "fa-long-arrow-left",
            "f178" => "fa-long-arrow-right",
            "f179" => "fa-apple",
            "f17a" => "fa-windows",
            "f17b" => "fa-android",
            "f17c" => "fa-linux",
            "f17d" => "fa-dribbble",
            "f17e" => "fa-skype",
            "f180" => "fa-foursquare",
            "f181" => "fa-trello",
            "f182" => "fa-female",
            "f183" => "fa-male",
            "f184" => "fa-gratipay",
            "f185" => "fa-sun-o",
            "f186" => "fa-moon-o",
            "f187" => "fa-archive",
            "f188" => "fa-bug",
            "f189" => "fa-vk",
            "f18a" => "fa-weibo",
            "f18b" => "fa-renren",
            "f18c" => "fa-pagelines",
            "f18d" => "fa-stack-exchange",
            "f18e" => "fa-arrow-circle-o-right",
            "f190" => "fa-arrow-circle-o-left",
            "f191" => "fa-caret-square-o-left",
            "f192" => "fa-dot-circle-o",
            "f193" => "fa-wheelchair",
            "f194" => "fa-vimeo-square",
            "f195" => "fa-try",
            "f196" => "fa-plus-square-o",
            "f197" => "fa-space-shuttle",
            "f198" => "fa-slack",
            "f199" => "fa-envelope-square",
            "f19a" => "fa-wordpress",
            "f19b" => "fa-openid",
            "f19c" => "fa-university",
            "f19d" => "fa-graduation-cap",
            "f19e" => "fa-yahoo",
            "f1a0" => "fa-google",
            "f1a1" => "fa-reddit",
            "f1a2" => "fa-reddit-square",
            "f1a3" => "fa-stumbleupon-circle",
            "f1a4" => "fa-stumbleupon",
            "f1a5" => "fa-delicious",
            "f1a6" => "fa-digg",
            "f1a7" => "fa-pied-piper-pp",
            "f1a8" => "fa-pied-piper-alt",
            "f1a9" => "fa-drupal",
            "f1aa" => "fa-joomla",
            "f1ab" => "fa-language",
            "f1ac" => "fa-fax",
            "f1ad" => "fa-building",
            "f1ae" => "fa-child",
            "f1b0" => "fa-paw",
            "f1b1" => "fa-spoon",
            "f1b2" => "fa-cube",
            "f1b3" => "fa-cubes",
            "f1b4" => "fa-behance",
            "f1b5" => "fa-behance-square",
            "f1b6" => "fa-steam",
            "f1b7" => "fa-steam-square",
            "f1b8" => "fa-recycle",
            "f1b9" => "fa-car",
            "f1ba" => "fa-taxi",
            "f1bb" => "fa-tree",
            "f1bc" => "fa-spotify",
            "f1bd" => "fa-deviantart",
            "f1be" => "fa-soundcloud",
            "f1c0" => "fa-database",
            "f1c1" => "fa-file-pdf-o",
            "f1c2" => "fa-file-word-o",
            "f1c3" => "fa-file-excel-o",
            "f1c4" => "fa-file-powerpoint-o",
            "f1c5" => "fa-file-image-o",
            "f1c6" => "fa-file-archive-o",
            "f1c7" => "fa-file-audio-o",
            "f1c8" => "fa-file-video-o",
            "f1c9" => "fa-file-code-o",
            "f1ca" => "fa-vine",
            "f1cb" => "fa-codepen",
            "f1cc" => "fa-jsfiddle",
            "f1cd" => "fa-life-ring",
            "f1ce" => "fa-circle-o-notch",
            "f1d0" => "fa-rebel",
            "f1d1" => "fa-empire",
            "f1d2" => "fa-git-square",
            "f1d3" => "fa-git",
            "f1d4" => "fa-hacker-news",
            "f1d5" => "fa-tencent-weibo",
            "f1d6" => "fa-qq",
            "f1d7" => "fa-weixin",
            "f1d8" => "fa-paper-plane",
            "f1d9" => "fa-paper-plane-o",
            "f1da" => "fa-history",
            "f1db" => "fa-circle-thin",
            "f1dc" => "fa-header",
            "f1dd" => "fa-paragraph",
            "f1de" => "fa-sliders",
            "f1e0" => "fa-share-alt",
            "f1e1" => "fa-share-alt-square",
            "f1e2" => "fa-bomb",
            "f1e3" => "fa-futbol-o",
            "f1e4" => "fa-tty",
            "f1e5" => "fa-binoculars",
            "f1e6" => "fa-plug",
            "f1e7" => "fa-slideshare",
            "f1e8" => "fa-twitch",
            "f1e9" => "fa-yelp",
            "f1ea" => "fa-newspaper-o",
            "f1eb" => "fa-wifi",
            "f1ec" => "fa-calculator",
            "f1ed" => "fa-paypal",
            "f1ee" => "fa-google-wallet",
            "f1f0" => "fa-cc-visa",
            "f1f1" => "fa-cc-mastercard",
            "f1f2" => "fa-cc-discover",
            "f1f3" => "fa-cc-amex",
            "f1f4" => "fa-cc-paypal",
            "f1f5" => "fa-cc-stripe",
            "f1f6" => "fa-bell-slash",
            "f1f7" => "fa-bell-slash-o",
            "f1f8" => "fa-trash",
            "f1f9" => "fa-copyright",
            "f1fa" => "fa-at",
            "f1fb" => "fa-eyedropper",
            "f1fc" => "fa-paint-brush",
            "f1fd" => "fa-birthday-cake",
            "f1fe" => "fa-area-chart",
            "f200" => "fa-pie-chart",
            "f201" => "fa-line-chart",
            "f202" => "fa-lastfm",
            "f203" => "fa-lastfm-square",
            "f204" => "fa-toggle-off",
            "f205" => "fa-toggle-on",
            "f206" => "fa-bicycle",
            "f207" => "fa-bus",
            "f208" => "fa-ioxhost",
            "f209" => "fa-angellist",
            "f20a" => "fa-cc",
            "f20b" => "fa-ils",
            "f20c" => "fa-meanpath",
            "f20d" => "fa-buysellads",
            "f20e" => "fa-connectdevelop",
            "f210" => "fa-dashcube",
            "f211" => "fa-forumbee",
            "f212" => "fa-leanpub",
            "f213" => "fa-sellsy",
            "f214" => "fa-shirtsinbulk",
            "f215" => "fa-simplybuilt",
            "f216" => "fa-skyatlas",
            "f217" => "fa-cart-plus",
            "f218" => "fa-cart-arrow-down",
            "f219" => "fa-diamond",
            "f21a" => "fa-ship",
            "f21b" => "fa-user-secret",
            "f21c" => "fa-motorcycle",
            "f21d" => "fa-street-view",
            "f21e" => "fa-heartbeat",
            "f221" => "fa-venus",
            "f222" => "fa-mars",
            "f223" => "fa-mercury",
            "f224" => "fa-transgender",
            "f225" => "fa-transgender-alt",
            "f226" => "fa-venus-double",
            "f227" => "fa-mars-double",
            "f228" => "fa-venus-mars",
            "f229" => "fa-mars-stroke",
            "f22a" => "fa-mars-stroke-v",
            "f22b" => "fa-mars-stroke-h",
            "f22c" => "fa-neuter",
            "f22d" => "fa-genderless",
            "f230" => "fa-facebook-official",
            "f231" => "fa-pinterest-p",
            "f232" => "fa-whatsapp",
            "f233" => "fa-server",
            "f234" => "fa-user-plus",
            "f235" => "fa-user-times",
            "f236" => "fa-bed",
            "f237" => "fa-viacoin",
            "f238" => "fa-train",
            "f239" => "fa-subway",
            "f23a" => "fa-medium",
            "f23b" => "fa-y-combinator",
            "f23c" => "fa-optin-monster",
            "f23d" => "fa-opencart",
            "f23e" => "fa-expeditedssl",
            "f240" => "fa-battery-full",
            "f241" => "fa-battery-three-quarters",
            "f242" => "fa-battery-half",
            "f243" => "fa-battery-quarter",
            "f244" => "fa-battery-empty",
            "f245" => "fa-mouse-pointer",
            "f246" => "fa-i-cursor",
            "f247" => "fa-object-group",
            "f248" => "fa-object-ungroup",
            "f249" => "fa-sticky-note",
            "f24a" => "fa-sticky-note-o",
            "f24b" => "fa-cc-jcb",
            "f24c" => "fa-cc-diners-club",
            "f24d" => "fa-clone",
            "f24e" => "fa-balance-scale",
            "f250" => "fa-hourglass-o",
            "f251" => "fa-hourglass-start",
            "f252" => "fa-hourglass-half",
            "f253" => "fa-hourglass-end",
            "f254" => "fa-hourglass",
            "f255" => "fa-hand-rock-o",
            "f256" => "fa-hand-paper-o",
            "f257" => "fa-hand-scissors-o",
            "f258" => "fa-hand-lizard-o",
            "f259" => "fa-hand-spock-o",
            "f25a" => "fa-hand-pointer-o",
            "f25b" => "fa-hand-peace-o",
            "f25c" => "fa-trademark",
            "f25d" => "fa-registered",
            "f25e" => "fa-creative-commons",
            "f260" => "fa-gg",
            "f261" => "fa-gg-circle",
            "f262" => "fa-tripadvisor",
            "f263" => "fa-odnoklassniki",
            "f264" => "fa-odnoklassniki-square",
            "f265" => "fa-get-pocket",
            "f266" => "fa-wikipedia-w",
            "f267" => "fa-safari",
            "f268" => "fa-chrome",
            "f269" => "fa-firefox",
            "f26a" => "fa-opera",
            "f26b" => "fa-internet-explorer",
            "f26c" => "fa-television",
            "f26d" => "fa-contao",
            "f26e" => "fa-500px",
            "f270" => "fa-amazon",
            "f271" => "fa-calendar-plus-o",
            "f272" => "fa-calendar-minus-o",
            "f273" => "fa-calendar-times-o",
            "f274" => "fa-calendar-check-o",
            "f275" => "fa-industry",
            "f276" => "fa-map-pin",
            "f277" => "fa-map-signs",
            "f278" => "fa-map-o",
            "f279" => "fa-map",
            "f27a" => "fa-commenting",
            "f27b" => "fa-commenting-o",
            "f27c" => "fa-houzz",
            "f27d" => "fa-vimeo",
            "f27e" => "fa-black-tie",
            "f280" => "fa-fonticons",
            "f281" => "fa-reddit-alien",
            "f282" => "fa-edge",
            "f283" => "fa-credit-card-alt",
            "f284" => "fa-codiepie",
            "f285" => "fa-modx",
            "f286" => "fa-fort-awesome",
            "f287" => "fa-usb",
            "f288" => "fa-product-hunt",
            "f289" => "fa-mixcloud",
            "f28a" => "fa-scribd",
            "f28b" => "fa-pause-circle",
            "f28c" => "fa-pause-circle-o",
            "f28d" => "fa-stop-circle",
            "f28e" => "fa-stop-circle-o",
            "f290" => "fa-shopping-bag",
            "f291" => "fa-shopping-basket",
            "f292" => "fa-hashtag",
            "f293" => "fa-bluetooth",
            "f294" => "fa-bluetooth-b",
            "f295" => "fa-percent",
            "f296" => "fa-gitlab",
            "f297" => "fa-wpbeginner",
            "f298" => "fa-wpforms",
            "f299" => "fa-envira",
            "f29a" => "fa-universal-access",
            "f29b" => "fa-wheelchair-alt",
            "f29c" => "fa-question-circle-o",
            "f29d" => "fa-blind",
            "f29e" => "fa-audio-description",
            "f2a0" => "fa-volume-control-phone",
            "f2a1" => "fa-braille",
            "f2a2" => "fa-assistive-listening-systems",
            "f2a3" => "fa-american-sign-language-interpreting",
            "f2a4" => "fa-deaf",
            "f2a5" => "fa-glide",
            "f2a6" => "fa-glide-g",
            "f2a7" => "fa-sign-language",
            "f2a8" => "fa-low-vision",
            "f2a9" => "fa-viadeo",
            "f2aa" => "fa-viadeo-square",
            "f2ab" => "fa-snapchat",
            "f2ac" => "fa-snapchat-ghost",
            "f2ad" => "fa-snapchat-square",
            "f2ae" => "fa-pied-piper",
            "f2b0" => "fa-first-order",
            "f2b1" => "fa-yoast",
            "f2b2" => "fa-themeisle",
            "f2b3" => "fa-google-plus-official",
            "f2b4" => "fa-font-awesome"
        );
    }

    /**
     * @return array
     */
    public static function getYesNoValues()
    {
        return array(
            0 => t("No"),
            1 => t("Yes")
        );
    }

    /**
     *
     * @param string $fontAwesomeIcon
     *
     * @return boolean
     */
    public static function isValidFontAwesomeIcon($fontAwesomeIcon)
    {
        return in_array(strtolower($fontAwesomeIcon), self::getKeys(self::getFontAwesomeIcons())) || $fontAwesomeIcon === "";
    }

    /**
     *
     * @param string $padding
     * @return boolean
     */
    public static function isValidPadding($padding)
    {
        return (in_array(strtolower($padding), array("initial", "inherit"))) || self::isValidUnit($padding);
    }

    /**
     *
     * @param string $unit
     * @return boolean
     */
    private static function isValidUnit($unit)
    {
        if (strlen($unit) > 2 && in_array(strtolower(substr($unit, strlen($unit) - 2)), array("px", "em", "ex", "pt", "in", "pc", "mm", "cm")) && is_numeric(substr($unit, 0, strlen($unit) - 2))) {
            return true;
        } elseif (strlen($unit) > 1 && substr($unit, strlen($unit) - 1) === "%" && is_numeric(substr($unit, 0, strlen($unit) - 1))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $width
     * @return boolean
     */
    public static function isValidBorderWidth($width)
    {
        return (in_array(strtolower($width), array("medium", "thin", "thick", "initial", "inherit"))) || self::isValidUnit($width);
    }

    /**
     *
     * @param string $margin
     * @return boolean
     */
    public static function isValidMargin($margin)
    {
        return (in_array(strtolower($margin), array("auto", "initial", "inherit"))) || self::isValidUnit($margin);
    }

    /**
     *
     * @param string $height
     * @return boolean
     */
    public static function isValidHeight($height)
    {
        return (in_array(strtolower($height), array("auto", "initial", "inherit"))) || self::isValidUnit($height);
    }

    /**
     *
     * @param string $lineHeight
     * @return boolean
     */
    public static function isValidLineHeight($lineHeight)
    {
        return (in_array(strtolower($lineHeight), array("normal", "initial", "inherit"))) || self::isValidUnit($lineHeight);
    }

    /**
     *
     * @param string $borderStyle
     * @return boolean
     */
    public static function isValidBorderStyle($borderStyle)
    {
        return (in_array(strtolower($borderStyle), array("none", "hidden", "dotted", "dashed", "solid", "double", "groove", "ridge", "inset", "outset", "initial", "inherit")));
    }

    /**
     * @param array $arr
     *
     * @return array
     */
    public static function getKeys($arr)
    {
        $keys = array();

        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                array_push($keys, $k);

                unset($v);
            }
        }

        return $keys;
    }
}
