/**
 * Tonjoo Fluid Responsive Slideshow
 * Copyright 2013, Tonjoo
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

;(function($) {

    $.fn.frs = function(options) {

        //Defaults to extend options
        var defaults = {
            'animation': 'horizontal-slide', // horizontal-slide, vertical-slide, fade
            'animationSpeed': 600, // how fast animtions are
            'timer': false, // true or false to have the timer
            'advanceSpeed': 4000, // if timer is enabled, time between transitions 
            'pauseOnHover': true, // if you hover pauses the slider
            'startClockOnMouseOut': false, // if clock should start on MouseOut
            'startClockOnMouseOutAfter': 1000, // how long after MouseOut should the timer start again
            'directionalNav': true, // manual advancing directional navs
            'captions': true, // do you want captions?
            'captionAnimation': 'fade', // fade, slideOpen, none
            'captionAnimationSpeed': 600, // if so how quickly should they animate in
            'bullets': false, // true or false to activate the bullet navigation
            'bulletThumbs': false, // thumbnails for the bullets
            'bulletThumbLocation': '', // location from this file where thumbs will be
            'afterSlideChange': function() {}, // empty function 
            'navigationSmallTreshold': 650,
            'navigationSmall': false,
            'skinClass': 'default',
            'width': 650,
            'height': 350,
            'fullWidth': false,
            'minHeight': 300,
            'maxHeight': 0, // '0' (zero) to unlimited
            'sbullets': false,
            'sbulletsItemWidth': 200,                        
            'continousSliding': false,
            'jsOnly': false,
            'slideParameter': []
        };


        // Extend those options
        var options = $.extend(defaults, options);

        frs_id = "#" + this.attr("id") + "-slideshow";
       
        return this.each(function() {

            // ==============
            // ! SETUP   
            // ==============
            // Global Variables
            var activeSlide = 0,
                activeSlideContinous = 0,
                numberSlides = 0,
                frsWidth,
                frsHeight,
                locked,
                caption_position,
                timeout;

            // Initialize
            var slideWrapper = $(this).children('.frs-slide-img-wrapper').addClass('frs-slide-img-wrapper');
            var frs = slideWrapper.wrap('<div class="frs-slideshow-content" />').parent();            
            var frsWrapper = frs.wrap('<div id="' + $(this).attr("id") + '-slideshow" class="frs-wrapper ' + options.skinClass.toLowerCase() + '" />').parent();
            var first_run = true
            var old_responsive_class = 'responsive-full'
            var responsiveClassLock = false

            //Lock slider before all content loaded
            frs_slider_lock();

            // Initialize and show slider after all content loaded
            var imgWidth = [],
                imgHeight = [],
                imgCount = 0;

            $(slideWrapper.children()).imagesLoaded()
                .progress( function( instance, image ) {
                    // collecting slide img original size
                    if($(image.img).parent().attr('class') == 'frs-slide-img')
                    {
                        imgWidth[imgCount] = image.img.width;
                        imgHeight[imgCount] = image.img.height;

                        imgCount++;
                    }
                })
                .always( function( instance ) {
                    
                    $(window).trigger('resize.frs-slideshow-container', true);

                    slideWrapper.fadeIn(function(){
                        $(this).css({"display": "block"});
                    })

                    slideWrapper.children().fadeIn(function(){
                        $(this).css({"display": "block"});
                    })
                    
                    frsWrapper.children('.frs-slideshow-content').fadeIn(function(){
                        $(this).css({"display": "block"});
                    })

                    frsWrapper.children('.frs-timer').fadeIn(function(){
                        $(this).css({"display": "block"});
                    })

                    frsWrapper.children('.frs-slider-nav').fadeIn(function(){
                        $(this).css({"display": "block"});
                    })

                    frsWrapper.children('.frs-bullets-wrapper').fadeIn(function(){
                        $(this).css({"display": "block"});

                        //unlock event in last displayed element
                        // if(options.timer) frs_slider_unlock();
                        frs_slider_unlock();

                        // hide the loader image
                        frsWrapper.children('.frs-slideshow-content').css('background-image','none');
                    })
                })


            frs.css({
                'height': options.height,
                'max-width': options.width
            });

            frsWrapper.parent().css({
                'height': options.height,
                'max-width': options.width
            });

            // Full width
            if(options.fullWidth == true)
            {
                frs.css({
                    'max-width': '100%'
                });

                frsWrapper.parent().css({
                    'max-width': '100%'
                });
            }


            frs.add(frsWidth)

            // Collect all slides and set slider size of largest image
            var slides = slideWrapper.children('div');

            // Count slide
            slides.each(function(index,slide) {

                numberSlides++;
            });

            // Animation locking functions
            function frs_slider_unlock() {
                locked = false;
            }

            function frs_slider_lock() {
                locked = true;
            }

            // If there is only a single slide remove nav, timer and bullets
            if (slides.length == 1) {
                options.directionalNav = false;
                options.timer = false;
                options.bullets = false;
            }

            // Set initial front photo z-index and fades it in
            if(options.continousSliding)
            {
                slides.eq(activeSlide)
                    .css({
                        "z-index": 3,
                        "display": "block",
                        "left": 0
                    })
                    .fadeIn(function() {
                        //brings in all other slides IF css declares a display: none
                        slides.css({
                            "display": "block"
                        })
                    });
            }



            // ====================
            // ! CALCULATE SIZE   
            // ====================
            function calculateHeightWidth() 
            {
                frsWidth = frs.innerWidth();

                var minus_resize = options.width - frsWidth;
                var percent_minus = (minus_resize / options.width) * 100;
                frsHeight = options.height - (options.height * percent_minus / 100);

                // max and min height
                if(frsHeight <= options.minHeight)
                {
                    frsHeight = options.minHeight;
                }
                else if(frsHeight >= options.maxHeight && options.maxHeight > 0)
                {
                    frsHeight = options.maxHeight;
                }

                applyImageSize() // apply image size
            }



            // ====================
            // ! APPLY IMAGE SIZE
            // ====================
            function applyImageSize()
            {
                var images = slideWrapper.find('.frs-slide-img').children('img');

                $.each(images,function(index){
                    var width = frsWidth;
                    var height = getImgHeight(width,index);

                    if(frsHeight > height) 
                    {
                        var curImgWidth = getImgWidth(frsHeight,index);
                        var curDiffWidth = (curImgWidth - width) * -1;

                        $(this).css({
                            'height': frsHeight + 'px',
                            'width': curImgWidth + 'px',
                            'max-height': frsHeight + 'px',
                            'max-width': curImgWidth + 'px',
                            'margin-left': curDiffWidth / 2  + 'px'
                        })

                        // neutralize
                        $(this).css({
                            'margin-top': ''
                        })
                    }
                    else 
                    {
                        var diff = frsHeight - height;

                        $(this).css('margin-top', (diff / 2) + 'px');

                        $(this).css({
                            'width': width + 'px',
                            'max-width': width + 'px'
                        })

                        // neutralize
                        $(this).css({
                            'height': 'auto',
                            'max-height':'none',
                            'margin-left': ''
                        })
                    }

                    // width                    
                    $(this).parent().width(width);                    
                });
            }

            /**
             * Function: getImgHeight
             */
            getImgHeight = function(width,index)
            {
                var Twidth = imgWidth[index];
                var Theight = imgHeight[index];

                var minusResize = Twidth - width;
                var percentMinus = (minusResize / Twidth) * 100;
                var height = Theight - (Theight * percentMinus / 100);
                    height = Math.round(height);

                return height
            }

            /**
             * Function: getImgWidth
             */
            getImgWidth = function(height,index)
            {
                var Twidth = imgWidth[index];
                var Theight = imgHeight[index];

                var minusResize = Theight - height;
                var percentMinus = (minusResize / Theight) * 100;
                var width = Twidth - (Twidth * percentMinus / 100);
                    width = Math.round(width);

                return width;
            }



            // ========================
            // ! CHECK AND APPLY CSS 3
            // ========================
            var vendorPrefix;

            function css3support() {
                var element = document.createElement('div'),
                    props = [ 'perspectiveProperty', 'WebkitPerspective', 'MozPerspective', 'OPerspective', 'msPerspective' ];
                for ( var i in props ) {
                    if ( typeof element.style[ props[ i ] ] !== 'undefined' ) {
                        vendorPrefix = props[i].replace('Perspective', '').toLowerCase();
                        return options.jsOnly ? false : true;
                    }
                }

                return false;
            };



            // ================
            // ! SETUP LAYOUT
            // ================
            var grouped_slideshow = "";

            if(! options.continousSliding)
            {
                if(options.animation == "horizontal-slide")
                {
                    slides.css({
                        "position": "relative",
                        "float": "left",
                        "display": "block",
                        "width": frsWidth + "px",
                        "height": + "100%"
                    });
                   
                    slides.parent().css({"width": frsWidth * numberSlides + "px", "height": frsHeight + "px"});
                }
                else if(options.animation == "vertical-slide")
                {
                    slides.parent().css({"width": frsWidth + "px", "height": frsHeight * numberSlides + "px"});

                    slides.css({
                        "position": "relative",
                        "display": "block",
                        "width": frsWidth + "px",
                        "height": frsHeight + "px"
                    });
                }
                else if(options.animation == "fade")
                {
                    slides.parent().css({"width": frsWidth + "px", "height": frsHeight + "px"});

                    slides.css({
                        "z-index": 1,
                        "width": frsWidth + "px",
                        "height": frsHeight + "px"
                    });

                    slides.eq(activeSlide).css({"z-index": 3});
                }
            }



            // ==============
            // ! TIMER   
            // ==============
            date = new Date();
            milliseconds = date.getTime();
            start_seconds = milliseconds / 1000;

            function log_time() {
                date = new Date();
                milliseconds = date.getTime();
                seconds = milliseconds / 1000;
                seconds = seconds - start_seconds;         
            }

            //Timer Execution
            function startCfrs_slider_lock() {

                if (!options.timer || options.timer == 'false') {
                    return false;
                                
                /**
                 * Because in startup timer is always hidden
                 * use this if you want to change the behaviour
                 *
                 * } else if (timer.is(':hidden')) {
                 *       timerRunning = true;
                 *       clock = setInterval(function(e) {
                 *
                 *           shift("next");
                 *
                 *      }, options.advanceSpeed);
                 *
                 */

                } else if(! css3support()) {
                    timerRunning = true;
                    pause.removeClass('frs-timer-active')
                    clock = setInterval(function(e) {
                
                        shift("next");
                
                    }, options.advanceSpeed);
                } else {
                    timerRunning = true;
                    pause.removeClass('frs-timer-active')
                    clock = setInterval(function(e) {

                        var degreeCSS = "rotate(" + degrees + "deg)"
                        rotator.css('-' + vendorPrefix + '-transform', degreeCSS);
                        degrees += 1
                        if (degrees >= 180) {

                            mask.addClass('frs-timer-move')
                            rotator.addClass('frs-timer-move')
                            mask_turn.css("display", "block")

                        }
                        if (degrees >= 360) {

                            degrees = 0;
                            mask.removeClass('frs-timer-move')
                            rotator.removeClass('frs-timer-move')
                            mask_turn.css("display", "none")

                            shift("next");
                        }
                    }, options.advanceSpeed / 360);
                }
            }

            function stop_slider_lock() {
                if (!options.timer || options.timer == 'false') {
                    return false;
                } else {
                    timerRunning = false;
                    clearInterval(clock);
                    pause.addClass('frs-timer-active');
                }
            }


            // Timer Setup
            if (options.timer) {
                var timerHTML = '<div class="frs-timer"><span class="frs-timer-mask"><span class="frs-timer-rotator"></span></span><span class="frs-timer-mask-turn"></span><span class="frs-timer-pause"></span></div>'
                frsWrapper.append(timerHTML);
                var timer = frsWrapper.children('div.frs-timer'),
                    timerRunning;
                if (timer.length != 0) {
                    var rotator = $(frs_id + ' div.frs-timer span.frs-timer-rotator'),
                        mask = $(frs_id + ' div.frs-timer span.frs-timer-mask'),
                        mask_turn = $(frs_id + ' div.frs-timer span.frs-timer-mask-turn'),
                        pause = $(frs_id + ' div.frs-timer span.frs-timer-pause'),
                        degrees = 0,
                        clock;
                    startCfrs_slider_lock();
                    timer.click(function() {
                        if (!timerRunning) {
                            startCfrs_slider_lock();
                        } else {
                            stop_slider_lock();
                        }
                    });
                    if (options.startClockOnMouseOut) {
                        var outTimer;
                        frsWrapper.mouseleave(function() {

                            outTimer = setTimeout(function() {
                                if (!timerRunning) {
                                    startCfrs_slider_lock();
                                }
                            }, options.startClockOnMouseOutAfter)
                        })
                        frsWrapper.mouseenter(function() {
                            clearTimeout(outTimer);
                        })
                    }
                }
            }

            // Pause Timer on hover
            if (options.pauseOnHover) {
                frsWrapper.mouseenter(function() {

                    stop_slider_lock();
                });
            }



            // ==================
            // ! DIRECTIONAL NAV   
            // ==================
            // DirectionalNav { rightButton --> shift("next"), leftButton --> shift("prev");
            if (options.directionalNav) {
                if (options.directionalNav == "false") {
                    return false;
                }
                var directionalNavHTML = '<div class="frs-slider-nav ' + caption_position + '"><span class="frs-arrow-right">›</span><span class="frs-arrow-left">‹</span></div>';
                frsWrapper.append(directionalNavHTML);
                var leftBtn = frsWrapper.children('div.frs-slider-nav').children('span.frs-arrow-left'),
                    rightBtn = frsWrapper.children('div.frs-slider-nav').children('span.frs-arrow-right');
                leftBtn.click(function() {
                    stop_slider_lock();
                    shift("prev");
                });
                rightBtn.click(function() {
                    stop_slider_lock();
                    shift("next")
                });
            }

            if (options.navigationSmall) {

                $(window).resize(function() {
                    if ($(window).width() < options.navigationSmallTreshold) {
                        frs.siblings("div.frs-slider-nav").addClass('small')
                    } else {
                        frs.siblings("div.frs-slider-nav").removeClass('small')
                    }
                });

                if (frs.width() < options.navigationSmallTreshold) {
                    frs.siblings("div.frs-slider-nav").addClass('small')
                }
            }



            // ==================
            // ! BULLET NAV   
            // ==================
            if (options.bullets) 
            {                
                var bulletHTML = '<ul class="frs-bullets"></ul>';

                if(options.sbullets)
                {
                    bulletHTML = '<ul class="frs-bullets frs-sbullets"></ul>';
                }

                var bulletHTMLWrapper = "<div class='frs-bullet-wrapper'></div>";
                frsWrapper.append(bulletHTML);

                var bullets = frsWrapper.children('ul.frs-bullets');
                for (i = 0; i < numberSlides; i++) {
                    var liMarkup = $('<li class="frs-slideshow-nav-bullets"></li>'); // If you want default numbering, add: (i + 1)
                    if (options.bulletThumbs) {
                        var thumbName = slides.eq(i).data('thumb');
                        if (thumbName) {
                            var liMarkup = $('<li class="has-thumb">' + i + '</li>')
                            liMarkup.css({
                                "background": "url(" + options.bulletThumbLocation + thumbName + ") no-repeat"
                            });
                        }
                    }
                    frsWrapper.children('ul.frs-bullets').append(liMarkup);
                    liMarkup.data('index', i);
                    liMarkup.click(function() {
                        stop_slider_lock();
                        shift($(this).data('index'));
                    });
                }
               
                bullets.wrap("<div class='frs-bullets-wrapper " + caption_position + "' />")
                setActiveBullet();
            }



            /**
             * SLIDING BULLETS
             */
            var sbullets = 0;
            var bulletsWalkingWidth = 0;            
            var bulletsMaxShowedIndex = 0;
            var bulletsBackChild = 0;
            var bulletsNextChild = 0;
            var bulletsOffsetWidth = 0;
            var bulletsPosition = 0;                        
            var bulletsOffsetEnable = false;
            var bulletsWidth = 0;
            var bulletsMovedWidth = 0;

            var each_width = options.sbulletsItemWidth;
            var total_width = each_width * numberSlides;
            
            /** 
             * generate slide bullet 
             * this function will be recall every slideshow resized
             */
            function generate_slide_bullet()
            {
                sbullets = frsWrapper.find('ul.frs-sbullets');

                bulletsWalkingWidth = 0;
                bulletsMaxShowedIndex = 0;
                bulletsBackChild = 0;
                bulletsNextChild = 0;
                bulletsOffsetWidth = 0;
                bulletsPosition = 0;                        
                bulletsOffsetEnable = false;              
                bulletsWidth = sbullets.parent().outerWidth(true);

                if(bulletsWidth > total_width)
                {
                    each_width = bulletsWidth / numberSlides;
                    total_width = each_width * numberSlides;
                }
                                
                sbullets.parent().css('overflow', 'hidden');
                sbullets.css('background-color', sbullets.children('li').last().css("background-color"));
                sbullets.children('li.frs-slideshow-nav-bullets').css('width',each_width + 'px');                
                sbullets.css('width', total_width + 'px');

                sbullets.find('li').each(function() {
                    bulletsWalkingWidth += each_width;

                    if (bulletsWalkingWidth + each_width > bulletsWidth) 
                    {
                        bulletsNextChild = $(this).index();
                        bulletsMaxShowedIndex = bulletsNextChild;
                    }
                    
                    if (bulletsWalkingWidth > bulletsWidth) 
                    {
                        $(this).addClass('frs-bullet-sliding-next');
                        bulletsOffsetWidth = bulletsWalkingWidth - bulletsWidth;

                        /* detect if bullets offset is too large */
                        if(bulletsOffsetWidth < each_width)
                        {
                            bulletsOffsetEnable = true;
                        }

                        return false;
                    }
                });
            }

            function slide_bullet(navigate)
            {
                if(navigate == 'next')
                {
                    if(sbullets.children('li').eq(numberSlides - 1).hasClass("frs-bullet-sliding-next"))
                    {
                        bulletsNavPixelWidth = (each_width * bulletsPosition) + bulletsOffsetWidth;
                    }
                    else
                    {
                        sbullets.children('li').removeClass('frs-bullet-sliding-back').removeClass('frs-bullet-sliding-next');

                        bulletsPosition++;
                        bulletsBackChild++;
                        bulletsNextChild++;

                        bulletsNavPixelWidth = (each_width * bulletsPosition) + bulletsOffsetWidth;
                    }

                    slide_bullet_add_class('sliding_one','frs-bullet-sliding-one-back',bulletsBackChild + 1)            
                }
                else if(navigate == 'back')
                {
                    sbullets.children('li').removeClass('frs-bullet-sliding-back').removeClass('frs-bullet-sliding-next');

                    bulletsPosition--;
                    bulletsBackChild--;
                    bulletsNextChild--;

                    bulletsNavPixelWidth = each_width * bulletsPosition;

                    slide_bullet_add_class('sliding_one','frs-bullet-sliding-one-next',bulletsNextChild - 1)
                }
                else if(navigate == 'first')
                {
                    sbullets.children('li').removeClass('frs-bullet-sliding-back').removeClass('frs-bullet-sliding-next');

                    bulletsPosition = 0;
                    bulletsBackChild = 0;                    
                    bulletsNextChild = bulletsMaxShowedIndex;

                    bulletsNavPixelWidth = each_width * bulletsPosition;

                    slide_bullet_add_class('sliding_one','frs-bullet-sliding-one-next',bulletsNextChild - 1)
                }
                else if(navigate == 'last')
                {
                    sbullets.children('li').removeClass('frs-bullet-sliding-back').removeClass('frs-bullet-sliding-next');

                    var numberBulletsByIndex = numberSlides - 1;

                    bulletsPosition = numberBulletsByIndex - bulletsMaxShowedIndex;
                    bulletsBackChild = numberBulletsByIndex - bulletsMaxShowedIndex;
                    bulletsNextChild = numberBulletsByIndex;

                    bulletsNavPixelWidth = (each_width * bulletsPosition) + bulletsOffsetWidth;

                    slide_bullet_add_class('sliding_one','frs-bullet-sliding-one-back',bulletsBackChild + 1)
                }

                /**
                 * Track moved width
                 */
                bulletsMovedWidth = bulletsNavPixelWidth;
                
                if(css3support())
                {
                    var properties = {};
                    properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                    properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(-' + bulletsNavPixelWidth + 'px, 0, 0)';

                    sbullets.css(properties);
                }
                else
                {
                    sbullets
                        .animate({
                            "left": '-' + bulletsNavPixelWidth + 'px'
                        }, options.animationSpeed);
                }
                
                // Apply class to bullet
                slide_bullet_add_class('sliding', 'frs-bullet-sliding-back', bulletsBackChild)
                slide_bullet_add_class('sliding', 'frs-bullet-sliding-next', bulletsNextChild)
            }

            function slide_bullet_add_class(li_type, li_class, li_index)
            {
                if(li_type == 'sliding_one')
                {
                    if(bulletsOffsetEnable == true)
                    {
                        sbullets.children('li').removeClass('frs-bullet-sliding-one-back').removeClass('frs-bullet-sliding-one-next');
                        
                        var addClassTo = sbullets.children('li').eq(li_index)

                        if(addClassTo.attr('class') == "frs-slideshow-nav-bullets")
                        {
                            addClassTo.addClass(li_class);
                        }                        
                    }
                }  
                else if(li_type == 'sliding')
                {
                    if(sbullets.children('li').eq(li_index).attr('class') == 'frs-slideshow-nav-bullets frs-bullets-active' && li_index > 0 )
                    {
                        if(li_class == 'frs-bullet-sliding-back')
                        {
                            li_index--
                        }
                        else if(li_class == 'frs-bullet-sliding-next')
                        {
                            li_index++
                        }
                    }

                    sbullets.children('li').eq(li_index).removeClass('frs-bullet-sliding-one-back')
                    sbullets.children('li').eq(li_index).removeClass('frs-bullet-sliding-one-next')
                    sbullets.children('li').eq(li_index).addClass(li_class)
                }              
            }

            function slide_bullet_one(type)
            {
                var oneMove = 0;

                sbullets.children('li').removeClass('frs-bullet-sliding-one-back').removeClass('frs-bullet-sliding-one-next');

                if(type == 'back')
                {
                    sbullets.children('li').eq(bulletsNextChild - 1).addClass('frs-bullet-sliding-one-next');

                    oneMove = bulletsMovedWidth - bulletsOffsetWidth;
                }
                else
                {
                    sbullets.children('li').eq(bulletsBackChild + 1).addClass('frs-bullet-sliding-one-back');

                    oneMove = bulletsMovedWidth + bulletsOffsetWidth;
                }

                /**
                 * Track moved width
                 */
                bulletsMovedWidth = oneMove;

                if(css3support())
                {
                    var properties = {};
                    properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                    properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(-' + oneMove + 'px, 0, 0)';

                    sbullets.css(properties);
                }
                else
                {
                    sbullets
                        .animate({
                            "left": '-' + oneMove + 'px'
                        }, options.animationSpeed);
                }
            }


            /**
             * SET ACTIVE BULLETS
             */
            function setActiveBullet() {
                if (!options.bullets) {
                    return false;
                } else {
                    bullets.children('li').removeClass('frs-bullets-active').eq(activeSlide).addClass('frs-bullets-active');

                    /**
                     * begin slide bullets
                     */
                    if(options.sbullets)
                    {
                        if(bullets.children('li.frs-bullets-active').hasClass('frs-bullet-sliding-next'))
                        {
                            if(bullets.children('li.frs-bullets-active').index() == (numberSlides-1))
                            {
                                slide_bullet('last');
                            }
                            else
                            {
                                slide_bullet('next');
                            }
                        }
                        else if(bullets.children('li.frs-bullets-active').hasClass('frs-bullet-sliding-back'))
                        {
                            if(bullets.children('li.frs-bullets-active').index() > 0)
                            {
                                slide_bullet('back');
                            }
                            else
                            {
                                slide_bullet('first');
                            }
                        }
                        else if(bullets.children('li.frs-bullets-active').hasClass('frs-bullet-sliding-one-next'))
                        {
                            slide_bullet_one('next')
                        }
                        else if(bullets.children('li.frs-bullets-active').hasClass('frs-bullet-sliding-one-back'))
                        {
                            slide_bullet_one('back')
                        }
                        else
                        {
                            if(bulletsMaxShowedIndex > 0)
                            {
                                if(bullets.children('li.frs-bullets-active').index() == 0)
                                {
                                    slide_bullet('first');
                                }
                                else if(bullets.children('li.frs-bullets-active').index() == (numberSlides-1))
                                {
                                    slide_bullet('last');
                                }
                            }
                        }
                    }
                }
            }


            // ====================
            // ! CAPTION POSITION   
            // ====================
            function set_caption_position()
            {
                caption_position = slides.eq(activeSlide).find('div.frs-caption');

                if(caption_position.length)
                {
                    caption_position = caption_position.attr('class').replace('frs-caption ','');
                }
                else
                {
                    caption_position = "undefined";
                }

                //set active caption position to bullet and navigation
                frsWrapper.find('div.frs-bullets-wrapper').attr('class', 'frs-bullets-wrapper ' + caption_position);
                frsWrapper.find('div.frs-slider-nav').attr('class', 'frs-slider-nav ' + caption_position);
            }


            // ====================
            // ! SHIFT ANIMATIONS  
            // ====================
            function shift(direction) 
            {
                // remember previous activeSlide
                var prevActiveSlide = activeSlide,
                    slideDirection = direction;
                // exit function if bullet clicked is same as the current image
                if (prevActiveSlide == slideDirection) {
                    return false;
                }
                // reset Z & Unlock
                function resetAndUnlock() {
                    if(options.continousSliding)
                    {
                        slides
                            .eq(prevActiveSlide)
                            .css({
                                "z-index": 1
                            });
                    }
                   
                    frs_slider_unlock();
                    options.afterSlideChange.call(this);                    
                }

                if (slides.length == "1") {
                    return false;
                }
                if (!locked) {
                    frs_slider_lock();
                    //deduce the proper activeImage
                    if (direction == "next") {
                        activeSlide++
                        activeSlideContinous++
                        if (activeSlide == numberSlides) {
                            activeSlide = 0;
                        }
                    } else if (direction == "prev") {
                        activeSlide--
                        activeSlideContinous--
                        if (activeSlide < 0) {
                            activeSlide = numberSlides - 1;
                        }
                    } else {
                        activeSlide = direction;
                        if (prevActiveSlide < activeSlide) {
                            slideDirection = "next";
                        } else if (prevActiveSlide > activeSlide) {
                            slideDirection = "prev"
                        }
                    }
                    // set to correct bullet
                    setActiveBullet();

                    // set previous slide z-index to one below what new activeSlide will be
                    if(options.continousSliding)
                    {
                        slides
                            .eq(prevActiveSlide)
                            .css({
                                "z-index": 2
                            });
                    }
                    

                    if(options.heightResize==true){

                    $(slides).parent('.frs-slideshow-content').animate(
                        {'height':frsHeight},
                        options.animationSpeed)
                    }

                    
                    /**
                     * Horizontal Slide
                     */
                    if (options.animation == "horizontal-slide") 
                    {                    
                        if(options.continousSliding)
                        {
                            var cssWidth = slideDirection == "next" ? frsWidth : -frsWidth;
                            var aniWidth = slideDirection == "next" ? -frsWidth : frsWidth;                                

                            if(css3support())
                            {
                                /**
                                 * belum jadi yang css 3 continous slide
                                 */

                                slides
                                    .eq(activeSlide)
                                    .css({
                                        "left": cssWidth,
                                        "z-index": 3
                                    })
                                    .animate({
                                        "left": 0

                                    }, options.animationSpeed, resetAndUnlock);

                                slides
                                    .eq(prevActiveSlide)
                                    .animate({
                                        "left": aniWidth
                                    }, options.animationSpeed);


                                // var properties = {};
                                // properties[ 'z-index' ] = 3;
                                // properties[ 'left' ] = cssWidth;

                                // slides.eq(activeSlide).css(properties);

                                // var properties = {};
                                // properties[ 'left' ] = 0;

                                // slides.eq(prevActiveSlide).css(properties);

                                // if(slides.eq(activeSlide).css('left') == cssWidth + 'px')
                                // {
                                //     var properties = {};
                                //     properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                //     properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(-'+ cssWidth +'px, 0, 0)';

                                //     slides.eq(activeSlide).css(properties);

                                //     var properties = {};
                                //     properties[ 'left' ] = 0;
                                //     properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                //     properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d('+ aniWidth +'px, 0, 0)';

                                //     slides.eq(prevActiveSlide).css(properties);

                                //     resetAndUnlock();
                                // }                                    
                            }
                            else
                            {
                                slides
                                    .eq(activeSlide)
                                    .css({
                                        "left": cssWidth,
                                        "z-index": 3
                                    })
                                    .animate({
                                        "left": 0

                                    }, options.animationSpeed, resetAndUnlock);

                                slides
                                    .eq(prevActiveSlide)
                                    .animate({
                                        "left": aniWidth
                                    }, options.animationSpeed);
                            }  
                        }
                        else
                        {
                            var slide_action = frsWidth * activeSlide < numberSlides * frsWidth ? '-' + frsWidth * activeSlide : 0 ;

                            if(css3support())
                            {
                                // Get the properties to transition
                                var properties = {};
                                properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d('+ slide_action +'px, 0, 0)';

                                // Do the CSS3 transition
                                slides.parent().css(properties);
                                resetAndUnlock();
                            }
                            else
                            {
                                slides.parent()
                                    .animate({
                                        "left": slide_action + 'px'
                                    }, options.animationSpeed, resetAndUnlock);
                            }
                        }
                    }

                    /**
                     * Vertical Slide
                     */
                    if (options.animation == "vertical-slide") 
                    {
                        var slide_action = frsHeight * activeSlide < numberSlides * frsHeight ? '-' + frsHeight * activeSlide : 0 ;

                        if(options.continousSliding)
                        {
                            /**
                             * BELUM JADI
                             */
                            if (slideDirection == "prev") {
                                if(css3support())
                                {
                                    // Get the properties to transition
                                    var properties = {};
                                    properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                    properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(0, '+ slide_action +'px, 0)';

                                    // Do the CSS3 transition
                                    slides.parent().css(properties);
                                    resetAndUnlock();
                                }
                                else
                                {
                                    slides
                                        .eq(activeSlide)
                                        .css({
                                            "top": frsHeight,
                                            "z-index": 3
                                        })
                                        .animate({
                                            "top": 0
                                        }, options.animationSpeed, resetAndUnlock);
                                }
                            }
                            if (slideDirection == "next") {
                                if(css3support())
                                {
                                    // Get the properties to transition
                                    var properties = {};
                                    properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                    properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(0, '+ slide_action +'px, 0)';

                                    // Do the CSS3 transition
                                    slides.parent().css(properties);
                                    resetAndUnlock();
                                }
                                else
                                {
                                    slides
                                        .eq(activeSlide)
                                        .css({
                                            "top": -frsHeight,
                                            "z-index": 3
                                        })
                                        .animate({
                                            "top": 0
                                        }, options.animationSpeed, resetAndUnlock);
                                }
                            }
                        }
                        else
                        {
                            if(css3support())
                            {
                                // Get the properties to transition
                                var properties = {};
                                properties[ '-' + vendorPrefix + '-transition-duration' ] = options.animationSpeed + 'ms';
                                properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(0, '+ slide_action +'px, 0)';

                                // Do the CSS3 transition
                                slides.parent().css(properties);
                                resetAndUnlock();
                            }
                            else
                            {
                                slides.parent()
                                    .animate({
                                        "top": slide_action + 'px'
                                    }, options.animationSpeed, resetAndUnlock);
                            }
                        }
                        
                    }

                    /**
                     * Fade
                     */
                    if (options.animation == "fade") 
                    {
                        if(css3support())
                        {                            
                            slides.eq(activeSlide).css({'z-index': 2});

                            // Get the properties to transition
                            var properties = {};
                            properties[ 'opacity' ] = 0;
                            properties[ '-' + vendorPrefix + '-transition' ] = 'all ' + options.animationSpeed + 'ms ease';

                            slides.eq(prevActiveSlide).css(properties);

                            clearTimeout(timeout);
                            timeout = setTimeout(function() {
                                slides.eq(activeSlide).css({'z-index': 3});

                                // Get the properties to transition
                                var properties = {};
                                properties[ 'opacity' ] = 1;
                                properties[ 'z-index' ] = 1;
                                properties[ '-' + vendorPrefix + '-transition' ] = '';
                                
                                slides.eq(prevActiveSlide).css(properties);
                            }, options.animationSpeed - (options.animationSpeed * 20 / 100));                            

                            resetAndUnlock();
                        }
                        else
                        {
                            slides
                                .eq(activeSlide)
                                .css({
                                    "opacity": 0,
                                    "z-index": 3
                                })
                                .animate({
                                    "opacity": 1,
                                }, options.animationSpeed, resetAndUnlock);
                        }
                    }

                    set_caption_position();
                } //lock                                
            } //frs function

            // set caption position
            set_caption_position();



            // ====================================
            // ! RESIZE WINDOWS EVENT: RESPONSIVE   
            // ====================================            
            $(window).bind('resize.frs-slideshow-container', function(event, force) {
                calculateHeightWidth();

                /**
                 * resize elements
                 */
                slides.width(frsWidth);                
                slides.height(frsHeight);

                // resize wrapper
                frs.css({'height': frsHeight + 'px'});
                frsWrapper.parent().css({'height': frsHeight + 'px'});

                if(! options.continousSliding)
                {
                    if(options.animation == "horizontal-slide")
                    {
                        slideWrapper.css({
                            'width': frsWidth * numberSlides + 'px'
                        });

                        var slide_action = frsWidth * activeSlide < numberSlides * frsWidth ? '-' + frsWidth * activeSlide : 0 ;

                        // Stabilize slide position
                        if(css3support())
                        {
                            var properties = {};
                            properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d('+ slide_action +'px, 0, 0)';

                            slides.parent().css(properties);
                        }
                        else
                        {
                            slides.parent()
                                .animate({
                                    "left": slide_action + 'px'
                                });
                        }

                    }
                    else if(options.animation == "vertical-slide")
                    {
                        slideWrapper.css({
                            'height': frsHeight * numberSlides + 'px'
                        });

                        var slide_action = frsHeight * activeSlide < numberSlides * frsHeight ? '-' + frsHeight * activeSlide : 0 ;

                        /** Stabilize slide position */
                        var properties = {};
                        properties[ '-' + vendorPrefix + '-transform' ] = 'translate3d(0, '+ slide_action +'px, 0)';

                        slides.parent().css(properties);
                    }

                    /**
                     * Slide bullets
                     */
                    if(options.sbullets)
                    {
                        generate_slide_bullet();
                        slide_bullet('first');
                        shift(0);
                    }
                }


                /**
                 * Resposive Class
                 * - frs-responsive-mobile-small (width <= 369)
                 * - frs-responsive-mobile-medium (width <= 499 && width <= frsWidth)
                 * - frs-responsive-full
                 */

                 if(370 <= frsWidth && frsWidth <= 499) {
                    doResponsiveClassStart('frs-responsive-mobile-medium')
                 }
                 else if(369 >= frsWidth ) {
                    doResponsiveClassStart('frs-responsive-mobile-small')
                 }
                 else {
                    // Desktop Mode
                    doResponsiveClassStart('frs-responsive-full')
                 }
            });

            function doResponsiveClassStart(responsiveClass){
                // if it is the first run dont do animation
                if(first_run)
                {
                    first_run = false
                    frsWrapper.attr('class','frs-wrapper ' + options.skinClass)
                    frsWrapper.addClass(responsiveClass)
                    return
                }

                if(old_responsive_class == responsiveClass) return

                old_responsive_class = responsiveClass

                // Do the loading animation
                frsWrapper.children('.frs-slideshow-content').css('background-image','');
                slideWrapper.hide()

                // Restore & change responsive class
                setTimeout(function() {
                    frsWrapper.attr('class','frs-wrapper ' + options.skinClass)
                    frsWrapper.addClass(responsiveClass)
                    slideWrapper.css('display','block')

                    frsWrapper.children('.frs-slideshow-content').css('background-image','none');
                }, 1000);                
            }

        }); // Each call
    } // Frs plugin call

})(jQuery);