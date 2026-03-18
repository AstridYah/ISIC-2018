<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<h2><?= Html::img('@web/images/ISICS2018.png') ?></h2>
<h2>PayPal Payment Confirmation - ISICS 2018</h2>
<p>Thank you for paying through PayPal you registration for the ISICS 2018 taking place at Merida, Mexico from March 21-23, 2018.</p>
<p>Your ISICS <strong>Registration Confirmation</strong> will be sent via email in the next 24 hours. If you do not received it, please, contact the Registration Chair at admin@isics-symposium.org attaching a copy of your PayPal payment receipt.</p>
<?= Html::a(Yii::t('app', 'Continue'), ['submitted', 'id' => $model->id, 'token'=>$model->token], ['class' => 'btn btn-success']) ?>