<?php
function parse_address($addr = NULL)
{
	$adr = "";

	if(!empty($addr))
	{
		$adr .= ( ! empty($addr->Street) ? $addr->Street." " : "");
		$adr .= ( ! empty($addr->Block) ? $addr->Block." " : "");
		$adr .= ( ! empty($addr->City) ? $addr->City." " : "");
		$adr .= ( ! empty($addr->County) ? $addr->County." " : "");
		$adr .= ( ! empty($addr->ZipCode) ? $addr->ZipCode : "");
	}

	return trim($adr);
}
