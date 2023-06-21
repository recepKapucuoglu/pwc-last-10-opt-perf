<?php require_once('_db.php'); ?>
<section id="onecikanegitimler">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="sectionbaslik">
					<h3 class="baslik">Öne Çıkan Eğitimler</h3>
				</div>
			</div>
		</div>
		<div class="listeleme">
						
			<div class="listeler gridlistele">
				<div class="row">
					<?php 	
						$db->where('durum', 1);
					// //	$db->where('egitim_tarih',$dateToday,'>=');
					// 	if($id<>"")
					// 		$db->where('id', $id, '<>');
					// 	$db->orderBy("egitim_tarih","asc");
						$oneCikanEgitimlerResult = $db->get('index_filter',Array (0, 4));
				foreach ($oneCikanEgitimlerResult as $row) {
				   $dateToday = date("Y-m-d");
				   if ($row['egitim_tarih'] >= $dateToday || $row['types'] == "E-Learning") { 
					  echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
				   <a href="' . $row['seo_url'] . '" class="card-href">
				   <div class="card">
				   <div class="card__thumb">
				   <a href="' . $row['seo_url'] . '"><img src="' . $row['resim'] . '" alt="' . $row['resim_alt_etiket'] . '" /></a>
				   </div>';
					  if ($row['types'] == "E-Learning") {
						 echo '<div class="card__category"><a href="#">E-Learning</a><img src="dosyalar/images/pointer.png" style="width:19px; filter: brightness(0) invert(1);"/></div>';
					  }
					  echo '<div class="card__body">
				   <div>
				   <!-- <div class="favoriiptal">Favorilerimden Çıkar!</div> -->
				   <h2 class="card__title">
				   <a href="' . $row['seo_url'] . '">
				   ' . $row['egitim_adi'] . '
				   </a>
				   </h2>
				   <span class="card__description">
				   ' . $row['kisa_aciklama'] . '
				   </span>
				   </div>
				   <div class="card__dates">';
					  if ($row['types'] != "E-Learning") {
						 echo '<div class="card__time">
				   <img src="dosyalar/images/calendar-alt.svg"/>
				   <time>' . date2Human($row['egitim_tarih']) . '</time>
				   </div>';
					  }
					  if ($row['types'] != "E-Learning") {
						 echo '<div class="card__location" >
				   <img src="dosyalar/images/location-arrow.png"/>
				   <lokasyon>';
						 if ($row['webex'] == 1) {
							echo "Webex";
						 } else {
							echo $row['sehir_adi'];
						 }
						 echo '</lokasyon>
				   </div>';
					  }
					  echo '<div class="fiyat">
				   <!--<del>1.673,99 TL</del>-->
				   <img src="dosyalar/images/money-bill.svg"/>
				   <b>';
					  if ($row['webex'] == 1) {
						 echo "Ücretsiz";
					  } else {
						 echo number_format($row['ucret'], 2, ',', '.') . '<span> TL + KDV</span>';
					  }
					  echo '</b>
				   </div>
				   </div>
				   <div class="kutu-buttons">';
					  if($row['types'] <> "") {
					  echo '<a class="online-button" style="background:#ffb600; color:#2d2d2d">';
					  echo $row['types'];
					  echo '</a>';}
					  if ($row['level_id'] == 1) {
						 $derece = "Başlangıç";
					  } elseif ($row['level_id'] == 2) {
						 $derece = "Orta";
					  } elseif ($row['level_id'] == 3) {
						 $derece = "İleri";
					  }
					  if ($derece <> "") {
						 echo '<a class="baslangic-button" style="background:#d04a22;" >' . $derece . '</a>';
					  }
					  if ($row['sehir_adi'] <> "" && $row['sehir_adi'] != "Elearning" ) {
						 echo '<a class="online-button" style="background:#000; margin-left:5px;">';
						 if ($row['sehir_adi'] != "") {
							if ($row['sehir_adi'] != "Elearning")
							   echo $row['sehir_adi'];
						 }
						 echo '</a>';
					  }
					  echo '</div>
				   </div>
				   </div>
				   </a>
				   </div>';
				   }
			

				   if (($row['egitim_tarih'] < $dateToday && $row['types'] != 'E-Learning' ) ) { //tarihi geçmiş egitimler veya elearning olup takvim (ücret) girilmemişler
					  echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			
						   <a href="' . $row['seo_url'] . '" class="card-href">
							   <div class="card">
								   <div class="card__thumb">';
					  echo '<a href="' . $row['seo_url'] . '"><img src="' . $row['resim'] . '" alt="' . $row['resim_alt_etiket'] . '" /></a>
								   </div>
								   <div class="card__body">
									   <div>
										   <h2 class="card__title"><a href="egitimlerimiz/' . $row['seo_url'] . '">' . $row['egitim_adi'] . '</a></h2>
										   <span class="card__description">' . $row['kisa_aciklama'] . '</span>
									   </div>
									   <div class="card__dates">
										   <time>Bu eğitim için açık bir tarih bulunmamaktadır. <br/>* Bilgi al formunu doldurarak ilgili eğitim takvimi planlandığında sizinle iletişime geçmemizi sağlayabilirsiniz.</time>
									   </div>
								   </div>
							   </div>
						   </a>
					   </div>';
				   }
				}
			
					
					
					?>
					
			</div>
		</div>
		</div>
	</div>
</section>