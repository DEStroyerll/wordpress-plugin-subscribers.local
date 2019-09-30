<?php

/**
 * Plugin Name: Сбор подписчиков
 * Plugin URI:  http://#
 * Description: Плагин предоставляет виджет, позволяющий собирать подписчиков
 * Version:     1.0.0
 * Author:      Denis Gurov
 * Author URI:  https://#
 */

require_once __DIR__ . '/dn-widget-subscriber.php';
require_once __DIR__ . '/dn-ajax-functions.php';
require_once __DIR__ . '/dn-helpers.php';

register_activation_hook( __FILE__, 'dn_create_table' );
add_action( 'widgets_init', 'dn_widget_subscriber' );
add_action( 'wp_ajax_dn_subscriber', 'dn_ajax_subscriber' );
add_action( 'wp_ajax_nopriv_dn_subscriber', 'dn_ajax_subscriber' );
add_action( 'admin_menu', 'dn_admin_menu' );
add_action( 'wp_ajax_dn_subscriber_admin', 'dn_ajax_subscriber_admin' );

function dn_create_table() {
	global $wpdb;
	$create_table = "CREATE TABLE IF NOT EXISTS `dn_subscribers` (
  `subscriber_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscriber_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `subscriber_email` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`subscriber_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$wpdb->query( $create_table );
}

function dn_widget_subscriber() {
	register_widget( 'DN_Widget_Subscriber' );
}

function dn_subscriber_scripts() {
	wp_register_script( 'dn-subscriber', plugins_url( 'js/main.js', __FILE__ ), array( 'jquery' ) );
	wp_enqueue_script( 'dn-subscriber' );
	wp_localize_script( 'dn-subscriber', 'dnajax', array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'dnajax' )
		)
	);
}

function dn_admin_menu() {
	add_options_page( 'Подписчики', 'Подписчики', 'manage_options',
		'dn-subscriber', 'dn_show_subscriber' );
	add_action( 'admin_enqueue_scripts', 'dn_admin_scripts' );
}

function dn_admin_scripts( $hook ) {
	if ( $hook != 'settings_page_dn-subscriber' ) {
		return;
	}
	wp_enqueue_style( 'dn-style', plugins_url( 'css/dn-subscriber.css', __FILE__ ) );
	wp_enqueue_script( 'dn-admin', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), '', true );
}

function dn_show_subscriber() {
	?>
  <div class="wrap">
    <h2>Подписчики</h2>
		<?php
		$pagination_params = pagination_params();
		$subscribers       = get_subscribers();
		?>
		<?php if ( $subscribers ) { ?>
      <p><b>Количество подписчиков: <?php echo $pagination_params['count'] ?></b></p>
      <table class="wp-list-table widefat fixed striped posts" id="dn-table">
        <thead>
        <tr>
          <td>ID</td>
          <td>Name</td>
          <td>Email</td>
        </tr>
        </thead>
        <tbody>
				<?php foreach ( $subscribers as $subscriber ) { ?>
          <tr>
            <td><?php echo $subscriber['subscriber_id']; ?></td>
            <td><?php echo $subscriber['subscriber_name']; ?></td>
            <td><?php echo $subscriber['subscriber_email']; ?></td>
          </tr>
				<?php } ?>
        </tbody>
      </table>

      <!--Пагинация-->
			<?php if ( $pagination_params['count_pages'] > 1 ) { ?>
        <div class="pagination">
					<?php echo pagination( $pagination_params['page'], $pagination_params['count_pages'] ); ?>
        </div>
			<?php } ?>
      <p>
        <label for="dn-textarea">Текст рассылки (для имени используйте шаблон %name%)</label>
        <textarea name="dn-textarea" id="dn_textarea" cols="30" rows="10" class="widefat dn-text"></textarea>
      </p>

      <button class="btn" id="btn">Сделать рассылку</button>
      <span id="loader" style="display: none">
          <img src="<?php echo plugins_url( 'img/loader.gif', __FILE__ ) ?>" alt="">
      </span>
      <div id="message"></div>
		<?php } else {
			echo 'Список подписчиков пуст!';
		} ?>
  </div>
	<?php
}