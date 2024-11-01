<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Uberbar
 * Description: Adds a menu to the admin tool bar with links to important pages on uberspace.de.
 * Version:     2014.06.01
 * Author:      toscho
 * Author URI:  http://wpkrauts.com/
 * License:     MIT
 * License URI: http://opensource.org/licenses/MIT
 */

/*
 * The default PHP version on uberspace is PHP 5.5, we require 5.4.
 * No need to be 5.2-compatible. :)
 */

class Uberbar
{
	/**
	 * @var string
	 */
	private $menu_id = 'uberspace';

	/**
	 * Initial setup.
	 *
	 * @wp-hook admin_bar_menu
	 * @param   WP_Admin_Bar $wp_admin_bar
	 * @return  void
	 */
	public function setup( WP_Admin_Bar $wp_admin_bar )
	{
		$user     = get_current_user();
		$server   = php_uname( "n" );
		$icon_url = plugins_url( 'icon.png', __FILE__ );

		$wp_admin_bar->add_menu(
			[
				'id'    => $this->menu_id,
				'title' => "<img src='$icon_url' alt='Uberspace' style='vertical-align: middle;'>",
				'href'  => 'https://uberspace.de/',
				'meta'  => [ 'title' => 'Uberspace.de' ],
			]
		);

		$wp_admin_bar->add_menu(
			[
				'parent' => $this->menu_id,
				'id'     => 'uberspace-dashboard',
				'title'  => 'Dashboard',
				'href'   => 'https://uberspace.de/dashboard',
			]
		);

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-wiki',
				 'title'  => 'Documentation',
				 'href'   => 'https://wiki.uberspace.de/',
			 ]
		);

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-email',
				 'title'  => 'Email Support',
				 'href'   => $this->get_mail_address( $server, $user ),
			 ]
		);

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-twitter',
				 'title'  => 'Twitter',
				 'href'   => 'https://twitter.com/ubernauten',
			 ]
		);

		if ( empty ( $server ) )
			return;

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-adminer',
				 'title'  => 'Adminer',
				 'href'   => "https://adminer.$server/",
			 ]
		);

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-phpmyadmin',
				 'title'  => 'phpMyAdmin',
				 'href'   => "https://pma.$server/",
			 ]
		);

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-webmail',
				 'title'  => 'Webmail',
				 'href'   => "https://webmail.$server/",
			 ]
		);

		if ( empty ( $user ) )
			return;

		$wp_admin_bar->add_menu(
			 [
				 'parent' => $this->menu_id,
				 'id'     => 'uberspace-webalizer',
				 'title'  => 'Webalizer',
				 'href'   => "https://stats.$server/$user/",
			 ]
		);
	}

	/**
	 * Creates an email address with a predefined body.
	 *
	 * @param  string $server
	 * @param  string $user
	 * @return string
	 */
	private function get_mail_address( $server, $user )
	{
		$address = 'mailto:hallo@uberspace.de';
		$text    = '';

		if ( '' === $server . $user )
			return $address;

		if ( ! empty ( $server ) )
			$text .= "Server: $server";

		if ( ! empty ( $user ) )
			$text .= ", User: $user\n";

		return $address . '?body=' . rawurlencode( $text );
	}
}

add_action( 'admin_bar_menu', function( \WP_Admin_Bar $wp_admin_bar ) {

	if ( ! is_super_admin() )
		return;

	$uberbar = new Uberbar;
	$uberbar->setup( $wp_admin_bar );

});