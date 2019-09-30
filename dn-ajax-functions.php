<?php

function dn_ajax_subscriber() {
	if ( ! wp_verify_nonce( $_POST['security'], 'dnajax' ) ) {
		die( "ERROR!" );
	}
	parse_str( $_POST['formData'], $str_array );

	if ( empty( $str_array['dn_name'] ) || empty( $str_array['dn_email'] ) ) {
		exit( 'Заполните поля!' );
	}

	if ( ! is_email( $str_array['dn_email'] ) ) {
		exit( 'Email не соответствует формату' );
	}

	global $wpdb;
	if ( $wpdb->get_var( $wpdb->prepare( "SELECT `subscriber_id` FROM `dn_subscribers` WHERE `subscriber_email` = %s",
		$str_array['dn_email'] ) ) ) {
		echo "Вы уже подписаны!";
	} else {
		if ( $wpdb->query( $wpdb->prepare( "INSERT INTO dn_subscribers (`subscriber_name`, `subscriber_email`) VALUES (%s, %s)",
			$str_array['dn_name'], $str_array['dn_email'] ) ) ) {
			echo 'Подписка оформлена';
		} else {
			echo 'Ошибка записи!';
		}
	}
	die;
}

function dn_ajax_subscriber_admin() {

//	if (empty($_POST['text'])) {
//		echo "Введите текст!";
//	}

	$subscribers = get_subscribers( true );
	$i           = 0;
	foreach ( $subscribers as $subscriber ) {
		$data = nl2br( str_replace( '%name%', $subscriber['subscriber_name'], $_POST['text'] ) );
		if ( wp_mail( $subscriber['subscriber_email'], 'Рассылка с плагина', $data ) ) {
			$i ++;
		}
	}

	die( "Рассылка была выполнена. Отправленных писем: {$i}" );
}