<?php
$NBITS = 8;
$R = 2; // Pipelining level

$sB = 120; // Block size
$margin = 50; // Margin between each blocks

$n = pow(2,$R)-1+1;// Number of floors

$filename = 'addsub_8_2.png'


$pas = $NBITS/pow(2, $R);

header('Content-Type: image/png');
//putenv('GDFONTPATH=' . realpath('.'));
putenv('GDFONTPATH=/usr/share/fonts/truetype/msttcorefonts/');
$font = 'arial.ttf';
$Ymax = ($sB+$margin)*$n+2*$margin;
$im = imagecreatetruecolor(($sB+$margin)*$NBITS+($sB+$margin)/2*($n-1)+2*$margin, $Ymax);
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
imagefill($im, 0, 0, $white);

$X=$margin/2;


for($i = 0; $i < $NBITS; $i++) {
	$I = $NBITS-$i-1;
	$Y=$margin/2+$margin;
	$PP = array(array($X+$sB/5,0), array($X+4*$sB/5,0));
	$nBefore = Rbefore($I);
	if($i > 0 && $i%$pas==0) {
	$dec=13;
		$PP[0] = Register($X-$dec, ($nBefore)*($margin+$sB)+$Y);
		createArrow(array($X+150, $X+4*$sB/10-$dec), array(($nBefore)*($margin+$sB)+$Y+2*$sB/10, ($nBefore)*($margin+$sB)+$Y+2*$sB/10));
		createArrow(	array(
				$X-$dec,
				$X-2*$sB/10+10-$dec,
				$X-2*$sB/10+10-$dec,
				$X-$margin
			),
			array(
				($nBefore)*($margin+$sB)+$Y+2*$sB/10,
				($nBefore)*($margin+$sB)+$Y+2*$sB/10,
				($nBefore+1)*($margin+$sB)+$Y+2*$sB/10,
				($nBefore+1)*($margin+$sB)+$Y+2*$sB/10
			)
		);
		
		$PP[0] = Register($X-$dec, ($nBefore)*($margin+$sB)+$Y+6*$sB/10);
		createArrow(array($X+150, $X+4*$sB/10-$dec), array(($nBefore)*($margin+$sB)+$Y+8*$sB/10, ($nBefore)*($margin+$sB)+$Y+8*$sB/10));
		createArrow(	array(
				$X-$dec,
				$X-$sB/10+5-$dec,
				$X-$sB/10+5-$dec,
				$X-$margin
			),
			array(
				($nBefore)*($margin+$sB)+$Y+8*$sB/10,
				($nBefore)*($margin+$sB)+$Y+8*$sB/10,
				($nBefore+1)*($margin+$sB)+$Y+8*$sB/10,
				($nBefore+1)*($margin+$sB)+$Y+8*$sB/10
			)
		);
		
		$X+=($sB+$margin)/2;
		label("C".($I+1), $X-$margin/2+3, ($nBefore)*($margin+$sB)+$Y+1*$sB/10);
		label("sub", $X-$margin/2+5, ($nBefore)*($margin+$sB)+$Y+7*$sB/10);
	}
	elseif($i > 0) {
		label("C".($I+1), $X-$margin/2+3, ($nBefore)*($margin+$sB)+$Y+1*$sB/10);
		createArrow(array($X, $X-$margin), array(($nBefore)*($margin+$sB)+$Y+2*$sB/10, ($nBefore)*($margin+$sB)+$Y+2*$sB/10));
		label("sub", $X-$margin/2+5, ($nBefore)*($margin+$sB)+$Y+7*$sB/10);
		createArrow(array($X, $X-$margin), array(($nBefore)*($margin+$sB)+$Y+8*$sB/10, ($nBefore)*($margin+$sB)+$Y+8*$sB/10));
	}
	if($I==0) {
		label("sub", $X+$sB+$margin/2+3, ($nBefore)*($margin+$sB)+$Y+1*$sB/10);
		createArrow(array($X+$sB+3*$margin, $X-$margin+$sB+$margin), array(($nBefore)*($margin+$sB)+$Y+2*$sB/10, ($nBefore)*($margin+$sB)+$Y+2*$sB/10));
		label("sub", $X+$sB+$margin/2+5, ($nBefore)*($margin+$sB)+$Y+7*$sB/10);
		createArrow(array($X+$sB+3*$margin, $X-$margin+$sB+$margin), array(($nBefore)*($margin+$sB)+$Y+8*$sB/10, ($nBefore)*($margin+$sB)+$Y+8*$sB/10));
	}

	$PP = array(array($X+$sB/5,0), array($X+4*$sB/5,0));
	for($j = 0; $j < $nBefore; $j++) {
		createArrows($Y+3*$sB/10, $PP);
		$PP[0] = Register($X,		3*$sB/10 + $Y);
		$PP[1] = Register($X + 3*$sB/5,	3*$sB/10 + $Y);
		//BitAdder($margin/2 + $i*($margin+$sB), $margin/2+$j*($margin+$sB));
		$Y += $margin+$sB;
	}
	createArrows($Y, $PP);
	//createArrow(array($X+$sB/5, $X+$sB/5), array($PP, $Y));
	//createArrow(array($X+4*$sB/5, $X+4*$sB/5), array($PP, $Y));
	$PP = array(BitAdder($X, $Y));
	$j++; $Y += $margin+$sB;
	for(; $j < $n; $j++) {
		createArrows($Y+3*$sB/10, $PP);
		//createArrow(array($X+3*$sB/10, $X+3*$sB/10), array($PP, $Y));
		$PP = array(Register($X+3*$sB/10,		3*$sB/10 + $Y));
		//BitAdder($margin/2 + $i*($margin+$sB), $margin/2+$j*($margin+$sB));
		$Y += $margin+$sB;
	}
	
	createArrows($Ymax, $PP);
	label("A".$I, $X+$margin/2+$sB/4-15, 20, 14);
	label("B".$I." xor sub", $X+$margin/2+$sB/4+90, 20, 14);
	label("S".$I, $X+$margin/2+$sB/2-10, $Ymax-40);
	$X+=$margin+$sB;
}

//imagecolorallocate($im, $cf[0], $cf[1], $cf[2]);
//imagesetpixel($im, $x, $y, $c);

imagepng($im, $filename, 9)or die('imagepng error');


function Register($x, $y) {
	global $im, $black, $sB;
	createBox($x, $y, $x+2*$sB/5, $y+2*$sB/5);
	label("R", $x+$sB/5, $y+$sB/5);
	return array($x+1*$sB/5, $y+2*$sB/5);
}

function BitAdder($x, $y) {
	global $im, $black, $sB;
	createBox($x, $y, $x+$sB, $y+$sB);
	label("1 bit +/-", $x+$sB/2, $y+$sB/2-3);
	return array($x+$sB/2, $y+$sB);
}

function Rbefore($x) {
	global $NBITS, $R, $pas;
	if($x == 0) return 0;
	else {
		$n=0;
		for($i = 1; $i <= $x; $i++) {
			if($i%$pas==0) $n++;
		}
		return $n;
	}
}

function label($string, $x, $y, $s=16) {
	global $im, $black, $sB, $font;
	imagettftext ( $im , $s , 0 , $x - round(strlen($string)*0.57*$s/2) , $y + $s/2 , $black, $font, $string);
}

function createArrows($Y, $PP) {
	for($i = 0; $i < count($PP); $i++) {
		createArrow(array($PP[$i][0], $PP[$i][0]), array($PP[$i][1], $Y));
	}
}

function createArrow($Xarr, $Yarr) {
	global $im, $black;
	$grey = imagecolorallocate($im, 70, 70, 70);
	for($i = 0; $i < count($Xarr)-1; $i++) {
		imageline($im, $Xarr[$i], $Yarr[$i], $Xarr[$i+1], $Yarr[$i+1], $grey);
		    if($Xarr[$i] == $Xarr[$i+1] && $Yarr[$i+1] >  $Yarr[$i]) { $sens=0; $pX=0; $pY=-1; }
		elseif($Xarr[$i] >  $Xarr[$i+1] && $Yarr[$i+1] == $Yarr[$i]) { $sens=1; $pX=1; $pY= 0; }
		if($i == count($Xarr) - 2) $coeff = 5; else $coeff=0;
		imageline($im, $Xarr[$i]-(1-abs($pX)), $Yarr[$i]-(1-abs($pY)), $Xarr[$i+1]-(1-abs($pX))+$coeff*$pX, $Yarr[$i+1]-(1-abs($pY))+$coeff*$pY, $grey);
		imageline($im, $Xarr[$i]+(1-abs($pX)), $Yarr[$i]+(1-abs($pY)), $Xarr[$i+1]+(1-abs($pX))+$coeff*$pX, $Yarr[$i+1]+(1-abs($pY))+$coeff*$pY, $grey);
	}

	createArrowExtremity($Xarr[$i]+$pX, $Yarr[$i]+$pY, $sens);
}
function createArrowExtremity($x, $y, $sens) {
	global $im, $black;
	$grey = imagecolorallocate($im, 70, 70, 70);
	$fb1 = imagecolorallocate($im, 100,100, 100);
	$fb2 = imagecolorallocate($im, 150, 150, 150);
	    if($sens==0) { $s1 =  1; $s2 = -1; }
	elseif($sens==1) { $s1 = -1; $s2 =  1; }
	$maxJ = 14;
	for($j = 0; $j < 14; $j++) {
		$m = floor(($j+1)/2);
		for($i = -$m; $i <= $m; $i++) {
			   if($s1 < 0) { $i1=$j; $j1=$i; }
			else           { $i1=$i; $j1=$j; }
			imagesetpixel($im, $x+$i1, $y+$s2*$j1, $grey);
		}
	}
}
function createBox($x1, $y1, $x2, $y2) {
	global $im, $black, $sB;
	$black0 = $black;
	$black1 = imagecolorallocate($im, 150, 150, 150);
	$black2 = imagecolorallocate($im, 80, 80, 80);
	$black3 = imagecolorallocate($im, 200, 200, 200);
	$grey1 = imagecolorallocate($im, 235, 235, 255);
	imagerectangle($im, $x1, $y1, $x2, $y2, $black0);
	imagerectangle($im, $x1+1, $y1+1, $x2-1, $y2-1, $black1);
	imagerectangle($im, $x1+2, $y1+2, $x2-2, $y2-2, $black3);
	imagefilledrectangle($im, $x1+3, $y1+3, $x2-3, $y2-3, $grey1);
	
	// Triangle pour l'horloge
	$x0 = $x2;
	$y0 = ($y1+$y2)/2;
	$max = 6;
	for($i = $max; $i > 0; $i--) {
	imagesetpixel($im, $x0-$i, $y0+$i-$max, $black2);
	imagesetpixel($im, $x0-$i, $y0+$i-$max-1, $black2);
	imagesetpixel($im, $x0-$i, $y0-$i+$max, $black2);
	imagesetpixel($im, $x0-$i, $y0-$i+$max-1, $black2);
	}
}
?>
