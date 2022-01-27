<div class="container">
    <div class="row p-5">
        <div class="col-md-12">

            <?php


            $query = $db->query(
                "SELECT
                urun.baslik
            FROM
                urun
            WHERE
                urun.sef = '$_GET[sef]' 
            LIMIT 1
            ",
                PDO::FETCH_ASSOC
            );

            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="kategori">Kategori</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Ürün -
                                        <?php
                                        if ($query->rowCount()) {
                                            foreach ($query as $row) {
                                                echo $row['baslik'];
                                            }
                                        }
                                        ?>
                                    </li>
                                </ol>
                            </nav>
                        </h5>
                    </div>
                </div>
            </div>
            <?php

            $query = $db->query(
                "SELECT
                urun.id,
                urun.baslik,
                urun.sef,
                urun.eski_fiyat,
                urun.fiyat,
                urun.kisa_aciklama,
                urun.aciklama,
                urun.stok
            FROM
                urun 
            WHERE
                urun.sef = '$_GET[sef]'
            GROUP BY urun.baslik
            LIMIT 1
            ",
                PDO::FETCH_ASSOC
            );
            if ($query->rowCount()) {
                foreach ($query as $row) {
            ?>
                    <div class="row shadow">
                        <div class="col-xl-7 col-md-6 col-12 border-end border-primary">

                            <!-- <img style="width:100%; object-fit:contain;" src="assets/images/test/urun_1.jpg" alt="resim1"> -->

                            <div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                </div>
                                <div class="carousel-inner">
                                    <?php
                                    $img = $db->query("SELECT 
                                                    * 
                                                FROM    
                                                    urun_img 
                                                WHERE 
                                                    urun_id = $row[id]", PDO::FETCH_ASSOC);

                                    if ($img->rowCount()) {
                                        foreach ($img as $img_r) {
                                    ?>
                                            <!-- BİR JAVASCRİPT YAZILACAK BURADA İLKİ ACTİVE CLASSIYLA GELSİN DİYE -->
                                            <div class="carousel-item ">
                                                <img src="upload/<?php echo $img_r['img'] ?>" class="d-block w-100" style="height:40rem; object-fit:contain;" alt="...">
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Önceki</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Sonraki</span>
                                </button>
                            </div>

                            <div style="width:100%; overflow-x: scroll;">
                                <?php echo $row['aciklama'] ?>
                            </div>
                        </div>
                        <div class="col-xl-5 col-md-6 col-12">

                            <div class="border-bottom">DETAYLAR</div>
                            <h3 class="title mt-4"><?php echo $row['baslik'] ?></h3>
                            <div class="m-2">
                                <?php
                                if (isset($_SESSION['giris'])) {
                                ?>
                                    <a id="favori-ekle" data-id="<?php echo $row['id'] ?>" href="#" class="wishlist">Favorilere Ekle</a>
                                <?php
                                } else {
                                ?>
                                    <a href="giris-kayit" class="wishlist">Favorilere Eklemek İçin Giriş Yapın</a>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="mt-5 m-2 text-center">
                                <b style="font-size:1.5rem"><?php echo fiyat($row['fiyat']) ?> TL <del><?php echo fiyat($row['eski_fiyat']) ?> TL</del></b>
                            </div>

                            <div class="mt-5 m-2 text-center">
                                <div class="product-variable-color">
                                    <h6 class="title">Seçenek(ler)</h6>
                                    <select class="form-control varyant" id="varyant" name="varyant">

                                        <option data-as="0" value="standart" selected="selected" disabled>
                                            Standart
                                        </option>


                                        <?php
                                        $query_varyant = $db->query("SELECT
                                                                        ur.id,
                                                                        ur.baslik,
                                                                        urs.baslik as varyant,
                                                                        urs.id	as ursid
                                                                    FROM
                                                                        urun_secenek AS ur
                                                                        INNER JOIN urun_secenek_alt AS urs ON urs.urun_secenek_id = ur.id 
                                                                    WHERE
                                                                     urun_id = $row[id]", PDO::FETCH_ASSOC);
                                        if ($query_varyant->rowCount()) {
                                            foreach ($query_varyant as $row_v) {
                                        ?>

                                                <option data-as="<?php echo $row_v['ursid'] ?>" value="<?php echo $row_v['varyant'] ?>">
                                                    <?php echo $row_v['varyant'] ?>
                                                </option>

                                        <?php
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                                <!-- End Product Single Variable -->
                                <div class="product-variable-color">
                                    <h6 class="title">Adet</h6>
                                    <input class="form-control adet" type="number" id="adet" value="1" min="1" max="10">
                                </div>
                            </div>

                            <div class="mt-5 m-2 text-center">
                                <?php
                                if (isset($_SESSION['giris'])) {
                                ?>
                                    <a href="#" data-id="<?php echo $row['id'] ?>" class="btn btn-primary sepete_at">Sepete Ekle</a>
                                <?php
                                } else {
                                ?>
                                    <a href="#" data-id="<?php echo $row['id'] ?>" class="btn btn-primary sepete_at_misafir">Sepete Ekle</a>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="mt-5 m-2 text-center">
                                Ürünün Diğer Renkleri
                                <div class="m-2 p-2">
                                    <?php
                                    $query_color = $db->query("SELECT
                                                            ur.*,
                                                            u.baslik as baslik,
                                                            ui.img as img
                                                        FROM
                                                            urun_renk as ur
                                                            INNER JOIN urun as u ON u.id = ur.renk_urun_id
                                                            INNER JOIN urun_img as ui ON u.id = ui.urun_id
                                                        WHERE ur.urun_id = $row[id]
                                                        GROUP BY u.baslik", PDO::FETCH_ASSOC);
                                    if ($query_color->rowCount()) {
                                        foreach ($query_color as $row_color) {
                                            $sef_renk = $db->query("SELECT * FROM urun WHERE id = $row_color[renk_urun_id]")->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                            <div class="border border primary">
                                                <span class="tt_txt"><a href="urun/<?php echo $sef_renk['sef'] ?>" class=""><?php echo $row_color['baslik'] ?></a></span>
                                                <a href="urun/<?php echo $sef_renk['sef'] ?>" class="">
                                                    <img style="width: 5rem; height: 7rem; object-fit:contain;" src="upload/<?php echo $row_color['img'] ?>" alt="">
                                                </a>
                                            </div>

                                    <?php
                                        }
                                    } else {
                                        echo "<h5>----</h5>";
                                    }
                                    ?>

                                </div>
                            </div>

                        </div>
                    </div>
            <?php
                }
            }
            ?>

        </div>
    </div>
</div>