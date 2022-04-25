<?php
 if(isset($_GET['signature'])){
ini_set('max_execution_time', 0);
function realFilename($url){
   $headers      = get_headers($url, 1);
   $headers      = array_change_key_case($headers, CASE_LOWER);
   $realfilename = '';
 
   if(isset($headers['content-disposition'])) 
      {
         $tmp_name = explode('=', $headers['content-disposition']);
         if($tmp_name[1]) 
            {
               $realfilename = trim($tmp_name[1], '";\'');
            }
      } 
   else  
      { 
         $info         = pathinfo($url);
         if(isset($info['extension']))
            {
               $realfilename = $info['filename'].'.'.$info['extension']; 
            }
      } 
 
  return $realfilename;
}
$useragent = "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Safari/537.36";
$v = base64_decode($_GET['signature']);
parse_str($v);
//$v = json_decode($v,true);
$title = realFilename($v);
parse_str($title);
//$v = $v['link'];
header('Content-Type: application/octet-stream');
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
curl_setopt($ch, CURLOPT_URL, $v);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$info = curl_exec($ch);
$size2 = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
header('Content-Type: application/octet-stream');

$filesize = $size2;
$offset = 0;
$length = $filesize;
if (isset($_SERVER['HTTP_RANGE'])) {
    $partialContent = "true";
    preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    $offset = intval($matches[1]);
    $length = $size2 - $offset - 1;
} else {
    $partialContent = "false";
}
if ($partialContent == "true") {
    header('HTTP/1.1 206 Partial Content');
    header('Accept-Ranges: bytes');
    header('Content-Range: bytes '.$offset.
        '-'.($offset + $length).
        '/'.$filesize);
} else {
    header('Accept-Ranges: bytes');
}
header("Content-length: ".$size2);
header('Content-Disposition: filename="'.$title.'"');

$ch = curl_init();
if (isset($_SERVER['HTTP_RANGE'])) {
    $partialContent = true;
    preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
    $offset = intval($matches[1]);
    $length = $filesize - $offset - 1;
    $headers = array(
        'Range: bytes='.$offset.
        '-'.($offset + $length).
        ''
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 222222);
curl_setopt($ch, CURLOPT_URL, $v);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_exec($ch);
 }
 
 ?>
