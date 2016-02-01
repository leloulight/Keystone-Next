$(document).ready(function() {
    if (window.navigator.standalone) {
        $('body').addClass('status-bar');
    }

    $('.bubble-menu-item').click(function(event) {
        var id = $(this).attr('data-id');
        if (id != $('body').attr('data-page')) {
            $('body').attr('class', id + (window.navigator.standalone ? ' status-bar' : ''));
            $('body').attr('data-page', id);

            $('.tiles-wrapper').addClass('tiles-leave');

            $.get('/feed/' + id, function(data) {
                pageUrl = '/' + id;
                window.history.pushState({
                    path: pageUrl
                }, '', pageUrl);

                $('.tiles').html(data);
                $('.tiles-wrapper').removeClass('tiles-leave');
            }).fail(function() {
                window.location.href = '/' + id;
            });
        }
    });
    // .dblclick(function(event) {
    //     var id = $(this).attr('data-id');
    //     if (id == $('body').attr('data-page')) {
    //         window.open('https://keystone.stpeters.sa.edu.au/Pages/MyTasks.aspx');
    //     }
    // }).on("touchstart", function(e) {
    //     if (!this.data('tapped'))
    //         this.data('tapped') = setTimeout(function() {
    //             this.data('tapped', clearTimeout(this.data('tapped')));
    //         }, 300);
    //     else {
    //         clearTimeout(this.data('tapped'));
    //         this.data('tapped') = null
    //         $(this).dblclick();
    //     }
    //     e.preventDefault()
    // });
});

whenReady(function() {
    if (window.navigator.standalone)
        $("a").click(function(event) {
            event.preventDefault();
            window.location = $(this).attr("href");
        });
})