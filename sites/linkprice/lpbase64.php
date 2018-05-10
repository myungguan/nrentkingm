<?php
	function lp_encode($src, $code, $pad)
	{
		$r1 = mt_rand(0, 63);
		$r2 = substr($pad, $r1, 1);
		$pad = substr($pad, $r1).substr($pad, 0, $r1);

		for ($i = 0 ; $i < strlen($src) / 3; $i++)
		{
			$s1 = ord($src[$i * 3 + 0]);
			$s2 = ord($src[$i * 3 + 1]);
			$s3 = ord($src[$i * 3 + 2]);

			$c1 = substr($pad, (($s1 >> 2) ^  ($i & 0x3f)) & 0x3f, 1);
			$c2 = substr($pad, (((($s1 & 0x03) << 4) | ($s2 >> 4)) ^ ($i & 0x3f)) & 0x3f, 1);
			$c3 = substr($pad, (((($s2 & 0x0f) << 2) | ($s3 >> 6)) ^ ($i & 0x3f)) & 0x3f, 1);
			$c4 = substr($pad, (($s3 & 0x3f) ^ ($i & 0x3f)) & 0x3f, 1);

			$v1 = (($v1 + ord($c1)) & 0x3f);
			$v2 = (($v2 + ord($c2)) & 0x3f);
			$v3 = (($v3 + ord($c3)) & 0x3f);
			$v4 = (($v4 + ord($c4)) & 0x3f);

			$rst .= $c4.$c2.$c3.$c1;
		}

		$v = substr($pad, $v1, 1).substr($pad, $v2, 1).substr($pad, $v3, 1).substr($pad, $v4, 1);

		return $r2.$rst.$v.$code;
	}

	function lp_url_trt($url, $code, $pad)
	{
		return substr($url, 0, strpos($url, "?") + 1)."lpev=".lp_encode(substr($url, strpos($url, "?") + 1), $code, $pad);
	}
?>