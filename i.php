<?php 
header("Content-type: text/html; charset=utf-8");
require 'Ip2Region.php';
$ip2region = new Ip2Region();

function getIP($sostr=''){
    $soisMatched = preg_match_all('/(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)\.(25[0-5]|2[0-4]\d|[0-1]\d{2}|[1-9]?\d)/', $sostr, $somatches);
        return($somatches[0]);
}
function getTxtcontent($txtfile){
	$file = @fopen($txtfile,'r');
	$content = array();
	if(!$file){
		return 'file open fail';
	}else{
		$i = 0;
		while (!feof($file)){
			$content[$i] = mb_convert_encoding(fgets($file),"UTF-8","GBK,ASCII,ANSI,UTF-8");
			$i++ ;
		}
		fclose($file);
		$content = array_filter($content);
	}
 
	return $content;
}
echo '<pre>';
//var_dump(getTxtcontent('log.txt'));
$ip_arr=(getIP(file_get_contents('log.txt')));

$ip_arr = array_count_values($ip_arr);
arsort($ip_arr);
$max_number = reset($ip_arr);
$more_value = key($ip_arr);
echo("IP出现次数最多的值为：{$more_value},总共出现{$max_number}次<br><br>");

foreach ($ip_arr as $k => $v) {
	$info = $ip2region->btreeSearch($k);
	echo $k.'--'.$info['region'].'<br>';   //获取所有IP及地址
	if(!strstr($info['region'],'中国')&&!strstr($info['region'],'内网')){
		//echo $k.'<br>'; 
		//echo $k.'--'.$info['region'].'<br>';  //获取所有国外IP
	}
}

echo '<br><br>';
// 敏感词
foreach (getTxtcontent('log.txt') as $k => $v) {
	if(strstr($v,'eval')||strstr($v,'zip')||strstr($v,'rar')||strstr($v,'diaosi')||strstr($v,'1.php')||strstr($v,'sb.php')||strstr($v,'python')){
		echo $v.'<br>';
	}
}

 ?>