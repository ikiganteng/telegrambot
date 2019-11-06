<?php
require 'class_samehadaku.php';

/*
BOT PENGANTAR
Materi EBOOK: Membuat Sendiri Bot Telegram dengan PHP
Ebook live http://telegram.banghasan.com/
oleh: bang Hasan HS
id telegram: @hasanudinhs
email      : banghasan@gmail.com
twitter    : @hasanudinhs
disampaikan pertama kali di: Grup IDT
dibuat: Juni 2016, Ramadhan 1437 H
nama file : PertamaBot.php
change log:
revisi 1 [15 Juli 2016] :
+ menambahkan komentar beberapa line
+ menambahkan kode webhook dalam mode comment
Pesan: baca dengan teliti, penjelasan ada di baris komentar yang disisipkan.
Bot tidak akan berjalan, jika tidak diamati coding ini sampai akhir.
Edited //ikiganteng
*/
//isikan token dan nama botmu yang di dapat dari bapak bot :
$TOKEN      = "ganti dgn bot token lu"; // ganti dengan token bot anda
$usernamebot= "isi dgn username bot lu"; // sesuaikan besar kecilnya, bermanfaat nanti jika bot dimasukkan grup.

// aktifkan ini jika perlu debugging
$debug = false;
 
// fungsi untuk mengirim/meminta/memerintahkan sesuatu ke bot 
function request_url($method)
{
    global $TOKEN;
    return "https://api.telegram.org/bot" . $TOKEN . "/". $method;
}

 function SendPhoto($text, $chatId, $image) {
  $chat = request_url('sendPhoto?chat_id='.$chatId);
$post_fields = array('caption'   => $text,
'photo'     => new CURLFile(realpath($image))
);
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_HTTPHEADER,array("Content-Type:multipart/form-data"));
    curl_setopt($ch, CURLOPT_URL, $chat);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_fields);
	$result = curl_exec($ch);
    curl_close($ch);
}

function sendMessage($chatid, $text)
{
    global $debug;
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text
    );
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options); 
    $result = file_get_contents(request_url('sendMessage'), false, $context);
    if ($debug) 
        print_r($result);
}
 
if (strlen($TOKEN)<20) 
    die("Token mohon diisi dengan benar!\n");

function cek(){
$sm = new samehadakuClass();
$chatId = 'isi chatId'; // ganti dengan chatId bot anda
$animes = $sm->getNewestAnime(1);
$judul = $animes[0]['title'];
$relase = $animes[0]['update'];
$img = $animes[0]['img'];
$slug = $animes[0]['slug'];
$cek = file_get_contents("anime.txt");
if($cek == $slug){
echo '-';
}else{
$dir = "ikiganteng";
if( is_dir($dir) === false )
{mkdir($dir);}
$filenya = ".jpg";
              $namaExp = $slug.$filenya;
              $filename = $dir."/".$namaExp;
              $bacaExp = file_get_contents($img);
              $file = fopen($dir . '/' . $namaExp,"w");
              fwrite($file, $bacaExp);
              fclose($file);
$result = sendMessage($chatId, 'Ada Anime baru');
$result = SendPhoto('https://www.samehadaku.tv/'.$slug, $chatId, $filename);
$result = sendMessage($chatId, 'Judul : '.$judul);
$result = sendMessage($chatId, 'Release : '.$relase);
echo '+';
file_put_contents("anime.txt", $slug);
				}
}

while (true) {
    cek();
    sleep(300);
}
    
?>
