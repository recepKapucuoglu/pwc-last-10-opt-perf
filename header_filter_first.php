<?php
require_once('dosyalar/dahili/_db.php');
$secilenTip = $_POST['type_id'];
$gelenData = $_POST['category_id'];
$category_seo_url = $_POST['category_seo_url'];
//elearning eğitimi olmayanlar
if ($secilenTip == "another_filter") {
    // bir category seçilmişse
    if ($gelenData) {
        $query = "SELECT resim,seo_url,egitim_adi,egitim_tarih,ucret,source FROM egitimlerimiz_filter WHERE kategoriler LIKE '%$gelenData%' AND (types IS NULL OR types != 'E-Learning')";    
        $sonuc = $db->rawQuery($query);
        $sonucSayisi = count($sonuc);

        //2.yol
        // $query = "SELECT resim,seo_url,egitim_adi,egitim_tarih,ucret,source FROM egitimlerimiz_filter WHERE kategoriler LIKE '%$gelenData%' AND (types IS NULL OR types != 'E-Learning') limit 3";    
        // $sonuc = $db->rawQuery($query);
        // $query = "SELECT count(*) as total FROM egitimlerimiz_filter WHERE kategoriler LIKE '%$gelenData%' AND (types IS NULL OR types != 'E-Learning')";    
        // $sonucSayisi=$db->rawQuery($query);
        // $sonucSayisi = $sonucSayisi[0]['total'];

        if ($sonucSayisi > 0) {
            $sayac = 0;
            foreach ($sonuc as $egitim) {
                if($egitim['source']=='education-calender'){

                echo '<li class="child-altmenu_item">
        <div class="child-altmenu_left">
            <div class="navbar-image">
                <img src="' . $egitim['resim'] . '" />
            </div>
            <a href="' . $egitim['seo_url'] . '"><p class="child-altmenu_p">' . $egitim['egitim_adi'] . '</p></a>
        </div>
        <div class="child-altmenu_right">
        <p  <time> ' . date2Human($egitim['egitim_tarih']) . '</span> </time></p>
        <p style="padding:0px;">' . $egitim['ucret'] . ' TL + KDV</p>
        </div>
    </li>';
                }
                else {
                    echo '<li class="child-altmenu_item">
                    <div class="child-altmenu_left">
                        <div class="navbar-image">
                            <img src="' . $egitim['resim'] . '" />
                        </div>
                        <a href="' . $egitim['seo_url'] . '"><p class="child-altmenu_p">' . $egitim['egitim_adi'] . '</p></a>
                    </div>
                </li>';
                }
                $sayac++;
                if ($sayac == 3) {
                    break;
                }
            }

          
            $kalanGoruntulenmeyenEgitim= $sonucSayisi-$sayac;
            if ($sonucSayisi > 3) {
                echo '<li class="child-altmenu_item" style="justify-content:end">
                <div class="child-altmenu_left">
                </div>
                <div class="child-altmenu_right">
                <div class="show-all_btn"><a style="background:transparent !important; color:white !important;" href="' . $category_seo_url . '"> Diğer (' . $kalanGoruntulenmeyenEgitim . ') eğitimi gör ></a></div>
                </div>
            </li>';
            }
           
        }
                
    }
}
//elearning egitimleri
if ($secilenTip == "elearning_filter") {
    $query = "SELECT resim,seo_url,egitim_adi,ucret FROM egitimlerimiz_filter WHERE kategoriler LIKE '%$gelenData%' AND (types = 'E-Learning')";
    $sonuc = $db->rawQuery($query);
    $sonucSayisi = count($sonuc);
    $sayac = 1; 
    foreach ($sonuc as $egitim) {
        echo '<li class="child-altmenu_item">
        <div class="child-altmenu_left">
            <div class="navbar-image">
                <img src="' . $egitim['resim'] . '" />
            </div>
            <a href="' . $egitim['seo_url'] . '"><p class="child-altmenu_p">' . $egitim['egitim_adi'] . '</p></a>
        </div>
        <div class="child-altmenu_right">
            <p style="padding:0px;">' . $egitim['ucret'] . ' TL + KDV</p>
        </div>
    </li>';
        $sayac++;
        if ($sayac == 4) {
            break;
        }
    }
    
    $kalanGoruntulenmeyenEgitim = $sonucSayisi - $sayac + 1;
    if ($sonucSayisi > 3) {
        echo '<li class="child-altmenu_item" style="justify-content:end">
        <div class="child-altmenu_left">
        </div>
        <div class="child-altmenu_right">
        <div class="show-all_btn"><a style="background:transparent !important;" href="' . $category_seo_url . '"> Diğer (' . $kalanGoruntulenmeyenEgitim . ') eğitimi gör ></a></div>
        </div>
    </li>';
    }
    if ($sonucSayisi == 0) {
        echo "Bu kategoride henüz elearning eğitimimiz yoktur.";
    }
}
?>