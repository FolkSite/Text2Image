<?php

/**
 * generateImage class
 */
class ImageGenerator
{
    public $image;
    public $modx;
    public $config = array();
    public $supportedFormats = array();

    function __construct($modx, $config) {
        $this->modx = $modx;
        $this->config = $config;
        $this->supportedFormats = array(
            'png',
            'jpeg',
            'gif'
        );
    }

    function generateImage() {
        $fontSize = $this->config['fontSize'];
        $fontFile = $this->config['fontFile'];
        $angle = $this->config['angle'];
        $padding = $this->config['padding'];
        $color = $this->_hex2RGB($this->config['color']);
        $bg = $this->_hex2RGB($this->config['bg']);
        $trp = $this->config['trp'];
        $format = $this->config['format'];
        $width = $this->config['w'];
        $height = $this->config['h'];
        $text = $this->config['text'];

        if (!in_array($format, $this->supportedFormats)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[Text2Image]: Unsupported format. Select supported one(" . implode(', ', $this->supportedFormats) . ").");
            return false;
        }
        if (!is_file($this->config['fontFile'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[Text2Image]: Font file not found at following path: '{$this->config['fontFile']}'.");
            return false;
        }

        $bBox = $this->_calculateTextBox($text, $fontFile, $fontSize, $angle);

        $width = $width ?: $bBox["width"] + $padding;
        $height = $height ?: $bBox["height"] + $padding;

        $x = $bBox["left"] + ($width / 2) - ($bBox["width"] / 2);
        $y = $bBox["top"] + ($height / 2) - ($bBox["height"] / 2);
        $im = imagecreate($width, $height);

        $bgColor = imagecolorallocate($im, $bg['red'], $bg['green'], $bg['blue']);

        if ($trp && in_array($format, array('png', 'gif')))
            imagecolortransparent($im, $bgColor);

        $textColor = imagecolorallocate($im, $color['red'], $color['green'], $color['blue']);


        imagettftext(
            $im,
            $fontSize,
            $angle,
            $x,
            $y,
            $textColor,
            $fontFile,
            $text
        );

        $rawImageBytes = $this->_getImageBytes($im, $format);
        return "data:image/$format;base64," . base64_encode($rawImageBytes);
    }

    /**
     * @param $im
     * @return string rawImageBytes
     */
    private function _getImageBytes($im, $format = 'png') {
        ob_start();
        switch ($format) {
            case 'png':
                imagepng($im);
                break;
            case 'jpeg':
                imagejpeg($im);
                break;
            case 'gif':
                imagegif($im);
                break;
        }
        imagedestroy($im);
        return ob_get_clean();
    }

    /**
     * Convert a hexa decimal color code to its RGB equivalent
     *
     * @param string $hexStr (hexadecimal color value)
     * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
     * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
     * @return array or string (depending on second parameter. Returns False if invalid hex color value)
     */
    private function _hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
        $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal = hexdec($hexStr);
            $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue'] = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }

    private function _calculateTextBox($text, $fontFile, $fontSize, $fontAngle) {
        $rect = imagettfbbox($fontSize, $fontAngle, $fontFile, $text);
        $minX = min(array($rect[0], $rect[2], $rect[4], $rect[6]));
        $maxX = max(array($rect[0], $rect[2], $rect[4], $rect[6]));
        $minY = min(array($rect[1], $rect[3], $rect[5], $rect[7]));
        $maxY = max(array($rect[1], $rect[3], $rect[5], $rect[7]));

        return array(
            "left" => abs($minX) - 1,
            "top" => abs($minY) - 1,
            "width" => $maxX - $minX,
            "height" => $maxY - $minY,
            "box" => $rect
        );
    }

}