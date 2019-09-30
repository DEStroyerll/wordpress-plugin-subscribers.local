<?php

class DN_Widget_Subscriber extends WP_Widget {

	function __construct() {
		$array = array(
			'name'        => 'Виджет подписки',
			'description' => 'Виджет выводит форму для ввода имени и email',
			'classname'   => 'dn-subscriber'
		);
		parent::__construct( 'dn_subscriber', '', $array );
	}

	function widget( $args, $instance ) {
		add_action( 'wp_footer', 'dn_subscriber_scripts' );

		extract( $args );
		extract( $instance );

		$title = apply_filters( 'widget_title', $title );

		echo $before_widget;
		echo $before_title . $title . $after_title;

		?>
    <form action="" method="post" id="dn_form_subscriber">
      <p>
        <label for="dn_name">Имя:</label>
        <input type="text" name="dn_name" id="dn_name">
      </p>

      <p>
        <label for="dn_email">Email:</label>
        <input type="text" name="dn_email" id="dn_email" required>
      </p>

      <p>
        <input type="submit" name="dn_submit" value="Подписаться" id="dn_submit">
        <span id="loader" style="display: none">
          <img src="<?php echo plugins_url( 'img/loader.gif', __FILE__ ) ?>" alt="">
        </span>
      </p>
      <div id="message"></div>
    </form>
		<?php
		echo $after_widget;
	}

	function form( $instance ) {
		extract( $instance );
		?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ) ?>">Заголовок</label>
      <input type="text" name="<?php echo $this->get_field_name( 'title' ) ?>"
             id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php if ( isset( $title ) )
				echo esc_attr( $title ) ?>"
             class="widefat">
    </p>
		<?php
	}
}