// God save the Dev

'use strict';

if (process.env.NODE_ENV !== 'production') {
    require('./assets/templates/layouts/index.html');
    require('./assets/templates/layouts/categories.html');
    require('./assets/templates/layouts/categories-filters-checked.html');
    require('./assets/templates/layouts/reviews.html');
    require('./assets/templates/layouts/404.html');
    require('./assets/templates/layouts/blog.html');
    require('./assets/templates/layouts/article.html');
    require('./assets/templates/layouts/search-results.html');
    require('./assets/templates/layouts/cart.html');
    require('./assets/templates/layouts/cart-empty.html');
    require('_templates/layouts/checkout.html');
    require('_templates/layouts/thx.html');

    require('./assets/templates/layouts/contacts.html');
    require('./assets/templates/layouts/about.html');
    require('./assets/templates/layouts/impresum.html');
    require('./assets/templates/layouts/privacy.html');
    require('./assets/templates/layouts/shipping.html');
    require('./assets/templates/layouts/investment.html');

    require('./assets/templates/layouts/product.html');
    require('./assets/templates/layouts/product-in-cart.html');
}

// Depends
var $ = require('jquery');
require('bootstrap-sass');

// Modules
var Forms = require('_modules/forms');
var Popup = require('_modules/popup');
var LightGallery = require('_modules/lightgallery');
var Slider = require('_modules/slider');
require('../node_modules/sumoselect/jquery.sumoselect.min');
//require('../node_modules/ez-plus/src/jquery.ez-plus');
require('../node_modules/sweetalert2/dist/sweetalert2');
require('../node_modules/mark.js/dist/jquery.mark.min');
require('../node_modules/jquery-validation/dist/jquery.validate.min');
require('_modules/succinct');

// Stylesheet entrypoint
require('_stylesheets/app.scss');

// Are you ready?
$(function () {
    new Forms();
    new Popup();
    new LightGallery();
    new Slider();

    setTimeout(function () {
        $('body').trigger('scroll');
        $(window).trigger('resize');
    }, 100);

    // scroll to id

    $(document).on("click", 'a[href*="#"]', function (e) {
        var id = $(this).attr("href");
        var $id = $(id);
        if ($id.length === 0) {
            return;
        }
        e.preventDefault();
        var pos = $id.offset().top;
        $("body, html").animate({scrollTop: pos}, 500);
    });
    $(document).on("click", 'a[href*="#"]', function (e) {
        e.preventDefault();
    });

    // fixed header

    var header = $('.header'),
        scrollPrev = 0;

    if ($(window).width() > 1439) {
        $(window).scroll(function () {
            var scrolled = $(window).scrollTop();
            if (scrolled > 210) {
                header.addClass('fixed');
                $('body').addClass('fixed-header');
            } else {
                header.removeClass('fixed');
                $('body').removeClass('fixed-header');
            }
            scrollPrev = scrolled;
        });
    }

    // truncate text

    $('.catalog-item__title').succinct({
        size: 70
    });

    $('.news-item__descr').succinct({
        size: 580
    });
    if ($(window).width() > 574) {
        $('.blog-item__text').succinct({
            size: 550,
            omission: ' .....'
        });
    }
    else{
        $('.blog-item__text').succinct({
            size: 340,
            omission: ' .....'
        });
    }
    if ($(window).width() > 767) {
        $('.article-sidebar .blog-item__text').succinct({
            size: 120,
            omission: ' .....'
        });
    }
    else{
        $('.article-sidebar .blog-item__text').succinct({
            size: 340,
            omission: ' .....'
        });
    }

    // input filled

    $('.input, .textarea').on('input', function () {
        var $wrap = $(this).closest('.input-wrapper');
        if ($(this).val().length) {
            $wrap.addClass('filled');
        } else {
            $wrap.removeClass('filled');
        }
    });

    // checkbox change

    $('.checkbox-wrapper input').on('change', function () {
        var btn = $(this).closest('form').find('button[type=submit]');
        if (this.checked) {
            btn.prop('disabled', false);
        } else {
            btn.prop('disabled', true);
        }
    });

    // select

    $('.select').SumoSelect({
        forceCustomRendering: true
    });

    $('.select').change(function () {
        var value = $(this).val();
        $(this).closest('.select-wrapper').removeClass('err').addClass('chosen').find('input').val(value);
        $(this).closest('.select-wrapper').find('.validate-error').remove();
    });

    // upload file

    $(document).delegate("#upload-files", "change", function () {
        var files = $("#upload-files")[0].files;
        for (var i = 0; i < files.length; i++) {
            $("#upload-preview").prepend('<div class="file"><span>' + files[i].name + '</span></div>');
        }
    });

    // fixed controls

    $(window).on('scroll', function() {
        var wh = $(window).height();
        if ($(this).scrollTop() > wh){
            $('.fixed-controls').addClass('active');
        }
        else{
            $('.fixed-controls').removeClass('active');
        }
    });

    // mobile menu

    var touch = $('.mobile-menu__btn');

    var toggles = document.querySelectorAll('.mobile-menu__btn');

    for (var i = toggles.length - 1; i >= 0; i--) {
        var toggle = toggles[i];
        toggleHandler(toggle);
    }

    function toggleHandler(toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            (this.classList.contains('active') === true) ? this.classList.remove('active') : this.classList.add('active');
        });
    }

    $(touch).click(function (e) {
        e.preventDefault();
        $('body').toggleClass('menu-opened');
        return false;
    });

    $(document).on('click', '.mobile-menu__btn', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.mobile-menu__wrapper', function (e) {
        e.stopPropagation();
    });

    $(window).resize(function () {
        if ($(window).width() > 991) {
            $('.mobile-menu__btn').removeClass('active');
            $('body').removeClass('menu-opened');
        }
    });

    $('.mobile-menu .menu-item-has-children').on('click', function () {
        $(this).toggleClass('opened').find('ul').slideToggle();
    });

    $('.mobile-menu .menu-item-has-children a').on('click', function (e) {
        e.preventDefault();
    });

    // header info

    $('.header-info').on('click', function () {
        $(this).parent().toggleClass('active');
    });

    $(document).click(function () {
        $('.header-info__wrapper').removeClass('active');
    });

    $(document).on('click', '.header-info__wrapper', function (e) {
        e.stopPropagation();
    });

    // header search

    if($('.header-search').length){

        if ($('.header-search input[type="search"]').val().length) {
            $('.header-search button').css('pointer-events', 'auto');
        }
        else {
            $('.header-search button').css('pointer-events', 'none');
        }

        $('.header-search input[type="search"]').on('keyup', function () {
            if ($('.header-search input[type="search"]').val().length) {
                $('.header-search button').css('pointer-events', 'auto');
            }
            else {
                $('.header-search button').css('pointer-events', 'none');
            }
        });

        $('.header-search input[type="search"]').bind('keyup keypress', function (e) {
            var code = e.keyCode || e.which;
            if (code == 13) {
                if ($(this).val() == '') {
                    e.preventDefault();
                    return false;
                }
            }
        });

        $('.header-search input[type="search"]').on('keyup', function (e) {
            var $this = $(this);
            if ($this.val().length < 3) {
                $this.closest('.header-search__wrapper').removeClass('focus').removeClass('active');
                $('.header-search > .search-results').hide();
                $('body').removeClass('open-search-results');
            } else {
                $this.closest('.header-search__wrapper').addClass('focus').addClass('active');
                $('.header-search > .search-results').show();
                $('body').addClass('open-search-results');
            }
        });

        $('.header-mob__search-btn').click(function () {
            $('body').toggleClass('mob-search').removeClass('mob-phones');
            $(this).toggleClass('active');
            $('.header-search__wrapper').toggleClass('show');
            $('.mobile-menu__btn').removeClass('active');
            $('.mobile-menu__wrapper').removeClass('active');
            $('body').addClass('active');
            return false;
        });

        $('.header-search__btn').click(function () {
            $('.header-search__wrapper').addClass('show');
        });

        $(document).click(function () {
            $('.header-search__wrapper').removeClass('show').removeClass('active');
            $('body').removeClass('mob-search').removeClass('mob-menu').removeClass('open-search-results');
            $('.header-search > .search-results').hide();
        });

        $(document).on('click', '.header-search__wrapper', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', '.header-search__wrapper .search-results', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', '.header-search__btn', function (e) {
            e.stopPropagation();
        });

        var $input = $('.header-search input'),
            $content = $('.header-search .search-results'),
            $results,
            currentIndex = 0;

        $input.on('input', function () {
            var searchVal = this.value;
            $('.header-search .search-results li a').each(function () {
                $(this).find('span').bind('DOMSubtreeModified', function () {
                    if ($(this).find('mark').length) {
                        $(this).closest('li').addClass('show').closest('ul').addClass('highlighting-results');
                    }
                    else {
                        $(this).closest('li').removeClass('show');
                    }
                });
            });

            $content.unmark({
                done: function () {
                    $content.mark(searchVal, {
                        separateWordSearch: true,
                        done: function () {
                            $results = $content.find('mark');
                            currentIndex = 0;
                        }
                    });
                }
            });
            if ($('.search-results > ul li.show').length) {
                $('.search-results > div > a').css('display', 'flex');
                $('.search-results > div > span').css('display', 'none');
            }
            else {
                $('.search-results > div > a').css('display', 'none');
                $('.search-results > div > span').css('display', 'flex');
            }
        });
    }

    // cart items

    if ($('.header-cart__main .cart-items li').length) {
        $('.header-cart__main').removeClass('empty');
    }
    else {
        $('.header-cart__main').addClass('empty');
    }

    $(document).on('click', '.cart-item__del', function () {
        var cart_items = $(this).closest('.cart-items').find('li').length;
        var $main_cart = $(this).closest('.header-cart__main');
        cart_items--;
        $(this).closest('li').remove();
        if (cart_items > 0) {
            $main_cart.removeClass('empty');
        }
        else {
            $main_cart.addClass('empty');
        }
    });

    // cart shake

    $('.to-cart-btn').on('click', function () {
        $('.header-cart__btn').addClass('animated').addClass('shake');
        setTimeout(function(){
            $('.header-cart__btn').removeClass('shake');
        }, 1000);
    });

    // filters
    $('.categories-filters__list li').on('click', function () {
        var left_pos = $(this).find('span').width(),
            top_pos = $(this).offset().top - $('.categories-filters__main').offset().top;
        console.log(top_pos);
        $('.categories-filters__submit').css({
            "left": left_pos + 85,
            "top": top_pos - 24,
            "display": "block"
        });
    });

    $('.categories-filter__head').on('click', function () {
        $(this).parent().toggleClass('active');
        $(this).next().slideToggle();
    });

    $('.categories-filters__btn').on('click', function () {
        $('body').addClass('open-filters');
    });

    $('.filters-close').on('click', function () {
        $('body').removeClass('open-filters');
    });

    $(document).click(function () {
        $('body').removeClass('open-filters');
    });

    $(document).on('click', '.categories-filters__btn', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.catalog-filters__mob', function (e) {
        e.stopPropagation();
    });

    $(window).resize(function () {
        if ($(window).width() > 991) {
            $('body').removeClass('filters-opened');
        }
    });

    // reviews

    $('.reviews-btn__open').on('click', function () {
        $('.review-form__top').slideToggle();
    });

    // remove from cart

    $('.del-popup__cancel').on('click', function () {
        $(this).closest('.del-popup').find('.mfp-close').trigger('click');
    });

    // checkout

    $('.step1 .input').keyup(function() {
        var empty = false;

        $('.step1 .input').each(function() {
            empty = $(this).val() === '';
        });

        if (empty){
            $('.step1 .btn').attr('disabled', 'disabled');
        }
        else{
            $('.step1 .btn').attr('disabled', false);
        }
    });

    $('.step1 .btn-next').on('click', function () {
        var name = $(this).closest('.checkout-step__body').find('[name="name"]').val(),
            vorname = $(this).closest('.checkout-step__body').find('[name="vorname"]').val(),
            telefon = $(this).closest('.checkout-step__body').find('[name="telefon"]').val(),
            email = $(this).closest('.checkout-step__body').find('[name="email"]').val();
        $(this).closest('.checkout-step').find('.checkout-step__summary-wrapper > span').html(name + ' ' + vorname + ', ' + telefon + ', ' + email);
        $(this).closest('.checkout-step__body').slideUp();
        $(this).closest('.checkout-step').find('.checkout-step__summary').slideDown();
        $(this).closest('.checkout-step').find('.checkout-step__head').addClass('filled');
        $('.step2 .checkout-step__body').slideDown();
    });

    $('.step1 .step-edit').on('click', function () {
        $(this).closest('.checkout-step').find('.checkout-step__body').slideDown();
        $(this).closest('.checkout-step__summary').slideUp();
        $('.step2 .checkout-step__body').slideUp();
        $('.step3 .checkout-step__body').slideUp();
    });

    $('.step2 .input').on('keyup', function() {
        var empty = false;

        $('.step2 .input').each(function() {
            empty = $(this).val() === '';
        });

        $('.step2 input[type="radio"]').on('click', function() {
            var radio_empty = false;

            $('.step2 input[type="radio"]').each(function() {
                radio_empty = !$('input[type="radio"]:checked').length;
            });
            if (empty && radio_empty){
                $('.step2 .btn').attr('disabled', 'disabled');
            }
            else{
                $('.step2 .btn').attr('disabled', false);
            }
        });
    });

    $('.step2 .btn-next').on('click', function () {
        var land = $(this).closest('.checkout-step__body').find('[name="land"]').val(),
            adresszusatz = $(this).closest('.checkout-step__body').find('[name="adresszusatz"]').val(),
            strase = $(this).closest('.checkout-step__body').find('[name="straße"]').val(),
            hausnummer = $(this).closest('.checkout-step__body').find('[name="hausnummer"]').val(),
            plz = $(this).closest('.checkout-step__body').find('[name="plz"]').val(),
            ort = $(this).closest('.checkout-step__body').find('[name="ort"]').val(),
            versandart = $(this).closest('.checkout-step__body').find('[name="versandart"]:checked').val();
        $(this).closest('.checkout-step').find('.checkout-step__summary-wrapper > span').html('Versandart (' + versandart + '), ' + land + ', ' + adresszusatz + ', ' + strase + ', ' + hausnummer + ', ' + plz + ', ' + ort);
        $(this).closest('.checkout-step__body').slideUp();
        $(this).closest('.checkout-step').find('.checkout-step__summary').slideDown();
        $(this).closest('.checkout-step').find('.checkout-step__head').addClass('filled');
        $('.step3 .checkout-step__body').slideDown();
    });

    $('.step2 .step-edit').on('click', function () {
        $(this).closest('.checkout-step').find('.checkout-step__body').slideDown();
        $(this).closest('.checkout-step__summary').slideUp();
        $('.step1 .checkout-step__body').slideUp();
        $('.step3 .checkout-step__body').slideUp();
    });

    $('.step3 input[type="radio"]').on('click', function() {
        var radio_empty = false;

        $('.step3 input[type="radio"]').each(function() {
            radio_empty = !$('input[type="radio"]:checked').length;
        });
        if (radio_empty){
            $('.step3 .btn').attr('disabled', 'disabled');
        }
        else{
            $('.step3 .btn').attr('disabled', false);
        }
    });

    // validation

    $('.validate-form').each(function () {
        $(this).validate({
            highlight: function (element) {
                $(element).parent().addClass('error');
            },
            unhighlight: function (element) {
                $(element).parent().removeClass('error');
            },
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                telefon: {
                    required: true,
                },
                vorname: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: 'Dieses Feld ist erforderlich'
                },
                email: {
                    required: 'Dieses Feld ist erforderlich',
                },
                telefon: {
                    required: 'Dieses Feld ist erforderlich',
                },
                vorname: {
                    required: 'Dieses Feld ist erforderlich',
                },
            },
            submitHandler: function () {
            }
        });
    });

    // tabs

    $('.tabs').on('click', 'li:not(.active)', function () {
        $(this)
            .addClass('active').siblings().removeClass('active')
            .closest('.tabs-wrapper').find('.tabs-content').removeClass('active').eq($(this).index()).addClass('active');
    });

    // btn go top
    $(window).scroll(function () {
        if ($(this).scrollTop() > 400) {
            $("#gotop").fadeIn();
        } else {
            $("#gotop").fadeOut();
        }
    });
    $("#gotop").click(function () {
        $('body, html').animate({
            scrollTop: 0
        }, 500);
    });

    $(window).resize(function () {
        if ($(window).width() > 574) {
            $('.distributors-info__head').removeClass('opened');
            $('.distributors-info__body').removeAttr('style');
        }
    });

    // product in cart

    if ($(window).width() < 992) {
        $('.in-cart-btn').on('click', function () {
            $('.product-in-cart').toggleClass('active');
        });

        $(document).click(function () {
            $('.product-in-cart').removeClass('active');
        });

        $(document).on('click', '.in-cart-btn', function (e) {
            e.stopPropagation();
        });

        $(document).on('click', '.product-in-cart', function (e) {
            e.stopPropagation();
        });
    }

    $('.in-cart-del').on('click', function () {
        $('.product-in-cart').remove();
    });

    // lazy load
    var lazyload = function () {
        var scroll = $(window).scrollTop() + $(window).height() * 3;

        $('.lazy').each(function () {
            var $this = $(this);
            if ($this.offset().top < scroll) {
                $this.attr('src', $(this).data('original'));
            }
        });
        $('.lazy-web').each(function () {
            var $this = $(this);
            if ($this.offset().top < scroll) {
                $this.attr('srcset', $(this).data('original'));
            }
        });
    };
    $(window).scroll(lazyload);

    // shop =====

    // слацдер на странице товара
    $(window).resize(function () {
        $(".lightgallery").lightGallery({
            tumbnail: true
        });
        if ($(window).width() >= 768) {
            $('.product-pic__main .slick-arrow').html('<svg width="20" height="8" viewBox="0 0 20 8" fill="none"' +
                ' xmlns="http://www.w3.org/2000/svg">\n' +
                '<line y1="4.10001" x2="13.0769" y2="4.10001" stroke="black"/>\n' +
                '<path d="M19 4L13 -2.62268e-07L13 8L19 4Z" fill="black"/>\n' +
                '</svg>');
            $('.product-pic__small .slick-arrow').html('<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<path d="M0.146606 9.51952L4.29363 5.37249L0.146606 1.22546L1.06817 0.303903L6.13675 5.37249L1.06817 10.4411L0.146606 9.51952ZM4.75441 9.51952L8.90144 5.37249L4.75441 1.22546L5.67597 0.303903L10.7446 5.37249L5.67597 10.4411L4.75441 9.51952Z" fill="white"/>\n' +
                '</svg>\n');
        } else {
            $('.product-pic__main .slick-arrow').html('<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<line y1="5.86523" x2="7.84607" y2="5.86523" stroke="black"/>\n' +
                '<g clip-path="url(#clip0_1290_858)">\n' +
                '<path d="M10.9614 6.0772L7.49992 3.76953L7.49992 8.38487L10.9614 6.0772Z" fill="black"/>\n' +
                '</g>\n' +
                '<defs>\n' +
                '<clipPath id="clip0_1290_858">\n' +
                '<rect width="11" height="8" fill="white" transform="translate(11) rotate(90)"/>\n' +
                '</clipPath>\n' +
                '</defs>\n' +
                '</svg>\n');
            $('.product-pic__small .slick-arrow').html('<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
                '<line y1="5.86523" x2="7.84607" y2="5.86523" stroke="black"/>\n' +
                '<g clip-path="url(#clip0_1290_858)">\n' +
                '<path d="M10.9614 6.0772L7.49992 3.76953L7.49992 8.38487L10.9614 6.0772Z" fill="black"/>\n' +
                '</g>\n' +
                '<defs>\n' +
                '<clipPath id="clip0_1290_858">\n' +
                '<rect width="11" height="8" fill="white" transform="translate(11) rotate(90)"/>\n' +
                '</clipPath>\n' +
                '</defs>\n' +
                '</svg>\n');
        }
    });


    // spoiler product-details

    $(".product-about__details-header").click(function () {
        $(this).toggleClass("active").next(".product-about__details-body").slideToggle();
    });

    // zoom product

    $(window).resize(function () {
        if ($(window).width() > 991) {
            $('.zoom-pic').ezPlus({
                borderSize: 0,
                easing: false,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
                zoomWindowHeight: 500,
                zoomWindowWidth: 680
            });
        } else {
            $('.zoom-pic').ezPlus({
                zoomType: 'inner',
                cursor: 'crosshair',
                borderSize: 0,
                zoomWindowHeight: 600,
                zoomWindowFadeIn: 300,
                zoomWindowFadeOut: 300,
                lensFadeIn: 300,
                lensFadeOut: 300,
            });
        }
    });
});

