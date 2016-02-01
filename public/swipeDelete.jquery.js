function debounce(delay, callback) {
    var timeout = null;
    return function() {
        //
        // if a timeout has been registered before then
        // cancel it so that we can setup a fresh timeout
        //
        if (timeout) {
            clearTimeout(timeout);
        }
        var args = arguments;
        var _this = this;
        timeout = setTimeout(function() {
            callback.apply(_this, args);
            timeout = null;
        }, delay);
    };
}

function throttle(delay, callback) {
    var previousCall = new Date().getTime();
    return function() {
        var time = new Date().getTime();

        //
        // if "delay" milliseconds have expired since
        // the previous call then propagate this call to
        // "callback"
        //
        var _this = this;
        if ((time - previousCall) >= delay) {
            previousCall = time;
            callback.apply(_this, arguments);
        }
    };
}

function updateHeadings(deltaY) {
    deltaY = deltaY ? deltaY : 0;
    $('.heading').each(function(hIndex, heading) {
        var $heading = $(heading);
        var $tiles = $heading.nextUntil('.heading');
        var isHidden = true;
        var isRecentHidden = false;
        $tiles.each(function(tIndex, tile) {
            if ($(tile).hasClass('complete-recent')) {
                isRecentHidden = true;
                isHidden = false;
                return false;
            }
            else if (!$(tile).hasClass('complete-invisible') && !$(tile).hasClass('deleting')) {
                isHidden = false;
                return false;
            }
        });

        if (isHidden && !$heading.hasClass('complete')) {
            $heading.addClass('complete-invisible complete complete-animate-away');
            deltaY -= $heading.outerHeight(true);
            $heading.nextAll().not('.deleting').css('transform', 'translateY(' + deltaY + 'px)');
            setTimeout(function() {
                $heading.removeClass('complete-animate-away');
            }, 300)
        }
        else if (isRecentHidden)
            $heading.addClass('complete-recent').removeClass('complete-invisible');
        else if (!isHidden)
            $heading.removeClass('complete-invisible complete');
    });
}

(function($) {

    $.fn.swipeDelete = function(onDelete) {
        var height = this.outerHeight(true);
        var startX;
        var startY;
        var deltaX;
        var deltaY;
        var deltaMax;
        var isDown = false;
        var percentage;

        return this.click(function() {
            if ($(this).hasClass('complete')) {
                $(this).removeClass('complete').removeClass('complete-recent');
                onDelete($(this), false);
            }
        }).on('touchstart mousedown', function(event) {
            if (!$(this).hasClass('complete')) {
                isDown = true;
                startX = event.originalEvent.touches ? event.originalEvent.touches[0].pageX : event.originalEvent.pageX;
                startY = event.originalEvent.touches ? event.originalEvent.touches[0].pageY : event.originalEvent.pageY;
                $(this).removeClass('animate').nextAll().removeClass('animate');
            }
        }).on('touchmove mousemove', function(event) {
            if (isDown) {
                deltaX = (event.originalEvent.touches ? event.originalEvent.touches[0].pageX : event.originalEvent.pageX) - startX;
                deltaY = (event.originalEvent.touches ? event.originalEvent.touches[0].pageY : event.originalEvent.pageY) - startY;
                deltaMax = Math.min(screen.width, 700);
                if (event.cancelable && Math.abs(deltaX) > Math.abs(deltaY)) {
                    percentage = Math.max(Math.min(1 - Math.abs(deltaX) / deltaMax, 1), 0);
                    $(this).css('transform', 'translateX(' + deltaX + 'px)').css('opacity', 0.6 + 0.4 * percentage).css('filter', 'grayscale(' + (1 - percentage) * 100 + '%)').css('-webkit-filter', 'grayscale(' + (1 - percentage) * 100 + '%)');
                    // $(this).nextAll().css('transform', 'translateY(-' + height * (1 - percentage) + 'px)');
                    event.preventDefault();
                }
                else
                    $(this).trigger('mouseup');
            }
        }).on('touchend mouseup mouseleave scrollstart', function(event) {
            if (isDown) {
                isDown = false;
                var newX = 0;
                var newY = 0;
                var newScale = 1;
                var $this = $(this);
                if (percentage <= 0.6) {
                    newX = screen.width * (deltaX / Math.abs(deltaX))
                    newScale = 0;
                    newY = -height / 2;

                    onDelete($this, true);


                    setTimeout(function() {
                        $this.nextAll().removeClass('animate').css('transform', '');
                        $this.remove();
                    }, 300);
                }
                else {
                    setTimeout(function() {
                        $this.removeClass('animate');
                        $this.nextAll().removeClass('animate');
                    }, 300);
                }
                // TODO: fix the jump
                $this.nextAll().addClass('animate').css('transform', 'translateY(' + (parseInt($this.css('marginTop')) + newY * 2) + 'px)');
                $this.addClass((newScale == 0) ? 'deleting' : '').addClass('animate').css('transform', 'translateX(' + newX + 'px)').css('opacity', '1').css('filter', 'grayscale(' + (1 - newScale) * 100 + '%)').css('-webkit-filter', 'grayscale(' + (1 - newScale) * 100 + '%)');

                updateHeadings(newY * 2);
            }
        });
    }

})(jQuery);