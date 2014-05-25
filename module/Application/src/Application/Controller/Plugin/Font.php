<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Font extends AbstractPlugin
{

    /**
    * Return length of generated string in points
    *
    * @param string                     $text
    * @param Zend_Pdf_Resource_Font|Zend_Pdf_Page     $font
    * @param int                         $fontSize
    * @return double
    */
    public static function getTextWidth($text, $resource, $fontSize = null, $encoding = null) {
        if( $encoding == null ) $encoding = 'UTF-8';

        if( $resource instanceof \ZendPdf\Page ){
            $font = $resource->getFont();
            $fontSize = $resource->getFontSize();
        }elseif( $resource instanceof \ZendPdf\Resource\Font ){
            $font = $resource;
            if( $fontSize === null ) throw new Exception('The fontsize is unknown');
        }

        $drawingText = $text;//iconv ( '', $encoding, $text );
        $characters = array ();
        for($i = 0; $i < strlen ( $drawingText ); $i ++) {
            $characters [] = ord ( $drawingText [$i] );
        }
        $glyphs = $font->glyphNumbersForCharacters ( $characters );
        $widths = $font->widthsForGlyphs ( $glyphs );

        $textWidth = (array_sum ( $widths ) / $font->getUnitsPerEm ()) * $fontSize;
        return $textWidth;
    }
}