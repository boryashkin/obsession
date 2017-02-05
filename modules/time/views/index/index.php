<?php
/* @var $this yii\web\View */
/* @var $activities Activity[] */
/* @var $tracks TimeTrack[] */
//@todo: refactor; ajax refreshing for each activity-element

use app\modules\time\models\Activity;
use app\modules\time\models\TimeTrack;
use app\modules\time\models\Plan;

$this->title = 'Time';
$this->params['breadcrumbs'][] = $this->title;

$urlStart = Yii::$app->urlManager->createUrl('/time/tracks/create');
$urlStop = Yii::$app->urlManager->createUrl('/time/tracks/update');

$this->registerAssetBundle(\app\modules\time\assets\TimeTrackAsset::class);
$plans = Plan::find()->where('completeness < 100')->select(['name'])->asArray()->indexBy('id')->column();
?>
<div class="hidden" data-description="This is for js">
    <span id="url-start-holder"><?= $urlStart ?></span>
    <span id="url-stop-holder"><?= $urlStop ?></span>
</div>
<?= \yii\bootstrap\Html::a('Daily stat', ['stat'], ['class' => 'btn btn-default']) ?>
<hr>
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
            <div class="row">
                <div class="col-xs-6 form-group ">
                    <label></label>
                    <input id="note-<?= $activity->id ?>"<?php if ($activity->activeTrack) : ?> style="display: none;"<?php endif; ?> type="text" class="input-lg form-control" placeholder="Note about the future track"/>
                </div>
                <div class="col-xs-6 form-group ">
                    <label></label>
                    <?= \yii\bootstrap\Html::dropDownList('planId', '',  $plans, [
                        'id' => 'plan-' . $activity->id,
                        'prompt' => '',
                        'class' => 'input-lg form-control',
                        'style' => $activity->activeTrack ? 'display: none;' : '',
                    ]) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

