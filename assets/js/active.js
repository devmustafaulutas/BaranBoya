// Index of jQuery Active Code

// :: 1.0 PRELOADER ACTIVE CODE
// :: 2.0 NAVIGATION MENU ACTIVE CODE
// :: 3.0 STICKY HEADER ACTIVE CODE
// :: 4.0 SCROLL TO TOP ACTIVE CODE
// :: 5.0 SCROLL LINK ACTIVE CODE
// :: 6.0 SMOOTH SCROLLING ACTIVE CODE
// :: 7.0 AOS ACTIVE CODE
// :: 8.0 WOW ACTIVE CODE
// :: 9.0 PREVENT DEFAULT ACTIVE CODE
// :: 10.0 COUNTERUP ACTIVE CODE
// :: 11.0 FANCYBOX VIDEO POPUP ACTIVE CODE
// :: 12.0 CIRCLE ANIMATION ACTIVE CODE
// :: 13.0 REVIEWS ACTIVE CODE
// :: 14.0 PORTFOLIO ACTIVE CODE
// :: 15.0 CONTACT FORM ACTIVE CODE

(function ($) {
    'use strict';

    var $window = $(window);
    var zero = 0;

    // :: 1.0 PRELOADER ACTIVE CODE
    $(window).on("load", function () {
        $("#digimax-preloader").addClass("loaded");

        if ($("#digimax-preloader").hasClass("loaded")) {
            $("#preloader").delay(900).queue(function () {
                $(this).remove();
            });
        }
    });
    $(function () {
        // Masaüstü de hover olduğu için JS hiç dokunmuyor (yalnızca aktiviteyi işlediğimiz kısım var)

        // 1) Sol sütunda hover veya click ile alt sütunu güncelle
        $('.mega-dropdown .categories li').on('mouseenter click', function (e) {
            e.stopPropagation();
            var id = $(this).data('cat-id');
            // alt-listeleri gizle/göster
            $('.subcat-list').hide();
            $('.subcat-list[data-parent-id="' + id + '"]').show();
            // active class
            $('.categories li.active').removeClass('active');
            $(this).addClass('active');
        });

        // 2) Mobil: başlığa tıkla mega-menü aç/kapa
        $('.mega-dropdown > .nav-link').on('click', function (e) {
            if ($(window).width() <= 768) {
                e.preventDefault();
                $(this).closest('.mega-dropdown').toggleClass('open');
            }
        });

        // 3) Mobil: sayfa dışına tıklayınca kapat
        $(document).on('click', function (e) {
            if ($(window).width() <= 768 && !$(e.target).closest('.mega-dropdown').length) {
                $('.mega-dropdown').removeClass('open');
            }
        });
    });
    // :: 16.0 MOBILE SIDEBAR MENU ACTIVE CODE
    $(function () {
        var $toggleBtn = $('.toggle-btn'),
            $panel = $('#mobileMenu'),
            $overlay = $('#mobileMenuOverlay'),
            $closeBtn = $panel.find('.close-btn');

        // Hamburger’a tıklayınca paneli ve overlay’i aç
        $toggleBtn.on('click', function (e) {
            e.preventDefault();
            $panel.addClass('open');
            $overlay.addClass('open');
        });

        // Kapat butonu veya overlay’e tıklayınca paneli ve overlay’i kapat
        $closeBtn.add($overlay).on('click', function () {
            $panel.removeClass('open');
            $overlay.removeClass('open');
        });
    });
    // :: 16.2 MOBILE ACCORDION FOR SUBMENUS
    $(function () {
        $('#mobileMenu .has-submenu > .submenu-toggle').on('click', function (e) {
            e.preventDefault();
            var $li = $(this).parent();

            // diğer açık alt menüleri kapat
            $li.siblings('.has-submenu.open').removeClass('open').find('> .submenu').slideUp(200);

            // kendi alt menüsünü toggle et
            $li.toggleClass('open');
            $li.find('> .submenu').slideToggle(200);
        });
    });


    // :: 3.0 Urunler.php category
    $(function () {
        // Panel başlığına tıklayınca aç/kapa
        $('.products-category-box h3').on('click', function (e) {
            e.stopPropagation();
            var $menu = $(this).siblings('.products-dropdown-menu');
            // diğerlerini kapat
            $('.products-dropdown-menu').not($menu).removeClass('show');
            $('.dropdown-toggle').not($(this).find('.dropdown-toggle')).removeClass('open');

            // kendi menüsünü toggle et ve ok ikonunu çevir
            $menu.toggleClass('show');
            $(this).find('.dropdown-toggle').toggleClass('open');
        });

        // dışarı tıklanınca hepsini kapat
        $(document).on('click', function () {
            $('.products-dropdown-menu').removeClass('show');
            $('.dropdown-toggle').removeClass('open');
        });
    });

    // :: 3.0 STICKY HEADER ACTIVE CODE
    $window.on('scroll', function () {
        if ($(window).scrollTop() > 100) {
            $('.navbar').addClass('navbar-sticky');
            $('.navbar .navbar-nav.action .btn').addClass('btn-bordered');
            $('.navbar .navbar-nav.action .btn').removeClass('btn-bordered-white');
        } else {
            $('.navbar').removeClass('navbar-sticky');
            $('.navbar .navbar-nav.action .btn').removeClass('btn-bordered');
            $('.navbar .navbar-nav.action .btn').addClass('btn-bordered-white');
        }
    });

    $window.on('scroll', function () {
        $('.navbar-sticky').toggleClass('hide', $(window).scrollTop() > zero);
        zero = $(window).scrollTop();
    });

    // :: 4.0 SCROLL TO TOP ACTIVE CODE
    var offset = 300;
    var duration = 500;

    $window.on('scroll', function () {
        if ($(this).scrollTop() > offset) {
            $("#scrollUp").fadeIn(duration);
        } else {
            $("#scrollUp").fadeOut(duration);
        }
    });

    $("#scrollUp").on('click', function () {
        $('html, body').animate({
            scrollTop: 0
        }, duration);
    });

    // :: 5.0 SCROLL LINK ACTIVE CODE
    var scrollLink = $('.scroll');

    // :: 6.0 SMOOTH SCROLLING ACTIVE CODE
    scrollLink.on('click', function (e) {
        e.preventDefault();
        $('body,html').animate({
            scrollTop: $(this.hash).offset().top
        }, 1000);
    });

    // :: 7.0 AOS ACTIVE CODE
    AOS.init();

    // :: 8.0 WOW ACTIVE CODE
    new WOW().init();

    // :: 9.0 PREVENT DEFAULT ACTIVE CODE
    $("a[href='#']").on('click', function ($) {
        $.preventDefault();
    });

    // :: 10.0 COUNTERUP ACTIVE CODE
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    // :: 11.0 FANCYBOX VIDEO POPUP ACTIVE CODE
    $(".play-btn").fancybox({
        animationEffect: "zoom-in-out",
        transitionEffect: "circular",
        maxWidth: 800,
        maxHeight: 600,
        youtube: {
            controls: 0
        }
    });

    // :: 12.0 CIRCLE ANIMATION ACTIVE CODE
    $(window).on("load", function () {
        $('.profile-circle-wrapper').addClass('circle-animation');
        $('.profile-icon').fadeIn();
    });

    // :: 13.0 REVIEWS ACTIVE CODE
    $('.client-reviews.owl-carousel').owlCarousel({
        loop: true,
        center: true,
        margin: 40,
        nav: false,
        dots: false,
        smartSpeed: 1000,
        autoplay: true,
        autoplayTimeout: 4000,
        animateOut: 'slideOutDown',
        animateIn: 'flipInX',
        responsive: {
            0: {
                items: 1
            },
            576: {
                items: 2
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });

    // :: 14.0 PORTFOLIO ACTIVE CODE
    $('.portfolio-area').each(function (index) {

        var count = index + 1;

        $(this).find('.portfolio-items').removeClass('portfolio-items').addClass('portfolio-items-' + count);
        $(this).find('.portfolio-item').removeClass('portfolio-item').addClass('portfolio-item-' + count);
        $(this).find('.portfolio-btn').removeClass('portfolio-btn').addClass('portfolio-btn-' + count);

        var Shuffle = window.Shuffle;
        var Filter = new Shuffle(document.querySelector('.portfolio-items-' + count), {
            itemSelector: '.portfolio-item-' + count,
            buffer: 1,
        })

        $('.portfolio-btn-' + count).on('change', function (e) {

            var input = e.currentTarget;

            if (input.checked) {
                Filter.filter(input.value);
            }
        })
    });

    // :: 15.0 CONTACT FORM ACTIVE CODE
    // Get the form.
    var form = $('#contact-form');
    // Get the messages div.
    var formMessages = $('.form-message');
    // Set up an event listener for the contact form.
    $(form).submit(function (e) {
        // Stop the browser from submitting the form.
        e.preventDefault();
        // Serialize the form data.
        var formData = $(form).serialize();
        // Submit the form using AJAX.
        $.ajax({
            type: 'POST',
            url: $(form).attr('action'),
            data: formData
        })
            .done(function (response) {
                // Make sure that the formMessages div has the 'success' class.
                $(formMessages).removeClass('error');
                $(formMessages).addClass('success');

                // Set the message text.
                $(formMessages).text(response);

                // Clear the form.
                $('#contact-form input,#contact-form textarea').val('');
            })
            .fail(function (data) {
                // Make sure that the formMessages div has the 'error' class.
                $(formMessages).removeClass('success');
                $(formMessages).addClass('error');

                // Set the message text.
                if (data.responseText !== '') {
                    $(formMessages).text(data.responseText);
                } else {
                    $(formMessages).text('Oops! An error occured and your message could not be sent.');
                }
            });
    });
}(jQuery));
