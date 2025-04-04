<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Başlıksız Belge</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <script>
        $(document).ready(function() {

            $('#yuklebakalim').click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#uploadform')[0]);


                $.ajax({
                    beforeSend: function() {
                        $('.islembasliyor').show();

                    },
                    type: "POST",
                    url: 'hareket.php?islem=elemangetir',
                    enctype: 'multipart/form-data',
                    data: formData,
                    processData: false,
                    contentType: false,
                }).done(function(donen_bilgi) {
                    $('#sonucgoster').html(donen_bilgi).slideDown();
                    $('#uploadformr').trigger("reset");
                    $('#elemangoster').html('<div class="alert alert-warning mt-5">Dosyalar Gönderildi.</div>');


                }).complete(function() {

                    $('.islembasliyor').hide();
                    $('#uploadformr').trigger("reset");
                });



            });

            $('#tekrartxt').click(function() {
                window.location.reload();

            });
            $('#wordtxt').click(function() {
                window.location.reload();

            });

            $('#linkler a').click(function() {
                var sectionId = $(this).attr('sectionId');
                var sectionId2 = $(this).attr('sectionId2');
                $.post("hareket.php?islem=silmehareketi", {
                    "dosyaad": sectionId,
                    "klasorad": sectionId2
                }, function(post_veri) {

                    $('#dosyasonuc' + sectionId2).html(post_veri).fadeIn(1000, function() {
                        $('#dosyasonuc' + sectionId2).fadeOut(2000, function() {
                            $("#linkler").load("hareket.php?islem=dosyalar");
                        });
                    });


                });



            });




        });
    </script>
</head>

<body>
    <?php

    @$islem = $_GET["islem"];

    switch ($islem):
            // inputlar geliyor
        case "formgelsin":

            @$sayi = $_POST["sayi"];
            $sayi++;

            echo '<form class="mt-2" id="uploadform">';

            for ($i = 1; $i < $sayi; $i++):
                echo '<input type="file" name="dosya' . $i . '" ><br><br>';

            endfor;


    ?>
            <input type="hidden" name="say" value="<?php echo $sayi ?>">
            <input type="button" id="yuklebakalim" class="btn btn-success" value="Yükle">
            </form>
        <?php
            break;

        // upload yapılıyor
        case "elemangetir":
            @$sayi = $_POST["say"];
            $izinverilen = array("image/png", "image/jpeg", "image/jpg"); // İzin verilen dosya türleri

            for ($i = 1; $i < $sayi; $i++):  // Döngü sayıyı da kapsamalı
                if (!isset($_FILES["dosya" . $i]) || $_FILES["dosya" . $i]["name"] == ""):
                    echo '<div class="alert alert-danger mt-1">' . $i . '. sıradaki dosya yüklenmedi.</div>';
                else:
                    // Boyut kontrolü
                    if ($_FILES["dosya" . $i]["size"] > (1024 * 1024 * 5)):
                        echo '<div class="alert alert-danger mt-1">' . $i . '. sıradaki dosyanın boyutu çok büyük.</div>';
                    elseif (!in_array($_FILES["dosya" . $i]["type"], $izinverilen)):  // Dosya tipi kontrolü
                        echo '<div class="alert alert-danger mt-1">' . $i . '. sıradaki dosyanın tipi uygun değil.</div>';
                    else:
                        // Güvenlik için dosya adı filtreleme
                        $dosyaAdi = basename($_FILES["dosya" . $i]["name"]);
                        $yuklemeYolu = 'yuklenenler/' . $dosyaAdi;

                        if (move_uploaded_file($_FILES["dosya" . $i]["tmp_name"], $yuklemeYolu)):
                            echo '<div class="alert alert-success mt-1">' . $i . '. sıradaki dosya yüklendi.</div>';
                        else:
                            echo '<div class="alert alert-danger mt-1">' . $i . '. sıradaki dosya yüklenirken hata oluştu.</div>';
                        endif;
                    endif;
                endif;
            endfor;

            break;

        // TXT Ekle
        case "txtekle":
            $metin = $_POST["metin"];
            $dosyaad = $_POST["dosyaad"];
            $klasoryolu = $_POST["klasoryolu"];

            $dt = fopen($klasoryolu . '/' . $dosyaad . '.txt', 'w');
            fwrite($dt, $metin);
            fclose($dt);
            echo '<div class="alert alert-success mt-2 text-center">TXT Dosyası Oluşturuldu.</div>';
            echo '<input type="button" id="tekrartxt" value="Başka Yüklemek İster misin?" class="btn btn-danger mt-2 mb-2 text-center">';
            break;

        // WORD Ekle
        case "wordekle":
            $metin = $_POST["metin"];
            $dosyaad = $_POST["dosyaad"];
            $klasoryolu = $_POST["klasoryolu"];

            $dt = fopen($klasoryolu . '/' . $dosyaad . '.doc', 'w');
            fwrite($dt, $metin);
            fclose($dt);
            echo '<div class="alert alert-success mt-2 text-center">WORD Dosyası Oluşturuldu.</div>';
            echo '<input type="button" id="wordtxt" value="Başka Yüklemek İster misin?" class="btn btn-danger mt-2 mb-2 text-center">';
            break;

        // KLASOR OLUŞTUR 
        case "klasorolustur":
            $klasorad = $_POST["klasorad"];
            mkdir($klasorad, 0755, true);


            echo '<div class="alert alert-light mt-5"><span>' . $klasorad . ' isminde klasör oluşturuldu.</span></div><br>';


            break;

            case "silmehareketi":

                if (!$_POST):
                    echo "posttan gelmiyorsun";
                else:
                    if (!isset($_POST["dosyaad"]) || !isset($_POST["klasorad"])) {
                        echo "Eksik parametreler!";
                        exit;
                    }
            
                    $dosyaad = $_POST["dosyaad"];
                    $klasorad = $_POST["klasorad"];
            
                    if ($klasorad === $dosyaad):
                        if (!is_dir($klasorad)) {
                            echo '<div class="alert alert-danger">Bu bir klasör değil.</div>';
                            exit;
                        }
            
                        $dosyasayi = 0;
                        foreach (scandir($klasorad) as $ic):
                            if ($ic === '.' || $ic === '..'):
                                continue;
                            endif;
                            $dosyasayi++;
                        endforeach;
            
                        if ($dosyasayi != 0):
                            echo '<div class="alert alert-danger">Bu klasör boş değil.</div>';
                        else:
                            if (rmdir($klasorad)):
                                echo '<div class="alert alert-success">Klasör başarıyla silindi.</div>';
                            else:
                                echo '<div class="alert alert-danger">Klasör silinemedi.</div>';
                            endif;
                        endif;
            
                    else:
                        $dosyayolu = $klasorad . '/' . $dosyaad;
                        if (!file_exists($dosyayolu)) {
                            echo '<div class="alert alert-danger">Dosya bulunamadı.</div>';
                        } else {
                            if (unlink($dosyayolu)):
                                echo '<div class="alert alert-success">Dosya başarıyla silindi.</div>';
                            else:
                                echo '<div class="alert alert-danger">Dosya silinemedi.</div>';
                            endif;
                        }
                    endif;
                endif;
            
                break;
            

        // Dosya ve Klasörleri listeleme
        case "dosyalar":
            $dosyalar = scandir(".");
            $klasorsayi = 0;
            $dosyasayi = 0;
        ?>

            <div class="container ">
                <?php foreach ($dosyalar as $dosya):
                    if (in_array($dosya, ['.', '..', 'hareket.php', 'index.php', 'load.gif'])):
                        continue;
                    endif;

                    $klasorsayi++; // Klasör sayısını artır
                ?>

                    <div class="card my-3 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <strong><?php echo $dosya; ?> Klasörü</strong>
                            <a class="m-3 link-dark fw-bold text-decoration-none" sectionId="<?php echo $dosya; ?>" sectionId2="<?php echo $dosya; ?>">X</a>
                        </div>
                        <div class="card-body">
                            <p class="alert alert-secondary">Klasörün İçeriği:</p>
                            <ul class="list-group">
                                <?php foreach (scandir($dosya) as $icdosya):
                                    if ($icdosya == '.' || $icdosya == '..'):
                                        continue;
                                    endif;
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?php echo $icdosya; ?>
                                        <a class="btn btn-danger btn-sm text-white" sectionId="<?php echo $icdosya; ?>" sectionId2="<?php echo $dosya; ?>">SİL</a>
                                    </li>
                                <?php

                                    $dosyasayi++; // dosya sayısını artır

                                endforeach;
                                ?>
                                <div class="alert alert-info mt-3">
                                    Toplam Dosya Sayısı: <strong><?php echo $dosyasayi; ?></strong>
                                </div>
                                <?php $dosyasayi = 0;

                                echo '<div id="dosyasonuc' . $dosya . '"></div>';

                                ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="alert alert-info">
                    Toplam Klasör Sayısı: <strong><?php echo $klasorsayi; ?></strong>
                </div>
            </div>
    <?php
            break;



    endswitch;
    ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>