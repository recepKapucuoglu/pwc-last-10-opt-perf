<?php
require_once('dosyalar/dahili/_db.php');

$gelenVeri = $_POST['selectedTypes'];
$gelenCategory = $_POST['selectedCategories']; // egitim kategorisi
$gelenLocation = $_POST['selectedLocations']; // lokasyon 
$veri = $gelenVeri;
$page = isset($_POST['pagination']) ? $_POST['pagination'] : 1; // varsayılan olarak 1. sayfa
$allPage=$_POST['allPage'];
$rakamsiz = array(); //egitim tipi
$rakamli = array(); // egitim level
$searchTerm = $_POST['searchTerm'];//arama butonu 
//pathden gelen category ismini alalım.
$page_url=$_POST['page_url'];
//category ismine göre listeleyelim
if(isset($page_url)){
	$db->where('seo_url', $page_url);
	$results = $db->get('categories');
 	foreach ($results as $value) {
	$page_url=$value['baslik'];
}	
}
$query = "SELECT * FROM egitimlerimiz_filter where kategoriler LIKE '%$page_url%'";

//arama butonu gönderilmişse
if(isset($searchTerm) && !isset($gelenVeri) && !isset($gelenLocation)){
   $query = "SELECT * FROM egitimlerimiz_filter 
   WHERE egitim_adi LIKE '%$searchTerm%' 
   AND kategoriler LIKE '%$page_url%' ";
 
 if (!$allPage) {
   $countQuery = $query;
   $countQuery = str_replace('*', 'COUNT(*) as total', $countQuery);
   $results = $db->rawQuery($countQuery);
   $totalDataCount=$results[0]['total'];
//1 sayfadaki data sayısı
$perPage = 8;
//toplam sayfa sayısı
$totalPageCount = ceil($totalDataCount / $perPage);
// echo $totalPageCount;
//son sayfaya gelindimi'
   if ($page == 1)
      $limit = 8;
   $limit = 8 + (($page - 1) * 8);
   
   $query .= " LIMIT $limit ";
}
   // echo $query; //sORGUYU GOSTER
$results = $db->rawQuery($query);
}
//type,level,lokasyon gönderilmişse
else if(isset($gelenCategory) || isset($gelenVeri) || isset($gelenLocation)){
foreach ($veri as $eleman) {
   $rakam_sonuc = preg_match('/\d+/', $eleman);
   if ($rakam_sonuc === 1) {
      array_push($rakamli, $eleman);
   } else {
      array_push($rakamsiz, $eleman);
   }
}
// kategori seçilmişse  
$i = 1;
if (count($gelenCategory) > 0) {
   foreach ($gelenCategory as $category) {
      //birden fazla kategori ise
      if ($i > 1) {
         $query .= "OR kategoriler LIKE '%$category%' ";
      } else {
         $query .= "WHERE ( kategoriler LIKE '%$category%' ";
      }
      $i++;
   }
   $query = rtrim($query, 'OR '); // Son "OR" ifadesini silmek için
   $query .= ") ";
}
// type seçilmişse
if(count($rakamsiz)>0){
   $i=1;
foreach ($rakamsiz as $type) {
   if ($i > 1) {
      $query .= "OR types = '$type' ";
   } else {
      if(substr($query, -strlen("egitimlerimiz_filter ")) === "egitimlerimiz_filter ") {
         $query .= "WHERE (types = '$type' ";
      }
      else{
         $query .= "AND (types = '$type' ";
      }      
   }
   $i++;

}
$query .= ")";
}
//level seçilmişse
if(count($rakamli)>0){
   $i=1;
  foreach ($rakamli as $level) {
     if ($i > 1) {
        $query .= "OR level_id = $level ";
     } else {
      if(substr($query, -strlen("egitimlerimiz_filter ")) === "egitimlerimiz_filter ") {
         $query .= "WHERE (level_id = $level ";
      }
      else{
         $query .= "AND (level_id = $level ";
      }
   }
     $i++;
  }
  $query .= ")";
  }
 //lokasyon seçilmişse
if(count($gelenLocation)>0){
   $i=1;
  foreach ($gelenLocation as $location) {
     if ($i > 1) {
        $query .= "OR sehir_adi = '$location' ";
     } else {
      if(substr($query, -strlen("egitimlerimiz_filter ")) === "egitimlerimiz_filter ") {
         $query .= "WHERE (sehir_adi = '$location' ";
      }
      else{
         $query .= "AND (sehir_adi = '$location' ";
      }
   }
     $i++;
  }
  $query .= ")";
  }
//   $query .= "ORDER BY 
//   CASE WHEN `source`='education' THEN 1 ELSE 0 END, 
//   `kayit_tarihi` DESC,
//   CASE WHEN `source` = 'education-calender' THEN 0 ELSE 1 END,
//   `kayit_tarihi` DESC ";

   // $query.="ORDER BY CASE WHEN
   // `egitim_tarih` IS NULL THEN 1 ELSE 0
   // END,
   // `egitim_tarih`";
    //pagination işlemi
   if (!$allPage) {
      $countQuery = $query;
      $countQuery = str_replace('*', 'COUNT(*) as total', $countQuery);
      $results = $db->rawQuery($countQuery);
      $totalDataCount=$results[0]['total'];
      //1 sayfadaki data sayısı
      $perPage = 8;
      //toplam sayfa sayısı
      $totalPageCount = ceil($totalDataCount / $perPage);
      // echo $totalPageCount;
      //son sayfaya gelindimi'
         if ($page == 1)
            $limit = 8;
         $limit = 8 + (($page - 1) * 8);
       
         $query .= "LIMIT $limit";
      }
// echo $query; //sORGUYU GOSTER
$results = $db->rawQuery($query);
}
//hiç biri seçilmemişse
else if(!isset($searchTerm) && !isset($gelenCategory) && !isset($gelenVeri) && !isset($gelenLocation) ){
	 //pagination işlemi
    if (!$allPage) {
      $countQuery = $query;
      $countQuery = str_replace('*', 'COUNT(*) as total', $countQuery);
      $results = $db->rawQuery($countQuery);
      $totalDataCount=$results[0]['total'];
   //1 sayfadaki data sayısı
   $perPage = 8;
   //toplam sayfa sayısı
   $totalPageCount = ceil($totalDataCount / $perPage);
   // echo $totalPageCount;
   //son sayfaya gelindimi'
   
      if ($page == 1)
         $limit = 8;
      $limit = 8 + (($page - 1) * 8);
      $query .= " LIMIT $limit";
   }
	  $results = $db->rawQuery($query);
}

//tümünü görüntüle basıldıysa
if(isset($allPage)){ echo '
   <script> 
   $(".tumunu_goruntule").hide();
   $(".dfazla_goruntule3").hide();
   </script>';}
   else  { //basılmadıysa
      // tumunu görüntüle hide olsun ve 
      //dfazla gorüntüle durumu
      if ($page >= $totalPageCount) {
         echo '
         <script> 
         $(".dfazla_goruntule3").hide();
         $(".tumunu_goruntule").hide();
         </script>';
      }
     else{ echo '<script> 
      $(".dfazla_goruntule3").show();
      $(".tumunu_goruntule").show();
      </script>';}
    
   }
// Sonuçları işleme
if ($db->count > 0) {
   foreach ($results as $row) {
      // if($i==13) break;
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
      // if ($row['egitim_tarih'] > $dateToday || $row['types'] == "E-Learning") {
      //    echo '<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
      // <a href="' . $row['seo_url'] . '" class="card-href">
      // <div class="card">
      // <div class="card__thumb">
      // <a href="' . $row['seo_url'] . '"><img src="' . $row['resim'] . '" alt="' . $row['resim_alt_etiket'] . '" /></a>
      // </div>';
      //    if ($row['types'] == "E-Learning") {
      //       echo '<div class="card__category"><a href="#">E-Learning</a><img src="dosyalar/images/pointer.png" style="width:19px; filter: brightness(0) invert(1);"/></div>';
      //    }
      //    echo '<div class="card__body">
      // <div>
      // <!-- <div class="favoriiptal">Favorilerimden Çıkar!</div> -->
      // <h2 class="card__title">
      // <a href="' . $row['seo_url'] . '">
      // ' . $row['egitim_adi'] . '
      // </a>
      // </h2>
      // <span class="card__description">
      // ' . $row['kisa_aciklama'] . '
      // </span>
      // </div>
      // <div class="card__dates">';
      //    if ($row['types'] != "E-Learning") {
      //       echo '<div class="card__time">
      // <img src="dosyalar/images/calendar-alt.svg"/>
      // <time>' . date2Human($row['egitim_tarih']) . '</time>
      // </div>';
      //    }
      //    echo '<div class="card__location">
      // <img src="dosyalar/images/location-arrow.png"/>
      // <lokasyon>';
      //    if ($row['webex'] == 1) {
      //       echo "Webex";
      //    } else {
      //       echo $row['sehir_adi'];
      //    }
      //    echo '</lokasyon>
      // </div>
      // <div class="fiyat">
      // <!--<del>1.673,99 TL</del>-->
      // <img src="dosyalar/images/money-bill.svg"/>
      // <b>';
      //    if ($row['webex'] == 1) {
      //       echo "Ücretsiz";
      //    } else {
      //       echo number_format($row['ucret'], 2, ',', '.') . '<span> TL + KDV</span>';
      //    }
      //    echo '</b>
      // </div>
      // </div>
      // <div class="kutu-buttons">
      // <a class="online-button">';
      //    if ($row['sehir_adi'] != "") {
      //       echo $row['sehir_adi'];
      //    }
      //    echo '</a>';
      //    if ($row['level_id'] == 1) {
      //       $derece = "Başlangıç";
      //    } elseif ($row['level_id'] == 2) {
      //       $derece = "Orta";
      //    } elseif ($row['level_id'] == 3) {
      //       $derece = "İleri";
      //    }
      //    echo '<a class="baslangic-button">' . $derece . '</a>
      // </div>
      // </div>
      // </div>
      // </a>
      // </div>';
      // }

      if ($row['egitim_tarih'] < $dateToday && $row['types'] != 'E-Learning') { //tarihi geçmiş egitimler
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

} else {
   // Sonuç yoksa yapılacak işlemler
   echo "Sonuç bulunamadı.";
}


?>