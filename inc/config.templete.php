<?
$tpldir = $config['basePath']."/sites/tpls/";
$template_dir = $tpldir.$design_layout;

$compile_dir  = $config['basePath']."/_compiles/".$design_layout."/";
if (!is_dir($compile_dir)) {
	mkdir($compile_dir, 0777, 1);
}

$ar_t = explode("/",$PHP_SELF);

$query = "SELECT * FROM config";
$r = mysql_query($query);
if(!isset($config)) {
	$config = [];
}
while($row = mysql_fetch_assoc($r)) {
	$config[$row['type']] = $row['value'];
}
$tem_global['config'] = $config;

$tem_global['c_id'] = $_COOKIE['c_id'];

$tem_global['siteurl'] = $_SERVER['HTTP_HOST'];
$tem_global['production'] = $config['production'];
$tem_global['geturl'] = $_REQUEST['url'];
$tem_global['nowurl'] = urlencode($_nowurl);
$tem_global['nowurlf'] = $ar_t[sizeof($ar_t)-2];
$tem_global['nowurlh'] = $PHP_SELF;
$tem_global['imgserver'] = $config['imgServer'];
$tem_global['textarea'] = "textarea";
$tem_global['adminurl'] = $config['adminUrl'];
$tem_global['description'] = '전국 실시간 렌트카 최저가 검색, 실시간 예약결제, 배달대여';
if($_SESSION['member_idx'])	{
	$tem_global['memindex'] = $g_memidx;
	$tem_global['memname'] = $g_memname;
	$tem_global['memid'] = $g_memid;
	$tem_global['memcp'] = $ar_init_member['cp'];
	$tem_global['memgrade'] = $ar_init_member['memgrade'];

	//도매때문에 수정
	$tem_global['shopname'] = $ar_init_member['shopname'];
	if ($tem_global['shopname'] == '') {
		$tem_global['shopname'] = $memname;
	}
	
	$tem_global['memphone1'] = $ar_phone[0];
	$tem_global['memphone2'] = $ar_phone[1];
	$tem_global['memphone3'] = $ar_phone[2];

	$ar_cp = explode("-",$ar_init_member['cp']);

	$tem_global['memcp1'] = $ar_cp[0];
	$tem_global['memcp2'] = $ar_cp[1];
	$tem_global['memcp3'] = $ar_cp[2];
	
	$tem_global['memzip1'] = $ar_init_member['zip1'];
	$tem_global['memzip2'] = $ar_init_member['zip2'];

	$tem_global['memislogin'] = "Y";

	$tem_global['membuyac'] = $ar_init_member['buyac'];
	$tem_global['membuyc'] = $ar_init_member['buyc'];
}
else
{
	$tem_global['memislogin'] = "N";
}

$tem_global['c_id'] = $_COOKIE['c_id'];


$defaultSearch = getDefaultSearch();
$tem_global['ptype'] = $defaultSearch['ptype'];
$tem_global['mindate'] = $defaultSearch['mindate'];
$tem_global['sdate'] = $defaultSearch['sdate'];
$tem_global['edate'] = $defaultSearch['edate'];
$tem_global['grade_idx'] = $defaultSearch['grade_idx'];
$tem_global['location'] = $defaultSearch['location'];
$tem_global['addr'] = $defaultSearch['addr'];
$tem_global['isMain'] = $PHP_SELF == '/index.php' ? true : false;

#NEWSKIN
$defskinfile = substr($PHP_SELF, 0, -4);

$tpls = new Template_;

//기본 TPL 파일 설정
//인크루드가 2번 될 수 있음 미리 선언되어 있는 TPL을 인정
if (!$npage['loca']) {
	$npage['loca'] = $defskinfile;
	$npage['skins'] = $design_layout;
}

$tpls_TPL = array(
	"mains" => "{$npage['loca']}.htm",
	"head" => "./_common/head.htm",
	"header" => "./module/header.htm",
	"footer" => "./module/footer.htm",
	"side_member" => "./module/side_member.htm",
	"side_about" => "./module/side_about.htm",
	"side_customer_center" => "./module/side_customer_center.htm",
	"searchbar" => "./module/searchbar.htm"
);

if (!is_dir($compile_dir)) {
	mkdir($compile_dir, 0755, true);
}

$tpls->define($tpls_TPL, '', $compile_dir, $template_dir);
$tpls->assign('global', $tem_global);
