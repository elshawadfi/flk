(function() {
	tinymce.PluginManager.add('sp_mce_button', function( editor, url ) {
		editor.addButton('sp_mce_button', {
			text: false,
            icon: false,
            image: url + '/lcp-logo.png',
            tooltip: 'Logo Carousel',
            onclick: function () {
                editor.windowManager.open({
                    title: 'Insert Shortcode',
					width: 400,
					height: 100,
					body: [
						{
							type: 'listbox',
							name: 'listboxName',
                            label: 'Select Shortcode',
							'values': editor.settings.spShortcodeList
						}
					],
					onsubmit: function( e ) {
						editor.insertContent( '[logocarousel id="' + e.data.listboxName + '"]');
					}
				});
			}
		});
	});
})();