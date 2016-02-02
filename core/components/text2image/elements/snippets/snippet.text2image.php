<?php
/** @var array $scriptProperties */
/** @var Text2Image $Text2Image */

if (!$Text2Image = $modx->getService('text2image', 'Text2Image', $modx->getOption('text2image_core_path', null, $modx->getOption('core_path') . 'components/text2image/') . 'model/text2image/', $scriptProperties)) {
    return 'Could not load Text2Image class!';
}

$w = $scriptProperties['w'] = $modx->getOption('w', $scriptProperties);
$h = $scriptProperties['h'] = $modx->getOption('h', $scriptProperties);

if (!isset($text, $bg))
    $scriptProperties['bg'] = '#ccc';

if ($scriptProperties['bg'])
    $scriptProperties['trp'] = false;

$scriptProperties['bg'] = $modx->getOption('bg', $scriptProperties, '#fff', true);

$size = (isset($w, $h)) ? $w . 'x' . $h : '';
$scriptProperties['text'] = $modx->getOption('text', $scriptProperties, $size, true);

if (!$scriptProperties['text']) return '';

if (!$Text2Image->initialize($scriptProperties)) return '';

$tpl = $modx->getOption('tpl', $scriptProperties, 'Item');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Output
$output = '';
if ($image = $Text2Image->generateImage()) {
    $output = $modx->getChunk($tpl, array(
        'image' => $image,
    ));
    return $output;
}

if (!empty($toPlaceholder)) {
    $modx->setPlaceholder($toPlaceholder, $output);

    return '';
}

return $output;