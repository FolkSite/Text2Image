<?php

$properties = array();

$tmp = array(
    'fontSize' => array(
        'type' => 'numberfield',
        'value' => 12,
    ),
    'fontFile' => array(
        'type' => 'textfield',
        'value' => '[[+assetsPath]]fonts/arial.ttf',
    ),
    'angle' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'padding' => array(
        'type' => 'numberfield',
        'value' => 0,
    ),
    'color' => array(
        'type' => 'textfield',
        'value' => '#000',
    ),
    'bg' => array(
        'type' => 'textfield',
        'value' => '',
    ),
    'break' => array(
        'type' => 'numberfield',
        'value' => 250,
    ),
    'trp' => array(
        'type' => 'combo-boolean',
        'value' => true,
    ),
    'format' => array(
        'type' => 'textfield',
        'value' => 'png',
    ),
    'tpl' => array(
        'type' => 'textfield',
        'value' => 'tpl.Text2Image.item',
    ),
    'toPlaceholder' => array(
        'type' => 'combo-boolean',
        'value' => false,
    ),
);

foreach ($tmp as $k => $v) {
    $properties[] = array_merge(
        array(
            'name' => $k,
            'desc' => PKG_NAME_LOWER . '_prop_' . $k,
            'lexicon' => PKG_NAME_LOWER . ':properties',
        ), $v
    );
}

return $properties;