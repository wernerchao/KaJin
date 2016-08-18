<?

$f = fopen("/home/kajinonline/public_html/a.txt", "a");
fputs($f, date("Y/m/d H:i:s ")."\r\n");
fclose($f);

echo "ok";

?>