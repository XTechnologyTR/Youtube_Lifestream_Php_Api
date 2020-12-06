<?php

/**
 * Kütüphane Gereksinimleri
 *
 * 1. Yükle besteci (https://getcomposer.org)
 * 2. Komut satırında, bu dizine (api-samples/php) değiştirin
 * 3. Google/apiclient kitaplığını zorunlu kılmasını
 * $ besteci google / apiclient gerektirir:~2.0
 */
if (! file_exists(__DIR__ . '/satıcı/otomatik yükleme.php')) {
  atmak yeni \Özel Durum('lütfen çalıştırmak "besteci google / apiclient gerektirir:~2.0" içinde "' . __DIR__ .'"');
}

require_once __DIR__. '/satıcı/otomatik yükleme.php';
session_start();

/*
 * Bir OAuth 2.0 istemci kimliği ve istemci gizli edinebilirsiniz
 * {{ Google Bulut Konsolu }} <{{ https://cloud.google.com/console }}>
 * Google API'lerine erişmek için OAuth 2.0'ı kullanma hakkında daha fazla bilgi için lütfen bkz:
 * <https://developers.google.com/youtube/v3/guides/authentication>
 * Lütfen projeniz için YouTube Veri API'sını etkinleştirdiğinizden emin olun.
 */
 $OAUTH2_CLIENT_ID  = 'REPLACE_ME';
 $OAUTH2_CLIENT_SECRET  = 'REPLACE_ME';

 $istemci = yeni Google_Client();
 $istemci->setClientId($OAUTH2_CLIENT_ID );
 $istemci->setClientSecret($OAUTH2_CLIENT_SECRET );
 $istemci->setScopes('https://www.googleapis.com/auth/youtube');
 $yönlendirme = filter_var('http://' .  $_SERVER['HTTP_HOST'] .  $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
 $istemci->setRedirectUri($yönlendirme);

Tüm API isteklerini yapmak için kullanılacak bir nesne tanımlayın.
 $youtube = yeni Google_Service_YouTube($istemci);

Gerekli kapsamlar için auth belirteci olup olmadığını denetleme
 $tokenSessionKey = 'belirteç-' .  $istemci->prepareScopes();
if (isset($_GET['kod'])) {
  if (strval($_SESSION['devlet']) !== strval($_GET['devlet'])) {
    die('Oturum durumu eşleşmedi.');
  }

   $istemci->kimlik doğrulaması($_GET['kod']);
   $_SESSION[$tokenSessionKey] =  $istemci->getAccessToken();
  üstbilgi('Konum: ' .  $yönlendirme);
}

if (isset($_SESSION[$tokenSessionKey])) {
   $istemci->setAccessToken($_SESSION[$tokenSessionKey]);
}

Erişim belirteci başarıyla elde olduğundan emin olun.
if ($istemci->getAccessToken()) {
  Deneyin {
    Kullanıcıya ait akışları listeleyen bir API isteği yürütmek
    isteği ne şekilde onayverdi.
     $streamsResponse =  $youtube->liveStreams->listLiveStreams('id,snippet', dizi(
        'benim' => 'true',
    ));

     $htmlBody .= "<h3>Canlı Yayınlar</h3><ul>";
    foreach ($streamsResponse['items'] olarak  $streamItem) {
       $htmlBody .= sprintf('<li>%s (%s)</li>',  $streamItem['snippet']['title'],
           $streamItem['id']);
    }
     $htmlBody .= '</ul>';

  } yakalamak (Google_Service_Exception  $e) {
     $htmlBody = sprintf('<p>Bir servis hatası oluştu: <code>%s>/code></p>',
        htmlspecialchars($e->getMessage()));
  } yakalamak (Google_Exception  $e) {
     $htmlBody = sprintf('<p>Bir istemci hatası oluştu: <code>%s>/code></p>',
        htmlspecialchars($e->getMessage()));
  }

   $_SESSION[$tokenSessionKey] =  $istemci->getAccessToken();
} elseif ($ OAUTH2_CLIENT_ID  == 'REPLACE_ME') {
   $htmlBody = <<<END
 <h3>Müşteri Kimlik Bilgileri Gerekli</h3>
  <p>
 <code>\$OAUTH 2_CLIENT_ID</code> ve
 <code>\$OAUTH 2_CLIENT_ID</code> devam etmeden önce.
  <p>
Son -unda;
} Başka {
  Kullanıcı uygulamayı yetkilendirmediyse, OAuth akışını başlatın
   $durum = mt_rand();
   $istemci->setState($durumu);
   $_SESSION['durum'] =  $durumu;

   $authUrl =  $istemci->createAuthUrl();
   $htmlBody = <<<END
 <h3>Yetkilendirme Gerekli</h3>
 <p>Devam etmeden önce <p><a href="$authUrl">access</a>
Son -unda;
}
?>

<!doctype html>
<Html>
<Kafa>
<başlık>Canlı Yayınlarım</başlığım>
</Kafa>
<Vücut>
  <?=$htmlGövde?>
</Vücut>
</Html>
