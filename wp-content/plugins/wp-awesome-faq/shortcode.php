<script>
	(function() {
    tinymce.PluginManager.add('jeweltheme_faq_button', function( editor, url ) {
        editor.addButton( 'jeweltheme_faq_button', {
            title: 'Awesome FAQ Button',
            type: 'menubutton',
            icon: '/icon.png',
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
                            title: 'Insert header tag',
                            body: [{
                                type: 'textbox',
                                name: 'title',
                                label: 'Your title'
                            },
                            {
                                type: 'textbox',
                                name: 'id',
                                label: 'Header anchor'
                            },
                            {
                                type: 'listbox', 
                                name: 'level', 
                                label: 'Header level', 
                                'values': [
                                    {text: <?php echo "Liton";?>, value: '3'},
                                    {text: '<h4>', value: '4'},
                                    {text: '<h5>', value: '5'},
                                    {text: '<h6>', value: '6'}
                                ]
                            }],
                            onsubmit: function( e ) {
                                editor.insertContent( '<h' + e.data.level + ' id="' + e.data.id + '">' + e.data.title + '</h' + e.data.level + '>');
                            }
                        });
                    }
                }
           ]
        });
    });
})();	
</script>
