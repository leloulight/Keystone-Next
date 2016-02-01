whenReady(function() {
    $('.tile.notification').each(function(index, el) {
        $(el).swipeDelete(function($this, read) {
            $.get('/notification/read/' + $this.attr('data-id'), function(data) {
                // if (data != 'true')
                //     alert('An error occurred, try again.');
            });

            console.log($('.tile').length);
            if ($('.tile').length <= 1) {
                $('.tiles').prepend('<div class="feed-empty">You don\'t have any unread notifications.</div>');
                $('.mark-all-read-button').addClass('animate-out').css('transform', 'translateY(' + -$('.feed-empty').addClass('appear').outerHeight(true) + 'px) scaleY(0)').addClass('to-invisible');
            }
        });
    });

    $('.mark-all-read-button').click(function(event) {
        $('.tiles').prepend('<div class="feed-empty">You don\'t have any unread notifications.</div>');
        var deltaY = -$('.feed-empty').addClass('appear').outerHeight(true);
        $('.tiles > div').not('.feed-empty').each(function(index, el) {
            var $el = $(el);
            $el.addClass('animate-out').css('transform', 'translateY(' + deltaY + 'px) scaleY(0)').addClass('to-invisible');
            deltaY -= $el.outerHeight(true);
        });

        $.get('/notification/read/all', function(data) {
            if (data != 'true')
                alert('An error occurred, try again.');
        });
    });
});