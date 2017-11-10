(function ($) {

    // Layout styles
    var top,
        left,
        padding_position = 'bottom',
        container = $('.ipff-feed-wrapper._2-layout'),
        container_height;

    if (container.length > 0 && $(window).width() > 992) {
        _layout2();

        $(window).resize(function () {
            reset_style();

            _layout2();
        });
    }

    function _layout2() {

        container_height = container.height();

        $('.item', container).each(function (i) {

            top = $(this).position().top;
            left = $(this).position().left;

            $(this).css({
                'left': left,
                'top': top,
            });

        }).promise().done(function () {

            container.height(container_height);
            container.addClass('ipff-overflow-hidden');

            $('.item', container).addClass('ipff-p-absolute');

            $('.item', container).each(function (i) {

                if (i % 3 == 0) {
                    $(this).css('padding-' + padding_position, '25%');
                    $(this).css('z-index', 1);

                    padding_position = switch_padding_position(padding_position);
                }

            });

        });

    }

    function switch_padding_position(current) {

        if (current == 'bottom')
            return 'right'
        else
            return 'bottom'

    }

    function reset_style() {

        $('.item', container).each(function (i) {

            var bg_img = $(this).css('background-image');

            $(this).removeAttr('style').css('background-image', bg_img);
            $(this).removeClass('ipff-p-absolute');

        })

        container.removeAttr('style');

    }

})(jQuery);
