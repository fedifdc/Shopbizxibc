jQuery(function ($) {
    var number_show = $('.show-more').data('num');
    $(".social-wrapper").each(function () {
        $(this).find("a:gt(" + number_show + "), button:gt(" + number_show + "):not(.show-more)").hide();
    });

    $('.show-more').on('click', function () {
        if ($(this).hasClass('show-less')) {
            $(this).parent().find(" a:gt(" + number_show + "), button:gt(" + number_show + "):not(.show-more)").hide();
            $(this).removeClass('show-less');
            $(this).html('+');
        } else {
            $(this).parent().find("a:gt(" + number_show + "), button:gt(" + number_show + ")").show();
            $(this).addClass('show-less');
            $(this).html('-');
        }

    });
});