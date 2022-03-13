<?php 


for ($i = 0; $i < 16; $i++) {
	$j = $i-1;
	echo "FullAdder(a=a[${i}], b=b[${i}], c=c${j}, sum=out[${i}], carray=c${i});". "\n";
}

?>
