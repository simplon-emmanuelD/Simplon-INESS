;(function ( $, window, document, undefined ) {

    var pluginName = 'accordionfaq',
        defaults = {
            transitionSpeed: 300,
            transitionEasing: 'ease',
            controlElement: '[data-control]',
            contentElement: '[data-content]',
            groupElement: '[data-accordion-group]',
            singleOpen: true
        };

    function Accordionfaq(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Accordionfaq.prototype.init = function () {
        var self = this,
            opts = self.options;

        var $accordionfaq = $(self.element),
            $controls = $accordionfaq.find('> ' + opts.controlElement),
            $content =  $accordionfaq.find('> ' + opts.contentElement);

        var accordionParentsQty = $accordionfaq.parents('[data-accordion]').length,
            accordionHasParent = accordionParentsQty > 0;

        var closedCSS = { 'max-height': 0, 'overflow': 'hidden' };

        var CSStransitions = supportsTransitions();

        function debounce(func, threshold, execAsap) {
            var timeout;

            return function debounced() {
                var obj = this,
                    args = arguments;

                function delayed() {
                    if (!execAsap) func.apply(obj, args);
                    timeout = null;
                };

                if (timeout) clearTimeout(timeout);
                else if (execAsap) func.apply(obj, args);

                timeout = setTimeout(delayed, threshold || 100);
            };
        }

        function supportsTransitions() {
            var b = document.body || document.documentElement,
                s = b.style,
                p = 'transition';

            if (typeof s[p] == 'string') {
                return true;
            }

            var v = ['Moz', 'webkit', 'Webkit', 'Khtml', 'O', 'ms'];

            p = 'Transition';

            for (var i=0; i<v.length; i++) {
                if (typeof s[v[i] + p] == 'string') {
                    return true;
                }
            }

            return false;
        }

        function requestAnimFrame(cb) {
            if(window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame) {
                return  requestAnimationFrame(cb) ||
                        webkitRequestAnimationFrame(cb) ||
                        mozRequestAnimationFrame(cb);
            } else {
                return setTimeout(cb, 1000 / 60);
            }
        }

        function toggleTransition($el, remove) {
            if(!remove) {
                $content.css({
                    '-webkit-transition': 'max-height ' + opts.transitionSpeed + 'ms ' + opts.transitionEasing,
                    'transition': 'max-height ' + opts.transitionSpeed + 'ms ' + opts.transitionEasing
                });
            } else {
                $content.css({
                    '-webkit-transition': '',
                    'transition': ''
                });
            }
        }

        function calculateHeight($el) {
            var height = 0;

            $el.children().each(function() {
                height = height + $(this).outerHeight(true);
            });

            $el.data('oHeight', height);
        }

        function updateParentHeight($parentAccordion, $currentAccordion, qty, operation) {
            var $content = $parentAccordion.filter('.open').find('> [data-content]'),
                $childs = $content.find('[data-accordion].open > [data-content]'),
                $matched;

            if(!opts.singleOpen) {
                $childs = $childs.not($currentAccordion.siblings('[data-accordion].open').find('> [data-content]'));
            }

            $matched = $content.add($childs);

            if($parentAccordion.hasClass('open')) {
                $matched.each(function() {
                    var currentHeight = $(this).data('oHeight');

                    switch (operation) {
                        case '+':
                            $(this).data('oHeight', currentHeight + qty);
                            break;
                        case '-':
                            $(this).data('oHeight', currentHeight - qty);
                            break;
                        default:
                            throw 'updateParentHeight method needs an operation';
                    }

                    $(this).css('max-height', $(this).data('oHeight'));
                });
            }
        }

        function refreshHeight($accordionfaq) {
            if($accordionfaq.hasClass('open')) {
                var $content = $accordionfaq.find('> [data-content]'),
                    $childs = $content.find('[data-accordion].open > [data-content]'),
                    $matched = $content.add($childs);

                calculateHeight($matched);

                $matched.css('max-height', $matched.data('oHeight'));
            }
        }

        function closeAccordion($accordionfaq, $content) {
            $accordionfaq.trigger('accordionfaq.close');
            
            if(CSStransitions) {
                if(accordionHasParent) {
                    var $parentAccordions = $accordionfaq.parents('[data-accordion]');

                    updateParentHeight($parentAccordions, $accordionfaq, $content.data('oHeight'), '-');
                }

                $content.css(closedCSS);

                $accordionfaq.removeClass('open');
            } else {
                $content.css('max-height', $content.data('oHeight'));

                $content.animate(closedCSS, opts.transitionSpeed);

                $accordionfaq.removeClass('open');
            }
        }

        function openAccordion($accordionfaq, $content) {
            $accordionfaq.trigger('accordionfaq.open');
            if(CSStransitions) {
                toggleTransition($content);

                if(accordionHasParent) {
                    var $parentAccordions = $accordionfaq.parents('[data-accordion]');

                    updateParentHeight($parentAccordions, $accordionfaq, $content.data('oHeight'), '+');
                }

                requestAnimFrame(function() {
                    $content.css('max-height', $content.data('oHeight'));
                });

                $accordionfaq.addClass('open');
            } else {
                $content.animate({
                    'max-height': $content.data('oHeight')
                }, opts.transitionSpeed, function() {
                    $content.css({'max-height': 'none'});
                });

                $accordionfaq.addClass('open');
            }
        }

        function closeSiblingAccordions($accordionfaq) {
            var $accordionGroup = $accordionfaq.closest(opts.groupElement);

            var $siblings = $accordionfaq.siblings('[data-accordion]').filter('.open'),
                $siblingsChildren = $siblings.find('[data-accordion]').filter('.open');

            var $otherAccordions = $siblings.add($siblingsChildren);

            $otherAccordions.each(function() {
                var $accordionfaq = $(this),
                    $content = $accordionfaq.find(opts.contentElement);

                closeAccordion($accordionfaq, $content);
            });

            $otherAccordions.removeClass('open');
        }

        function toggleAccordion() {
            var isAccordionGroup = (opts.singleOpen) ? $accordionfaq.parents(opts.groupElement).length > 0 : false;

            calculateHeight($content);

            if(isAccordionGroup) {
                closeSiblingAccordions($accordionfaq);
            }

            if($accordionfaq.hasClass('open')) {
                closeAccordion($accordionfaq, $content);
            } else {
                openAccordion($accordionfaq, $content);
            }
        }

        function addEventListeners() {
            $controls.on('click', toggleAccordion);
            
            $controls.on('accordionfaq.toggle', function() {
                if(opts.singleOpen && $controls.length > 1) {
                    return false;
                }
                
                toggleAccordion();
            });

            $(window).on('resize', debounce(function() {
                refreshHeight($accordionfaq);
            }));
        }

        function setup() {
            $content.each(function() {
                var $curr = $(this);

                if($curr.css('max-height') != 0) {
                    if(!$curr.closest('[data-accordion]').hasClass('open')) {
                        $curr.css({ 'max-height': 0, 'overflow': 'hidden' });
                    } else {
                        toggleTransition($curr);
                        calculateHeight($curr);

                        $curr.css('max-height', $curr.data('oHeight'));
                    }
                }
            });


            if(!$accordionfaq.attr('data-accordion')) {
                $accordionfaq.attr('data-accordion', '');
                $accordionfaq.find(opts.controlElement).attr('data-control', '');
                $accordionfaq.find(opts.contentElement).attr('data-content', '');
            }
        }

        setup();
        addEventListeners();
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName,
                new Accordionfaq( this, options ));
            }
        });
    }

})( jQuery, window, document );
