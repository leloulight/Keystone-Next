function showComplete() {
    $('.heading.complete-invisible').each(function(hIndex, heading) {
        var groupDeltaY = -$(heading).outerHeight(true);
        $(heading).css('transform', 'scaleY(0)');
        $(heading).nextUntil('.heading').each(function(tIndex, tile) {
            var $tile = $(tile);
            $tile.css('transform', 'translateY(' + groupDeltaY + 'px) scaleY(0)');
            groupDeltaY -= $tile.css('display', 'flex').outerHeight(true);
            $tile.css('block', '')
        });
    });

    var deltaY = $('.button-wrapper').outerHeight(true);
    $('.complete-invisible').each(function(index, el) {
        $(el).removeClass('complete-invisible').addClass('complete-animate-appear');
        deltaY -= $(this).outerHeight(true);
        $(el).nextUntil('.complete-invisible').addClass('to-move').css('transform', 'translateY(' + deltaY + 'px)');
    });


    setTimeout(function() {
        $('.complete-animate-appear').css('transform', '');
        $('.to-move').addClass('animate').removeClass('to-move').css('transform', 'translateY(0)');
    }, 0);
}

function hideComplete() {
    var deltaY = $('.button-wrapper').outerHeight(true);
    $('.tiles > div').not('.button-wrapper').each(function(index, el) {
        var $el = $(el);
        if ($el.hasClass('complete') && !$el.hasClass('complete-recent')) {
            $el.css('transform', 'translateY(' + deltaY + 'px) scaleY(0)').addClass('to-invisible');
            deltaY -= $el.outerHeight(true);
        }
        else {
            $el.css('transform', 'translateY(' + deltaY + 'px)');
        }
    });

    setTimeout(function() {
        $('.to-invisible').addClass('complete-invisible').removeClass('to-invisible').removeClass('complete-animate-appear').css('display', '').css('transform', '');
        $('.tiles div:not(.complete-invisible)').removeClass('animate').css('transform', '');
    }, 300);
}

whenReady(function() {
    var hiddenVisible = false;

    $('.tile.homework').each(function(index, el) {
        var $this = $(this);
        $(el).swipeDelete(function($this, complete) {
            $.get('/homework/complete/' + $this.attr('data-id') + '/' + (complete ? 'true' : 'false'), function(data) {
                if (data != 'true') {
                    console.error(data);
                    alert('An error occurred, try again.');
                }
            });
        });
    });

    $('.toggle-complete-button').click(function(event) {
        if (!hiddenVisible)
            showComplete();
        else
            hideComplete();
        hiddenVisible = !hiddenVisible;

        $(this).text(hiddenVisible ? 'Hide Complete' : 'Show Complete');
    });
});