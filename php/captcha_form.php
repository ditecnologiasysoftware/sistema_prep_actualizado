<?php
@session_start();

header("Content-type: image/png");
$long=90; //longitud de la imagen
$width=25; //ancho de la imagen
$length = 8; //longitud de la cadena
$size = 10; //tamaño de la letra
$im = imagecreate($long, $width) or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 192, 192, 192);
$text_color = imagecolorallocate($im, rand(0,100), rand(0,100), rand(0,100));
$line_color = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9");
$textstr = "";
for ($i=0; $i<$length; $i++) {
$textstr .= $chars[rand(0, count($chars)-1)];
}
$_SESSION["captcha_formulario"]= $textstr;
// Traza Lineas
imageline($im, rand(0,$long), rand(0,$width), rand(0,$long), rand(0,$width), $line_color);
imageline($im, rand(0,$long), rand(0,$width), rand(0,$long), rand(0,$width), $line_color);

imagestring($im, $size, 5, 5, $textstr, $text_color);
imagepng($im);
imagedestroy($im); 

/*<img src="php/captcha.php">*/
?>