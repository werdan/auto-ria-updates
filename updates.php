<?php

$url = "http://auto.ria.ua/blocks_search_ajax/search/?countpage=100&category_id=1&view_type_id=0&page=0&marka=52&model=2684&s_yers=2008&po_yers=0&state=0&city=0&price_ot=20000&price_do=24000&currency=1&gearbox=0&type=0&drive_type=0&door=0&color=0&metallic=0&engineVolumeFrom=3.0&engineVolumeTo=3.0&raceFrom=&raceTo=110&powerFrom=&powerTo=&power_name=1&fuelRateFrom=&fuelRateTo=&fuelRatesType=city&custom=0&damage=0&saledParam=0&under_credit=0&confiscated_car=0&auto_repairs=0&with_exchange=0&with_real_exchange=0&exchangeTypeId=0&with_photo=0&with_video=0&is_hot=0&vip=0&checked_auto_ria=0&top=0&order_by=0&hide_black_list=0&auto_id=&deletedAutoSearch=0&user_id=0&scroll_to_auto_id=0&expand_search=0&can_be_checked=0&last_auto_id=0&matched_country=-1&seatsFrom=&seatsTo=&wheelFormulaId=0&axleId=0&carryingTo=&carryingFrom=&search_near_states=0";

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$result=curl_exec($ch);
$result = json_decode($result, true);
$carIds = $result['result']['search_result']['ids'];

$carsViewed = scandir(".");

foreach ($carIds as $carId) {
	if (in_array($carId, $carsViewed)) continue; 
	$carUrl = "http://auto.ria.ua/blocks_search/view/auto/" . $carId . "/";	
	curl_setopt($ch, CURLOPT_URL,$carUrl);
	$carHtml=curl_exec($ch);

	$carHtml = str_replace('<a href="/', '<a href="http://auto.ria.ua/', $carHtml);
	$fullCarHtml = "<html><head><meta charset='utf-8' /></head><body>" . $carHtml . "</body></html>";
	file_put_contents($carId, $fullCarHtml);	

	$to = 'samilyak@gmail.com';
	$subject = 'New car posted';

	$headers = "From: samilyak@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	mail($to, $subject, $fullCarHtml, $headers);
}