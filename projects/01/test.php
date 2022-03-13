<?php 


for ($i = 0; $i < 16; $i++) {
	$j = $i-1;
	echo "RAM8(in=in, load=i{$i}, address=address[3..5], out=o{$i});". "\n";
}

?>
