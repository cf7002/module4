;(function ($) {
    var flag = $('#isSubscribe').val();

    var show = function () {
        $('#offerModal').modal('show');
    };

    $(function () {
        if (!flag) setTimeout(show, 15000);
    })
})(jQuery);

(function ($) {
    var price, discount;
    var elem = $('.ads');
    var popContent;
    var popTitle;

    elem.mouseenter(function (e) {
        var body = $('body');
        if (body.attr('data-popover') !== '1') {
            body.attr('data-popover', 1);

            popTitle = "<span>Купон на скидку!</span>" +
                "<button type='button' class='close'><sup><i class='fas fa-times fa-xs'></i></sup></button>";
            popContent = "<div>" + $(this).data('coupon') + "</div><div class='text-center mb-2'><a href='" +
                $(this).find('.vendor').attr('href') + "/" + $(this).data('coupon') +
                "'>примените и получите скидку 10%</a></div>";

            $(this).popover({
                animation: true,
                title: popTitle,
                content: popContent,
                html: true,
            });

            price = $(this).find('.full_price');
            discount = $(this).find('.discount');
            price.fadeOut(200);
            discount.fadeIn(500);
            $(this).popover('show');
        }

        $('.close').click(function (e) {
            elem.popover('hide');
            discount.fadeOut(100);
            price.fadeIn(100);
            $('body').attr('data-popover', 0);
        })
    });
})(jQuery);

(function ($) {
    $('button.vote').click(function (e) {
        var direction = $(this).data('vote');
        var comment_id = $(this).parent("div").data('comment');

        $.ajax({
            type: "POST",
            url: "/comment/vote",
            data: {
                comment_id: comment_id,
                direction: direction
            },
            dataType: "html"
        })
            .done(function(response) {
                alert(response);
            })
            .fail(function() {
                alert("Request failed: Ошибка запроса");
            });
    })
})(jQuery);

(function ($) {
    $('a.reply').click(function (e) {
        // var form = $('#replyComment');
        var comment_id = $(this).parent("div").data('comment');
        // $('#replyComment').css("display", "block");
        $('#replyComment').show();
        $('#newComment').hide();
        $('#inputComment').val(comment_id);
        console.log(comment_id);
    })
})(jQuery);
