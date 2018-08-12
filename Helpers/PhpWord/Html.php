<?php

namespace kosuhin\Yii2BaseKit\Helpers\PhpWord;

class Html extends \PhpOffice\PhpWord\Shared\Html
{
    public static function addHtml($element, $html, $fullHTML = false, $preserveWhiteSpace = true)
    {
        /*
         * @todo parse $stylesheet for default styles.  Should result in an array based on id, class and element,
         * which could be applied when such an element occurs in the parseNode function.
         */

        // Preprocess: remove all line ends, decode HTML entity,
        // fix ampersand and angle brackets and add body tag for HTML fragments
        $html = str_replace(array("\n", "\r"), '', $html);
        $html = str_replace(array('&lt;', '&gt;', '&amp;'), array('_lt_', '_gt_', '_amp_'), $html);
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        $html = str_replace('&', '&amp;', $html);
        $html = str_replace(array('_lt_', '_gt_', '_amp_'), array('&lt;', '&gt;', '&amp;'), $html);

        if (false === $fullHTML) {
            $html = '<body>' . $html . '</body>';
        }

        // Load DOM
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = $preserveWhiteSpace;
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        $node = $dom->getElementsByTagName('body');

        self::parseNode($node->item(0), $element);
    }
}