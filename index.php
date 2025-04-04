<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Başlıksız Belge</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


  <script>
    $(document).ready(function() {

      $("#linkler").load("hareket.php?islem=dosyalar");

      $('.islembasliyor').hide();
      $('#txtgoster').hide();
      $('#wordgoster').hide();


      $('#btn').click(function() {
        $.ajax({
          type: "POST",
          url: 'hareket.php?islem=formgelsin',
          data: $('#getir').serialize(),
          success: function(donen_bilgi) {
            $('#getir').trigger("reset");
            $('#elemangoster').html(donen_bilgi).slideDown();
          }
        });
      });

      $('input').on('change', function() {

        var deger = $('input[type="radio"][name="sec"]:checked').val();

        if (deger == "txt") {
          $('#bilgi').hide();
          $('#wordgoster').hide();
          $('#ana').fadeIn();
          $('#txtgoster').fadeIn();


        } else if (deger == "word") {
          $('#ana').hide();
          $('#txtgoster').hide();
          $('#wordgoster').fadeIn();

        }


      });

      $('#txtekle').click(function() {
        $.ajax({
          type: "POST",
          url: 'hareket.php?islem=txtekle',
          data: $('#txtyap').serialize(),
          success: function(donen_bilgi) {
            $("#linkler").load("hareket.php?islem=dosyalar");
            $('#dosya').trigger("reset");
            $('#txtyap').trigger("reset");

            $('#txtyap').fadeOut(800, function() {
              $('#txtsonuc').html(donen_bilgi).fadeIn().delay(2000);
            });

          }
        });
      });

      $('#wordekle').click(function() {
        $.ajax({
          type: "POST",
          url: 'hareket.php?islem=wordekle',
          data: $('#wordyap').serialize(),
          success: function(donen_bilgi) {
            $("#linkler").load("hareket.php?islem=dosyalar");
            $('#dosya').trigger("reset");
            $('#wordyap').trigger("reset");

            $('#wordyap').fadeOut(800, function() {
              $('#wordsonuc').html(donen_bilgi).fadeIn().delay(2000);
            });

          }
        });
      });

      $('#klasorekle').click(function() {
        $.ajax({
          type: "POST",
          url: 'hareket.php?islem=klasorolustur',
          data: $('#klasorolustur').serialize(),
          success: function(donen_bilgi) {
            $("#linkler").load("hareket.php?islem=dosyalar");
            $('#klasorolustur').trigger("reset");
            $('#klasorsonuc').html(donen_bilgi).fadeIn(1000);
            $('#klasorsonuc').fadeOut(2000).delay(7000);

          }
        });
      });


    });
  </script>
</head>

<body>
  <div class="container-fluid">
    <div class="row mt-3">

      <div class="col-md-4 border border-light p-3 rounded shadow-sm bg-white">

        <div class="row justify-content-center">
          <!-- Dosya Adet Formu -->
          <div class="col-md-8 d-flex justify-content-center align-items-center mt-2" style="min-height: 180px;">
            <form class="text-center p-4 shadow rounded bg-white border" id="getir" style="max-width: 400px; width: 100%;">
              <label class="fw-bold mb-3 fs-5 text-primary d-block">Kaç Adet Dosya Gelsin?</label>
              <input type="number" name="sayi" class="form-control mb-3 text-center border-primary" placeholder="Adet girin">
              <button type="button" id="btn" class="btn btn-danger w-100 fw-bold">Oluştur</button>
            </form>
          </div>

          <!-- Uyarı Alanı -->
          <div class="col-md-8 border-top mt-3 text-center py-4" id="elemangoster">
            <div class="alert alert-info shadow-sm p-3 rounded">
              📂 <strong>Lütfen Yüklenecek Dosya Adetini Seçiniz.</strong>
            </div>
          </div>

          <!-- Sonuç Alanı -->
          <div class="col-md-8 border-top mt-3 text-center py-4" id="sonucgoster">
            <div class="islembasliyor my-3 d-none">
              <img src="load.gif" alt="Yükleniyor..." class="img-fluid" style="max-width: 60px;">
            </div>
            <div class="alert alert-warning shadow-sm p-3 rounded">
              ⚠️ <strong>Yükleme Sonucunu Burada Göreceksiniz</strong>
            </div>
          </div>

        </div>
      </div>

      <div class="col-md-4 border border-light p-3 rounded shadow-sm bg-white">
        <!-- Başlık -->
        <div class="text-center mb-4">
          <h4 class="text-secondary fw-bold">📁 Hangi Dosyadan Oluşturacağız?</h4>
        </div>

        <!-- Format Seçimi -->
        <form id="dosya" class="p-3 bg-light rounded shadow-sm text-center">
          <label class="d-block fw-bold mb-2">Dosya Türü Seçin:</label>
          <div class="form-check form-check-inline">
            <input type="radio" name="sec" value="txt" id="txtSec" class="form-check-input">
            <label for="txtSec" class="form-check-label">TXT</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" name="sec" value="word" id="wordSec" class="form-check-input">
            <label for="wordSec" class="form-check-label">WORD</label>
          </div>
        </form>

        <!-- Bilgilendirme Alanı -->
        <div id="bilgi" class="alert alert-danger text-center mt-3">
          🚨 <strong>Dosya Formatını Seçiniz</strong>
        </div>

        <!-- TXT Dosya Alanı -->
        <div id="txtgoster" class="border rounded p-3 bg-light shadow-sm mt-3 ">
          <form id="txtyap">
            <textarea class="form-control" rows="5" name="metin" placeholder="Metninizi buraya girin..."></textarea>
            <input type="text" name="dosyaad" class="form-control mt-3 mb-2" placeholder="Dosya Adı">
            <select name="klasoryolu" class="form-select mb-3">
              <?php
              $dosyalar = scandir(".");
              foreach ($dosyalar as $dosya):
                if (!in_array($dosya, ['.', '..', 'hareket.php', 'index.php', 'load.gif'])):
                  echo '<option value="' . $dosya . '">' . $dosya . '</option>';
                endif;
              endforeach;
              ?>
            </select>
            <button type="button" id="txtekle" class="btn btn-primary w-100">📄 TXT Ekle</button>
          </form>
          <div id="txtsonuc" class="d-flex flex-column align-items-center"></div>
        </div>

        <!-- WORD Dosya Alanı -->
        <div id="wordgoster" class="border rounded p-3 bg-light shadow-sm mt-3 ">
          <form id="wordyap">
            <textarea class="form-control" rows="5" name="metin" placeholder="Metninizi buraya girin..."></textarea>
            <input type="text" name="dosyaad" class="form-control mt-3 mb-2" placeholder="Dosya Adı">
            <select name="klasoryolu" class="form-select mb-3">
              <?php
              $dosyalar = scandir(".");
              foreach ($dosyalar as $dosya):
                if (!in_array($dosya, ['.', '..', 'hareket.php', 'index.php', 'load.gif'])):
                  echo '<option value="' . $dosya . '">' . $dosya . '</option>';
                endif;
              endforeach;
              ?>
            </select>
            <button type="button" id="wordekle" class="btn btn-success w-100">📄 WORD Ekle</button>
          </form>
          <div id="wordsonuc" class="d-flex flex-column align-items-center"></div>
        </div>

        <!-- Klasör Oluşturma Alanı -->
        <div id="klasorolusturdiv" class="border rounded p-3 bg-light shadow-sm mt-3">
          <form id="klasorolustur">
            <input type="text" name="klasorad" class="form-control mb-2" placeholder="Klasör Adı">
            <button type="button" id="klasorekle" class="btn btn-warning w-100">📂 Klasör Ekle</button>
          </form>
          <div id="klasorsonuc"></div>
        </div>
      </div>


      <!-- BURADA DOSYALARI GÖRME -->
      <div class="col-md-4 border border-light ">
        <div class="row">

          <div class="col-md-12" id="linkler">




          </div>

        </div>

      </div>
      <!-- BURADA DOSYALARI GÖRME -->
    </div>
  </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>