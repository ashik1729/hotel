<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<section class="my-account">
			<div class="container">
				<div class="row">
					<div class="col-12 ">
						<ul class="my-account-nav">
							<li><a class="<?= $active == "dashboard" ? 'active' :'';?>" href="<?php echo Yii::$app->request->baseUrl; ?>/dashboard">My Account</a></li>
							<li><a class="<?= $active == "package-history" ? 'active' :'';?>" href="<?php echo Yii::$app->request->baseUrl; ?>/package-history">Tour Package History</a></li>
							<li><a class="<?= $active == "visa-enquiry" ? 'active' :'';?>" href="<?php echo Yii::$app->request->baseUrl; ?>/visa-enquiry">Visa Enquiries</a></li>
							<li><a  href="<?php echo Yii::$app->request->baseUrl; ?>/log-out">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>