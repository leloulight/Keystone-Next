whenReady(function() {
    var touchTimer;
    $('.person').on('touchstart', function(event) {
        var $this = $(this).removeClass('hover');
        clearTimeout(touchTimer);

        if (!$this.hasClass('show-name')) {
            $('.person.show-name').removeClass('show-name');
            $this.addClass('show-name');

            touchTimer = setTimeout(function() {
                $this.removeClass('show-name');
            }, 1500);
        }
        else
            $this.removeClass('show-name');

        event.preventDefault();
    });

    console.log('hiwf');
    $('.map-wrapper').on('mousedown', function() {
        $(this).find('.map').removeClass('scroll-off');
    }).bind('mouseup scroll mousewheel mouseleave', function(event) {
        $(this).find('.map').addClass('scroll-off');
    });

    // $('.map-wrapper').bind('scroll mousewheel', function(event) {

    //     console.log('mm');
    //     $(this).addClass('scroll-off');
    //     event.preventDefault();
    // });
});