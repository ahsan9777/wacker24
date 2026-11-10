(function ($) {
    "use strict";

    var ver = 'simpleGallery-1.0.2';
    var loadedBigImages = {};

    function preloadUrl(url, cb) {
        if (!url) return;
        if (loadedBigImages[url]) {
            if (cb) cb();
            return;
        }
        var preImg = new Image();
        preImg.onload = function () {
            loadedBigImages[url] = true;
            if (cb) cb();
        };
        preImg.src = url;
    }

    $.fn.simpleGallery = function (options) {
        var opts = $.extend({}, $.fn.simpleGallery.defaults, options);
        var $thumbs = this;

        if ($thumbs.length <= 0) {
            return false;
        }

        var $galleryContainer = $thumbs.first().closest(opts.gallery_container);
        var $initialBig = $galleryContainer.find(opts.big_image_container).find('.' + opts.big_image_class);
        if ($initialBig.length) {
            var initSrc = $initialBig.attr('src');
            if (initSrc) {
                $initialBig.attr('data-current-big', initSrc);
                if ($initialBig[0].complete && $initialBig[0].naturalWidth > 0) {
                    loadedBigImages[initSrc] = true;
                } else {
                    $initialBig.one('load', function () {
                        loadedBigImages[initSrc] = true;
                    });
                }
            }
        }

        function preloadAllThumbBigImages() {
            $thumbs.each(function () {
                var $wrap = $(this).closest(opts.thumbnail_anchor);
                var bigUrl = $wrap.attr(opts.big_image_attr);
                if (bigUrl) {
                    preloadUrl(bigUrl);
                }
            });
        }

        if ($initialBig.length && !$initialBig[0].complete) {
            $initialBig.one('load', function () {
                setTimeout(preloadAllThumbBigImages, 50);
            });
            setTimeout(preloadAllThumbBigImages, 600);
        } else {
            setTimeout(preloadAllThumbBigImages, 100);
        }

        var switchImage = function (e) {
            if (e && e.type === 'click') {
                e.preventDefault();
            }
            var $thumbImg = $(this).is('img') ? $(this) : $(this).find('img').first();
            var parent_anchor = $(this).closest(opts.thumbnail_anchor);
            if (!parent_anchor.length) return;

            var src = parent_anchor.attr(opts.big_image_attr),
                lens_image = parent_anchor.attr(opts.lens_image_attr),
                title = parent_anchor.attr('title') || $thumbImg.attr('alt') || '',
                thumb_src = $thumbImg.attr('src');

            if (!src) return;

            parent_anchor.closest('.simpleLens-thumbnails-container').find(opts.thumbnail_anchor).removeClass('active-thumb');
            parent_anchor.addClass('active-thumb');

            var image_container = parent_anchor.closest(opts.gallery_container).find(opts.big_image_container);
            var $existingAnchor = image_container.find('.' + opts.parent_anchor_class);
            var $existingImg = image_container.find('.' + opts.big_image_class);

            if ($existingImg.length && $existingImg.attr('data-current-big') === src) {
                return;
            }

            $('.simpleLens-lens-element, .simpleLens-mouse-cursor').remove();

            if (!$existingAnchor.length || !$existingImg.length) {
                $existingAnchor = $('<a>', {
                    'href': '#',
                    'role': 'button',
                    'class': opts.parent_anchor_class,
                    'data-lens-image': lens_image,
                    'title': title
                }).on('click', function () { return false; });
                $existingImg = $('<img>', {
                    'class': opts.big_image_class,
                    'alt': title
                }).appendTo($existingAnchor);
                image_container.html($existingAnchor);
            } else {
                $existingAnchor.attr(opts.lens_image_attr, lens_image).attr('title', title);
                $existingImg.attr('alt', title);
            }

            $existingImg.attr('data-current-big', src);

            if (loadedBigImages[src]) {
                $existingImg.attr('src', src);
            } else {
                if (thumb_src) {
                    $existingImg.attr('src', thumb_src);
                }
                preloadUrl(src, function () {
                    if ($existingImg.attr('data-current-big') === src) {
                        $existingImg.attr('src', src);
                    }
                });
            }
        };

        $thumbs.on(opts.show_event + ' click', switchImage);
        $thumbs.closest(opts.thumbnail_anchor).on('click', switchImage);

        return this;
    };

    $.fn.simpleGallery.ver = function () { return ver; };

    $.fn.simpleGallery.defaults = {
        thumbnail_anchor: '.simpleLens-thumbnail-wrapper',
        big_image_class: 'simpleLens-big-image',
        lens_image_attr: 'data-lens-image',
        big_image_attr: 'data-big-image',
        parent_anchor_class: 'simpleLens-lens-image',
        gallery_container: '.simpleLens-gallery-container',
        big_image_container: '.simpleLens-big-image-container',
        loading_image: '',
        show_event: 'mouseenter'
    };

})(jQuery);
