<?php include('dosyalar/dahili/header.php');
// error_reporting(E_ALL);
// ini_set('display_error',1);

?>

<section id="sayfaust" style="background-image:url(dosyalar/images/sayfaust-bg.jpg);">
	<div class="basliklar">
		<div class="baslik">EĞİTİMLERİMİZ</div>
		<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="/"><span itemprop="name">Anasayfa</span></a>
				<meta itemprop="position" content="1" />
			</li>
			<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="javascript:;"><span itemprop="name">Eğitimlerimiz</span></a>
				<meta itemprop="position" content="2" />
			</li>
		</ol>
	</div>
</section>
<section style="margin-top:30px">
	<div class="education-type">
		<div class="container education-type__container">
			<div class="education-type_container">
				<div>
					<div id="educationCategory">
						<span>
							Eğitim Kategorileri
						</span>
						<img id="educationLevelDown" src="dosyalar/images/down-arrow.png" />
					</div>
				</div>
				<div>
					<div id="educationType">
						<span>
							<? if (isset($_POST['selectedTypes'])) {
								$selectedTypes = $_POST['selectedTypes'];
								echo $selectedTypes;

							} ?>
							Eğitim Tipi
						</span>
						<img id="educationTypeDown" src="dosyalar/images/down-arrow.png" />
					</div>
				</div>
				<div>
					<div id="educationLevel">
						<span>
							Eğitim Seviyesi
						</span>
						<img id="educationLevelDown" src="dosyalar/images/down-arrow.png" />
					</div>
				</div>
				<div>
					<div id="educationLocation">
						<span>
							Lokasyon
						</span>
						<img id="educationLocationDown" src="dosyalar/images/down-arrow.png" />
					</div>
				</div>
			</div>
			<div class="education-type_search-container">
				<input type="text" placeholder="Arama..." class="education-type_search" id="search-input" />
				<div class="search-icon__education-type">
					<img src="dosyalar/images/search.png"
						style="filter: invert(13%) sepia(0%) saturate(3436%) hue-rotate(317deg) brightness(108%) contrast(90%); cursor:pointer" />
				</div>
			</div>
		</div>
	</div>

	<div class="education-level" id="education" style="display:none">
		<div class="container">
			<div class="education-type_container" id="educationTypeContainer" style="display:none">
				<?php
				$query = "SELECT DISTINCT type_ismi FROM education_list WHERE type_ismi IS NOT NULL";
				$type_list = $db->rawQuery($query);
				foreach ($type_list as $type) {
					?>
					<div style="margin-right:25px">
						<input class="egitim-filtre" type="checkbox" id="<?php echo $type['type_ismi'] ?>"
							name="<?php echo $type['type_ismi'] ?>" value="<?php echo $type['type_ismi'] ?>">
						<label for="<?php echo $type['type_ismi'] ?>"><?php echo $type['type_ismi'] ?></label><br>
					</div>
				<?php } ?>
			</div>
			<div class="education-type_container" id="educationLevelContainer" style="display:none">
				<?php
				//level_idsi olmayanlar gelmesin 
				$query = "SELECT DISTINCT education_level_id FROM education WHERE education_level_id IS NOT NULL";
				$level_list = $db->rawQuery($query);
				foreach ($level_list as $level) {
					if ($level['education_level_id'] == 1) {
						$level_adi = "Başlangıç";
					}
					if ($level['education_level_id'] == 2) {
						$level_adi = "Orta";
					}
					if ($level['education_level_id'] == 3) {
						$level_adi = "İleri Seviye";
					}
					?>
					<div style="margin-right:25px">
						<input class="egitim-filtre" type="checkbox" id="<?php echo $level_adi ?>"
							name="<?php echo $level_adi ?>" value="<?php echo $level['education_level_id'] ?>">
						<label for="<?php echo $level_adi ?>"><?php echo $level_adi ?></label><br>
					</div>
				<?php } ?>
			</div>
			<div class="education-type_container" id="educationCategoryContainer" style="display:none">
				<?php
				$active_categories_all = $db->get('active_categories_all');
				foreach ($active_categories_all as $category) {
					?>
					<div class="egitim-filtre_container">
						<div style="display:flex; align-items:center">
							<input class="egitim-filtre" type="checkbox"
								id="category_<?php echo $category['category_id']; ?>"
								name="<?php echo $category['category_baslik']; ?>"
								value="<?php echo $category['category_baslik']; ?>">
							<label for="category_<?php echo $category['category_id']; ?>"><?php echo $category['category_baslik']; ?></label><br>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="education-type_container" id="educationLocationContainer" style="display:none">
				<?php
				$query = "SELECT DISTINCT sehir_adi from egitimlerimiz_filter where sehir_adi IS NOT NULL";
				$location_list = $db->rawQuery($query);
				foreach ($location_list as $location) {
					if ($location['sehir_adi'] != 'Elearning') {
						?>
						<div class="egitim-filtre_container">
							<div style="display:flex; align-items:center">
								<input class="egitim-filtre" type="checkbox" id="location_<?php echo $location['sehir_adi']; ?>"
									name="deneme" value="<?php echo $location['sehir_adi'] ?>">
								<label for="location_<?php echo $location['sehir_adi']; ?>"><?php echo $location['sehir_adi']; ?></label><br>
							</div>
						</div>
					<?php }
				}
				?>
			</div>
		</div>
	</div>
	<div class="container" style="margin-bottom:30px; margin-top:30px;">
		<div class="row list-filters-items">

			<?php
			// $query = "SELECT * FROM egitimlerimiz_filter ";
			// $query .= "ORDER BY 
			// CASE WHEN `source`='education' THEN 1 ELSE 0 END, 
			// `kayit_tarihi` DESC,
			// CASE WHEN `source` = 'education-calender' THEN 0 ELSE 1 END,
			// `kayit_tarihi` DESC LIMIT 8";		
			// $results = $db->rawQuery($query);
			// if ($db->count > 0) {
			// 	foreach ($results as $row) {
			// 	   $dateToday = date("Y-m-d");
			// 	   if ($row['egitim_tarih'] >= $dateToday || $row['types'] == "E-Learning") {
			// 		  echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			// 	   <a href="' . $row['seo_url'] . '" class="card-href">
			// 	   <div class="card">
			// 	   <div class="card__thumb">
			// 	   <a href="' . $row['seo_url'] . '"><img src="' . $row['resim'] . '" alt="' . $row['resim_alt_etiket'] . '" /></a>
			// 	   </div>';
			// 		  if ($row['types'] == "E-Learning") {
			// 			 echo '<div class="card__category"><a href="#">E-Learning</a><img src="dosyalar/images/pointer.png" style="width:19px; filter: brightness(0) invert(1);"/></div>';
			// 		  }
			// 		  echo '<div class="card__body">
			// 	   <div>
			// 	   <!-- <div class="favoriiptal">Favorilerimden Çıkar!</div> -->
			// 	   <h2 class="card__title">
			// 	   <a href="' . $row['seo_url'] . '">
			// 	   ' . $row['egitim_adi'] . '
			// 	   </a>
			// 	   </h2>
			// 	   <span class="card__description">
			// 	   ' . $row['kisa_aciklama'] . '
			// 	   </span>
			// 	   </div>
			// 	   <div class="card__dates">';
			// 		  if ($row['types'] != "E-Learning") {
			// 			 echo '<div class="card__time">
			// 	   <img src="dosyalar/images/calendar-alt.svg"/>
			// 	   <time>' . date2Human($row['egitim_tarih']) . '</time>
			// 	   </div>';
			// 		  }
			// 		  if ($row['types'] != "E-Learning") {
			// 			 echo '<div class="card__location" >
			// 	   <img src="dosyalar/images/location-arrow.png"/>
			// 	   <lokasyon>';
			// 			 if ($row['webex'] == 1) {
			// 				echo "Webex";
			// 			 } else {
			// 				echo $row['sehir_adi'];
			// 			 }
			// 			 echo '</lokasyon>
			// 	   </div>';
			// 		  }
			// 		  echo '<div class="fiyat">
			// 	   <!--<del>1.673,99 TL</del>-->
			// 	   <img src="dosyalar/images/money-bill.svg"/>
			// 	   <b>';
			// 		  if ($row['webex'] == 1) {
			// 			 echo "Ücretsiz";
			// 		  } else {
			// 			 echo number_format($row['ucret'], 2, ',', '.') . '<span> TL + KDV</span>';
			// 		  }
			// 		  echo '</b>
			// 	   </div>
			// 	   </div>
			// 	   <div class="kutu-buttons">';
			// 		  if ($row['types'] <> "") {
			// 			 echo '<a class="online-button" style="background:#ffb600; color:#2d2d2d">';
			// 			 echo $row['types'];
			// 			 echo '</a>';
			// 		  }
			// 		  if ($row['level_id'] == 1) {
			// 			 $derece = "Başlangıç";
			// 		  } elseif ($row['level_id'] == 2) {
			// 			 $derece = "Orta";
			// 		  } elseif ($row['level_id'] == 3) {
			// 			 $derece = "İleri";
			// 		  }
			// 		  if ($derece <> "") {
			// 			 echo '<a class="baslangic-button" style="background:#d04a22;" >' . $derece . '</a>';
			// 		  }
			// 		  if ($row['sehir_adi'] <> "" && $row['sehir_adi'] != "Elearning") {
			// 			 echo '<a class="online-button" style="background:#000; margin-left:5px;">';
			// 			 if ($row['sehir_adi'] != "") {
			// 				if ($row['sehir_adi'] != "Elearning")
			// 				   echo $row['sehir_adi'];
			// 			 }
			// 			 echo '</a>';
			// 		  }
			// 		  echo '</div>
			// 	   </div>
			// 	   </div>
			// 	   </a>
			// 	   </div>';
			// 	   }
			// 	   if ($row['egitim_tarih'] < $dateToday && $row['types'] != 'E-Learning') { //tarihi geçmiş egitimler
			// 		  echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
			
			// 			   <a href="' . $row['seo_url'] . '" class="card-href">
			// 				   <div class="card">
			// 					   <div class="card__thumb">';
			// 		  echo '<a href="' . $row['seo_url'] . '"><img src="' . $row['resim'] . '" alt="' . $row['resim_alt_etiket'] . '" /></a>
			// 					   </div>
			// 					   <div class="card__body">
			// 						   <div>
			// 							   <h2 class="card__title"><a href="egitimlerimiz/' . $row['seo_url'] . '">' . $row['egitim_adi'] . '</a></h2>
			// 							   <span class="card__description">' . $row['kisa_aciklama'] . '</span>
			// 						   </div>
			// 						   <div class="card__dates">
			// 							   <time>Bu eğitim için açık bir tarih bulunmamaktadır. <br/>* Bilgi al formunu doldurarak ilgili eğitim takvimi planlandığında sizinle iletişime geçmemizi sağlayabilirsiniz.</time>
			// 						   </div>
			// 					   </div>
			// 				   </div>
			// 			   </a>
			// 		   </div>';
			// 	   }
			// 	}
			
			//  } 
			//  else {
			// 	// Sonuç yoksa yapılacak işlemler
			// 	echo "Sonuç bulunamadı.";
			//  }
			?>


		</div>
		<!-- PAGİNATİON -->
		<div style='margin-top:25px; width: 100%; display:flex; justify-content:center;'>
			<div style='padding:0px 20px; display:inline-block; background:#ffb600; margin-right:25px'
				class="dfazla_goruntule2">

				<!-- <div id="dahafazla geri_paginate" onclick="return filter_back_page()" class="dahafazla">Tümünü Göster</div> -->

				<!-- <a class="pagination-count" onclick="return education_more_page();"></a> -->


				<div id="dahafazla ileri_paginate" onclick="return filter_next_page()" class="dahafazla">Daha Fazla
					Görüntüle</div>

			</div>
			<div style='padding:0px 20px; display:inline-block; background:#2d2d2d' >
				<div class="dahafazla tumunu_goruntule" style="color:#fff">Tümünü Görüntüle</div>
			</div>
		</div>
	</div>
</section>
<section class="action" style="margin-bottom:20px">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="action-body__contact">
					<p>PwC çözümleri hakkında daha fazla bilgi edinmek için bize ulaşın</p>
					<div class="action-body__info">
						<p><a href="/iletisim" style="color:#2d2d2d">Bilgi ve Teklif Talep Formu</a></p>
					</div>
					<div class="action-body_button">
						<a class="bilgi-al" href="/iletisim">Bilgi Al</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>

	var pagination = 1;
	var selectedTypes;
	var selectedCategories;
	var selectedLocations;
	var searchTerm;
	var aramaSearchTerm;
	
	document.addEventListener("DOMContentLoaded", function (event) {

		$('.tumunu_goruntule').click(function () {

			searchTerm = $('#search-input').val();
			page_url = $('#page_url').val();
			var allPage = 1;
			console.log(allPage);
			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					selectedTypes: selectedTypes,
					selectedCategories: selectedCategories,
					selectedLocations: selectedLocations,
					allPage: allPage,
					page_url: page_url,
					searchTerm: searchTerm,
				},
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
		});
		//arama butonu tıklandıgında
		$('.search-icon__education-type').click(function () {
			selectedTypes = [];
			selectedCategories = [];
			selectedLocations = [];
			pagination = 1;
			searchTerm = $('#search-input').val();

			// Burada searchTerm değişkeni ile input alanındaki değeri alıyoruz.
			console.log(searchTerm);
			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					searchTerm, searchTerm,
					pagination: pagination,
				},
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
			// Arama sayfasına yönlendiriyoruz ve URL'e searchTerm değerini ekliyoruz.
		});
		//eğitimleri filterle çek
		$('.education-type_container input[type=checkbox]').on('change', function () {
			selectedTypes = [];
			selectedCategories = [];
			selectedLocations = [];
			pagination = 1;
			$('.education-type_container input[type=checkbox]:checked').each(function () {
				if ($(this).closest('.education-type_container').attr('id') == 'educationTypeContainer') {
					selectedTypes.push($(this).val());
				}
				if ($(this).closest('.education-type_container').attr('id') == 'educationCategoryContainer') {
					selectedCategories.push($(this).val());
				}
				if ($(this).closest('.education-type_container').attr('id') == 'educationLevelContainer') {
					selectedTypes.push($(this).val());
				}
				if ($(this).closest('.education-type_container').attr('id') == 'educationLocationContainer') {
					selectedLocations.push($(this).val());
				}
			});
			console.log(pagination);
			console.log(selectedTypes);
			console.log(selectedCategories);
			console.log(selectedLocations);

			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					selectedTypes: selectedTypes,
					selectedCategories: selectedCategories,
					selectedLocations: selectedLocations,
					pagination: pagination,

				},
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
		});
		$('#ileri_paginate').click(function () {
			console.log("ss");
			// Your code here
		});
		
		//sayfa yüklendiği anda tüm eğitimleri çek
		if(aramaSearchTerm){
			$(document).ready(function () {
			pagination = 1;
			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					pagination: pagination,
					searchTerm :aramaSearchTerm
				},
				async: true,
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
		});
		}
		else{
			$(document).ready(function () {
			pagination = 1;
			console.log(pagination);
			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					pagination: pagination,
				},
				async: true,
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
		});
		}
		
	});
	function filter_next_page() {
		pagination = pagination + 1;
		console.log(pagination);
		$.ajax({
			url: "/egitimlerimiz_filter.php",
			type: "POST",
			data: {
				selectedTypes: selectedTypes,
				selectedCategories: selectedCategories,
				selectedLocations: selectedLocations,
				pagination: pagination,
				searchTerm: searchTerm,
			},
			success: function (cevap) {
				$('.list-filters-items').html(cevap);
			},
		});
		return false; // sayfa yenilenmesini önlemek için false değer döndürür
	}
	function filter_back_page() {
		if (pagination >= 2) {
			pagination = pagination - 1;
			console.log(pagination);
			$.ajax({
				url: "/egitimlerimiz_filter.php",
				type: "POST",
				data: {
					selectedTypes: selectedTypes,
					selectedCategories: selectedCategories,
					selectedLocations: selectedLocations,
					pagination: pagination,
				},
				success: function (cevap) {
					$('.list-filters-items').html(cevap);
				},
			});
		}

		return false; // sayfa yenilenmesini önlemek için false değer döndürür
	}

</script>

<?php include('dosyalar/dahili/footer.php'); ?>