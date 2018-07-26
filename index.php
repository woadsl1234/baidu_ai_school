<h1> ckj123 </h1>
<?php 
$a = rand(0,100); 
$b = rand(0,100); 
$c = rand(0,100); 
$d = rand(0,100); 
$e = rand(0,100);
$url = '101.71.29.5:10003/flag.php';
$result = ((($a - $b)/$c)+$d) * $e; 
function login_post($url, $cookie, $post)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    $rs = curl_exec($curl);
    curl_close($curl);
    return $rs;
}
// login_post($)
echo $result;
 ?>
