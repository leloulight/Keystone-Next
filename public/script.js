var isReady = false;

function whenReady(callback) {
    if (isReady)
        callback();
    else
        $(document).ready(callback);
}

$(document).ready(function() {
    isReady = true;
});
