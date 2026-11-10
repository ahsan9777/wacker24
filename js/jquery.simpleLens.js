/*!
 * jQuery Lens Simple Plugin
 * Version: 1.0.2
 */
(function ($) {
    "use strict";

    var ver = 'simpleLens-1.0.2';
    var lensImageCache = {};

    function preloadLensImage(url, cb) {
        if (!url) return;
        if (lensImageCache[url]) {
            if (cb) cb(lensImageCache[url]);
            return;
        }
        var pre = new Image();
        pre.onload = function () {
            var dims = {
                width: pre.naturalWidth || pre.width || 1200,
                height: pre.naturalHeight || pre.height || 1200
            };
            lensImageCache[url] = dims;
            if (cb) cb(dims);
        };
        pre.src = url;
    }

    $.fn.simpleLens = function (options) {
        var opts = $.extend({}, $.fn.simpleLens.defaults, options);

        var big_image = {
            init: function (image) {
                this.height = image.height() || 445;
                this.width = image.width() || 445;
                this.offset = image.offset() || { left: 0, top: 0 };
                this.position = image.position() || { left: 0, top: 0 };

                this.calc_image_limits();
            },
            calc_image_limits: function () {
                this.limits = {
                    x_left: (this.position.left),
                    x_right: (this.position.left + this.width),
                    y_top: (this.position.top),
                    y_bottom: (this.position.top + this.height)
                };
            },
            calc_view_position: function (e) {
                return {
                    org_x: (e.pageX - this.offset.left),
                    org_y: (e.pageY - this.offset.top)
                };
            }
        };

        var cursor = {
            init: function (parent_anchor, lens_container, ratio, position) {
                this.parent_anchor = parent_anchor;
                this.lens_container = lens_container;
                this.ratio = ratio;

                this.cursor_position = {
                    'top': 0,
                    'left': 0
                };

                this.calc_cursor_size();
                this.insert_cursor(position.org_x, position.org_y);
            },
            destroy: function () {
                $('.' + opts.cursor_class).remove();
            },
            calc_cursor_size: function () {
                this.cursor_height = this.lens_container.height() / this.ratio.y;
                this.cursor_width = this.lens_container.width() / this.ratio.x;
                this.height_center = (this.cursor_height / 2);
                this.width_center = (this.cursor_width / 2);
            },
            update_cursor_position: function (x, y) {
                var new_y = (y - this.height_center);
                var new_bottom_y = (y + this.height_center);
                var new_x = (x - this.width_center);
                var new_right_x = (x + this.width_center);

                var limits = big_image.limits;
                if (parseFloat(limits.y_top) > parseFloat(new_y)) {
                    new_y = limits.y_top;
                } else if (parseFloat(new_bottom_y) > parseFloat(limits.y_bottom)) {
                    new_y = limits.y_bottom - this.cursor_height;
                }

                if (parseFloat(limits.x_left) > parseFloat(new_x)) {
                    new_x = limits.x_left;
                } else if (parseFloat(new_right_x) > parseFloat(limits.x_right)) {
                    new_x = limits.x_right - this.cursor_width;
                }

                this.cursor_position = {
                    'top': new_y,
                    'left': new_x,
                    'center_top': new_y + this.height_center,
                    'center_left': new_x + this.width_center
                };

                if (this.cursor !== undefined) {
                    this.cursor.css(this.cursor_position);
                }
            },
            insert_cursor: function (x, y) {
                $('.' + opts.cursor_class).remove();
                this.cursor = $('<div>', { 'class': opts.cursor_class });
                this.cursor.css({
                    'height': this.cursor_height + 'px',
                    'width': this.cursor_width + 'px'
                });

                this.cursor.appendTo(this.parent_anchor);
                return this.update_cursor_position(x, y);
            }
        };

        var lens = {
            active: false,
            lastEvent: null,
            init: function (parent_anchor) {
                this.parent_anchor = parent_anchor;
                this.parent_div = parent_anchor.parent(opts.parent_class);
                this.big_img_el = parent_anchor.find(opts.big_image_class);
                this.lens_image_url = parent_anchor.attr(opts.lens_image_attr) || this.big_img_el.attr('src');
                this.fallback_url = this.big_img_el.attr('src') || this.lens_image_url;
                this.lens_container = $('<div>', { 'class': opts.lens_class });
                this.lens_image = $('<img>');
                big_image.init(this.big_img_el);
            },
            update_lens_position: function (position) {
                this.lens_image.css({
                    'top': position.top,
                    'left': position.left
                });
            },
            calc_lens_position: function (cursor_position) {
                return {
                    left: (cursor_position.center_left * this.ratio.x - this.container.width) * -1,
                    top: (cursor_position.center_top * this.ratio.y - this.container.height) * -1
                };
            },
            lens_event_bind: function () {
                var that = this;
                var mouse_callback = function (mouse) {
                    that.lastEvent = mouse;
                    var position = big_image.calc_view_position(mouse);
                    cursor.update_cursor_position(position.org_x, position.org_y);
                    that.update_lens_position(that.calc_lens_position(cursor.cursor_position));
                };

                this.parent_anchor.off('mousemove.simpleLens').on('mousemove.simpleLens', mouse_callback);
            },
            lens_event_unbind: function () {
                if (this.parent_anchor !== undefined) {
                    this.parent_anchor.off('mousemove.simpleLens');
                }
            },
            destroy: function () {
                this.active = false;
                cursor.destroy();

                if (this.lens_container !== undefined && this.lens_container.length > 0) {
                    $('.' + opts.lens_class).remove();
                    this.lens_event_unbind();
                }
            },
            applyDimensionsAndPosition: function (imgW, imgH, eventObj) {
                this.container = {
                    width: this.lens_container.width() / 2,
                    height: this.lens_container.height() / 2
                };
                this.img_size = {
                    width: imgW,
                    height: imgH
                };
                this.lens_image.css({
                    'width': imgW + 'px',
                    'height': imgH + 'px',
                    'max-width': 'none'
                });
                this.ratio = {
                    y: imgH / big_image.height,
                    x: imgW / big_image.width
                };
                var position = big_image.calc_view_position(eventObj);
                cursor.init(this.parent_anchor, this.lens_container, this.ratio, position);
                this.update_lens_position(this.calc_lens_position(cursor.cursor_position));
            },
            load: function (e) {
                var that = this;
                this.active = true;
                this.lastEvent = e;

                $('.' + opts.lens_class).remove();
                this.lens_container.appendTo(this.parent_div);

                var targetUrl = this.lens_image_url;
                var cached = lensImageCache[targetUrl];

                if (cached) {
                    this.lens_image.attr('src', targetUrl);
                    this.lens_container.html(this.lens_image);
                    this.applyDimensionsAndPosition(cached.width, cached.height, e);
                    this.lens_event_bind();
                } else {
                    var previewW = Math.max(Math.round(big_image.width * 2.4), 1000);
                    var previewH = Math.max(Math.round(big_image.height * 2.4), 1000);
                    this.lens_image.attr('src', this.fallback_url);
                    this.lens_container.html(this.lens_image);
                    this.applyDimensionsAndPosition(previewW, previewH, e);
                    this.lens_event_bind();

                    preloadLensImage(targetUrl, function (dims) {
                        if (!that.active || that.lens_image_url !== targetUrl) return;
                        that.lens_image.attr('src', targetUrl);
                        that.applyDimensionsAndPosition(dims.width, dims.height, that.lastEvent || e);
                    });
                }
            }
        };

        var init = function (e) {
            if (window.innerWidth <= 768) return;
            var parent_anchor = $(this);
            lens.init(parent_anchor);
            lens.load(e);
        };

        var destroy = function () {
            lens.destroy();
        };

        var $parent = $(this).parents(opts.parent_class);
        $parent.on(opts.open_lens_event, opts.anchor_parent_class, init);
        $parent.on('mouseleave', opts.anchor_parent_class, destroy);

        $(this).closest('.simpleLens-gallery-container').on('mouseenter', function () {
            var $activeAnchor = $parent.find(opts.anchor_parent_class);
            var url = $activeAnchor.attr(opts.lens_image_attr);
            if (url) preloadLensImage(url);
        });
    };

    $.fn.simpleLens.ver = function () { return ver; };

    $.fn.simpleLens.defaults = {
        anchor_parent_class: '.simpleLens-lens-image',
        lens_image_attr: 'data-lens-image',
        big_image_class: '.simpleLens-big-image',
        parent_class: '.simpleLens-big-image-container',
        lens_class: 'simpleLens-lens-element',
        cursor_class: 'simpleLens-mouse-cursor',
        loading_image: '',
        open_lens_event: 'mouseenter'
    };

})(jQuery);
