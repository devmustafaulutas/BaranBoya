<?php include "header.php"; ?>

<section class="section breadcrumb-area overlay-dark d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content text-center">
                    <h2 class="text-white text-uppercase mb-3">Hizmet Alanlarımız ve Uygulama Yöntemlerimiz</h2>
                    <ol class="breadcrumb d-flex justify-content-center">
                        <li class="breadcrumb-item"><a class="text-uppercase text-white" href="home">Ana Sayfa</a></li>
                        <li class="breadcrumb-item text-white active">Blog</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container usage-areas">
    <h2 class="section-title">Kullanım Alanları</h2>
    <div class="row">
        <?php
        $usage_areas = [
            ["HAVACILIK VE SAVUNMA SANAYİ", "assets/img/blog/havacilik.jpg", "Havacılık ve savunma sanayinde, dayanıklılık ve hassasiyet gerektiren uygulamalarda..."],
            ["ENDÜSTRİYEL TASARIM", "assets/img/blog/endustriyel.jpg", "Endüstriyel tasarım süreçlerinde estetik ve dayanıklılık sağlayan çözümler..."],
            ["CARBON FİBER ARABA", "assets/img/blog/carbon-fiber.jpg", "Araç üretiminde karbon fiber malzemelerle hafiflik ve dayanıklılık sağlıyoruz..."],
            ["SANAT & HOBİ", "assets/img/blog/sanat-hobi.jpg", "Epoksi reçineler, sanat ve hobi alanında geniş uygulama alanı buluyor..."],
            ["RÜZGAR TÜRBİN", "assets/img/blog/ruzgar-turbin.jpg", "Rüzgar türbinlerinde yapısal sağlamlık için dayanıklı malzemeler..."],
            ["EPOKSİ MASALAR", "assets/img/blog/epoksi-masa.jpg", "Epoksi reçineler ile estetik ve dayanıklı masa tasarımları..."]
        ];

        foreach ($usage_areas as $area) {
            echo "
            <div class='col-12 blog-post'>
                <div class='row'>
                    <div class='col-md-5 image-container'>
                        <img src='{$area[1]}' alt='{$area[0]}'>
                    </div>
                    <div class='col-md-7 content-container'>
                        <h3>{$area[0]}</h3>
                        <p>{$area[2]}</p>
                    </div>
                </div>
            </div>";
        }
        ?>
    </div>
</section>

<section class="container methods">
    <h2 class="section-title">Boyamada Kullanılan Yöntemler</h2>
    <div class="row">
        <?php
        $methods = [
            ["İNFÜZYON", "assets/img/blog/infusyon.jpg", "İnfüzyon yöntemi, malzeme kalitesini artırmak için reçine infüzyon tekniklerini içerir..."],
            ["FİLAMENT SARMA", "assets/img/blog/filament.jpg", "Filament sarma tekniği, yüksek dayanıklılık gerektiren projelerde tercih edilir..."],
            ["EL YATIRMASI", "assets/img/blog/elyatirma.jpg", "El yatırması tekniği, uzman işçilik ile yapılan dayanıklı ve estetik sonuçlar sağlar..."]
        ];

        foreach ($methods as $method) {
            echo "
            <div class='col-12 blog-post'>
                <div class='row'>
                    <div class='col-md-5 image-container'>
                        <img src='{$method[1]}' alt='{$method[0]}'>
                    </div>
                    <div class='col-md-7 content-container'>
                        <h3>{$method[0]}</h3>
                        <p>{$method[2]}</p>
                    </div>
                </div>
            </div>";
        }
        ?>
    </div>
</section>

<?php include "footer.php"; ?>
