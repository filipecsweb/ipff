(function ($) {

    $('[data-toggle="tooltip"]').hover(function () {

        var title = $(this).attr('title');

        $(this).data('tipText', title).removeAttr('title');

        $('<p class="tooltip"></p>')
            .text(title)
            .appendTo('body')
            .fadeIn('slow');

    }, function () {

        $(this).attr('title', $(this).data('tipText'));

        $('.tooltip').remove();

    }).mousemove(function (e) {

        var mousex = e.pageX + 20;
        var mousey = e.pageY + 10;

        $('.tooltip')
            .css({top: mousey, left: mousex})

    });

})(jQuery);
