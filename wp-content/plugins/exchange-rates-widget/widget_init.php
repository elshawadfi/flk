<?php
/**
 * @version 1.2.0
 */
/*
    Plugin Name: Exchange Rates Widget
    Plugin URI: https://currencyrate.today/exchangerates-widget
    Description: Simple and powerful currency exchange rates widget for your website or blog. Included <strong>195+ world currencies</strong> with <strong>popular cryptocurrencies</strong>. Updates each hour automatically. Multi Language support: English, Русский, Italiano, Français, Español, Deutsch, 中国, Português, 日本語, Bahasa Indonesia, हिन्दी.
    Version: 1.2.0
    Author: CurrencyRate.today
    Author URI: https://currencyrate.today
    License: GPLv2 or later
    Text Domain: erw_exchange_rates_widget
*/

/*
    Load functions
*/
require_once 'functions.php';
require_once 'languages.php';

/*
    Init widget
*/
add_action('widgets_init', function () {
    register_widget('erw_exchange_rates_widget');
});

/*
    Shortcode
*/
function callback_erw_exchange_rates_widget($atts, $content = null)
{
    $_lg = erw_return_language_detected();

    extract(shortcode_atts(array(
        'size_width' => '100%',
        'fm' => 'EUR',
        'to' => 'USD,GBP,AUD,CNY,JPY,RUB',
        'st' => 'info',
        'lg' => $_lg,
        'tz' => 0,
        'cd' => 0,
        'am' => 100,
    ), $atts, 'erw_exchange_rates_widget'));

    $lg = (empty($lg)) ? $_lg : ((in_array($lg, array_keys(erw_return_list_languages()))) ? $lg : 'en');
    $fm = (empty($fm)) ? 'EUR' : $fm;
    $to = (empty($to)) ? 'USD,GBP,AUD,CNY,JPY,RUB' : $to;

    $height = (90 + (count(explode(',', $to)) * 37));
    $params = array(
      'fm' => $fm,
      'to' => $to,
      'st' => $st,
      'lg' => $lg,
      'tz' => $tz,
      'cd' => $cd,
      'am' => $am,
      'wp' => 'erw_sc',
    );

    $language = erw_widget_language($lg);

    $output = erw_return_iframe($params, $size_width, $height, 1, $language['title']);

    return $output;
}

add_shortcode('erw_exchange_rates_widget', 'callback_erw_exchange_rates_widget');

/*
    Class of widget
*/
class erw_exchange_rates_widget extends WP_Widget
{
    /*
        Register widget with WordPress.
    */
    public function __construct()
    {
        parent::__construct(
            'erw_exchange_rates_widget',
            esc_html__('Exchange Rates Widget', 'erw_exchange_rates_widget'),
            array(
                'description' => esc_html__('Displays an exchange rates online.', 'erw_exchange_rates_widget'),
            )
        );
    }

    /*
        Update the widget settings.
    */
    public function update($new_instance, $old_instance)
    {
        $currency_list = erw_return_currency_list();

        $instance = $old_instance;

        $instance['fm'] = sanitize_text_field($new_instance['fm']);
        $instance['to'] = sanitize_text_field($new_instance['to']);
        $instance['lg'] = sanitize_text_field($new_instance['lg']);
        $instance['tz'] = sanitize_text_field($new_instance['tz']);
        $instance['st'] = sanitize_text_field($new_instance['st']);
        $instance['cd'] = sanitize_text_field($new_instance['cd']);
        $instance['am'] = sanitize_text_field($new_instance['am']);
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['signature'] = sanitize_text_field($new_instance['signature']);
        $instance['size_width'] = sanitize_text_field($new_instance['size_width']);
        $instance['currency_name'] = (1 == $new_instance['cd']) ? $new_instance['fm'] : $currency_list[$new_instance['fm']];

        return $instance;
    }

    /*
        Update the widget settings.
        Make use of the get_field_id() and get_field_name() function when creating your form elements. This handles the confusing stuff.
    */
    public function form($instance)
    {
        /*
            Default widget settings
        */
        $defaults = array(
            'currency_name' => 'Euro',
            'title' => $this->_lang('title'),
            'size_width' => '100%',
            'signature' => 1,
            'fm' => 'EUR',
            'to' => 'USD,GBP,AUD,CNY,JPY,RUB',
            'lg' => erw_return_language_detected(),
            'st' => 'info',
            'tz' => 0,
            'cd' => 0,
            'am' => 100,
        );

        if (empty($instance)) {
            $instance = $defaults;
        }

        $currency_list = erw_return_currency_list();

        $fm = sanitize_text_field($instance['fm']);
        $to = sanitize_text_field($instance['to']);
        $lg = sanitize_text_field($instance['lg']);
        $tz = sanitize_text_field($instance['tz']);
        $st = sanitize_text_field($instance['st']);
        $cd = sanitize_text_field($instance['cd']);
        $am = sanitize_text_field($instance['am']);
        $title = sanitize_text_field($instance['title']);
        $signature = sanitize_text_field($instance['signature']);
        $size_width = sanitize_text_field($instance['size_width']);

        echo '<p><label for="',$this->get_field_id('title'),'">',$this->_lang('heading'),':',
             '<input id="',$this->get_field_id('title'),'" type="text" name="',$this->get_field_name('title'),'" value="',$title,'" style="width:100%"></label></p>';

        echo '<p><label for="',$this->get_field_id('fm'),'">',$this->_lang('base_currency'),':',
             '<select id="',$this->get_field_id('fm'),'" name="',$this->get_field_name('fm'),'" style="width:100%">',
             erw_print_select_options($fm, $currency_list, true),
             '</select></label></p>';

        echo '<p><label for="',$this->get_field_id('to'),'"><a href="https://currencyrate.today/different-currencies" target="_blank">',$this->_lang('сodes_currencies'),'</a> <small>(',$this->_lang('сodes_currencies_open'),')</small>:',
             '<input id="',$this->get_field_id('to'),'" type="text" name="',$this->get_field_name('to'),'" value="',$to,'" style="width:100%"></label></p>';

        echo '<p><label for="',$this->get_field_id('am'),'">',$this->_lang('amount'),':',
             '<input id="',$this->get_field_id('am'),'" type="text" name="',$this->get_field_name('am'),'" value="',$am,'" style="width:100%"></label></p>';

        echo '<p><label for="',$this->get_field_id('lg'),'">',$this->_lang('language'),':',
             '<select id="',$this->get_field_id('lg'),'" name="',$this->get_field_name('lg'),'" style="width:100%">',
             erw_print_select_options($lg, erw_return_list_languages()),
             '</select></label></p>';

        echo '<p><label for="',$this->get_field_id('tz'),'">',$this->_lang('timezone'),':',
             '<select id="',$this->get_field_id('tz'),'" name="',$this->get_field_name('tz'),'" style="width:100%">',
             erw_print_timezone_list($tz, $this->_timezones),
             '</select></label></p>';

        echo '<p><label for="',$this->get_field_id('st'),'">',$this->_lang('theme'),':',
             '<select id="',$this->get_field_id('st'),'" name="',$this->get_field_name('st'),'" style="width:100%">',
             erw_print_select_options($st, $this->_lang('themes')),
             '</select></label></p>';

        echo '<p><label for="',$this->get_field_id('size_width'),'">',$this->_lang('size_width'),':',
             '<select id="',$this->get_field_id('size_width'),'" name="',$this->get_field_name('size_width'),'" style="width:100%">',
             erw_print_select_options($size_width, $this->_lang('sizes')),
             '</select></label></p>';

        echo '<p><label for="',$this->get_field_id('cd'),'">',
             '<input type="checkbox" ',checked($cd, 1),' id="',$this->get_field_id('cd'),'" name="',$this->get_field_name('cd'),'" value="1">',
             $this->_lang('currency_code'),
             '</label></p>';

        echo '<p><label for="',$this->get_field_id('signature'),'">',
             '<input type="checkbox" ',checked($signature, 1),' id="',$this->get_field_id('signature'),'" name="',$this->get_field_name('signature'),'" value="1">',
             $this->_lang('signature'),
             '</label></p>';

        $widget_params = array(
            'lg' => $lg,
            'tz' => $tz,
            'fm' => $fm,
            'to' => $to,
            'st' => $st,
            'cd' => $cd,
            'am' => $am,
            'wp' => 'erw',
        );

        echo '<hr>',
             '<div><h3>',$this->_lang('preview'),'</h3>',
             $this->_output_widget($widget_params, $size_width),
             '</div>';

        $short_attrs = '';
        unset($widget_params['wp']);
        foreach ($widget_params as $key => $value) {
            $short_attrs .= $key.'="'.$value.'" ';
        }

        echo '<hr>',
             '<div><h3>',$this->_lang('generated_shortcode'),'</h3>',
             '<textarea onclick="this.select()" style="width:100%;height:80px;">[erw_exchange_rates_widget ',trim($short_attrs),'][/erw_exchange_rates_widget]</textarea></div>',
             '<hr>';
    }

    /*
        Output widget
    */
    public function widget($args, $instance)
    {
        // Register style
        wp_register_style('erw-exchange-rates-widget', plugin_dir_url(__FILE__).'assets/frontend.css');
        wp_enqueue_style('erw-exchange-rates-widget', plugin_dir_url(__FILE__).'assets/frontend.css');

        // Get values
        extract($args);

        $currency_list = erw_return_currency_list();

        $lg = sanitize_text_field($instance['lg']);
        $tz = sanitize_text_field($instance['tz']);
        $fm = sanitize_text_field($instance['fm']);
        $to = sanitize_text_field($instance['to']);
        $st = sanitize_text_field($instance['st']);
        $cd = sanitize_text_field($instance['cd']);
        $am = sanitize_text_field($instance['am']);
        $title = sanitize_text_field($instance['title']);
        $signature = sanitize_text_field($instance['signature']);
        $size_width = sanitize_text_field($instance['size_width']);

        //$target_url = strtolower('http://'.$fm.(('en' != $lg) ? '.'.$lg : '').'.currencyrate.today');

        echo $args['before_widget'];

        // Title
        echo $args['before_title'].$title.$args['after_title'];

        // Load language
        $language = erw_widget_language($lg);

        // Output
        echo $this->_output_widget(array(
            'lg' => $lg,
            'tz' => $tz,
            'fm' => $fm,
            'to' => $to,
            'st' => $st,
            'cd' => $cd,
            'am' => $am,
            'wp' => 'erw',
        ), $size_width, $signature, $language['title']);

        echo $args['after_widget'];
    }

    // Private

    /*
        Timezone list
    */
    private $_timezones = array(
      array('-12', '(GMT -12:00) Eniwetok, Kwajalein'),
      array('-11', '(GMT -11:00) Midway Island, Samoa'),
      array('-10', '(GMT -10:00) Hawaii'),
      array('-9', '(GMT -9:00) Alaska'),
      array('-8', '(GMT -8:00) Pacific Time (US &amp; Canada)'),
      array('-7', '(GMT -7:00) Mountain Time (US &amp; Canada)'),
      array('-6', '(GMT -6:00) Central Time (US &amp; Canada), Mexico City'),
      array('-5', '(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima'),
      array('-4', '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz'),
      array('-3.5', '(GMT -3:30) Newfoundland'),
      array('-3', '(GMT -3:00) Brazil, Buenos Aires, Georgetown'),
      array('-2', '(GMT -2:00) Mid-Atlantic'),
      array('-1', '(GMT -1:00 hour) Azores, Cape Verde Islands'),
      array('0', '(GMT) Western Europe Time, London, Lisbon, Casablanca'),
      array('1', '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris'),
      array('2', '(GMT +2:00) Kaliningrad, South Africa'),
      array('3', '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg'),
      array('3.5', '(GMT +3:30) Tehran'),
      array('4', '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi'),
      array('4.5', '(GMT +4:30) Kabul'),
      array('5', '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent'),
      array('5.5', '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi'),
      array('6', '(GMT +6:00) Almaty, Dhaka, Colombo'),
      array('7', '(GMT +7:00) Bangkok, Hanoi, Jakarta'),
      array('8', '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong'),
      array('9', '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
      array('9.5', '(GMT +9:30) Adelaide, Darwin'),
      array('10', '(GMT +10:00) Eastern Australia, Guam, Vladivostok'),
      array('11', '(GMT +11:00) Magadan, Solomon Islands, New Caledonia'),
      array('12', '(GMT +12:00) Wellington, Auckland, New Zealand'),
    );

    /*
        Output widget
    */
    private function _output_widget($params, $width, $signature = null, $text = null)
    {
        $height = (90 + (count(explode(',', $params['to'])) * 37));
        $output = erw_return_iframe($params, $width, $height, $signature, $text);

        return $output;
    }

    /*
        Load languages text
    */
    private function _lang($value)
    {
        $_erw_widget_language = erw_widget_language(erw_return_language_detected());

        return $_erw_widget_language[$value];
    }
}
