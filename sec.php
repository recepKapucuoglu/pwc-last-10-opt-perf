<?php
// is payten active?
$payten_active = true;

include('dosyalar/dahili/ustkisim.php');

// Netahsilat
$db->where ('customer_number', trim($_SESSION["CustomerCode"]));
$is_nts_active = $db->getValue("netahsilat", "count(*)");

// Payten active?
if($payten_active){
    // netahsilat active?
    if($is_nts_active){
        $payment_url = "https://tahsilat.tepeguvenlik.com.tr/";
    } else{
        $unique = sha1(time() . rand(1, 1000) . mt_rand(1000, 100000));
        $payment_url = "https://online.securitasalarm.com.tr/makePayment.php?key=$unique";
    }
} else {
    $payment_url = "https://tahsilat.tepeguvenlik.com.tr/";
}
?>
<div class="arkaplanli" style="background-image:url(dosyalar/images/slayt1.png);">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="baslik"><?php echo $baslik; ?></h1>
				<div id="breadcrumbs">
					<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
						<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="" href="#"><span property="name">Anasayfa</span></a><meta property="position" content="1"></span>
						<i class="fa fa-angle-right"></i> 
						<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="" href="#"><span property="name">Online İşlemler</span></a><meta property="position" content="2"></span> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<section class="onlineislemler">
	<div class="container">
		<div class="row">
		    <?php
		    // Kullanıcının 6 ayda bir şifreyi değiştirmesi gerekiyor
		    list($shouldChange, $lastDate) = shouldChangePassword($_SESSION["CustomerCode"]);
		    if($shouldChange){
		        $lastDate = $lastDate->format('d-m-Y H:i:s');
		        echo sprintf("<div class='alert' style='background:#031f30; color:white'>Şifrenizi en son <b>%s</b> tarihinde güncellediniz. Güvenliğiniz için lütfen 6 ayda bir şifrenizi güncelleyiniz.</div>", $lastDate);
		    }
		    ?>
			<div class="col-sm-12">
				<?php echo $_SESSION['bilgilendirmeYazisi']; ?>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-9">
				<?php if($id=='online-islemler' or $id=='' or $id==null){
					
			
					
					$guncelTutar = explode(".",$_SESSION['aktifFatura']->EtNextInv->item->Ssprc); 
					$faturaKesimTarihi = substr($_SESSION['aktifFatura']->EtNextInv->item->InvDate,0,10);
					$suan=date("Y-m-d");

					$tarihFark = fark_bul($faturaKesimTarihi,$suan,'-');
					
					if($tarihFark>30)
							$tarihFark = 30;
					elseif($tarihFark<1)
						$tarihFark = 0;
					
					if($guncelTutar[0]<>"") {
				?>
				<div class="faturasontarih temizle">
					<div class="faturadongusu" data-kalangun="<?php echo $tarihFark; ?>" data-toplamgun="30">
						<span class="fd-sol">
							<span class="fd-bar"></span>
						</span>
						<span class="fd-sag">
							<span class="fd-bar"></span>
						</span>
						<div class="ickisim">
							<div class="aciklama">KALAN GÜN</div>
							<div class="kalangun"><b class="saydir" data-to=""></b> GÜN</div>
						</div>
					</div>
					<div class="guncelfatura">
						<div class="gf_baslik">Güncel Fatura Tutarı</div>
						<div class="gf_tutar"><?php echo $guncelTutar[0]; ?>,<span><?php echo substr($guncelTutar[1],0,2); ?></span> <i class="fas fa-lira-sign"></i></div>
						<div class="gf_tarih"><b>Fatura Kesim Tarihi</b><time><?php echo date2Human(substr($_SESSION['aktifFatura']->EtNextInv->item->InvDate,0,10)); ?></time></div>
					</div>
				</div>
				<br />
				<br />
				<?php } 
				
				$db->where('kategori', 5); // Mobil Kampanyalar
				$db->where('dil', 'TR'); 
				$db->where('durum', 1); 
				$total = $db->getValue ('blog', "count(id)");
				if($total>0) {
					
					echo "<div class=\"slaytlar owl-carousel owl-theme\">";
					//Kampanya Listesini Çekiyoruz.
					$db->where('kategori', 5);
					$db->where('dil', 'TR'); 
					$db->where('durum', 1);
					$results = $db->get('blog');
					foreach ($results as $value) {
						
						echo "<div class=\"slayt\">
								<a href='/index.php?id=kampanya&camp=".$value['id']."'><img src=\"https://www.securitasalarm.com.tr".$value['resim']."\" alt=\"".$value['baslik']."\" /></a>
							</div>";
						
					}
					echo "</div>";
				} else {
					echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Aktif kampanya bulunmamaktadır. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>";
				}
				?>
				
				
					
					
				
				<br />
				<br />
				<div class="kisayollinkler">
					<div class="kisayol">
						<a href="index.php?id=faturalarim" class="hvr-float-shadow">
							<figure data-hover="Detaylı Bilgi">
								<img src="dosyalar/images/faturalarim.jpg" alt="Faturalarım" />
							</figure>
							<div class="yollink">Fatura İşlemlerim</div>
						</a>
					</div>
					<div class="kisayol">
						<a href="index.php?id=abonelik-bilgilerim" class="hvr-float-shadow">
							<figure data-hover="Detaylı Bilgi">
								<img src="dosyalar/images/bilgilerim.jpg" alt="Abonelik Bilgilerim" />
							</figure>
							<div class="yollink">Abonelik Bilgilerim</div>
						</a>
					</div>
					<div class="kisayol">
						<a href="index.php?id=kullanicilarim" class="hvr-float-shadow">
							<figure data-hover="Detaylı Bilgi">
								<img src="dosyalar/images/kullanicilar.jpg" alt="kullanicilarim" />
							</figure>
							<div class="yollink">Kullanıcılarım</div>
						</a>
					</div>
					<div class="kisayol">
						<a href="index.php?id=abonelik-sozlesme" class="hvr-float-shadow">
							<figure data-hover="Detaylı Bilgi">
								<img src="dosyalar/images/sozlesme.jpg" alt="Sözleşmelerim" />
							</figure>
							<div class="yollink">Sözleşmeleri Görüntüle</div>
						</a>
					</div>
					<div class="kisayol">
						<a href="index.php?id=alarmlarim" class="hvr-float-shadow">
							<figure data-hover="Detaylı Bilgi">
								<img src="dosyalar/images/alarmlarim.jpg" alt="Alarm Raporlarım" />
							</figure>
							<div class="yollink">Alarm Raporlarım</div>
						</a>
					</div>
                    <?php if($payten_active && !$is_nts_active) {include "invoices.php";} ?>
				</div>
				<?php }elseif($id=='kampanya'){ 
				
				$camp = intval($_GET['camp']);
				$db->where('kategori', 5); // Mobil Kampanyalar
				$db->where('dil', 'TR');
				$db->where('id', $camp);				
				$db->where('durum', 1); 
				$total = $db->getValue ('blog', "count(id)");
				if($total>0) {
					
					//Kampanya Listesini Çekiyoruz.
					$db->where('kategori', 5);
					$db->where('dil', 'TR'); 
					$db->where('id', $camp);
					$db->where('durum', 1);
					$results = $db->get('blog');
					foreach ($results as $value) {
						
						echo "<div class=\"slayt\">
								<img src=\"https://www.securitasalarm.com.tr".$value['resim']."\" alt=\"".$value['baslik']."\" />
							</div>";
							
						echo "<br/><h3>".$value['baslik']."</h3>";
						echo "<hr><p>".t_decode($value['aciklama'])."</p>";
						
						if($value['talep']==1)
							echo "<br/><br/><div align=\"right\">
									<input onclick=\"location.href='/index.php?id=yeni-referans-olustur';\" type=\"submit\" value=\"Yeni Referans Oluştur\"/>
								</div>";
					}
				} else {
					echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Aradığınız kampanya sona ermiştir. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>";
				}
				?>
				
			<?php }elseif($id=='yeni-referans-olustur'){
				
						
				?>
				<div class="listeletablo tip1">
					<div class="referansResult">
					<?php echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Aşağıda yer alan formu doldurarak, referans bilgilerinizi iletebilirsiniz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
					</div>
					<form id="referans_form" method="post" onsubmit="return yeni_referans();">
						<label for="FirmaUnvani">Ad Soyad *</label>
						<input autocomplete="off" maxlength="50" name="adsoyad" type="text" required>
						<label for="FirmaUnvani">Şehir *</label>
						<select name="sehir" class="js-example-basic-multiple" required>
							<option></option>
							<?php
								$results = $db->get('netia_city');
								foreach ($results as $value) {
									echo "<option value=\"".$value['crm_id']."\">".$value['name']."</option>";
								}
							?>
						</select>
						<label for="Telefon" style="margin-top:10px">Telefon *</label>
						<input autocomplete="off" type="tel" name="tel" placeholder="(5__) ___ __ __" required>	
						<div class="onay">
							<?php $onayid = rand(0,999);?>
							<label for="onayA<?php echo $onayid;?>"><input type="checkbox" name="onay1" id="onayA<?php echo $onayid;?>"/><i>Elektronik Ticaretin Düzenlenmesi Hakkında Kanun çerçevesinde iletişim bilgilerimin reklam, bilgilendirme gibi amaçlarla kullanılmasına onay veriyorum.</i></label>
							<label for="onayB<?php echo $onayid;?>"><input type="checkbox" name="onay2" id="onayB<?php echo $onayid;?>" required/><i><a href="https://www.securitasalarm.com.tr/kisisel-verilerin-korunmasi-hakkinda-bilgilendirme" target="_blank">Kişisel verilerin korunması</a> hakkındaki bilgilendirmeyi okudum, onay veriyorum.</i></label>
						</div>
						<input type="submit" value="Gönder"/>
					</form>
				</div>				
					
					
					
				<?php }elseif($id=='abonelik-bilgilerim'){ 
				
				
				include_once('dosyalar/dahili/libs/a_traq_tepe_entegrasyon.php');
				$clientTepe = new aTraqTepeEntegrasyon();
				
				$responseTepe = $clientTepe->AboneBilgileriTest($_SESSION['CustomerCode']);
				$sxe = new SimpleXMLElement($responseTepe->AboneBilgileriTestResult->any);


				$IS_ANLASMASI = substr($sxe->NewDataSet->AboneBilgileri->IS_ANLASMASI,0,12);
				$aTraqPhone = $sxe->NewDataSet->AboneBilgileri->SozlesmeTelefon;
				$TepeUserName = $sxe->NewDataSet->AboneBilgileri->Adi;
				$MusteriNo = $sxe->NewDataSet->AboneBilgileri->MusteriNo;
				$AboneNo = $sxe->NewDataSet->AboneBilgileri->AboneNo;
				$Adres = $sxe->NewDataSet->AboneBilgileri->Adres;
				$Sehir = $sxe->NewDataSet->AboneBilgileri->Sehir;
				$AboneBaslangic = $sxe->NewDataSet->AboneBilgileri->AboneBaslangic;
				$AboneBitis = $sxe->NewDataSet->AboneBilgileri->AboneBitis;
				
				?>
				<div class="listeletablo tip1">
					<table>
						<tbody>
							<tr>
								<td>Abone Adı</td>
								<td><?php echo $TepeUserName; ?></td>
							</tr>
							<tr>
								<td>Abone No</td>
								<td><?php echo $AboneNo; ?></td>
							</tr>
							<tr>
								<td>İş Anlaşması</td>
								<td><?php echo $IS_ANLASMASI; ?></td>
							</tr>
							<tr>
								<td>Sinyal ID Numarası</td>
								<td><?php echo $MusteriNo; ?></td>
							</tr>
							<tr>
								<td>Adres</td>
								<td><?php echo $Adres; ?></td>
							</tr>
							<tr>
								<td>Şehir</td>
								<td><?php echo $Sehir; ?></td>
							</tr>
							<tr>
								<td>Telefon</td>
								<td><?php echo $aTraqPhone; ?></td>
							</tr>
							
							<tr>
								<td>Email</td>
								<td><?php echo $_SESSION['Email']; ?></td>
							</tr>
							<tr>
								<td>Abonelik Tarihi</td>
								<td><?php echo date2Human(substr($AboneBaslangic,0,10)); ?></td>
							</tr>
							<tr>
								<td>Abonelik Bitiş</td>
								<td><?php echo date2Human(substr($AboneBitis,0,10)); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php }elseif($id=='alarmlarim'){?>
                <?php
                    $excel_AboneNo = array();
                    $excel_SinyalAciklama = array();
                    $excel_Aciklama = array();
                    $excel_sinyalTarihi = array();
                ?>
				<div class="listeletablo tip2">
					<form id="alarm_form" method="post" action="index.php?id=alarmlarim">
						<div class="row">
							<div class="col-sm-3">
								<label for="">Başlangıç Tarihi</label>
								<input type="date" name="baslangic" value="<?php if($_SERVER["REQUEST_METHOD"] == "POST"){echo explode(" ", $_POST['baslangic'])[0];} ?>" />
							</div>
							<div class="col-sm-3">
								<label for="">Bitiş Tarihi</label>
								<input type="date" name="bitis" value="<?php if($_SERVER["REQUEST_METHOD"] == "POST"){echo explode(" ", $_POST['bitis'])[0];} ?>" />
							</div>
                            <div class="col-sm-4">
                                <label for="type">Sinyal Türü</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="one" <?php if($_POST["type"] == "one"){echo "selected"; } ?>>Tüm Sinyaller Raporu</option>
                                    <option value="two" <?php if($_POST["type"] == "two"){echo "selected"; } ?>>Açılış/Kapanış Raporu</option>
                                </select>
                            </div>
							<div class="col-sm-2">
								<input type="submit" value="Sorgula"/>
							</div>
						</div>
					</form>
					<div class="alarmResult">
						<?php if($_SERVER["REQUEST_METHOD"] !== "POST") echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Alarm raporlarınızı görüntülemek istediğiniz iki tarih aralığını seçiniz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
                        <?php
                        if($_SERVER["REQUEST_METHOD"] == "POST"){
                            if($_POST['baslangic'] AND $_POST['bitis'])
                            {
                                $baslangic=valueClear($_POST['baslangic']);
                                $bitis=valueClear($_POST['bitis']);

                                $tarihfark = fark_bul($baslangic,$bitis,'-');

                                if($baslangic>$bitis){
                                    echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi kirmizi\">Başlangıç tarihi, bitiş tarihinden büyük olamaz lütfen tarihleri konrol ederek tekrar deneyiniz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>";
                                } if($tarihfark>31){
                                echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi kirmizi\">Seçmiş olduğunuz tarih aralığı 1 aydan fazla olamaz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>";
                            } else {

                                include_once('dosyalar/dahili/libs/a_traq_tepe_entegrasyon.php');
                                $clientTepe = new aTraqTepeEntegrasyon();
                                $response = $clientTepe->TumSinyaller($_SESSION['CustomerCode'],$baslangic,$bitis."T23:59:59");
                                $sxe = new SimpleXMLElement($response->TumSinyallerResult->any);
                                $sxe->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');


                                if(count($sxe->NewDataSet)>0) {
                                    if(count($sxe->NewDataSet)>1)
                                        $GetAllSignals = $sxe->NewDataSet;
                                    else
                                        $GetAllSignals = array($sxe->NewDataSet);
                                } else
                                    $GetAllSignals = array(array('Table' => array()));




                                if(count($GetAllSignals[0]->Table)>0){


                                    echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi yesil\">Alarm raporlarınız <b>".date2Human($_POST['baslangic'])."</b> ile <b>".date2Human($_POST['bitis'])."</b> tarihleri arasında aşağıdaki gibi listelenmektedir. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>											<button style='margin-bottom:5px;'><a href='excel.php' style='color:white'>Rapor Al</a>
						
						
					</div>";
                                    echo "<table id=\"signals\" class=\"table table-striped table-bordered\" style=\"width:100%\">

					<thead>
						<tr>
							<td width=\"20\">Abone No</td>
							<td class='notexport' width=\"20\">Tip</td>
							<td width=\"200\">Alarm Açıklaması</td>
							<td width=\"250\">Açıklama</td>
							<td width=\"200\">Sinyal Tarihi</td>
						</tr>
					</thead>
					<tbody>";
                                    foreach ($GetAllSignals[0]->Table as $value) {
                                        $type = $_POST["type"];
                                        if($type == "two"){
                                            if(!in_array($value->SinyalTuru, ["OPN", "CLO"])){
                                                continue;
                                            }
                                        }
                                        array_push($excel_AboneNo, $value->AboneNo);
                                        array_push($excel_SinyalAciklama, $value->SinyalAciklama);
                                        array_push($excel_Aciklama, $value->Aciklama);
                                        array_push($excel_sinyalTarihi, date2Human(substr($value->SinyalTarihi,0,10)) . " " . substr($value->SinyalTarihi,11,8));
                                        echo	"<tr>
							<td>".$value->AboneNo."</td>
							<td>".$value->SinyalTuru."</td>
							<td>".$value->SinyalAciklama."</td>
							<td>".$value->Aciklama."</td>
							<td>".date2Human(substr($value->SinyalTarihi,0,10))." ".substr($value->SinyalTarihi,11,8)."</td>
						</tr>";
                                    }
                                    echo "</tbody>
				</table>";
                                    $stringtosend = implode("#!", $excel_AboneNo) . "*" . implode("#!", $excel_SinyalAciklama) . "*" . implode("#!", $excel_Aciklama) . "*" . implode("#!", $excel_sinyalTarihi);
                                    $crypted_string = base64_encode($stringtosend);
                                    $_SESSION["excel"] = $crypted_string;
                                } else {
                                    echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi kirmizi\">Girmiş olduğunuz tarihler arasında alarm raporu bulunmamaktadır. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>";
                                }

                                /*
                                include_once('libs/a_traq_entegrasyon.class.php');
                                $entegrasyon = new aTraqEntegrasyon();
                                $alarmResult = $entegrasyon->GetAllSignals($_SESSION['genelBilgilerDetay']->GetSubscriptionDetailResult->SubscriptionNumber,$baslangic."T10:46:39.300-00:00",$bitis."T10:46:39.300-24:00",1000);
                                if(count($alarmResult->GetAllSignalsResult->Signal)>0) {

                                echo "<div class=\"bilgilendirme\">
                                            <div class=\"bilgi yesil\">Alarm raporlarınız <b>".date2Human($_POST['baslangic'])."</b> ile <b>".date2Human($_POST['bitis'])."</b> tarihleri arasında aşağıdaki gibi listelenmektedir. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
                                        </div>";

                                echo "<table>
                                        <thead>
                                            <tr>
                                                <td width=\"20\">Abone No</td>
                                                <td width=\"20\">Tip</td>
                                                <td width=\"200\">Alarm Açıklaması</td>
                                                <td width=\"250\">Açıklama</td>
                                                <td width=\"200\">Sinyal Tarihi</td>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                        $alarmlar = array_reverse($alarmResult->GetAllSignalsResult->Signal);
                                        foreach ($alarmlar as $value) {
                                            if($value->SignalType<>"TST") {
                                        echo	"<tr>
                                                <td>".$value->SubscriptionNumber."</td>
                                                <td>".$value->SignalType."</td>
                                                <td>".$value->SignalDescription."</td>
                                                <td>".$value->Description."</td>
                                                <td>".$value->SignalDate."</td>
                                            </tr>";
                                        }}
                                        echo "</tbody>
                                    </table>";
                                    } else {
                                        echo "<div class=\"bilgilendirme\">
                                                <div class=\"bilgi kirmizi\">Girmiş olduğunuz tarihler arasında alarm raporu bulunmamaktadır. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
                                            </div>";
                                    }
                                    */
                            }
                            }
                        }
                        ?>
					</div>
					
				</div>
				<?php }elseif($id=='abonelik-sozlesme'){
					$SOAP_AUTH_SAP_SOZLESME= array( 'login' => 'rfcuser1', 'password' => 'Sap12345');
					#SpecifyWSDL
					$WSDLSAPSozlesme = "http://blkcrmprda1.securitasturkey.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zcrm_web_talep/100/zcrm_web_talep/zcrm_web_talep_b?sap-client=100";
					$clientSAPSozlesme= new SoapClient($WSDLSAPSozlesme,$SOAP_AUTH_SAP_SOZLESME);

					
					$reqReturn = $clientSAPSozlesme->ZCRM_WEB_DOCUMENT_GET_LIST(
							array(
								"IV_PARTNER" => NULL,
								"IV_BUAG" => $_SESSION['IS_ANLASMASI']
							)
						);
					 $toplam_kayit = count($reqReturn->EV_RESULT->item);
					
					 
					if($toplam_kayit<2){
						$sozlesmeList = $reqReturn->EV_RESULT;
					} else {
						$sozlesmeList = $reqReturn->EV_RESULT->item;
					}
					
					?>
				<div class="listeletablo tip1">
					<?php if($toplam_kayit>0) {  ?>
					<table>
						<thead>
							<tr>
								<td width="20%">Abone No.</td>
								<td width="20%">Müşteri No.</td>
								<td width="25%">Açıklama</td>
								<td width="35%">Sözleşme</td>
							</tr>
						</thead>
						<tbody>
							<?php 
								$i=0;
							foreach ($sozlesmeList as $value) {
											$i++;
							?>
							<tr>
								<td><?php echo $_SESSION['SubscriptionNumber']; ?></td>
								<td><?php echo $_SESSION['CustomerCode']; ?></td>
								<td>Sözleşme - <?php echo $i;?> (<?php echo $value->FILE_NAME; ?>)</td>
								<td><a href="sozlesme-goruntule.php?id=<?php echo $i; ?>" target="_blank" class="odebuton">Sözleşme Görüntüle</a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php } else { echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Görüntülenecek sözleşme dosyası bulunamadı! <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; } ?>
				</div>
				<?php }elseif($id=='faturalarim'){ 
				
				$SOAP_AUTH= array( 'login' => 'NETAHSILAT', 'password' => 'BilkentNT*2020');
			
				$WSDL="http://blkhybprda1.securitasturkey.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/z_fica_invoice_info/100/z_fica_invoice_info/z_fica_invoice_info?sap-client=100";
				   

				
				#CreateClient Object, download and parse WSDL
				$client= new SoapClient($WSDL,$SOAP_AUTH);

		
				$openInvoiceList = $client->ZFicaInvoiceInfo(
				array(
					"IvVkont" => $_SESSION['IS_ANLASMASI']
					)
				);
			//	$openInvoiceList = $client->ZFicaInvoiceInfo(
			//	array(
			//		"IvVkont" => 200000044749
			//		)
			//	);	
						
				 $toplam_kayit = count($openInvoiceList->EtInvoice->item);
				
				 
				if($toplam_kayit<2){
					$invoiceList = $openInvoiceList->EtInvoice;
				} else {
					$invoiceList = $openInvoiceList->EtInvoice->item;
				}
				
				
				?>
                    <?php
                    if($_GET["type"] == "error"){
                        $responseMsg = empty($_GET["msg"]) ? "Beklenmedik bir hata oluştu, lütfen daha sonra tekrar deneyiniz." : base64_decode($_GET["msg"]);
                        echo "<div class=\"alert alert-danger\" role=\"alert\">
                            $responseMsg
                    </div>";
                    }else if($_GET["type"] == "success"){
                        $responseSuccess = base64_decode($_GET["msg"]);
                        echo "<div class=\"alert alert-success\" role=\"alert\">
                            $responseSuccess
                    </div>";
                    }
                    ?>
				<div class="listeletablo tip2">
					<table>						
												
						<thead>
							<tr>
								<td width="20%">Fatura No.</td>
								<td width="13%">Fatura Tarihi</td>
								<td width="14%">Fatura Tutarı</td>
								<td width="13%">Son Ödeme Tarihi</td>
								<td width="14%">Fatura Görüntüleme</td>
								<td width="26%">Ödeme Durumu</td>
							</tr>
							
						</thead>
						<tbody>
						
							<?php 
								$i=0;
								foreach ($invoiceList as $value) {
											$i++;
											if(number_format($value->Augbt,2,",",".")=="0,00")
										$odeme_durum ="<span style=\"color:#4CAF50\">Ödendi</span>";
									else
										$odeme_durum ="<span style=\"color:#f44336\">Ödenmedi</span> <a href=\"$payment_url\" target=\"_blank\" class=\"odebuton\">Hemen Öde</a>";
					
							?>
							<tr>
								<td><?php echo $value->Xblnr; ?></td>
								<td><?php echo date2Human(substr($value->Budat,0,10)); ?></td>
								<td><?php if(number_format($value->Augbt,2,",",".")<>"0,00" AND $value->Betrh<>$value->Augbt) { 
											echo number_format(($value->Augbt),2,",",".")." ₺"; $odeme_durum ="<span style=\"color:#f44336\">Eksik Ödeme</span> <a href=\"$payment_url\" target=\"_blank\" class=\"odebuton\">Hemen Öde</a>"; 
										} else echo number_format($value->Betrh,2,",",".")." ₺";  ?></td>
								<td><?php echo date2Human(substr($value->Faedn,0,10)); ?></td>
                                <td><?php echo "<a href=\"https://earsivfatura.securitasalarm.com.tr/Genel/Fatura/{$value->Iuuid}\" target=\"_blank\" class=\"odebuton\">Görüntüle</a>"; ?></td>
								<td>
									<?php echo $odeme_durum; ?>
								</td>
							</tr>
							<?php } ?>
						
							
						</tbody>
					</table>
				</div>
				<?php }elseif($id=='kullanicilarim'){?>
				<div class="listeletablo tip2">
					<table>
						<thead>
							<tr>
								<td width="100">Abone No</td>
								<td width="200">Müşteri No</td>
								<td width="200">Kullanıcı No.</td>
								<td width="200">Kullanıcı</td>
								<td width="200">Telefon</td>
							</tr>
						</thead>
						<tbody>
							<div id="sonuclar"></div>
							<?php 
							require __DIR__ . '/dosyalar/dahili/libs/a_traq_tepe_entegrasyon.php';
							// hazırlanan servis için instance oluşturalım
							$userService = new aTraqTepeEntegrasyon();
							// kullanıcı listesi fonksiyonu ile
							// kullanıcıların listesini alalım
							$userList = $userService->KullaniciListesi($_SESSION['SubscriptionNumber']);
							// Veriler SOAP servisten geldiği için SimpleXMLElement class kullanıyoruz
							$sxe = new SimpleXMLElement($userList->KullaniciListesiResult->any);
							// Doğru bir reflection için tanımlama yapıyoruz
			                $sxe->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
			                // Verilerin toplam sayısını buluyoruz
			                // SOAP servisten dönen objeyi doğru 
			                // bir şekilde array içinde depoluyoruz.
                			if(count($sxe->NewDataSet)>0) {
                				if(count($sxe->NewDataSet->Table)>1) 
                					$kullaniciListArray = array($sxe->NewDataSet);
                				else
                					$kullaniciListArray[] = array('Table' => array($sxe->NewDataSet->Table));
                			} else
                			$kullaniciListArray = array('Table' => array());
							foreach ($kullaniciListArray[0][0] as $value) { ?>
							<tr>
								<td><?php echo $_SESSION['SubscriptionNumber']; ?></td>
								<td><?php echo $_SESSION['CustomerCode']; ?></td>
								<td><?php echo $value->ZoneNo; ?></td>
								<td><?php echo $value->ZoneAdi; ?></td>
								<td><?php echo $value->Telefon1; if($value->Telefon2<>"") echo " - ".$value->Telefon2; if($value->Telefon3<>"") echo " - ".$value->Telefon3; if($value->Telefon4<>"") echo " - ".$value->Telefon4; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div align="right">
					<input onclick="location.href='/index.php?id=yeni-kullanici';" type="submit" value="Yeni Kullanıcı Oluştur"/>
				</div>
				
				<?php }elseif($id=='zone-listesi'){?>
				<div class="listeletablo tip2">
					<table>
						<thead>
							<tr>
								<td width="10" align="center">Bölge No</td>
								<td width="200" align="center">Bölge Adı</td>
							</tr>
						</thead>
						<tbody>
							<div id="sonuclar"></div>
							<?php 
							require __DIR__ . '/dosyalar/dahili/libs/a_traq_tepe_entegrasyon.php';
							// hazırlanan servis için instance oluşturalım
							$userService = new aTraqTepeEntegrasyon();
							$zone_list_xml = $userService->ZoneListesi($_SESSION['SubscriptionNumber'])->BolgelerResult->any;
                        	$zone_list_sxe = new SimpleXMLElement($zone_list_xml);
                        	$zone_list_sxe->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
                        	if(count($zone_list_sxe->NewDataSet)>0) {
                                        if(count($zone_list_sxe->NewDataSet->Table)>1) 
                                        	$zoneListesiArray = array($zone_list_sxe->NewDataSet);
                                        else
                                        	$zoneListesiArray[] = array('Zones' => array($zone_list_sxe->NewDataSet->Table));
                                        } else {
                                        	    $zoneListesiArray = array('Zones' => array());
                                 }
							foreach ($zoneListesiArray[0][0] as $value) { ?>
							<tr>
								<td align="center"><?php echo trim($value->ZoneNo); ?></td>
								<td align="center"><?php echo trim($value->ZoneAdi); ?></td>
							</tr>
							<?php }  ?>
						</tbody>
					</table>
				</div>
				<?php }elseif($id=='yeni-kullanici'){?>
				<div class="kullaniciResult">
					<?php echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Yeni kullanıcı eklemek için aşağıdaki bilgileri doldurarak tarafımıza iletebilirsiniz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
				</div>
				<form id="kullanici_form" method="post" onsubmit="return kullanici_send();">
					<label for="">Ad Soyad *</label>
					<input autocomplete="off" maxlength="50" name="adsoyad" type="text" required>
					
					<label for="Telefon" style="margin-top:10px">Telefon *</label>
					<input autocomplete="off" type="text" name="tel" placeholder="05_______" required>	
					
					<label for="password" style="margin-top:10px">Şifre *</label>
					<input autocomplete="off" type="text" name="password" required>	
					
					<input type="submit" value="Yeni Kullanıcı Ekle"/>
				</form>
				<?php }elseif($id=='taleplerim'){
					
					$SOAP_AUTH= array( 'login' => 'rfcuser1', 'password' => 'Sap12345');
					#SpecifyWSDL

					$WSDL="http://blkcrmprda1.securitasturkey.com:8000/sap/bc/srt/wsdl/flv_10002A101AD1/bndg_url/sap/bc/srt/rfc/sap/zcrm_web_talep/100/zcrm_web_talep/zcrm_web_talep_b?sap-client=100";
					#CreateClient Object, download and parse WSDL
					$client= new SoapClient($WSDL,$SOAP_AUTH);
					$talepList = $client->ZCRM_WEBMOB_TALEP_GORUNTULE(
						array(
							"IV_IS_ANLASMASI" => $_SESSION['IS_ANLASMASI']
						)
					);
					
				 $toplam_kayit = count($talepList->EX_DATA->item);
					
				
					 
				if($toplam_kayit<2){
					$talepListele = $talepList->EX_DATA;
				} else {
					$talepListele = $talepList->EX_DATA->item;
				}
				if($toplam_kayit>0) {	
				?>
				<div class="listeletablo tip2">
					<table>
						<thead>
							<tr>
								<td width="100">Tarih</td>
								<td width="250">Talep ID</td>
							</tr>
						</thead>
						<tbody>
							
							<?php 
							 
								$i=0;
								foreach ($talepListele as $value) {
											$i++;
							?>
							<tr>
								<td><?php echo date2Human(substr($value->TARIH,0,10)); ?></td>
								<td><?php echo $value->TALEP_ID; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>	
				</div>
				
				<?php } else 
					echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Herhangi bir talebiniz bulunmamaktadır. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
				<div align="right">
					<input onclick="location.href='index.php?id=yeni-talep';" type="submit" value="Yeni Talep Oluştur"/>
					<input onclick="window.open('https://online.securitasalarm.com.tr/iptal-formu', '_blank');" type="submit" value="Hizmet Sonlandırma Formu İndir"/>
				</div>
				<?php }elseif($id=='yeni-talep'){?>
				<div class="talepResult">
					<?php echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Her türlü soru ve görüşleriniz için bize yazın, en kısa zamanda cevaplayalım. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
				</div>
				<form id="talep_form" method="post" onsubmit="return talep_send();">
					<label for="">Ad Soyad *</label>
					<input autocomplete="off" maxlength="50" name="adsoyad" type="text" value="<?php echo $_SESSION['TepeUserName']; ?>" readonly>
					
					<label for="Subject" style="margin-top:10px">Konu *</label>
					<select name="subject" class="js-example-basic-multiple-status" required>
						<option value="8">Memnuniyet</option>
						<option value="7">Öneri</option>
						<option value="6">Şikayet</option>
						<option value="9">Servis Talebi</option>
						<option value="10">Yeni Kullanıcı Ekleme Talebi</option>
						<option value="12">İlave Ürün/Hizmet Talebi</option>
					</select>
					
					<label for="" style="margin-top:10px">Mesajınız*</label>
					<textarea name="mesajiniz" id="" cols="30" rows="9" required></textarea>
					
					<input type="submit" value="Gönder"/>
				</form>
				<?php }elseif($id=='sifre-islemlerim'){?>
				<div class="sifreResult">
					<?php echo "<div class=\"bilgilendirme\">
						<div class=\"bilgi turuncu\">Mevcut şifrenizi ve yenilemek istediğiniz <b>en az 6 haneli</b> yeni şifrenizi giriniz. <div class=\"kapat\"><i class=\"fas fa-times\"></i></div></div>
					</div>"; ?>
				</div>
				<form id="sifre_form" method="post" onsubmit="return sifre_send();">
					<label for="">Şu Anki Şifreniz</label>
					<input type="password" name="sifreeski" />
					
					<label for="">Yeni Şifre</label>
					<input type="password" name="sifre1" />
					
					<label for="">Yeni Şifre (Tekrar)</label>
					<input type="password" name="sifre2" />
					
					<input type="submit" value="Güncelle"/>
				</form>
				<?php }elseif($id=='iletisim'){?>
				<div class="kisatanitim">
					<div class="kisatanitimlar">
						<div class="slayt" style="height:350px;">
							<div class="basliklar">
								<h1>Securitas Alarm İletişim</h1>
								<p>7/24 Çağrı Merkezi</p>
								<p><a href="tel:4448373"><h2>444 83 73</h2></a></p>
							</div>
							<div class="karart kirmizibg aktif"></div>
							<img src="dosyalar/images/slayt1.png" alt="İletişim" />
						</div>
					</div>
				</div>
				<?php }elseif($id=='gizlilik'){?>
	 <h3>GİZLİLİK, GÜVENLİK ve ÇEREZ KULLANIM POLİTİKASI</h3><br/>
		<p>Bu beyan Securitas Alarm A.Ş.’nin gizlilik ve güvenlik politikasını içerir. Securitas Alarm A.Ş. olarak önceliklerimizden biri de siz değerli müşterilerimizin/Kullanıcılarımızın kişisel bilgilerinizin güvenliğini sağlamaktır. Bilgilerinizin güvenliği bizim için öncelik taşımakta ve yasalara uygun olarak korunmaktadır.</p><br/>
		•	Uygulama ve Internet Sitesi üzerinden girilen, kişisel veriler aksi belirtilmedikçe Securitas Alarm A.Ş. veri tabanında saklanmaktadır. <br/>
		<p>	Kişisel verileriniz Firmamız ile Bilkent Holding A.Ş., mevcut ve ilerideki iştirakleri, bağlı şirketleri, ortakları, halefleri ve/veya bunların belirleyecekleri üçüncü kişiler/kuruluşlar tarafından, kampanya bilgileri, size özel teklif ve promosyonlar, tanıtım, reklam, satış, pazarlama, mağazalarda yapılacak etkinliklerin sizlerle e-posta, SMS, internet, telefon ve benzeri diğer iletişim araçları ile, her türlü tanıtım, bilgilendirme, reklam, ve diğer amaçlarla paylaşılması için kullanılacaktır. Siz değerli üyelerimizin bu gibi bilgilendirmeleri alıp almama konusunda seçim yapma hakkı bulunmakta olup; sitemiz üyelik formunun doldurulması esnasında bu tercihi yapabilir, üyelik işlemleriniz sonrasında ise bu seçimi değiştirebilirsiniz. Aksine bir yazılı bildiriminiz olmadığı müddetçe Firmamızın tarafınıza yönelik belirtilen iletişim faaliyetlerinde bulunabileceğini kabul ve beyan etmektesiniz.</p><br/>
		•	Müşterilerimize/Kullanıcılarımıza ait bilgiler yasal düzenlemelerin öngördüğü sınırlamalar çerçevesinde ve usulüne uygun şekilde talep edilmesi halinde, yasal gereklilikleri ifa etmek ve/veya resmi merciilerin taleplerini yerine getirmek amacı ile müşterilerimizin tüm hakları gözetilerek ilgili kişi ve/veya kuruluşlara açıklanabilir.<br/><br/>
		•	Internet Sitemiz üzerinden yapacağınız tüm kredi kartı işlemleri ve onayları Securitas Alarm A.Ş’den bağımsız olarak ilgili banka ve benzeri kart kuruluşlarınca online olarak müşterilerimiz ve banka arasında gerçekleştirilmektedir. Kredi kartı bilgileriniz sistemimizde saklanmamaktadır.<br/><br/>
		•	Internet Alışveriş sırasında kullanılan kredi kartı ile ilgili bilgiler internet sitemizden tamamen bağımsız olarak 128 bit SSL (Secure Sockets Layer) protokolü ile şifrelenip sorgulanmak üzere ilgili bankaya ulaştırılır. Kartın kullanılabilirliği onaylandığı takdirde alışverişe devam edilir. Kartınızla ilgili hiçbir bilgi tarafımızdan görüntülenememekte ve sitemizin veya sitemizin işbirliği içinde olduğu şirketlerin sunucularında tutulmamakta olup; yalnızca bilgisayarınız (tarafınız) ve bankanız arasında görüntülenebilmektedir. <br/><br/>
		•	Internet Sitesinin ve Uygulamanın diğer web sitelerine bağlantı verdiği hallerde tüm kullanım ve işlemler için o sitelerin gizlilik, güvenlik politikaları ve kullanım şartları geçerlidir; sitemizden reklam, banner, içerik görmek amacıyla veya başka herhangi bir amaç ile ulaşılan diğer web sitelerinden bilgi kullanımları, keza sitelerin etik ilkeleri, gizlilik-güvenlik prensipleri, servis kalitesi ve diğer uygulamaları sebebi ile oluşabilecek ihtilaf, maddi-manevi zarar ve kayıplardan sitemiz sorumlu değildir.<br/><br/>
		•	Securitas Alarm’ın gerekli bilgi güvenliği önlemlerini almasına karşın, securitasalarm.com.tr'ye ve sisteme yapılan saldırılar sonucunda gizli bilgilerin zarar görmesi veya üçüncü kişilerin eline geçmesi durumunda, Tepe Güvenlik’in herhangi bir sorumluluğu olmayacaktır.<br/><br/>
		<b>Çerezler ve Diğer Teknolojiler</b><br/>

		<p>Securitas Alarm A.Ş. Internet Siteleri ve Uygulama, çerezleri ve diğer teknolojileri (kısaca “çerezler” ) kullanabilir. Çerezler bir web sunucusu tarafından sabit diskinize taşınan ve ardından bilgisayarınızda saklanan ufak metin dosyalarıdır.</p><br/>
		<p>Çerezler, Müşteri/Kullanıcı davranışını daha iyi anlamamıza yardımcı olur; Internet Sitemizin kullanımı ve ziyaret verileri ile ilgili bilgi verir, sitemizi geliştirmemize yardımcı olur. </p><br/>
		<p>Çerezler, Müşteriler/Kullanıcılar Internet Sitesini ya da Uygulamayı kullanırken, müşterilerin kişisel bilgilerini hatırlamak için de kullanırlar. Böylece Internet Sitemizi ve Uygulamayı kullanımınız kolaylaşır, kişiye özel hizmet sunabiliriz. </p><br/>
		<p>Çerezleri devre dışı bırakmak istiyorsanız kullandığınız web tarayıcı ve mobil cihaz üzerinden gereken ayarları yapabilirsiniz. </p><br/>
		<p>Bazı bilgileri otomatik olarak toplarız ve bunları günlük dosyalarında saklarız. Bu bilgiler İnternet Protokolü (IP) adreslerini, tarayıcı türünü ve dilini, İnternet servis sağlayıcısını (ISP), başvuran ve çıkış internet sitelerini ve uygulamalarını, işletim sistemini, tarih/zaman damgasını ve tıklama dizisi verilerini içerir.</p><br/>
		<p>Bu bilgileri, eğilimleri anlamak ve analiz etmek, siteyi yönetmek, sitedeki kullanıcı davranışı hakkında bilgi edinmek, ürün ve hizmetlerimizi geliştirmek ve bir bütün olarak kullanıcı tabanımız hakkında demografik bilgiler toplamak için kullanırız. </p><br/>
		<p>Bu Politikada yer alan hükümler Securitas Alarm A.Ş. tarafından gerek görüldüğü takdirde mevzuat hükümlerine uygun olarak Internet Sitesinde yayınlanmak suretiyle değiştirebilir. İşbu hükümlerinden herhangi birisinin değişmesi halinde ilgili değişiklikler, değişikliğin yayınlandığı tarihte yürürlüğe girer. </p><br/>
		<p>Bu Politikayı kabul edip etmemekte ve tamamen kendi takdirinize göre kişisel bilgilerinizi verip vermemekte serbestsiniz, </p><br/>
		<p><b>Lütfen, Uygulamayı ve Internet Sitemizi ve/veya hizmetlerimizi kullanmakla bu Politikayı kabul etmiş olduğunuzu unutmayınız. </b></p><br/>

		<b>SECURITAS ALARM ANONİM ŞİRKETİ</b><br/>
		<b>Adresi:</b> Üniversiteler Mah. 1597 Cadde No:3 Bilkent Center AVM No: 42/90 Çankaya Ankara<br/>
		<b>Telefon:</b> 444 83 73<br/>
		<b>Eposta:</b> info@securitasalarm.com.tr


				
				
				
				<?php }?>
			</div>
			<div class="col-sm-3">
				<div class="bilesen hesabimdivler">
					<div class="baslik">Merhaba <?php echo $_SESSION['TepeUserName']; ?></div>
					<div class="bilesenic">
						<ul>
							<li class="<?php echo ($id == 'online-islemler' ? 'aktif' : '');?>"><a href="index.php">Online İşlemler</a></li>
							<li class="<?php echo ($id == 'abonelik-bilgilerim' ? 'aktif' : '');?>"><a href="index.php?id=abonelik-bilgilerim">Abonelik Bilgilerim</a></li>
							<li class="<?php echo ($id == 'abonelik-sozlesme' ? 'aktif' : '');?>"><a href="index.php?id=abonelik-sozlesme">Abonelik Sözleşmeleri</a></li>
							<li class="<?php echo ($id == 'faturalarim' ? 'aktif' : '');?>"><a href="index.php?id=faturalarim">Faturalarım</a></li>
							<li class="<?php echo ($id == 'kullanicilarim' ? 'aktif' : '');?>"><a href="index.php?id=kullanicilarim">Kullanıcılarım</a></li>
							<li class="<?php echo ($id == 'zone-listesi' ? 'aktif' : '');?>"><a href="index.php?id=zone-listesi">Bölgelerim</a></li>
							<li class="<?php echo ($id == 'taleplerim' ? 'aktif' : '');?>"><a href="index.php?id=taleplerim">Taleplerim</a></li>
							<li class="<?php echo ($id == 'sifre-islemlerim' ? 'aktif' : '');?>"><a href="index.php?id=sifre-islemlerim">Şifre İşlemlerim</a></li>
							<li class="<?php echo ($id == 'alarmlarim' ? 'aktif' : '');?>"><a href="index.php?id=alarmlarim">Alarmlarım</a></li>
							<li><a href="https://www.securitasalarm.com.tr/kvkk-bilgi" target="_blank">Kişisel Verilerin Korunması Hk.</a></li>
							<li><a href="https://www.securitasalarm.com.tr/bilgi-guvenligi-politikasi" target="_blank">Bilgi Güvenliği Politikası</a></li>
							<li class="<?php echo ($id == 'gizlilik' ? 'aktif' : '');?>"><a href="index.php?id=gizlilik">Gizlilik</a></li>
							<li class="<?php echo ($id == 'iletisim' ? 'aktif' : '');?>"><a href="index.php?id=iletisim">İletişim</a></li>
							<li><a href="logout.php">Çıkış Yap</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include('dosyalar/dahili/altkisim.php');?>