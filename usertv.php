<?php
function getBrowser($u_agent) {
  $platform = 'Unknown';
  $bname = 'Unknown';
  $ub = "";
  $version = "";

  //First get the platform?
  if (preg_match('/linux/i', $u_agent)) {
    $platform = 'linux';
  }elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    $platform = 'mac';
  }elseif (preg_match('/windows|win32/i', $u_agent)) {
    $platform = 'windows';
  }

  // Next get the name of the useragent yes seperately and for good reason
  if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }elseif(preg_match('/Firefox/i',$u_agent)){
    $bname = 'Mozila Firefox';
    $ub = "Firefox";
  }elseif(preg_match('/OPR/i',$u_agent)){
    $bname = 'Opera';
    $ub = "Opera";
  }elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
    $bname = 'Google Chrome';
    $ub = "Chrome";
  }elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
    $bname = 'Apple Safari';
    $ub = "Safari";
  }elseif(preg_match('/Netscape/i',$u_agent)){
    $bname = 'Netscape';
    $ub = "Netscape";
  }elseif(preg_match('/Edge/i',$u_agent)){
    $bname = 'Edge';
    $ub = "Edge";
  }elseif(preg_match('/Trident/i',$u_agent)){
    $bname = 'Internet Explorer';
    $ub = "MSIE";
  }

  // finally get the correct version number
  $known = array('Version', $ub, 'other');
  $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
  if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
  }
  // see how many we have
  $i = count($matches['browser']);
  if ($i != 1) {
    //we will have two since we are not using 'other' argument yet
    //see if version is before or after the name
    if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version= $matches['version'][0];
    }else {
        $version= $matches['version'][1];
    }
  }else {
    $version= $matches['version'][0];
  }

  // check if we have a number
  if ($version==null) {$version="";}

  return array(
    'platform'  => $platform,
    'bname'      => $bname,
    'version'   => $version
  );
}

$usertag = $_GET['tag'];
$userip = array_Key_exists('userip', $_GET) ? $_GET['userip'] : '';
$ub = array_Key_exists('useragent', $_GET) ? getBrowser($_GET['useragent']) : null;
$arr = array('python3', 'newtv.py', $usertag, $userip);
$command = join(" ", $arr);
if (! is_null($ub)) {
    $command .= ' ' . str_replace(' ', ',', join(",", $ub));
}
$output = null;
$retcode = null;
$ret = exec($command, $output, $retcode);
if ($ret == '' and $retcode == 0 and count($output) == 0) {
    header('Location: index.html');
}
?>