<?php
/** @var array $scriptProperties */
/** @var Text2Image $Text2Image */

if (!$Text2Image = $modx->getService('text2image', 'Text2Image', $modx->getOption('text2image_core_path', null, $modx->getOption('core_path') . 'components/text2image/') . 'model/text2image/', $scriptProperties)) {
    return 'Could not load Text2Image class!';
}

if (!($size && $proportions = $Text2Image->getProportions($size))) {
    return '';
}

$width = $scriptProperties['w'] = $proportions['width'];
$height = $scriptProperties['h'] = $proportions['height'];

$scriptProperties['bg'] = $modx->getOption('bg', $scriptProperties);
$scriptProperties['trp'] = $modx->getOption('trp', $scriptProperties);
$scriptProperties['text'] = $modx->getOption('text', $scriptProperties, $size, true);

if ($scriptProperties['trp'])
    $scriptProperties['bg'] = "#fff";

if (!$scriptProperties['text']) return '';

if (!$Text2Image->initialize($scriptProperties)) return '';

$tpl = $modx->getOption('tpl', $scriptProperties, 'Item');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Output
$output = '';
if ($image = $Text2Image->generateImage()) {
    $output = $modx->getChunk($tpl, array(
        'image' => $image,
        'width' => $Text2Image->getWidth(),
        'height' => $Text2Image->getHeight(),
    ));
}
if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);

    return '';
}

return $output;