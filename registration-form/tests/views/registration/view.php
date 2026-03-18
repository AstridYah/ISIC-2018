<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use app\models\AdditionalTickets;

/* @var $this yii\web\View */
/* @var $model app\models\Registration */


	$s_transm  = $model->create_s_transm();
	
	$c_referencia  = $model->create_c_referencia();
	
	$t_servicio = '99'; // Servicios
	
	$val_6 = '825'; // Clave Cuenta Bancaria
	
	$t_importe = $model->total_payment; // Total de Importe
	
	$val_7 = '58'; // Servicio a utilizar
	
	$s_desc = $model->folio.': '.$model->fullName.', '.$model->registrationType->nameCost; // Descripción
	
	$s_idioma = '02'; // Idioma
	
	$s_concepto = 'Registration Fee ISMAR 2016'; // Concepto del servicio
	
	$s_nom = $model->first_name.'/'.$model->last_name.'/ /'; // Nombre completo
	
	$s_email = $model->email; // Correo electrónico
	
	$val_8 = '111'; // Medio de pago
	
	$s_verificacion = '0p78fYu54i98utn88vya5oi2n%fg2z65%8a47e!s!!09mG4spi&%hgs';

?>
<?php
if($model->payment_type == 1 && empty($model->paid_by_credit_card) && Yii::$app->request->get('credit_card') == 'yes')
{
	$this->registerJs('
		$("#form-credit-card").submit();
	');
}
if($model->payment_type == 4 && empty($model->paid_by_credit_card) && Yii::$app->request->get('paypal') == 'yes')
{
	$this->registerJs('
		window.location.href = "'.Url::to(['registration/paypal-view']).'";
	');
}
?>
<div class="registration-view">
   
		<?php if( empty( $model->paid_by_credit_card ) && empty($model->payment_receipt) && empty($model->registrationCode) ): ?>
		<div class="alert alert-warning">
			<h2>Pending Registration - IEEE ISMAR 2016</h2>
			<p><?= Html::encode($model->prefix) ?> <?= Html::encode($model->fullName) ?>, <br /> Thank you for registering for the IEEE ISMAR 2016 taking place at Merida, Mexico from September 19-23, 2016.</p>
            <p>To complete your registration you need to pay online with credit or debit card or upload your payment receipt using the buttons below.</p>
			<p> If you have paid by PayPal, your ISMAR Registration Confirmation will be sent via email in the next 24 hours. If you do not received it, please, contact the Registration Chair at registration@ismar2016.org attaching a copy of your PayPal payment receipt.</p>
		</div>
		<?php endif; ?>
		
		<?php if( !empty( $model->paid_by_credit_card ) || !empty($model->payment_receipt) || !empty($model->registrationCode) ): ?>
		<div class="alert alert-success">
			<h2>Registration Confirmation - IEEE ISMAR 2016</h2>
			<p><?= Html::encode($model->prefix) ?> <?= Html::encode($model->fullName) ?>, <br /> Thank you for registering for the IEEE ISMAR 2016 taking place at Merida, Mexico from September 19-23, 2016.</p>
		</div>
		<?php endif; ?>

	<div class="col-md-12">
	
	<?php if( Yii::$app->user->isGuest ): ?>
		<?= Html::a(Yii::t('app', 'Update'), ['update-submit', 'id' => $model->id, 'token'=>$model->token], ['class' => 'btn btn-primary']) ?>
	<?php else: ?>
		<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	<?php endif; ?>
	
	<?php if( empty( $model->paid_by_credit_card ) && empty($model->payment_receipt) && empty($model->registrationCode) ): ?>
			
			<?= Html::beginForm(
				'http://www.pagos.uady.mx/sim/RecibePago/uady/registropago.php',
				'post',
				[
					'id'=>'form-credit-card',
					'style' => 'display:inline',
				]
			) ?>
		
			<?= Html::a(Yii::t('app', 'Upload Payment Receipt'), ['upload-payment-receipt', 'id' => $model->id, 'token' => $model->token ], ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('app', 'Pay by PayPal'), ['paypal-view'], ['class' => 'btn btn-primary']) ?>
			
			<?php if( !Yii::$app->user->isGuest ): ?>
			
			<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
					'method' => 'post',
				],
			]) ?>
			
			<?php endif; ?>
			
			
			
			<?= Html::hiddenInput('s_transm', $s_transm) ?>
			<?= Html::hiddenInput('c_referencia', $c_referencia) ?>
			<?= Html::hiddenInput('t_servicio', $t_servicio) ?>
			<?= Html::hiddenInput('val_6', $val_6) ?>
			<?= Html::hiddenInput('val_7', $val_7) ?>
			<?= Html::hiddenInput('t_importe', $t_importe) ?>
			<?= Html::hiddenInput('s_desc', $s_desc) ?>
			<?= Html::hiddenInput('s_idioma', $s_idioma) ?>
			<?= Html::hiddenInput('s_concepto', $s_concepto) ?>
			<?= Html::hiddenInput('s_nom', $s_nom) ?>
			<?= Html::hiddenInput('s_email', $s_email) ?>
			<?= Html::hiddenInput('val_8', $val_8) ?>
			<?= Html::hiddenInput('s_verificacion', $s_verificacion) ?>
			
			<?= Html::submitButton('Pay by Credit Card', ['class' => 'btn btn-primary']) ?>
			
			<?= Html::endForm() ?>
	<?php endif; ?>
	</div>
	

    <?= DetailView::widget([
		'template' => '<tr><th width="250px">{label}</th><td>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
			'folio',
			[
				'label' => 'Registration Type',
				'value' => (empty($model->one_day_registration))?$model->registrationType->name:$model->registrationType->name.' ('.$model->getOneDayRegistrationText($model->one_day_registration).')',
			],
            'organization_name',
			'prefix',
            'first_name',
            'last_name',
            'display_name',
            'address',
            'city',
            'state',
            'zip',
            'country',
            'business_phone',
            'fax',
            'email:email',
            'emergency_name',
            'emergency_phone',
			'diet',
			[
				'label' => 'Student Proof',
				'value' => Html::a($model->student_id, ['registration/view-student-id', 'id'=>$model->id, 'token'=>$model->token]),
				'format' => 'html',
				'visible' => !empty($model->student_id),
			],
			[
				'label' => 'Contribution 1 (Type / Title)',
				'value' => $model->contribution_type1.' / '.$model->contribution_title1,
				'visible' => !empty($model->contribution_type1) && !empty($model->contribution_title1),
			],
			[
				'label' => 'Contribution 2 (Type / Title)',
				'value' => $model->contribution_type2.' / '.$model->contribution_title2,
				'visible' => !empty($model->contribution_type2) && !empty($model->contribution_title2),
			],
			[
				'label' => 'Workshops and Tutorials',
				'value' => $model->getListWorkShops(),
				'format' => 'html',
			],
			'banquet_ticket',
			'proceedings_copies',
			'reception_ticket',
			[
				'label' => 'Registration Code',
				'attribute' => 'registrationCode.code',
				'visible' => !empty($model->registrationCode),
			],
			[
				'label' => 'Payment Receipt',
				'value' => Html::a($model->payment_receipt, ['registration/view-payment-receipt', 'id'=>$model->id, 'token'=>$model->token]),
				'format' => 'html',
				'visible' => $model->payment_type == 2,
			],
			'creation_date',
			[
				'attribute' => 'modification_date',
				'visible' => !empty($model->modification_date),
			],
			[
				'attribute' => 'paid_by_credit_card',
				'visible' => $model->paid_by_credit_card == true,
				'value' => ($model->paid_by_credit_card)? 'Yes': 'No',
			],
			[
				'attribute' => 'credit_card_import',
				'visible' => $model->paid_by_credit_card == true,
			],
			[
				'attribute' => 'credit_card_autorization',
				'visible' => $model->paid_by_credit_card == true,
			],
			[
				'attribute' => 'credit_card_date_paid',
				'visible' => $model->paid_by_credit_card == true,
			],
        ],
    ]) ?>
	
	<?php if(!empty($model->invoice)): ?>
	
	<h2>Datos de Facturación</h2>
	
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
	
	<?php if(empty($model->registrationCode)): ?>
	<h2>Registration Payment</h2>
	<table class="table table-striped table-bordered detail-view">
  
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
		<?php $proceedings_copies = AdditionalTickets::findOne(2); ?>
		<?php $reception_ticket = AdditionalTickets::findOne(3); ?>

		<?php if( ($model->banquet_ticket) > 0 ): ?>
			<tr>
				<td><?= Html::encode($banquet_ticket->name)?></td>
				<td align="center"><?= Html::encode($model->banquet_ticket) ?>  </td>
				<td align="right">$ <?= Html::encode($banquet_ticket->price) ?></td>
				<td align="right">$ <?= ($model->banquet_ticket*$banquet_ticket->price) ?></td>
			</tr>
		<?php endif; ?>

		<?php if( ($model->proceedings_copies) > 0 ): ?>
			<tr>
				<td><?= Html::encode($proceedings_copies->name)?></td>
				<td align="center"><?= Html::encode($model->proceedings_copies) ?> </td>
				<td align="right">$ <?= Html::encode($proceedings_copies->price) ?></td>
				<td align="right">$ <?= ($model->proceedings_copies*$proceedings_copies->price) ?></td>
			</tr>
		<?php endif; ?>
		
		<?php if( ($model->reception_ticket) > 0 ): ?>
			<tr>
				<td><?= Html::encode($reception_ticket->name)?></td>
				<td align="center"><?= Html::encode($model->reception_ticket) ?></td>
				<td align="right">$ <?= Html::encode($reception_ticket->price) ?></td>
				<td align="right">$ <?= ($model->reception_ticket*$reception_ticket->price) ?></td>
			</tr>
		<?php endif; ?>


		<?php
			$total = ($model->registrationType->cost) + $model->banquet_ticket*$banquet_ticket->price + $model->proceedings_copies*$proceedings_copies->price + $model->reception_ticket*$reception_ticket->price;
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
</div>







