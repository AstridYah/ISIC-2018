<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\i18n\Formatter;
use app\models\Registration; // I did this
use app\models\RegistrationType;
use app\models\AdditionalTickets;
use app\models\Workshops;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use \yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $registration app\models\Registration */
/* @var $invoice app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$jsWorkShop = '';
foreach($registration->workshop as $workshop){
	$jsWorkShop .= '$("[name=\'workshop[]\'][value=\''.$workshop.'\']").prop("checked", true);';
}

$jsTickets = '';
foreach($registration->tickets as $idTicket => $numTickets)
{
	$jsTickets .= '$("[name=\'Registration[tickets]['.$idTicket.']\']").val(\''.$numTickets.'\');';
}


$registerTypes = RegistrationType::find()->all();
$strJsRegisterTypes = '';
foreach($registerTypes as $registerType)
{
	$strJsRegisterTypes .= '{
		id:'.$registerType->id.', 
		cost: '.$registerType->cost.', 
		costo_early_bird: '.$registerType->cost_early_bird.', 
		cost_late: '.$registerType->cost_late.'
	},';
}

$this->registerJs('
	
	function showFileStudentId()
	{
		$("[name=\'Registration[file_student_id]\']").removeAttr("disabled");
		$(".field-registration-file_student_id").show();
		$(".student-proof-text").show();
	}
	
	function hideFileStudentId()
	{
		$("[name=\'Registration[file_student_id]\']").attr("disabled","disabled");
		$(".field-registration-file_student_id").hide();
		$(".student-proof-text").hide();
	}
	
	function showOneDayRegistration()
	{
		$("[name=\'Registration[one_day_registration]\']").removeAttr("disabled");
		$(".field-registration-one_day_registration").show();
	}
	
	function hideOneDayRegistration()
	{
		$("[name=\'Registration[one_day_registration]\']").attr("disabled","disabled");
		$(".field-registration-one_day_registration").hide();
	}
	
	function showFilePaymentReceipt()
	{
		$("[name=\'Registration[file_payment_receipt]\']").removeAttr("disabled");
		$(".field-registration-file_payment_receipt").show();
	}
	
	function hideFilePaymentReceipt()
	{
		$("[name=\'Registration[file_payment_receipt]\']").attr("disabled","disabled");
		$(".field-registration-file_payment_receipt").hide();
	}
	
	function showRegistrationCode()
	{
		$("[name=\'Registration[registration_code]\']").removeAttr("disabled");
		$(".field-registration-registration_code").show();
	}
	
	function hideRegistrationCode()
	{
		$("[name=\'Registration[registration_code]\']").attr("disabled","disabled");
		$(".field-registration-registration_code").hide();
	}

	function togglePaymentReceipt()
	{
		if( $("[name=\'Registration[payment_type]\']:checked").val() == 2 ){
			showFilePaymentReceipt();
		}else{
			hideFilePaymentReceipt();
		}
	}
	
	function toggleRegistrationCode()
	{
		if( $("[name=\'Registration[payment_type]\']:checked").val() == 3 ){
			showRegistrationCode();
		}else{
			hideRegistrationCode();
		}
	}
		
	function toggleStudentId()
	{
		var registrationType2 = $("[name=\'Registration[registration_type_id]\']").val();
		switch( registrationType2 )
		{
			case "1":
			case "2": 
			case "5":
			case "6":
			case "10":
			case "11":
			case "14":
			case "15": hideFileStudentId(); break;
			case "3": 
			case "4": 
			case "7": 
			case "9": 
			case "12": 
			case "13": 
			case "16": 
			case "17": showFileStudentId(); break;
		}
	}
	
	function toggleOneDayRegistration()
	{
		var registrationType2 = $("[name=\'Registration[registration_type_id]\']").val();
		switch( registrationType2 )
		{
			case "10":
			case "11":
			case "12": 
			case "13": showOneDayRegistration(); break;
			default: hideOneDayRegistration(); break;
		}
	}
	
	function disableInvoice()
	{
		$("[name=\'Invoice[business_name]\']").attr("disabled","disabled");
		$("[name=\'Invoice[rfc]\']").attr("disabled","disabled");
		$("[name=\'Invoice[address]\']").attr("disabled","disabled");
		$("[name=\'Invoice[zip_code]\']").attr("disabled","disabled");
		$("[name=\'Invoice[city]\']").attr("disabled","disabled");
		$("[name=\'Invoice[state]\']").attr("disabled","disabled");
		$("[name=\'Invoice[email]\']").attr("disabled","disabled");
	}
	
	function toggleInvoice()
	{
		if( $("[name=\'Registration[invoice_required]\']:checked").val() == "0" )
		{
			disableInvoice();
			$(".field-invoice-business_name").hide();
			$(".field-invoice-rfc").hide();
			$(".field-invoice-address").hide();
			$(".field-invoice-zip_code").hide();
			$(".field-invoice-city").hide();
			$(".field-invoice-state").hide();
			$(".field-invoice-email").hide();
		}
		else
		{
			$("[name=\'Invoice[business_name]\']").removeAttr("disabled");
			$(".field-invoice-business_name").show();
			$("[name=\'Invoice[rfc]\']").removeAttr("disabled");
			$(".field-invoice-rfc").show();
			$("[name=\'Invoice[address]\']").removeAttr("disabled");
			$(".field-invoice-address").show();
			$("[name=\'Invoice[zip_code]\']").removeAttr("disabled");
			$(".field-invoice-zip_code").show();
			$("[name=\'Invoice[city]\']").removeAttr("disabled");
			$(".field-invoice-city").show();
			$("[name=\'Invoice[state]\']").removeAttr("disabled");
			$(".field-invoice-state").show();
			$("[name=\'Invoice[email]\']").removeAttr("disabled");
			$(".field-invoice-email").show();
		}
	} // end of toogleInvoice()
	
	
	$("[name=\'Registration[registration_type_id]\']").change(function(){
		toggleStudentId();
		toggleOneDayRegistration();
	});
	
	
	$("[name=\'Registration[invoice_required]\']").change(function (){
		toggleInvoice();
	});
	
	$("[name=\'Registration[payment_type]\']").change(function(){
		togglePaymentReceipt();
		toggleRegistrationCode();
	});
	
	scenario = "'.$registration->scenario.'";
		
	toggleStudentId();
	toggleOneDayRegistration();
	togglePaymentReceipt();
	toggleInvoice();
	toggleRegistrationCode();
	
	if(scenario=="Update"){
		hideFilePaymentReceipt();
		hideRegistrationCode();
		disableInvoice();
	}

	var $grid = $(\'#fee_type\'); // your registration grid identifier

	$("input[name=kvradio][value=\''. $registration->registration_type_id .'\']").prop("checked",true);

	$grid.on( \'grid.radiochecked\', function(ev, key, val){
		$("#registration-registration_type_id").val(val);
			switch( val )
			{
				case "1":
				case "2": 
				case "5":
				case "6":
				case "10":
				case "11":
				case "14":
				case "15": hideFileStudentId(); break;
				case "3": 
				case "4": 
				case "7": 
				case "9": 
				case "12": 
				case "13": 
				case "16": 
				case "17": showFileStudentId(); break;
			}
			switch( val )
			{
				case "10":
				case "11":
				case "12": 
				case "13": showOneDayRegistration(); break;
				default: hideOneDayRegistration(); break;
			}
			calculateTotalPayment();
		}
	);

	function checkWorkShops()
	{
		'.$jsWorkShop.'
	}
	
	checkWorkShops();
	
	function selectTickets()
	{
		'.$jsTickets.'
	}
	
	selectTickets();
	
	registerTypes = [
		'.$strJsRegisterTypes.'
	];
	
	banquet_ticket_cost = '.AdditionalTickets::findOne(1)->price.'
	proceedings_copies_cost = '.AdditionalTickets::findOne(2)->price.'
	reception_ticket_cost = '.AdditionalTickets::findOne(3)->price.'
	
	function calculateTotalPayment(){
		var id = $("#registration-registration_type_id").val();
		var registerType = $.grep(registerTypes, function(e){ return e.id == id; });
		var banquet_ticket = $("[name=\'Registration[tickets][1]\']").val();
		var proceedings_copies = $("[name=\'Registration[tickets][2]\']").val();
		var reception_ticket = $("[name=\'Registration[tickets][3]\']").val();
		var totalCost = registerType[0].cost + (banquet_ticket*banquet_ticket_cost) + (proceedings_copies*proceedings_copies_cost) + (reception_ticket*reception_ticket_cost);
		$(".amount").html(totalCost);
	}
	
	$("[name^=\'Registration[tickets]\']").change(function(){
		calculateTotalPayment();
	});
	
	calculateTotalPayment();
'); ?>


<div class="registration-form">

    <?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
		'options' => ['enctype' => 'multipart/form-data'],
	]); ?>
	

    <h3><?= Html::encode('Personal Information') ?></h3>


	<?= $form->field($registration, 'prefix')->inline(true)->radioList(
		[
			'Ms.' => 'Ms.',
			'Mr.' => 'Mr.',
			'Dr.' => 'Dr.',
			'Prof.' => 'Prof.',			
		]
	) ?>    


	

    <?= $form->field($registration, 'first_name')->textInput([
		'maxlength' => true,
		'onchange' => "$('#registration-display_name').val(
			$('#registration-first_name').val() + ' ' + 
			$('#registration-last_name').val()
		)",
	]) ?>

    <?= $form->field($registration, 'last_name')->textInput([
		'maxlength' => true,
		'onchange' => "$('#registration-display_name').val(
			$('#registration-first_name').val() + ' ' + 
			$('#registration-last_name').val()
		)",
	]) ?>

    <?= $form->field($registration, 'display_name')->textInput([
		'maxlength' => true,
		'placeholder' => 'As displayed in badge',
	]) ?>

	<?= $form->field($registration, 'organization_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'zip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'country')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'business_phone')->textInput([
		'maxlength' => true,
		'placeholder' => 'Please enter your phone number with code area (e.g. 001-555-555-5555)',
	]) ?>

    <?= $form->field($registration, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'email')->textInput(['maxlength' => true]) ?>

	<?= $form->field($registration, 'diet')->inline(true)->radioList(
		[
			'None' => 'None',
			'Vegetarian' => 'Vegetarian',
		]
	) ?>    
	
    <?= $form->field($registration, 'emergency_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($registration, 'emergency_phone')->textInput(['maxlength' => true]) ?>
	
    
    
    <?= $form->field($registration, 'registration_type_id')->hiddenInput([
		'disabled' => ($registration->scenario == 'Update')? true: false,
	])->label(false) ?>
    
    <h3>Registration Information</h3>
    
    <h4>Registration Types</h4>
    
	<p style="margin-left:0.5cm"> <b> <?= Html::encode('Full participation (5 days, 19-23 Sept.):')?> </b> <?= Html::encode('All Conference Sessions Access, Workshops and Tutorials, USB Proceedings, Conference and Workshops Receptions, Banquet.')?> 
        <br>
        <b> <?= Html::encode('Main conference (3 days, 19-21 Sept.):')?> </b> <?= Html::encode('All Conference Sessions Access, USB Proceedings, Conference Reception, Banquet.')?> 
        <br>
        <b> <?= Html::encode('Workshops and Tutorials Only (2 days, 22-23 Sept.):')?> </b> <?= Html::encode('Workshops and Tutorials Sessions Access, USB Proceedings.')?> 

        <br> <b> <?= Html::encode('Single Day:')?> </b> <?= Html::encode('Sessions Access for one day only, USB Proceedings. If applicable, a ticket to the social event (Reception/Banquet) of the day must be purchased separately.')?> 
    </p>
        
    <p> <?= Html::encode('Registration prices are in ')?> <b> <?= Html::encode('Mexican pesos (MXN), ')?> </b> <?= Html::encode('but subject to change due to currency fluctuations:')?>
    </p>
	
	<?php if($registration->scenario == 'Update'): ?>
	<p class="alert alert-warning"><em>Note: You can't update your registration type, if you have a problem with this contact us!</em></p>
	<?php endif; ?>

    <?php $dataProviderReg = new ActiveDataProvider([
		'query' => RegistrationType::find(),
	]); ?>
    
	<?= GridView::widget([
		'id' => 'fee_type',
		'dataProvider' => $dataProviderReg,
		'columns' => [
			[
				'class' => 'kartik\grid\RadioColumn',
			 	'showClear' => false,
				'radioOptions' => [
					'disabled' => ($registration->scenario == 'Update')? true: false,
				],
			],
			
			'registrationType',
			
			[
				'class' => '\kartik\grid\DataColumn',
				'attribute' => 'cost_early_bird',
				'header' => 'Advance <br> Registration',
			],			
			
			//'advanceRegistration',			
			
			[
				'class' => '\kartik\grid\DataColumn',
				'attribute' => 'cost_late',
				'header' => 'Late <br> Registration',
			],			
			//'lateRegistration',
			/*[
				'class' => '\kartik\grid\DataColumn',
				'attribute' => 'cost',
				'header' => 'On site <br> cost',
			],*/			
			//'costOnSite',
		],
		'summary'=>'',
		'options' => ['style' => 'width:700px;'],
	]);?>
	
	<p class="student-proof-text"><span style="color: red">*</span> Student Registration and Life Member Registration require a proof of status or, for students, a student ID confirming that the registered person is a full-time student at the time of the conference.</p>    
    
	<?php if(!$registration->isNewRecord): ?>


	<?php $this->registerJs('

		function showChangeFileStudentId()
		{
			$("[name=\'Registration[change_file_student_id][]\']").removeAttr("disabled");
			$(".field-registration-change_file_student_id").show();
		}
		
		function hideChangeFileStudentId()
		{
			$("[name=\'Registration[change_file_student_id][]\']").attr("disabled","disabled");
			$(".field-registration-change_file_student_id").hide();
		}
		
		function toggleChangeFileStudentId()
		{
			var registrationType = $("[name=\'Registration[registration_type_id]\']:checked").val();
			switch( registrationType )
			{
				case "2": 
				case "4": 
				case "5": showChangeFileStudentId(); break;
				case "1":
				case "3": hideChangeFileStudentId(); break;
			}
		}
		
		hideFileStudentId();
		hideFilePaymentReceipt();
		toggleChangeFileStudentId();
		
		// I made this comment to avoid duplicate Student File dialog. Anabel
		/*
		$("[name=\'Registration[change_file_student_id][]\']").change(function (){
			if( $(this).is(":checked") )
				showFileStudentId();
			else
				hideFileStudentId();
		});	
		*/
		
		$("[name=\'Registration[change_file_payment_receipt][]\']").change(function (){
			if( $(this).is(":checked") )
				showFilePaymentReceipt();
			else
				hideFilePaymentReceipt();
		});
	
	
	'); ?>
    
	    
	<?php endif; ?>

	<?= $form->field($registration, 'file_student_id')->fileInput()->label(null,['class'=>'control-label col-sm-3 required']) ?>
	
	<?= $form->field($registration, 'one_day_registration')->dropDownList([
		'19' => 'Monday, September 19th',
		'20' => 'Tuesday, September 20th',
		'21' => 'Wednesday, September 21th',
		'22' => 'Thursday, September 22th',
		'23' => 'Friday, September 23th',
	],[
		'prompt' => 'Select the single day of registration',
	])->label(null,['class'=>'control-label col-sm-3 required']) ?>
	
	<?php $dataProvider = new ActiveDataProvider([
		'query' => Registration::find(),
	]); ?>
    

	<h3>Information for Authors</h3>
	<p>Authors are required to register. At least one non-refundable registration must be attached to each accepted paper.</p>
	<p>For each contribution, please list:<br/>
		1) The type of contribution (Paper, poster, demo, workshop paper, tutorial, etc.).<br/>
		2) The title of contribution.</p>
		
	<div class="form-group">
		<label class="control-label col-sm-2">Contribution 1:</label>
		<div class="col-sm-3">
			<?= $form->field($registration, 'contribution_type1',[
				'horizontalCssClasses' => [
					'wrapper' => 'col-sm-9',
				],
			])->dropDownList([
				'Paper' => 'Paper',
				'Poster' => 'Poster',
				'Demo' => 'Demo',
				'Workshop paper' => 'Workshop paper',
				'Tutorial' => 'Tutorial',
				'Other' => 'Other',
			],[
				'prompt' => 'Select one type',
			]) ?>
		</div>
		<div class="col-sm-5">
			<?= $form->field($registration, 'contribution_title1', [
				'horizontalCssClasses' => [
					'label' => 'col-sm-2',
					'wrapper' => 'col-sm-9',
				],
			])->textInput(['maxlength' => true]) ?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">Contribution 2:</label>
		<div class="col-sm-3">
			<?= $form->field($registration, 'contribution_type2',[
				'horizontalCssClasses' => [
					'wrapper' => 'col-sm-9',
				],
			])->dropDownList([
				'Paper' => 'Paper',
				'Poster' => 'Poster',
				'Demo' => 'Demo',
				'Workshop paper' => 'Workshop paper',
				'Tutorial' => 'Tutorial',
				'Other' => 'Other',
			],[
				'prompt' => 'Select one type',
			]) ?>
		</div>
		<div class="col-sm-5">
			<?= $form->field($registration, 'contribution_title2', [
				'horizontalCssClasses' => [
					'label' => 'col-sm-2',
					'wrapper' => 'col-sm-9',
				],
			])->textInput(['maxlength' => true]) ?>
		</div>
	</div>


    <h3>Workshops and Tutorials</h3>
	
	<?php $dataProviderWork = new ActiveDataProvider([
		'query' => Workshops::find(),
	]); ?>

	<?= GridView::widget([
		'id' => 'workshop_type',
		'dataProvider' => $dataProviderWork,
		'columns' => [
			[
				'class' => 'kartik\grid\CheckboxColumn',
				//'rowHighlight' => true,
				'header' => '',
				'name' => 'workshop',
				/*'checkboxOptions' => [
					'disabled' => ($registration->scenario == 'Update')? true: false,
				],*/
			],
			//'id',
			'name',
			'description',
		],
		'summary'=>'',
		'options' => ['style' => 'width:700px;'],
	]);?>

	<h3>Additional Tickets</h3>

	<?php $dataProviderTickets = new ActiveDataProvider([
		'query' => AdditionalTickets::find(),
	]); ?>
     <p> <?= Html::encode('Prices are in ')?> <b> <?= Html::encode('Mexican pesos (MXN), ')?> </b> <?= Html::encode('but subject to change due to currency fluctuations:')?>
    </p>
	
	<?php if($registration->scenario == 'Update'): ?>
	<p class="alert alert-warning"><em>Note: You can't update your additional tickets, if you have a problem with this contact us!</em></p>
	<?php endif; ?>
	
	<?php $visibleInputTickets = ($registration->scenario == 'Update')? true: false;  ?>
	<?= GridView::widget([
		'id' => 'tickets_type',
		'dataProvider' => $dataProviderTickets,
		'columns' => [
			[
				'class' => 'yii\grid\DataColumn',
			    'value' => function ($model, $key, $index, $widget) use ($registration) {
					//return Html::textInput('', $model->quantity);
					// return Html::activeDropDownList($model, "quantity[$key]", range(0,5));
					// return Html::activeDropDownList($registration, "tickets[$key]", range(0,5));
					return Html::dropDownList("Registration[tickets][$key]", null, range(0,3), [
						'disabled' => ($registration->scenario == 'Update')? true: false,
					]);
				},
				'format' => 'raw',
				// 'visible' => $visibleInputTickets,
			],
			'name',
			//'cost'
			'price'
		],
		'summary'=>'',
		'options' => ['style' => 'width:700px;'],
	]);?>
    
   	<h3><?= Html::encode('For Mexicans Only (official tax deductable document)')?></h3>
	
	<?php if($registration->scenario == 'Update'): ?>
	<p class="alert alert-warning"><em>Note: You can't update invoice information, if you have a problem with this contact us!</em></p>
	<?php endif; ?>

	<?= $form->field($registration, 'invoice_required')->radioList([
			0 => 'Not required',
			1 => 'Required',
		],[
			'itemOptions' => [
				'disabled' => ($registration->scenario == 'Update')? true: false
			],
			'unselect' => null,
		]
	) ?>

	
    <?= $form->field($invoice, 'business_name')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'rfc')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'address')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'zip_code')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'city')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'state')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>

    <?= $form->field($invoice, 'email')->textInput([
		'maxlength' => true,
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>
        
    
	<h3>Cancellation Policy</h3>
	<p> <?= Html::encode('The registration fee will not be refunded to the authors if it is required to cover the publications expenses of accepted papers. Any cancellations after registration will incur $1,000 MXN administrative charges. No refunds will be made for cancellations after August 15, 2016. No refunds will be given for non-attendance. Note that "No Show" authors will have their paper removed from the Proceedings and would not get a refund. To requests for cancellations, substitutions, or other changes, please contact the Registration Chair at registration@ismar2016.org.')?> </p>

   	<h3>Payment</h3>
	
	<?php if($registration->scenario == 'Update'): ?>
	<p class="alert alert-warning"><em>Note: You can't update payment information, if you have a problem with this contact us!</em></p>
	<?php endif; ?>
	
	<div class="row">
		<p class="total-payment col-md-6 col-md-offset-3">Total Payment: <span class="amountStyle">MXN $<span class="amount"><?= Yii::$app->formatter->asCurrency($registration->total_payment) ?></span>.00</span></p>
	</div>
	
	<?= $form->field($registration, 'payment_type')->radioList([
		1 => 'Credit Card',
		2 => 'Payment Receipt (Upload your bank transfer receipt)',
		3 => 'Registration Code',
		4 => 'PayPal',
	],[
		'itemOptions' => [
			'disabled' => ($registration->scenario == 'Update')? true: false
		],
		'unselect' => null,
	]) ?>

	<?php echo $form->field($registration, 'file_payment_receipt')->fileInput()->label(null,[
		'class'=>'control-label col-sm-3 required',
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>
	
	<?php echo $form->field($registration, 'registration_code')->textInput(['maxlength' => true])->label(null,[
		'class'=>'control-label col-sm-3 required',
		'disabled' => ($registration->scenario == 'Update')? true: false,
	]) ?>
	
	
    <div class="form-group">
        <?= Html::submitButton($registration->isNewRecord ? Yii::t('app', 'Submit') : Yii::t('app', 'Update data'), ['class' => $registration->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
