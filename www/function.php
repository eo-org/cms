<?php
function getDomain($requestHost, $ourDomain = "")
{
	$tlds = array(
		'cc'	=> true,
		'cn'	=> array('com' => true, 'net' => true, 'org' => true),
    	'com'	=> array('enorange' => true),
		'info'	=> true,
		'me'	=> true,
		'mobi'	=> true,
		'name'	=> true,
		'net'	=> true,
		'org'	=> true,
    	'uk'	=> array('co' => true),
		'test'	=> true,
    	'tv'	=> true
	);
	$parts = explode('.', $requestHost);
	$reversedParts = array_reverse($parts);
	$tmp = $tlds;
	
	foreach ($reversedParts as $key => $part) {
	    if (isset($tmp[$part])) {
	        $tmp = $tmp[$part];
	    } else {
	        break;
	    }
	}
	
	$domainNameArr = array_slice($parts, -$key - 1);
	$domainName = implode('.', $domainNameArr);
	
	if($domainName == $ourDomain) {
		$domainNameArr = array_slice($parts, -$key - 2);
		$domainName = implode('.', $domainNameArr);
	}
	return $domainName;
}
    
function getSubdomain($requestHost, $ourDomain)
{
	$hostParts = explode('.', $requestHost);
	$ourParts = explode('.', $ourDomain);

	if(count($hostParts) > count($ourParts)) {
		return $hostParts[0];
	}
	return '';
}