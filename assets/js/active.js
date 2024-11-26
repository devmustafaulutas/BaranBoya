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

    // :: 2.0 NAVIGATION MENU ACTIVE CODE
    jQuery(function ($) {
        'use strict';

        // RESPONSIVE NAVIGATION
        function navResponsive() {

            let navbar = $('.navbar .items');
            let menu = $('#menu .items');

            menu.html('');
            navbar.clone().appendTo(menu);

            $('.menu .fa-angle-right').removeClass('fa-angle-right').addClass('fa-angle-down');
        }

        navResponsive();

        $(window).on('resize', function () {
            navResponsive();
        })

        // PREVENT DROPDOWN
        $('.menu .dropdown-menu').each(function () {
            var children = $(this).children('.dropdown').length;
            $(this).addClass('children-' + children);
        })

        $('.menu .nav-item.dropdown').each(function () {
            var children = $(this).children('.nav-link');
            children.addClass('prevent');
        })

        $(document).on('click', '#menu .nav-item .nav-link', function (e) {

            if ($(this).hasClass('prevent')) {
                e.preventDefault();
            }

            var nav_link = $(this);

            nav_link.next().toggleClass('show');

            if (nav_link.hasClass('smooth-anchor')) {
                $('#menu').modal('hide');
            }
        })
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
    $('.portfolio-area').each(function(index) {

        var count = index + 1;

        $(this).find('.portfolio-items').removeClass('portfolio-items').addClass('portfolio-items-'+count);
        $(this).find('.portfolio-item').removeClass('portfolio-item').addClass('portfolio-item-'+count);
        $(this).find('.portfolio-btn').removeClass('portfolio-btn').addClass('portfolio-btn-'+count);
        
        var Shuffle = window.Shuffle;
        var Filter  = new Shuffle(document.querySelector('.portfolio-items-'+count), {
            itemSelector: '.portfolio-item-'+count,
            buffer: 1,
        })
    
        $('.portfolio-btn-'+count).on('change', function (e) {
    
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
    $(document).ready(function () {
        // Main dropdown toggle
        const dropdownItems = document.querySelectorAll('.nav-item.dropdown');

        dropdownItems.forEach(item => {
            item.addEventListener('click', function (e) {
                const submenu = this.querySelector('.dropdown-menu');

                // Close all other main dropdown menus
                dropdownItems.forEach(otherItem => {
                    const otherSubmenu = otherItem.querySelector('.dropdown-menu');
                    if (otherSubmenu && otherSubmenu !== submenu) {
                        otherSubmenu.style.display = 'none';
                    }
                });

                // Toggle the clicked main dropdown menu
                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    e.stopPropagation(); // Prevent click event from propagating
                }
            });
        });

        // Submenu toggle
        const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

        dropdownSubmenus.forEach(submenuItem => {
            submenuItem.addEventListener('click', function (e) {
                const submenu = this.querySelector('.dropdown-menu');

                // Close all other submenus
                dropdownSubmenus.forEach(otherSubmenuItem => {
                    const otherSubmenu = otherSubmenuItem.querySelector('.dropdown-menu');
                    if (otherSubmenu && otherSubmenu !== submenu) {
                        otherSubmenu.style.display = 'none';
                    }
                });

                // Toggle the clicked submenu
                if (submenu) {
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    e.stopPropagation(); // Prevent click event from propagating
                }
            });
        });

        // Close all dropdown menus and submenus when clicking outside
        document.addEventListener('click', function () {
            dropdownItems.forEach(item => {
                const submenu = item.querySelector('.dropdown-menu');
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });

            dropdownSubmenus.forEach(submenuItem => {
                const submenu = submenuItem.querySelector('.dropdown-menu');
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });
        });
    });


$(document).ready(function() {
    // Kategori tıklama işlemi
    $('.category-toggle').on('click', function(e) {
        e.preventDefault(); // Sayfa yenilenmesini engelle
        const subcategoryList = $(this).next('.subcategory-list'); // Tıklanan kategoriye ait alt kategoriyi bul

        // Alt kategoriyi toggle et (aç/kapa)
        subcategoryList.toggle();

        // Diğer tüm alt kategorileri kapat
        $('.subcategory-list').not(subcategoryList).hide();
    });

    // Sayfanın dışına tıklanınca tüm alt kategorileri kapat
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.category-list').length) {
            $('.subcategory-list').hide();
        }
    });

    // Mobilde "menu-link" sınıfına sahip öğeler için tıklama olaylarını dinle
    $('.menu-link').on('click', function(event) {
        const submenu = $(this).next('.dropdown-menu');
        
        if (submenu.length && !submenu.hasClass('show')) {
            event.preventDefault(); // İlk tıklamada yönlendirmeyi durdur
            submenu.toggleClass('show'); // Alt menüyü aç/kapa
            
            // Diğer açık olan alt menüleri kapat
            $('.dropdown-menu').not(submenu).removeClass('show');
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    let slideIndex = 0;
    const slides = document.querySelectorAll(".slide");
    const totalSlides = slides.length;

    function showSlide(index) {
        // Slide'ları doğru sırada göstermek için translateX kullanıyoruz
        slides.forEach((slide, i) => {
            slide.style.transform = `translateX(-${index * 100}%)`;
        });
    }

    function prevSlide() {
        slideIndex = (slideIndex > 0) ? slideIndex - 1 : totalSlides - 1;
        showSlide(slideIndex);
    }

    function nextSlide() {
        slideIndex = (slideIndex < totalSlides - 1) ? slideIndex + 1 : 0;
        showSlide(slideIndex);
    }

    function autoSlide() {
        nextSlide();
        setTimeout(autoSlide, 4000); // Otomatik geçiş süresi 4 saniye
    }

    document.querySelector(".prev").addEventListener("click", prevSlide);
    document.querySelector(".next").addEventListener("click", nextSlide);

    showSlide(slideIndex);
    autoSlide();
});

document.addEventListener('DOMContentLoaded', function () {
    // Sayfa yüklendiğinde parametreye göre kategori ve alt kategoriyi kontrol et
    const params = new URLSearchParams(window.location.search);
    const kategori_id = params.get('kategori_id') || 0;
    const alt_kategori_id = params.get('alt_kategori_id') || 0;
    const alt_kategori_alt_id = params.get('alt_kategori_alt_id') || 0;

    // Eğer kategori id varsa, ürünleri yükle ve alt kategorileri aç
    if (kategori_id) {
        loadProducts(kategori_id, alt_kategori_id, alt_kategori_alt_id); // Ürünleri yükle
    }

    // Kategori tıklama olayını dinleyelim
    const categoryLinks = document.querySelectorAll('.category-toggle');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Sayfanın yeniden yüklenmesi için URL güncelleme
            const kategori_id = new URLSearchParams(this.search).get('kategori_id'); // Kategori id'sini al
            window.location.href = `urunler?kategori_id=${kategori_id}`; // URL'yi güncelle ve sayfayı yeniden yükle
        });
    });

    // Alt kategori tıklama olayını dinleyelim
    const subcategoryLinks = document.querySelectorAll('.subcategory-toggle');
    subcategoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Sayfanın yeniden yüklenmesi için URL güncelleme
            const kategori_id = new URLSearchParams(window.location.search).get('kategori_id');
            const alt_kategori_id = new URLSearchParams(this.search).get('alt_kategori_id'); // Alt kategori id'sini al
            window.location.href = `urunler?kategori_id=${kategori_id}&alt_kategori_id=${alt_kategori_id}`; // URL'yi güncelle ve sayfayı yeniden yükle
        });
    });

    // Ürünleri yükleme fonksiyonu
    function loadProducts(kategori_id, alt_kategori_id = 0, alt_kategori_alt_id = 0) {
        const url = `urunler_listele?kategori_id=${kategori_id}&alt_kategori_id=${alt_kategori_id}&alt_kategori_alt_id=${alt_kategori_alt_id}`;
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('urunler-category-productlist').innerHTML = data; // Ürünleri yükle
            })
            .catch(error => {
                console.error('Ürünler yüklenirken bir hata oluştu:', error);
            });
    }

    // Alt kategoriler görünürlük kontrolü
    function toggleSubcategories(kategori_id, alt_kategori_id = 0, alt_kategori_alt_id = 0) {
        // Alt kategori kontrolü
        const subcategoryContainer = document.querySelector(`#subcategory-container-${kategori_id}`);
        if (subcategoryContainer) {
            const subcategories = subcategoryContainer.querySelectorAll('.subcategory');
            subcategories.forEach(subcategory => {
                const subcategoryId = subcategory.getAttribute('data-id');
                if (subcategoryId === alt_kategori_id.toString()) {
                    subcategory.classList.add('open'); // Alt kategori aç
                } else {
                    subcategory.classList.remove('open'); // Diğerlerini kapat
                }
            });
        }

        // Alt alt kategori kontrolü
        const subSubcategoryContainer = document.querySelector(`#subcategory-alt-container-${alt_kategori_id}`);
        if (subSubcategoryContainer) {
            const subSubcategories = subSubcategoryContainer.querySelectorAll('.subcategory-alt');
            subSubcategories.forEach(subSubcategory => {
                const subSubcategoryId = subSubcategory.getAttribute('data-id');
                if (subSubcategoryId === alt_kategori_alt_id.toString()) {
                    subSubcategory.classList.add('open'); // Alt alt kategori aç
                } else {
                    subSubcategory.classList.remove('open'); // Diğerlerini kapat
                }
            });
        }
    }
});
$(document).ready(function() {
    // Kategorilerin başlıklarına tıklanabilirlik ekle
    $(".products-category-box h3").on("click", function() {
        var $parentBox = $(this).closest(".products-category-box");
        
        // Menü zaten açıksa kapat
        if ($parentBox.find(".products-dropdown-menu").hasClass("show")) {
            $parentBox.find(".products-dropdown-menu").removeClass("show");
            $parentBox.find(".dropdown-toggle").removeClass("open");
        } else {
            // Menü kapanmışsa, önce tüm menüleri kapat
            $(".products-dropdown-menu").removeClass("show");
            $(".dropdown-toggle").removeClass("open");
            
            // Tıklanan menüyü aç
            $parentBox.find(".products-dropdown-menu").addClass("show");
            $parentBox.find(".dropdown-toggle").addClass("open");
        }
    });
});

