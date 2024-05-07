<?php

$url = "https://app.ahrefs.com" . $_SERVER["REQUEST_URI"];

// Private web proxy script by Heiswayi Nrird (http://heiswayi.github.io)
// Released under MIT license
// Free Software should work like this: whatever you take for free, you must give back for free.

ob_start("ob_gzhandler");

if (!function_exists("curl_init")) die ("This proxy requires PHP's cURL extension. Please install/enable it on your server and try again.");

//Adapted from http://www.php.net/manual/en/function.getallheaders.php#99814
if (!function_exists("getallheaders")) {
  function getallheaders() {
    $result = array();
    foreach($_SERVER as $key => $value) {
      if (substr($key, 0, 500) == "HTTP_") {
        $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
        $result[$key] = $value;
      } else {
        $result[$key] = $value;
      }
    }
    return $result;
  }
}

//define("PROXY_PREFIX", "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER["SERVER_NAME"] . ($_SERVER["SERVER_PORT"] != 80 ? ":" . $_SERVER["SERVER_PORT"] : "") . $_SERVER["SCRIPT_NAME"] . "/");

//Makes an HTTP request via cURL, using request data that was passed directly to this script.
function makeRequest($url) {

  //Tell cURL to make the request using the brower's user-agent if there is one, or a fallback user-agent otherwise.
 // $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.119 Safari/537.36";
  
  $ch = curl_init();
//  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

  //Proxy the browser's request headers.
  $browserRequestHeaders = getallheaders();
  //(...but let cURL set some of these headers on its own.)
  //TODO: The unset()s below assume that browsers' request headers
  //will use casing (capitalizations) that appear within them.
  unset($browserRequestHeaders["Host"]);
  unset($browserRequestHeaders["Content-Length"]);
  //Throw away the browser's Accept-Encoding header if any;
  //let cURL make the request using gzip if possible.
  unset($browserRequestHeaders["Accept-Encoding"]);
 // curl_setopt($ch, CURLOPT_ENCODING, "");
  //Transform the associative array from getallheaders() into an
  //indexed array of header strings to be passed to cURL.
  $curlRequestHeaders = array();
  foreach ($browserRequestHeaders as $name => $value) {
    $curlRequestHeaders[] = $name . ": " . $value;
  }
  //curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);

  //Proxy any received GET/POST/PUT data.
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
      $getData = array();
      foreach ($_GET as $key => $value) {
          $getData[] = urlencode($key) . "=" . urlencode($value);
      }
      if (count($getData) > 0) {
        //Remove any GET data from the URL, and re-add what was read.
        //TODO: Is the code in this "GET" case necessary?
        //It reads, strips, then re-adds all GET data; this may be a no-op.
        $url = substr($url, 0, strrpos($url, "?"));
        $url .= "?" . implode("&", $getData);
      }
    break;
    case "POST":
      curl_setopt($ch, CURLOPT_POST, true);
      //For some reason, $HTTP_RAW_POST_DATA isn't working as documented at
      //http://php.net/manual/en/reserved.variables.httprawpostdata.php
      //but the php://input method works. This is likely to be flaky
      //across different server environments.
      //More info here: http://stackoverflow.com/questions/8899239/http-raw-post-data-not-being-populated-after-upgrade-to-php-5-3
      curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));
    break;
    case "PUT":
      curl_setopt($ch, CURLOPT_PUT, true);
      curl_setopt($ch, CURLOPT_INFILE, fopen("php://input"));
    break;
  }
  
  
$r = ['accept', 'accept-language', 'x-requested-with', 'main-request', 'x-newrelic-id', 'x-xsrf-token',  'authorization', 'x-access-token', 'x-human-token', 'x-csrf-token', 'x-requested-with', 'content-type'];

foreach (getallheaders() as $n => $v)
{
    if (in_array(strtolower($n) , $r))
    {
        $headers[] = $n . ':' . $v;
    }
}



//   include 'cookie.php';
//   $cookie2 = $cookiedb;
  
   $cookie2 = '1=1';
  
  $headers[] = 'Cookie: '.$cookie2;

// Add new headers or overwrite existing headers
$headers[] = "Origin: https://app.ahrefs.com";
// $headers[] = "content-type: application/json";


  $headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.6367.113 Mobile Safari/537.36';

 
    $agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36";
  //Other cURL options.
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
    // curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    // curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    // curl_setopt ($ch, CURLOPT_FAILONERROR, true);
    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, $_SERVER["REQUEST_METHOD"]);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents("php://input"));
    

  
  



//  curl_setopt($ch, CURLOPT_COOKIE, "host=plustools.net; km_ni=jadersondapaz1@gmail.com; km_lv=x; km_ai=jadersondapaz1@gmail.com; __utmz=222526621.1621854618.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __adroll_fpc=eb92861e23e1f95b848447831661dde0-1621854619455; __ar_v4=LJZZ2FR4DBF6VBE3Y6JRRQ:20210523:6|XZKOSCTQYNAN7GPG77G4CP:20210523:23|R66JUCUFRZCG5AJHMQEGA4:20210523:23|KADNOU2AURG7HNUPOYHPUW:20210523:17; __utma=222526621.1524567690.1621854618.1621861365.1621867016.3; _ga=GA1.1.1457956668.1624391945; _jsuid=1793719557; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6Ilcxc3pNREExTlROZExDSWtNbUVrTVRJa2N6VnBRMVowZWpaTFptbG1NVVpRZWpkdGRYWjJkU0lzSWpFMk1qUTRPRFkyTnpVdU1qY3pORFF4TXlKZCIsImV4cCI6IjIwMjEtMDctMTJUMTM6MjQ6MzUuMjczWiIsInB1ciI6ImNvb2tpZS5yZW1lbWJlcl91c2VyX3Rva2VuIn19--64c68d469a6895eaf4347861b8029bc57c55f335; _wordai_rails_session=TtiLLwb3Kae6JW8bcI0ffQZe91UKaXEppUqyH2BfLHJtwGpOtaShFfAtye72f4OYwMjnmEuUMFPgIRB+kYOm+Vw2PhXTTn4GfBnJ/A1vtwQXj15uLd7s314Wmi44aTw/l16YOPdXllZGxmX0Am0BkxYowjOuimlgqvUodVERiH9gDrJu+S36pNOC6Df+XxQMpmUnZsotu8SBNmiXUWgeMF+LuRtPyXJOrmqlXR3SpZAk4vbfEP++7rYuV5eHJ79IGyPZckfWCL679rmIvDklwEuCegw0lLU3YvaRtM5LTweb3bNmrbg6u5JlaKoIbH4Vycu4mrAkWinufAPKx2ne6ULcslsMB3uMvThwu+y0WYG+dty0RAWA6pWoUDVEyORcJpQvBdEAH7BaC5iLxYyArtyn5LeU--OQZMzRqRy3QEcy5N--Ph6mjd3Xpwet5OsNIVMN2A==; _ga_J6KTZN2VVY=GS1.1.1624950055.13.1.1624950056.0; kvcd=1624950056083; km_vs=1");

  //Set the request URL.
  curl_setopt($ch, CURLOPT_URL, $url);

  //Make the request.
  $response = curl_exec($ch);
  $responseInfo = curl_getinfo($ch);
  $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  curl_close($ch);

  //Setting CURLOPT_HEADER to true above forces the response headers and body
  //to be output together--separate them.
  $responseHeaders = substr($response, 0, $headerSize);
  $responseBody = substr($response, $headerSize);

  return array("headers" => $responseHeaders, "body" => $responseBody, "responseInfo" => $responseInfo);
}

//Converts relative URLs to absolute ones, given a base URL.
//Modified version of code found at http://nashruddin.com/PHP_Script_for_Converting_Relative_to_Absolute_URL
function rel2abs($rel, $base) {
  if (empty($rel)) $rel = ".";
  if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel; //Return if already an absolute URL
  if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel; //Queries and anchors
  extract(parse_url($base)); //Parse base URL and convert to local variables: $scheme, $host, $path
  $path = isset($path) ? preg_replace('#/[^/]*$#', "", $path) : "/"; //Remove non-directory element from path
  if ($rel[0] == '/') $path = ""; //Destroy path if relative url points to root
  $port = isset($port) && $port != 80 ? ":" . $port : "";
  $auth = "";
  if (isset($user)) {
    $auth = $user;
    if (isset($pass)) {
      $auth .= ":" . $pass;
    }
    $auth .= "@";
  }
  $abs = "$auth$host$path$port/$rel"; //Dirty absolute URL
  for ($n = 1; $n > 0; $abs = preg_replace(array("#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"), "/", $abs, -1, $n)) {} //Replace '//' or '/./' or '/foo/../' with '/'
  return $scheme . "://" . $abs; //Absolute URL is ready.
}

//Proxify contents of url() references in blocks of CSS text.
function proxifyCSS($css, $baseURL) {
  return preg_replace_callback(
    '/url\((.*?)\)/i',
    function($matches) use ($baseURL) {
        $url = $matches[1];
        //Remove any surrounding single or double quotes from the URL so it can be passed to rel2abs - the quotes are optional in CSS
        //Assume that if there is a leading quote then there should be a trailing quote, so just use trim() to remove them
        if (strpos($url, "'") === 0) {
          $url = trim($url, "'");
        }
        if (strpos($url, "\"") === 0) {
          $url = trim($url, "\"");
        }
        if (stripos($url, "data:") === 0) return "url(" . $url . ")"; //The URL isn't an HTTP URL but is actual binary data. Don't proxify it.
        //return "url(" . PROXY_PREFIX . rel2abs($url, $baseURL) . ")";
    },
    $css);
}

// // Create log
// function recordLog($url) {
//   $userip = $_SERVER['REMOTE_ADDR'];
//   $rdate = date("d-m-Y", time());
//   $data = $rdate.','.$userip.','.$url.PHP_EOL;
//   $logfile = 'logs/'.$userip.'_log.txt';
//   $fp = fopen($logfile, 'a');
//   fwrite($fp, $data);
// }

// recordLog($url);
//cURL can make multiple requests internally (while following 302 redirects), and reports
//headers for every request it makes. Only proxy the last set of received response headers,
//corresponding to the final request made by cURL for any given call to makeRequest().


//$proxy_prefix = PROXY_PREFIX;
$htmlcode = <<<ENDHTML
<style>
[aria-haspopup="menu"]{
    display: none!important;
}
</style>
ENDHTML;

/**/
// $url = "https://tool3.toolszap.com" . $_SERVER["REQUEST_URI"];
/**/

/**/


/**/
if (strpos($url, "//") === 0){
    /**/
    $url = "http:" . $url; // assume that any supplied URLs starting with // are HTTP URLs.
    /**/
} 

/**/
if (!preg_match("@^.*://@", $url)){
    /**/
    $url = "http://" . $url; // assume that any supplied URLs without a scheme are HTTP URLs.
    /**/
} 

/**/
$response = makeRequest($url);
/**/
$rawResponseHeaders = $response["headers"];
/**/
$responseBody = $response["body"];
$responseInfo = $response["responseInfo"];
/**/
$responseHeaderBlocks = array_filter(explode("\r\n\r\n", $rawResponseHeaders));
/**/
$lastHeaderBlock = end($responseHeaderBlocks);
/**/
$headerLines = explode("\r\n", $lastHeaderBlock);
/**/

/**/
foreach ($headerLines as $header){
    /**/
    if (stripos($header, "Content-Length") === false && stripos($header, "Transfer-Encoding") === false){
        /**/
        header($header);
        /**/
    }
}

/**/
$contentType = $responseInfo["content_type"] ?? "text/html";
/**/

 $responseBody = str_replace('<h1><p><a href="/member/user/info" target="_blank">ä¸ªäººä¸­å¿ƒ</a>&nbsp; &nbsp; é‡è¦å…¬å‘Šï¼šå› ä¸ºå…³é”®è¯æŸ¥è¯¢å®˜æ–¹æœ‰å•ä½æ—¶é—´é™åˆ¶ï¼Œå¦‚æžœå‘çŽ°æŸ¥è¯¢ä¸äº†è¯·ç‚¹å‡»å³ä¾§æŒ‰é’®åˆ‡æ¢è´¦å·ï¼š<a href="/member/account/node1" target="_blank">è´¦å·1</a>&nbsp;&nbsp;&nbsp;<a href="/member/account/node2" target="_blank">è´¦å·2</a>&nbsp; &nbsp;&nbsp;<a href="/member/account/node3" target="_blank">è´¦å·3</a>&nbsp; &nbsp;&nbsp;<a href="/member/account/node4" target="_blank">è´¦å·4</a>&nbsp; &nbsp;<a href="/member/user/logout" target="_blank">é€€å‡º</a></p><!--?xml version="1.0" encoding="UTF-8"?--></h1>', '
 <p><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> &nbsp; &nbsp; Important Announcement: Because the keyword query is officially limited by unit time, if you find that the query cannot be found, please click the button on the right to switch accounts: </font></font><a href="/member/account/node1" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Account 1 </font></font></a>&nbsp;&nbsp;&nbsp;<a href="/member/account/node2" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Account 2 </font></font></a>&nbsp; &nbsp;&nbsp;<a href="/member/account/node3" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Account 3 </font></font></a>&nbsp; &nbsp;&nbsp;<a href="/member/account/node4" target="_blank"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Account 4 </font></font></a>&nbsp; &nbsp;</p>', $responseBody);



//  $responseBody = str_replace('</body>', $htmlcode, $responseBody);


header('Access-Control-Allow-Origin: *');
header_remove('content-encoding');
header_remove('content-security-policy');
header_remove('Content-Length');
header_remove('set-cookie');

/**/
if (stripos($contentType, "application/json") !== false){
    /**/
    $responseBody = str_replace('href="/', 'href="https://ahrefs.com/', $responseBody);
    /**/
    $responseBody = str_replace('src="/', 'src="https://ahrefs.com/', $responseBody);
    /**/
    echo $responseBody;
    /**/
}
else if (stripos($contentType, "application/css") !== false){ 
    /**/
    echo proxifyCSS($responseBody, $url); // this is CSS, so proxify url() references.
    /**/
}
else {
    /**/
    header("Content-Length: " . strlen($responseBody));  // this isn't a web page or CSS, so serve unmodified through the proxy with the correct headers (images, JavaScript, etc.)
    /**/
  

    echo $responseBody;
    /**/
}
