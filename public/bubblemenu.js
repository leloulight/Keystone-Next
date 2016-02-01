$(document).ready(function() {
    var bubbles = [{
        name: 'SportsZone',
        initials: 'SZ',
        id: 'sportszone'
    }, {
        name: 'Timetable',
        initials: 'TT',
        id: 'timetable'
    }, {
        name: 'Homework',
        initials: 'HW',
        id: 'homework'
    }, {
        name: 'Notifications',
        initials: 'NT',
        id: 'notifications'
    }, {
        name: 'Pastoral Care',
        initials: 'PC',
        id: 'pastoralcare'
    }, {
        name: 'Options',
        initials: 'OP',
        id: 'options'
    }];

    var scaleShift = function($bubble, percentage, shift) {
        //  translateX(' + shift + '%)
        // $bubble.css('transform', 'scale(' + percentage + ')');
        $bubble.css('flex-grow', percentage);
    }

    function gaussian(x, inverted) {
        x = Math.max(Math.min(x, 1 / 4), -1 / 4);
        return Math.cos(4 * Math.PI * x) + 2;
    }
    var scaleAt = function(percentage) {
        var children = $(this).children();
        var length = (bubbles.length - 1)
        for (var i = 0; i < bubbles.length; i++) {
            var gaussianX = ((i - (length / 2)) / bubbles.length) + percentage;
            scaleShift($(children[i]), gaussian(gaussianX) + 1, gaussian(gaussianX, true) * 70);
        };
    }

    var selected;
    var labelDebounceTimer;
    var lockUntilLeave;
    var labelHiddenTimer;
    var clearDirectionTimer;
    var labelExitTimer;
    var isClick;

    var updateLabel = function(newSelected) {
        labelHiddenTimer = clearTimeout(labelHiddenTimer);
        clearDirectionTimer = clearTimeout(clearDirectionTimer);

        var enterDirection = newSelected > selected ? 'left' : 'right';
        var exitDirection = newSelected > selected ? 'right' : 'left';

        if (selected !== undefined) {
            var $oldActive = $('.selection-label:not(.left):not(.right):not(.hidden)');
            $oldActive.addClass(exitDirection);
            labelHiddenTimer = setTimeout(function() {
                $oldActive.addClass('hidden').removeClass('left right');
            }, 200);
        }

        var $newActive = bubbles[newSelected].$label;
        $newActive.addClass(enterDirection).removeClass('hidden');
        setTimeout(function() {
            $newActive.removeClass('left right');
        }, 10);

        selected = newSelected;
    }

    var endHover = function() {

    }

    for (var i = 0; i < bubbles.length; i++) {
        var $bubble = $('<div class="bubble-menu-item bubble-' + bubbles[i].id + '"><div class="bubble-name">' + bubbles[i].initials + '</div></div>');
        $('.bubble-menu').append($bubble);
        bubbles[i].$bubble = $bubble;

        var $label = $('<div class="selection-label hidden label-' + bubbles[i].id + '">' + bubbles[i].name + '</div>');
        $('.selection').append($label);
        bubbles[i].$label = $label;
    };


    $('.bubble-menu').on('touchstart mousedown', function(event) {
        $('.bubble-menu-item').removeClass('no-animation');
        lockUntilLeave = false;
        var x = (event.originalEvent.touches ? event.originalEvent.touches[0].pageX : event.originalEvent.pageX) - this.offsetLeft;
        var percentage = Math.max(Math.min(x / $(this).width(), 1), 0);
        scaleAt.call(this, .5 - percentage);
        var newSelected = Math.floor(percentage * bubbles.length);
        updateLabel(newSelected);
        isClick = setTimeout(function() {
            isClick = false;
        }, 200);
    }).on('touchmove mousemove', function(event) {
        if (!isClick && !lockUntilLeave) {
            console.log('touchmove');
            var x = (event.originalEvent.touches ? event.originalEvent.touches[0].pageX : event.originalEvent.pageX) - this.offsetLeft;
            var percentage = Math.max(Math.min(x / $(this).width(), 1), 0);
            scaleAt.call(this, 0.5 - percentage);

            var newSelected = Math.floor(percentage * bubbles.length);
            if (newSelected != selected) {
                if (labelDebounceTimer) {
                    clearTimeout(labelDebounceTimer);
                }
                labelDebounceTimer = setTimeout(function() {
                    updateLabel(newSelected);
                }, 50);
            }

            $('.bubble-menu-item').addClass('no-animation');
            event.preventDefault();
        }
    }).on('touchend scrollstart mouseup mouseleave', function(event) {
        labelDebounceTimer = clearTimeout(labelDebounceTimer);
        if (selected !== undefined) {
            labelHiddenTimer = clearTimeout(labelHiddenTimer);
            clearDirectionTimer = clearTimeout(clearDirectionTimer);
            labelExitTimer = clearTimeout(labelExitTimer);

            $('body').attr('class', bubbles[selected].id);
            selected = undefined;
            $('.selection-label:not(.hidden)').addClass('exit-up').removeClass('left right');
            labelExitTimer = setTimeout(function() {
                $('.selection-label.exit-up').removeClass('exit-up').addClass('hidden');
            }, 200);

            if (isClick) {

                console.log('its a click');
                isClick = clearTimeout(isClick);
                $('.bubble-menu-item').addClass('no-animation');
                scaleShift($('.bubble-menu-item'), 1);
                // setTimeout(function() {
                // $('.bubble-menu-item').removeClass('no-animation');
                // }, 10);
            }
            else {
                $('.bubble-menu-item').removeClass('no-animation');
                scaleShift($('.bubble-menu-item'), 1);
            }
        }
        event.preventDefault();

    }).on('mouseup touchend', function() {
        lockUntilLeave = true;
    }).on('mouseleave', function() {
        lockUntilLeave = false;
    });
});