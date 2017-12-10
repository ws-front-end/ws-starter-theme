(function($) {

})( jQuery );
(function($) {

        var mainMenu = function() {

            var $mainMenuContainer = $('#js-main-menu-container');
            var $mainMenuToggle = $('#js-main-menu-toggle');
            var $mainMenu = $('#js-main-menu');

            $mainMenu.attr('aria-expanded', 'false');

            /* Toggle menu */
            $mainMenuToggle.on('click', function(e) {

                e.preventDefault();
                e.stopImmediatePropagation();

                $mainMenuContainer.stop().slideToggle(350);

                if ($mainMenuContainer.is('.is-toggled')) {
                    $mainMenuContainer.removeClass('is-toggled');
                    $mainMenuToggle.attr('aria-expanded', 'false').removeClass('is-active');
                    $mainMenu.attr('aria-expanded', 'false');
                } else {
                    $mainMenuContainer.addClass('is-toggled');
                    $mainMenuToggle.attr('aria-expanded', 'true').addClass('is-active');
                    $mainMenu.attr('aria-expanded', 'true');
                }
                
            });

            /* Close menu when a click is made elsewhere on the page */
            $(document).on('click', function(e) {

                if ($mainMenuContainer.is('.is-toggled')) {
                    mainMenuArea = $(e.target).closest($mainMenuContainer).length;
                    menuToggleArea = $(e.target).closest($mainMenuToggle).length;
                    if(!mainMenuArea && !menuToggleArea) {
                        $mainMenuContainer.stop().slideToggle(350);
                        $mainMenuContainer.removeClass('is-toggled');
                        $mainMenuToggle.attr('aria-expanded', 'false').removeClass('is-active');
                        $mainMenu.attr('aria-expanded', 'false');
                    }
                }

            });

		}

		mainMenu();

        var tabsNav = function() {

            $('.js-tabs-nav a').on('click', function(e) {

                e.preventDefault();

                var tab = $(this).attr('href');
                var $navItem = $(this).parent('li');

                if ( !$navItem.hasClass('is-active') ) {
                    $navItem.siblings('li').removeClass('is-active');
                    $navItem.addClass('is-active');
                    $(tab).siblings().removeClass('is-active');
                    $(tab).addClass('is-active');
                }

            });

        }

        tabsNav();

        var anchorScroll = function() {
            $('.js-anchor').on('click', function(e) {

                e.preventDefault();
                var section = $(this).attr('href');
                var scrollDistance = $(section).offset().top;
                
                $('html, body').animate({
                    scrollTop: scrollDistance
                }, 800);

            });
        }

        anchorScroll();

})( jQuery );