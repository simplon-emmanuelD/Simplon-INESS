(function() {
    tinymce.PluginManager.add('jeweltheme_faq_button', function( editor, url ) {
        editor.addButton( 'jeweltheme_faq_button', {
            title: 'Awesome FAQ Button',
            type: 'menubutton',
            icon: 'faq-icon',
            menu: [
                {
                    text: 'All FAQ',
                    value: '[faq]',
                    onclick: function() {
                        editor.insertContent(this.value());
                    }
                },
                {
                    text: 'FAQ Category',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'FAQ Category by ID',
                            body: [{
                                type: 'textbox',
                                name: 'title',
                                label: 'Category ID'
                            }],
                            onsubmit: function( e ) {
                                editor.insertContent( '[faq cat_id="' + e.data.title + '"]');
                            }
                        });
                    }
                }
           ]
        });
    });
})();