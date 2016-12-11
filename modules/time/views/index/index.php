<?php
/* @var $this yii\web\View */
/* @var $activities Activity[] */
/* @var $tracks TimeTrack[] */
//@todo: refactor; ajax refreshing for each activity-element

use app\modules\time\models\Activity;
use app\modules\time\models\TimeTrack;

$this->title = 'Wallet';
$this->params['breadcrumbs'][] = $this->title;

$urlStart = Yii::$app->urlManager->createUrl('/time/tracks/create');
$urlStop = Yii::$app->urlManager->createUrl('/time/tracks/update');
$js = <<<JS
var urlStart = "{$urlStart}";
var urlStop = "{$urlStop}";
JS;
$js .= <<<'JS'

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
        note = $noteField.val();
    $this.attr('disabled', true);
    
    $.ajax({
        method: "POST",
        url: urlStart,
        data: { activityId: aid, note: note },
        dataType: "json",
        complete: function(resp) {
            
        },
        success: function(response) {
            //var response = resp.responseJSON; 
            if (response.status == true) {
                $('#stop-' + aid).attr('disabled', false).attr('data-track-id', response.trackId);
                $('#track-' + aid).attr('data-timestamp', response.timestamp);
                $noteField.hide();
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
        $noteField = $('#note-' + aid);
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
JS;

$this->registerJs($js);
$this->registerCss('.ttl-sec {font-size: 0.7em;}')
?>
<?php if ($activities) : ?>
    <?php foreach ($activities as $activity) : ?>
        <div class="container-fluid activity-container" id="activity-<?= $activity->id ?>" data-id="<?= $activity->id ?>">
            <div class="row">
                <div class="col-xs-12">
                    <h3>
                        <span class="time-track" id="track-<?= $activity->id ?>"
                            <?php if ($activity->activeTrack) : ?>
                                data-timestamp="<?= $activity->activeTrack->timestampStart ?>"
                            <?php endif; ?>
                        >
                            <?php if ($activity->activeTrack) : ?>
                                <?= $activity->activeTrack->duration->format('%d, %h:%i:%s') ?>
                            <?php endif; ?>
                        </span>
                        <span class="name"><?= $activity->name ?></span>
                        <span class="ttl-sec" id="total-<?= $activity->id ?>" data-last="<?= $activity->totalTrackedSeconds ?>"></span>
                    </h3>
                </div>
            </div>
            <div class="row">
                <?php
                if ($activity->activeTrack) {
                    $startStatus = ' disabled';
                    $stopStatus = ' data-track-id="' . $activity->activeTrack->id . '"';
                } else {
                    $startStatus = '';
                    $stopStatus = ' disabled';
                }
                ?>
                <div class="col-xs-6">
                    <button id="start-<?= $activity->id ?>" class="btn btn-lg btn-block btn-success btn-start"
                        data-id="<?= $activity->id ?>"
                        <?= $startStatus ?>
                    >
                        Start
                    </button>
                </div>
                <div class="col-xs-6">
                    <button id="stop-<?= $activity->id ?>" class="btn btn-lg btn-block btn-danger btn-stop"
                        data-id="<?= $activity->id ?>"
                        <?= $stopStatus ?>
                    >
                        Stop
                    </button>
                </div>
            </div>
            <?php if (!$activity->activeTrack) : ?>
                <div class="row">
                    <div class="col-xs-6 form-group ">
                        <label></label>
                        <input id="note-<?= $activity->id ?>" type="text" class="input-lg form-control" placeholder="Note about the future track"/>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

