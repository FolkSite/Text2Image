<?php

/**
 * The base class for Text2Image.
 */
class Text2Image
{
    /* @var modX $modx */
    public $modx;

    public $ImageGenerator;

    /* @var array The array of config */
    public $config = array();

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('text2image_core_path', $config, $this->modx->getOption('core_path') . 'components/text2image/');
        $assetsPath = $this->modx->getOption('minishop2.assets_path', $config, $this->modx->getOption('assets_path') . 'components/text2image/');
        $assetsUrl = $this->modx->getOption('text2image_assets_url', $config, $this->modx->getOption('assets_url') . 'components/text2image/');
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
//			'cssUrl' => $assetsUrl . 'css/',
//			'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'corePath' => $corePath,
            'assetsPath' => $assetsPath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
//			'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
//			'processorsPath' => $corePath . 'processors/'
        ), $config);

        $this->modx->addPackage('text2image', $this->config['modelPath']);
        $this->modx->lexicon->load('text2image:default');
    }

    public function initialize($scriptProperties = array()) {
        $this->config = array_merge($this->config, $scriptProperties);
        $pls = $this->makePlaceholders($this->config);
        $this->config['fontFile'] = (str_replace($pls['pl'], $pls['vl'], $this->config['fontFile']));

        if (!$this->modx->loadClass('text2image.imagegenerator', $this->config['modelPath'], true, true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[Text2Image]: Could not load class ImageGenerator!");
            return false;
        }
        $this->ImageGenerator = new ImageGenerator($this->modx, $this->config);

        return true;
    }

    public function generateImage() {
        return $this->ImageGenerator->generateImage();
    }

    public function getProportions($size) {
        if (preg_match('/(\d+)x(\d+)/', $size, $proportions))
            $output = array('width' => $proportions['1'], 'height' => $proportions['2']);
        else
            $output = false;
        return $output;
    }

    public function getWidth() {
        return $this->ImageGenerator->width;
    }

    public function getHeight() {
        return $this->ImageGenerator->height;
    }

    /**
     * Method for transform array to placeholders
     *
     * @var array $array With keys and values
     * @var string $prefix Placeholders prefix
     *
     * @return array $array Two nested arrays With placeholders and values
     */
    public function makePlaceholders(array $array = array(), $prefix = '') {
        $result = array('pl' => array(), 'vl' => array());
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result, $this->makePlaceholders($v, $k . '.'));
            } else {
                $result['pl'][$prefix . $k] = '[[+' . $prefix . $k . ']]';
                $result['vl'][$prefix . $k] = $v;
            }
        }
        return $result;
    }
}