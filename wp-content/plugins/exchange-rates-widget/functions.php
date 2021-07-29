<?php

function erw_return_list_languages()
{
    return array(
        'en' => 'English', 
        'ru' => 'Русский', 
        'it' => 'Italiano', 
        'fr' => 'Français', 
        'es' => 'Español', 
        'cn' => '中国', 
        'de' => 'Deutsch',
        'hi' => 'हिन्दी',
        'id' => 'Bahasa Indonesia',
        'ja' => '日本語',
        'pt' => 'Português',
    );
}

function erw_return_language_detected()
{
    $sl = substr(get_bloginfo('language'), 0, 2);

    return (in_array($sl, array_keys(erw_return_list_languages()))) ? $sl : 'en';
}

function erw_return_currency_list()
{
    $contents = file_get_contents(plugin_dir_path(__FILE__).'data/currencies_'.erw_return_language_detected().'.json');

    return json_decode($contents, true);
}

function erw_return_iframe($params, $width, $height, $signature = null, $text = null)
{
    $target_url = strtolower('https://'.$params['fm'].(('en' != $params['lg']) ? '.'.$params['lg'] : '').'.currencyrate.today');

    $url = 'https://currencyrate.today/load-exchangerates?'.http_build_query($params);
    $output = '<iframe src="'.$url.'" height="'.$height.'" width="'.$width.'" frameborder="0" scrolling="no" class="erw-iframe" name="erw-exchange-rates-widget"></iframe>';
    if ($signature) {
        $output .= '<p>'.(($text) ? $text.': ' : '').'<a href="'.$target_url.'" target="_blank" class="erw-base-currency-link">'.$params['fm'].'</a></p>';
    }

    return $output;
}

function erw_print_timezone_list($code, $arr)
{
    $output_string = '';
    foreach ($arr as $v) {
        $output_string .= '<option value="'.$v[0].'"'.(($code == $v[0]) ? ' selected' : '').'>'.$v[1].'</option>'.PHP_EOL;
    }

    echo $output_string;
}

function erw_print_select_options($code, $arr, $o = false)
{
    $output_string = '';
    foreach ($arr as $k => $v) {
        $output_string .= '<option value="'.$k.'"'.(($code == $k) ? ' selected' : '').'>'.((true === $o) ? $k.' - '.$v : $v).'</option>'.PHP_EOL;
    }

    echo $output_string;
}
