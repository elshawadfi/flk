/**
 *  Tab navigation for shortcode generator
 */
(function ($) {
    'use strict';

    $(document).ready(function(){

        $('div.wplmb-nav a').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('.wplmb-nav a').removeClass('nav-tab-active');
            $('.sp-lc-tab-content').removeClass('nav-tab-active');

            $(this).addClass('nav-tab-active');
            $("#"+tab_id).addClass('nav-tab-active');
        })

    });

    // Initializing WP Color Picker
    $('.wpl-color-picker').each(function(){
        $(this).wpColorPicker();
    });

     // On click shortcode copy to clipboard.
  $('.lc-sc-code.selectable').click(function (e) {
    e.preventDefault();
    lc_copyToClipboard($(this));
    lc_SelectText($(this));
    $(this).focus().select();
    jQuery(".lc-after-copy-text").animate({
      opacity: 1,
      top: 36
    }, 300);
    setTimeout(function () {
      jQuery(".lc-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".lc-after-copy-text").animate({
        top: 0
      }, 0);
    }, 2000);
  });

  $('.post-type-sp_lc_shortcodes .column-shortcode input').click(function (e) {
    e.preventDefault();
    /* Get the text field */
    var copyText = $(this);
    /* Select the text field */
    copyText.select();
    document.execCommand("copy");
    jQuery(".lc-after-copy-text").animate({
      opacity: 1,
      top: 36
    }, 300);
    setTimeout(function () {
      jQuery(".lc-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".lc-after-copy-text").animate({
        top: 0
      }, 0);
    }, 2000);
  });
  function lc_copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
  }
  function lc_SelectText(element) {
    var r = document.createRange();
    var w = element.get(0);
    r.selectNodeContents(w);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(r);
  }

})(jQuery);