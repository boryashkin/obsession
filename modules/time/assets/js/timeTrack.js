var urlStart = document.getElementById('url-start-holder').innerText,
    urlStop = document.getElementById('url-stop-holder').innerText;
function dateDiff(date1, date2) {
    //Customise date2 for your required future time
    var diff = (date2 - date1)/1000,
        diff = Math.abs(Math.floor(diff));
    var days = Math.floor(diff/(24*60*60)),
        leftSec = diff - days * 24*60*60;
    var hrs = Math.floor(leftSec/(60*60)),
        leftSec = leftSec - hrs * 60*60;
    var min = Math.floor(leftSec/(60)),
        leftSec = leftSec - min * 60;
    var diffObject = {
        'days': days,
        'hours': hrs,
        'minutes': min,
        'seconds': leftSec,
        'totalSeconds': (days * 24 * 60 * 60) + (hrs * 60 * 60) + (min * 60) + leftSec,
    };

    return diffObject;
}
function addNewClock(aid, timestamp) {
    timestamp = parseInt(timestamp) * 1000;
    var clock = new Date(timestamp)
    dateTimers[aid] = {};
    dateTimers[aid]['start'] = clock;
}
function removeClock(aid) {
    return delete dateTimers[aid];
}
/* collection of start-dates of each one activity */
var dateTimers = {};
$('.time-track').each(function() {
    var $this = $(this),
        Cid = $this.closest('.activity-container').attr('data-id');
    if ($this.attr('data-timestamp')) {
        addNewClock(Cid, $this.attr('data-timestamp'));
    }
});

/* refresh timers */
var interval = setInterval(function() {
    for (var aid in dateTimers) {
        var diffObj = dateDiff(new Date(), dateTimers[aid]['start']),
            $timeField = $('#track-' + aid),
            $totalField = $('#total-' + aid),
            totalSec = parseInt($totalField.attr('data-last')) + diffObj['totalSeconds'];
        $timeField.text(diffObj['days'] + ', ' + diffObj['hours'] + ':' + diffObj['minutes'] + ':' + diffObj['seconds']);
        $totalField.text(totalSec);
    }
}, 1000);

$('.btn-start').click(function(){
    var $this = $(this),
        aid = $this.attr('data-id'),
        $noteField = $('#note-' + aid),
        note = $noteField.val(),
        $planField = $('#plan-' + aid),
        planId = $planField.val();
    $this.attr('disabled', true);

    $.ajax({
        method: "POST",
        url: urlStart,
        data: { activityId: aid, note: note, planId: planId },
        dataType: "json",
        complete: function(resp) {

        },
        success: function(response) {
            //var response = resp.responseJSON;
            if (response.status == true) {
                $('#stop-' + aid).attr('disabled', false).attr('data-track-id', response.trackId);
                $('#track-' + aid).attr('data-timestamp', response.timestamp);
                $noteField.hide();
                $planField.hide();
                addNewClock(aid, response.timestamp);
            } else {
                $this.attr('disabled', false);
                var errors = '';
                for (var e in response.errors) {
                    response.errors[e].forEach(function(txt){
                        errors = errors + ' ' + txt;
                    });
                }
                alert('Errors: ' + errors);
            }
        },
        error: function(e) {
            $this.attr('disabled', false);
            alert(e.status + ' ' + e.statusText);
        }
    });
});
$('.btn-stop').click(function(){
    var $this = $(this),
        aid = $this.attr('data-id'),
        trackId = $this.attr('data-track-id'),
        $noteField = $('#note-' + aid),
        $planField = $('#plan-' + aid);
    $this.attr('disabled', true);

    $.ajax({
        method: "POST",
        url: urlStop + '?id=' + trackId,
        data: { activityId: aid, 'action': 'stop' },
        dataType: "json",
        success: function(response) {
            if (response.status) {
                $('#start-' + aid).attr('disabled', false);
                $this.attr('data-track-id', '');
                removeClock(aid);
                $('#track-' + aid).attr('data-timestamp', '').text('');
                $noteField.val('').show();
                $planField.val('').show();
            } else {
                $this.attr('disabled', false);
                var errors = '';
                for (var e in response.errors) {
                    response.errors[e].forEach(function(txt){
                        errors = errors + ' ' + txt;
                    });
                }
                alert('Errors: ' + errors);
            }
        },
        error: function(e) {
            $this.attr('disabled', false);
            alert(e.status + ' ' + e.statusText)
        }
    });
});