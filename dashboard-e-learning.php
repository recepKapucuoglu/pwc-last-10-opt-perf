<?php include('dosyalar/dahili/header.php');
if ($_SESSION['dashboardUser']) { ?>
	<section class="ortakisim">
		<div class="container">
			<div class="row">
				<div class="hidden-xs col-sm-3 col-md-3 col-lg-3">
					<?php include('dosyalar/dahili/dashboard-menu.php'); ?>
				</div>
				<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
					<section class="dashboard-detay favorilerim-sayfasi">
						<div class="sectionbaslik">E-Learning Eğitimlerim</div>
						
						<?php
						$db->where('email', $_SESSION['dashboardUser']);
						$results = $db->get('web_user');
						foreach ($results as $value) {
							$dashboardUserMailStatus = $value['mail_status'];
						}
						{
							?>
							<div class="dbkutuicerik">
								<div class="row">
									<?php
									$db->where('user_id', $_SESSION['dashboardUserId']);
									$db->where('edu_type_id',3);
									//$db->where('odenen_tutar', null, 'IS NOT');
									$db->where('elearning_detail_path', null, 'IS NOT');
									$egitimlerim = $db->get('web_education_order_list');
									if (count($egitimlerim) < 1) { ?>
										 <div style="
   												 padding: 10px 40px 0px 20px;
    											font-size: 0.9rem;
    											font-weight: 300;">
												<p>  Satın alınan E-Learning eğitimlerinizi bu sayfadan görüntüleyebilirsiniz.</p>
												<p>	Satın alınan E-Learning eğitimleriniz ödeme onayının ardından 2 iş günü içerisinde hesabınıza tanımlanacaktır.</p>
												</div>
								<?php	} else {

										echo "<div class=\"dbkutuicerik\"><div class=\"listeler kucukresim\">";
										if (count($egitimlerim)>0){
											foreach ($egitimlerim as $valueCalender) {
												//ödenmiş siparişin user codunu alalım
												$_SESSION['dashboardUserElearningCode'] = $valueCalender['elearning_user_code'];
												?>
												<div class="col-md-4 e-learning-card_space">

												<a href="dashboard-e-learning-detay.php?path=<?php echo $valueCalender['elearning_detail_path'] ?>">														<div class='square' style="height:350px">
															<img src="<?php echo $valueCalender['resim']; ?>" alt="">
															<div class='square-body'>
																<div class='h1'>
																	<a href="dashboard-e-learning-detay.php?path=<?php echo $valueCalender['elearning_detail_path'] ?>"
																		class="elearningDetayGit">
																		<h4 class="elearningDetayGit">
																			<?php echo $valueCalender['baslik']; ?>
																		</h4>
																	</a>
																</div>
																<div style="margin-top:15px">
																	<a href="dashboard-e-learning-detay.php?path=<?php echo $valueCalender['elearning_detail_path'] ?>"
																		class='button'>Eğitime Git</a>
																</div>
															</div>
														</div>
													</a>
												</div>
											<?php }}
											else {?>
											<div style="
   												 padding: 10px 40px 0px 20px;
    											font-size: 0.9rem;
    											font-weight: 300;">
												<p>  Satın alınan E-Learning eğitimlerinizi bu sayfadan görüntüleyebilirsiniz.</p>
												<p>	Satın alınan E-Learning eğitimleriniz ödeme onayının ardından 2 iş günü içerisinde hesabınıza tanımlanacaktır.</p>
												</div>
											<?php }
									}
									?>
								</div>
							</div>
						<?php } ?>
					</section>
				</div>
			</div>
		</div>
	</section>
<?php } else {

	echo "<script language=\"JavaScript\">location.href=\"/login.php\";</script>";

}
include('dosyalar/dahili/footer.php'); ?>
<script>$('body').addClass('dashboard');</script>