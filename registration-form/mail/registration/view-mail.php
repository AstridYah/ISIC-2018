<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\models\AdditionalTickets;


/* @var $this yii\web\View */
/* @var $model app\models\Registration */

?>

<style>
	table{
		width: 700px;
		border: solid 2px gray;
	}
	th {
		border: solid 2px gray;
		padding: 3px;
	}
	td{
		padding: 3px;

	}
</style>

<div class="registration-view">
	
    <img src="https://www.isics-symposium.org/registration-form/web/images/ISICS2018.png" />

  
	<?php if( empty( $model->paid_by_credit_card ) && empty($model->payment_receipt) && empty($model->registrationCode) ): ?>
	<div class="alert alert-warning">
		<h2>Pending Registration - ISICS 2018</h2>
		<p>Dear <?= Html::encode($model->prefix) ?> <?= Html::encode($model->fullName) ?>, 
        <br />
        Thank you for your interest in attending the ISICS 2018 taking place at Merida, Mexico from March 21-23, 2018.
        </p>
        <p>To complete your registration you need to pay online with credit or debit card or upload your payment receipt using the link below.</p>

		<p><?= Html::a(Yii::t('app', 'Complete Registration'), Url::to(['submitted', 'id' => $model->id, 'token' => $model->token],true), ['class' => 'btn btn-primary']) ?></p>
	</div>
	<?php endif; ?>
	
    
	<?php if( !empty( $model->paid_by_credit_card ) || !empty($model->payment_receipt) || !empty($model->registrationCode) ): ?>
	<div class="alert alert-success">
		<h2>Registration Confirmation - ISICS 2018</h2>
		<p>Dear <?= Html::encode($model->prefix) ?> <?= Html::encode($model->fullName) ?>, 
        <br />
        Thank you for registering for the ISICS 2018 taking place at Merida, Mexico from March 21-23, 2018.</p>
	</div>
	<?php endif; ?>

		<div class="alert alert-info">
		<p>You can view or update your data using the link below.
		<br /><?= Html::a(Yii::t('app', 'Update Registration'), Url::to(['submitted', 'id' => $model->id, 'token' => $model->token],true), ['class' => 'btn btn-primary']) ?></p>
	</div>

	<p> <?= date("l"), ", ", date("F"), " ", date("d"), ", ", date("Y")  ?> </p>

	<p><?= Html::encode($model->prefix) ?> <?= Html::encode($model->fullName) ?>, 
    <br />
    <?= Html::encode($model->organization_name) ?>
    <br />
    <?= Html::encode($model->city) ?>, <?= Html::encode($model->country) ?>
    <br />
    <?= Html::encode($model->zip) ?>
    <br />
    <?= Html::encode($model->email) ?>
    <br />
    Dietary restrictions: <?= Html::encode($model->diet) ?>
    </p>
    <p>
    Registration Number: <?= Html::encode($model->folio) ?>
    </p>
    <p>
    Registration Type: <?= Html::encode($model->registrationType->name) ?>
    </p>

    <?php if( !empty( $model->contribution_type1 ) && !empty($model->contribution_title1) ): ?>
    <p>
    Contribution 1 (Type / Title): <?= Html::encode($model->contribution_type1.' / '.$model->contribution_title1) ?>
    </p>
    <?php endif; ?>
    <?php if( !empty( $model->contribution_type2 ) && !empty($model->contribution_title2) ): ?>
    <p>
    Contribution 2 (Type / Title): <?= Html::encode($model->contribution_type2.' / '.$model->contribution_title2) ?>
    </p>
    <?php endif; ?>	
	
    
	<?php if(empty($model->registrationCode)): ?>

        <?php if( ($model->id) > 22): ?>

	<h3> Registration Summary / Invoice</h3>

	<table>
  
		<tr>
			<th style="width:470px">Details</th>
			<th style="width:30px">#</th>
			<th style="width:80px">Fee</th>
			<th style="width:120px">Sub-Total</th>
		</tr>
		<tr>
			<td><?= Html::encode($model->registrationType->name) ?></td>
			<td align="center">1</td>
			<td align="right">$ <?= Html::encode($model->registrationType->cost) ?> </td>
			<td align="right">$ <?= Html::encode($model->registrationType->cost) ?> </td>
		</tr>
		
		<?php $banquet_ticket = AdditionalTickets::findOne(1); ?>

		<?php if( ($model->banquet_ticket) > 0 ): ?>
			<tr>
				<td><?= Html::encode($banquet_ticket->name)?></td>
				<td align="center"><?= Html::encode($model->banquet_ticket) ?>  </td>
				<td align="right">$ <?= Html::encode($banquet_ticket->price) ?></td>
				<td align="right">$ <?= ($model->banquet_ticket*$banquet_ticket->price) ?></td>
			</tr>
		<?php endif; ?>


		<?php
			$total = ($model->registrationType->cost) + $model->banquet_ticket*$banquet_ticket->price;
		?>
			<tr>
				<td></td>
				<td></td>
				<td  style = "border-top: solid 2px gray; "> Total :</td>
				<td  style = "border-top: solid 2px gray; " align="right">MXN  $ <?= $total ?> </td>
			</tr>

	</table>
    <p>
    Prices are in Mexican pesos (MXN).
    </p>

    <?php endif; ?>


    <?php if( ($model->id) <= 22): ?>
	<h3> Registration Summary / Invoice</h3>

	<table>
  
		<tr>
			<th style="width:470px">Details</th>
			<th style="width:30px">#</th>
			<th style="width:80px">Fee</th>
			<th style="width:120px">Sub-Total</th>
		</tr>
		<tr>
			<td><?= Html::encode($model->registrationType->name) ?></td>
			<td align="center">1</td>
			<td align="right">$ <?= Html::encode($model->registrationType->cost_early_bird) ?> </td>
			<td align="right">$ <?= Html::encode($model->registrationType->cost_early_bird) ?> </td>
		</tr>
		
		<?php $banquet_ticket = AdditionalTickets::findOne(1); ?>

		<?php if( ($model->banquet_ticket) > 0 ): ?>
			<tr>
				<td><?= Html::encode($banquet_ticket->name)?></td>
				<td align="center"><?= Html::encode($model->banquet_ticket) ?>  </td>
				<td align="right">$ <?= Html::encode($banquet_ticket->price_early) ?></td>
				<td align="right">$ <?= ($model->banquet_ticket*$banquet_ticket->price_early) ?></td>
			</tr>
		<?php endif; ?>	



		<?php
			$total = ($model->registrationType->cost_early_bird) + $model->banquet_ticket*$banquet_ticket->price_early;
		?>
			<tr>
				<td></td>
				<td></td>
				<td  style = "border-top: solid 2px gray; "> Total :</td>
				<td  style = "border-top: solid 2px gray; " align="right">MXN  $ <?= $total ?> </td>
			</tr>

	</table>
    <p>
    Prices are in Mexican pesos (MXN).
    </p>

    <?php endif; ?>
	
    <?php endif; ?>


	<?php if(!empty($model->invoice)): ?>
	
	<h3>Datos de Facturación</h3>
	
	<?= DetailView::widget([
        'model' => $model->invoice,
        'attributes' => [
			'business_name',
			'rfc',
			'address',
			'zip_code',
			'city',
			'state',
			'email',
        ],
    ]) ?>
	
	<?php endif; ?>
        
        
    <?php if(!empty($model->registrationCode)): ?>
	<p>Registration Code: <?= Html::encode($model->registrationCode->code) ?></p>
	<?php endif; ?>

</div>
