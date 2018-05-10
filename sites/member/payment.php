<?
include $_SERVER['DOCUMENT_ROOT']."/old/inc/base.php";
include $config['incPath']."/connect.php";
include $config['incPath']."/session.php";
include $config['incPath']."/config_site.php";
include $config['incPath']."/access.php";


$page[type] = "2";
$page[title] = "결제내역";

$query = "
	SELECT
		m.*,
		mt.idx order_idx,
		mt.vehicle_idx,
		mt.retype,
		mt.pricetype,
		mt.ddata1,
		mt.ddata2,
		mt.rtype,
		mt.sdate,
		mt.edate,
		mt.account,
		mt.account1,
		mt.account2,
		mt.preaccount,
		mt.insu,
		mt.insuac,
		mt.insu_exem,
		mt.addr,
		mt.raddr,
		default_image.path default_image_path,
		UNIX_TIMESTAMP(default_image.dt_create) default_image_timestamp,
		car_image.path car_image_path,
		UNIX_TIMESTAMP(car_image.dt_create) car_image_timestamp,
		vs.name modelname,
		c_fuel.sname fuel_sname,
		vp.price_insu1_exem,
		vp.price_insu2_exem,
		vp.price_insu3_exem,
		vpl.price_longterm_insu_exem,
		v.insu_per car_insu_per,
		v.insu_goods car_insu_goods,
		v.insu_self car_insu_self,
		r.name rentshop_name,
		r.affiliate,
		r.addr1 rentshop_addr1,
		r.addr2 rentshop_addr2,
		r.dphone2 rentshop_dphone2,
		r.dcp2 rentshop_dcp2,
		v.color car_color,
		v.rundistan car_rundistan,
		v.rentage car_rentage,
		v.license_limit car_license_limit,
		mt.insu_limit,
		mt.distance_limit,
		mt.distance_additional_price,
		(SELECT rawdata FROM payment_accounts WHERE tbtype = 'I' AND payment_idx = m.idx ORDER BY dt_create DESC LIMIT 0,1) rawdata,
		(SELECT account FROM payment_accounts WHERE tbtype = 'I' AND payment_idx = m.idx ORDER BY dt_create DESC LIMIT 0,1) payment_account,
		(SELECT GROUP_CONCAT(CASE WHEN op_data = '있음' THEN op_name ELSE op_data END SEPARATOR ' / ') FROM vehicle_opt WHERE vehicle_idx = v.idx AND op_data <> '') opt
	FROM payments m
		LEFT JOIN reservation mt ON mt.idx = m.reservation_idx
		LEFT JOIN vehicles v ON mt.vehicle_idx = v.idx
		LEFT JOIN rentshop r ON v.rentshop_idx = r.idx
		LEFT JOIN vehicle_models vs ON v.model_idx = vs.idx
		LEFT JOIN files default_image ON default_image.article_type = 'car' AND default_image.article_idx = vs.idx AND default_image.article_info = 'default'
		LEFT JOIN files car_image ON car_image.article_type = 'car' AND car_image.article_idx = vs.idx AND car_image.article_info = v.color
		LEFT JOIN codes c_fuel ON v.fuel_idx = c_fuel.idx
		LEFT JOIN vehicle_price vp ON vp.idx = v.price_idx
		LEFT JOIN vehicle_price_longterm vpl ON vpl.idx = v.price_longterm_idx
	WHERE
		m.member_idx = $g_memidx
	ORDER BY m.dt_create DESC
";

$result = mysql_query($query);
$list = array();
while($row = mysql_fetch_assoc($result)) {
	$rawdata = explode('|R|', $row['rawdata']);
	$row['tno'] = $rawdata[2];
	$list[] = $row;
}

$tpls->assign('olist', $list);
$tpls->assign('page', $page);
$tpls->print_('mains');
?>

