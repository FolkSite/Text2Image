<?php

/**
 * Class Text2ImageMainController
 */
abstract class Text2ImageMainController extends modExtraManagerController {
	/** @var Text2Image $Text2Image */
	public $Text2Image;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('text2image_core_path', null, $this->modx->getOption('core_path') . 'components/text2image/');
		require_once $corePath . 'model/text2image/text2image.class.php';

		$this->Text2Image = new Text2Image($this->modx);
		//$this->addCss($this->Text2Image->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->Text2Image->config['jsUrl'] . 'mgr/text2image.js');
		$this->addHtml('
		<script type="text/javascript">
			Text2Image.config = ' . $this->modx->toJSON($this->Text2Image->config) . ';
			Text2Image.config.connector_url = "' . $this->Text2Image->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('text2image:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends Text2ImageMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}