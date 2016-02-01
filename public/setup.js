function updateStatus() {
    var text = [
        ['Done! Try refreshing.', 'a second'],
        ['Waiting in queue...', '1 to 5 minutes'],
        ['Updating homework...', '1 minute 20 seconds'],
        ['Updating notifications...', '1 minute 20 seconds'],
        ['Updating Pastoral Care...', '1 minute 15 seconds'],
        ['Updating Sport Zone...', '1 minute'],
        ['Updating timetable...', '40 seconds'],
    ];
    $.get('/setup/state', function(data) {
        if (data == 0)
            window.location.href = '/';
        $('.setup-status').text(text[data][0]);
        $('.setup-time').text('About ' + text[data][1] + ' remaining');
    });

}

$(document).ready(function() {
    updateStatus();
    setInterval(updateStatus, 2000);
});