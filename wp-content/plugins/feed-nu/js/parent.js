(function() {
  var util = {},
  counter = 0,
  remote = null,
  iframe = "s",
  funcs = {},
  postMsg = (function() {
    function responseFromFeed(event) {
      if(event.origin !== remote) { return; };
      funcs[event.data.action] && funcs[event.data.action].apply(undefined, event.data.args);
    };

    window.addEventListener('message', responseFromFeed, false);

    return function(data){
      var data = util.extend(true, {}, data);
      iframe.contentWindow.postMessage( data, remote );
    }
  }());


  util.type = (function toType(global) {
    var c =({}).toString;
    return function(obj) {
      if (obj === global) {
        return "global";
      }
      var test = typeof obj;
      return (test !="object")?test:c.call(obj).slice(8,-1).toLowerCase()
    }
  })(this);

  util.extend = function() {
    var src, copyIsArray, copy, name, options, clone, type,
      target = arguments[0] || {},
      i = 1,
      length = arguments.length,
      deep = false;

    // Handle a deep copy situation
    if ( util.type(target) === "boolean" ) {
      deep = target;
      target = arguments[1] || {};
      // skip the boolean and the target
      i = 2;
    }

    // Handle case when target is a string or something (possible in deep copy)
      if (! /object|function/.test( typeof target ) ) {
      target = {};
    }

    // extend util itself if only one argument is passed
    if ( length === i ) {
      target = this;
      --i;
    }

    for ( ; i < length; i++ ) {
      // Only deal with non-null/undefined values
      if ( (options = arguments[ i ]) != null ) {
        // Extend the base object
        for ( name in options ) {
          src = target[ name ];
          copy = options[ name ];
                  copyType = util.type(copy);

          // Prevent never-ending loop
          if ( target === copy ) {
            continue;
          }

          // Recurse if we're merging plain objects or arrays
          if ( deep && copy && ( copyType === "object" || (copyIsArray = copyType === "array") ) ) {
            if ( copyIsArray ) {
              copyIsArray = false;
              clone = src && util.type(src) === "array" ? src : [];
            } else {
                          clone = src && util.type(src) === "object" ? src : {};
            }

            // Never move original objects, clone them
            target[ name ] = util.extend( deep, clone, copy );

          // Don't bring in undefined values
          } else if ( copyType === "function" ) {
            target[ name ] = copy;
            funcId = ['func', ++counter].join('');
            funcs[funcId] = copy;
            target[ name ] = funcId;
          } else if ( copy !== undefined ) {
            target[ name ] = copy;
          }
        }
      }
    }

    // Return the modified object
    return target;
  };




  jQuery(function($){
    remote = window.feed_remote_url;
    var
    selectImage = function(meta, description) {

      //If the frame already exists, reopen it
      window.custom_file_frame && window.custom_file_frame.close();

      //Create WP media frame.
      window.custom_file_frame = wp.media.frames.customHeader = wp.media({
        //Title of media manager frame
        title: description,
        library: {
          type: 'image'
        },
        button: {
          //Button text
          text: "Select this image"
        },
        //Do not allow multiple files, if you want multiple, set true
        multiple: false
      });

      //callback for selected image
      window.custom_file_frame.on('select', function() {
        var attachment, xhr;

        attachment = window.custom_file_frame.state().get('selection').first().toJSON();
        xhr = new XMLHttpRequest();
        xhr.open('GET', attachment.url, true);
        xhr.responseType = 'blob';

        xhr.onload = function(e) {
          if (this.status == 200) {
            var blob = this.response;
            postMsg({ updateMeta: meta, blob: blob });
          }
        };

        xhr.send();
      });

      window.custom_file_frame.open();
    },

    token = window.feed_settings.token;



    iframe = $('iframe[src="'+remote+'"]')[0];
    // Wait for iframe to finnish loading
    iframe.addEventListener('load', function() {
 
      postMsg({
        imagePicker: selectImage,
        defaultPackageName: window.feed_settings.defaultPackageName,
        login: [token, function(error) {
          if(error) { return; }

          if(window.feed_migrate_settings != undefined && window.feed_migrate_settings != null) {

            var packageName = window.feed_migrate_settings.Android.General.packagename.UserValue;
            
            if(packageName != undefined && packageName != null && packageName.length > 1) {
              packageName = 'com.warting.blogg.' + packageName;
            }
            else {
              packageName = window.feed_settings.defaultPackageName
            }

            var currentVersionCode = window.feed_migrate_settings.Android.CurrentVersionCode;
            var currentVersionName = window.feed_migrate_settings.Android.CurrentVersion;

            var data = {
              packageName : packageName,
              buildNumber : currentVersionCode,
              meta : [
                {name: "versionName", value: currentVersionName}
              ]
            };

            try {
              data.meta.push({name: "border", value: "#" + window.feed_migrate_settings.Android.Color.background.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "background", value: "#" + window.feed_migrate_settings.Android.Color.background.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "list_background", value: "#" + window.feed_migrate_settings.Android.Color.listbackground.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "text", value: "#" + window.feed_migrate_settings.Android.Color.text.UserValue});
            } catch(e) {};

            
            try {
              data.meta.push({name: "feed_description", value: window.feed_migrate_settings.Android.Language.feedDescription.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "app_name", value: window.feed_migrate_settings.Android.Language.app_name.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "settings", value: window.feed_migrate_settings.Android.Language.settings.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "sync_now", value: window.feed_migrate_settings.Android.Language.sync_now.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "reset_database", value: window.feed_migrate_settings.Android.Language.reset_database.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "mark_all_as_read", value: window.feed_migrate_settings.Android.Language.mark_all_as_read.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "read_more", value: window.feed_migrate_settings.Android.Language.read_more.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "start_reading", value: window.feed_migrate_settings.Android.Language.start_reading.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "no_unread_items", value: window.feed_migrate_settings.Android.Language.no_unread_items.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "share", value: window.feed_migrate_settings.Android.Language.share.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "share_title", value: window.feed_migrate_settings.Android.Language.share_title.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "share_message", value: window.feed_migrate_settings.Android.Language.share_message.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "menu_about", value: window.feed_migrate_settings.Android.Language.menu_about.UserValue});
            } catch(e) {};
            try {
              data.meta.push({name: "about_text", value: window.feed_migrate_settings.Android.Language.about_text.UserValue});
            } catch(e) {};


            try {
              data.meta.push({name: "article_css", value: window.feed_migrate_settings.Android.General.ArticleCSS.UserValue});
            } catch(e) {};
            
            try {
              data.meta.push({name: "app_date_format", value: window.feed_migrate_settings.Global.app_date_format.UserValue});
            } catch(e) {};
            

            postMsg({
               // Kod som kan köras när man är inloggad
              createApp: [data, function(error) {
                // Kod som körs efter att appen har skapas 

                var data = {
                  action: 'feed_migrated'
                };
                jQuery.post(ajaxurl, data, function(response) {
                  //alert('Got this from the server: ' + response);
                });

              }]
            })
          }

        }]
      });

    }, false);

  });

}());