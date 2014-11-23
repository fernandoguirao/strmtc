<?php
/**
 * WP-Members Core Functions
 *
 * Handles primary functions that are carried out in most
 * situations. Includes commonly used utility functions.
 * 
 * This file is part of the WP-Members plugin by Chad Butler
 * You can find out more about this plugin at http://rocketgeek.com
 * Copyright (c) 2006-2014  Chad Butler
 * WP-Members(tm) is a trademark of butlerblog.com
 *
 * @package WordPress
 * @subpackage WP-Members
 * @author Chad Butler 
 * @copyright 2006-2014
 */


/**
 * Include utility functions
 */
require_once( 'utilities.php' ); 


if ( ! function_exists( 'wpmem' ) ):
/**
 * The Main Action Function
 *
 * Does actions required at initialization
 * prior to headers being sent.
 *
 * @since 0.1 
 *
 * @global string $wpmem_a the action variable also used in wpmem_securify
 * @global string $wpmem_regchk contains messages returned from $wpmem_a action functions, used in wpmem_securify
 */
function wpmem()
{	
	global $wpmem_a, $wpmem_regchk;

	$wpmem_a = ( isset( $_REQUEST['a'] ) ) ? trim( $_REQUEST['a'] ) : '';

	switch ($wpmem_a) {

	case ( 'login' ):
		$wpmem_regchk = wpmem_login();
		break;

	case ( 'logout' ):
		wpmem_logout();
		break;

	case ( 'register' ):
		include_once( 'wp-members-register.php' );
		$wpmem_regchk = wpmem_registration( 'register' );
		break;
	
	case ( 'update' ):
		include_once( 'wp-members-register.php' );
		$wpmem_regchk = wpmem_registration( 'update' );
		break;
	
	case ( 'pwdchange' ):
		$wpmem_regchk = wpmem_change_password();
		break;
	
	case ( 'pwdreset' ):
		$wpmem_regchk = wpmem_reset_password();
		break;

	} // end of switch $a (action)

	/**
	 * Filter wpmem_regchk.
	 *
	 * The value of wpmem_regchk is determined by functions that may be run in the wpmem() function.
	 * This value determines what happens in the wpmem_securify() function.
	 *
	 * @since 2.9.0
	 *
	 * @param  string $wpmem_regchk The value of wpmem_regchk.
	 * @param  string $wpmem_a      The $wpmem_a action.
	 * @return string $wpmem_regchk Filtered or unfiltered $wpmem_regchk value.
 	 */
	$wpmem_regchk = apply_filters( 'wpmem_regchk', $wpmem_regchk, $wpmem_a );
}
endif;


if ( ! function_exists( 'wpmem_securify' ) ):
/**
 * The Securify Content Filter
 *
 * This is the primary function that picks up where wpmem() leaves off.
 * Determines whether content is shown or hidden for both post and
 * pages.
 *
 * @since 2.0
 *
 * @global var    $wpmem_a the action variable received from wpmem()
 * @global string $wpmem_regchk contains messages returned from wpmem() action functions
 * @global string $wpmem_themsg contains messages to be output
 * @global string $wpmem_captcha_err contains error message for reCAPTCHA
 * @global array  $post needed for protecting comments
 * @param  string $content
 * @return string $content
 */
function wpmem_securify( $content = null ) 
{ 
	$content = ( is_single() || is_page() ) ? $content : wpmem_do_excerpt( $content );

	if ( ( ! wpmem_test_shortcode( $content, 'wp-members' ) ) ) {	
		global $wpmem_regchk, $wpmem_themsg, $wpmem_a;
		
		if( $wpmem_regchk == "captcha" ) {
			global $wpmem_captcha_err;
			$wpmem_themsg = __( 'There was an error with the CAPTCHA form.' ) . '<br /><br />' . $wpmem_captcha_err;
		}

		// Block/unblock Posts
		if( !is_user_logged_in() && wpmem_block() == true ) {
		
			// protects comments if user is not logged in
			global $post;
			/**
			 * Filter the post password.
			 *
			 * @since 2.8.0
			 *
			 * @param string The autogenerated post password.
			 */
			$post->post_password = apply_filters( 'wpmem_post_password' , wp_generate_password() );
		
			include_once( WPMEM_PATH . 'wp-members-dialogs.php' );
			
			// show the login and registration forms
			if( $wpmem_regchk ) {
				
				// empty content in any of these scenarios
				$content = '';
	
				switch( $wpmem_regchk ) {
	
				case "loginfailed":
					$content = wpmem_inc_loginfailed();
					break;
	
				case "success":
					$content = wpmem_inc_regmessage( $wpmem_regchk, $wpmem_themsg );
					$content = $content . wpmem_inc_login();
					break;
	
				default:
					$content = wpmem_inc_regmessage( $wpmem_regchk, $wpmem_themsg );
					$content = $content . wpmem_inc_registration();
					break;
				}
	
			} else {
			
				// toggle shows excerpt above login/reg on posts/pages
				if( WPMEM_SHOW_EXCERPT == 1 ) {

					if( ! stristr( $content, '<span id="more' ) ) {
						$content = wpmem_do_excerpt( $content );
					} else {
						$len = strpos( $content, '<span id="more' );
						$content = substr( $content, 0, $len );
					}
					
				} else {
				
					// empty all content
					$content = '';
				
				}
	
				$content = $content . wpmem_inc_login();
				
				$content = ( WPMEM_NO_REG != 1 ) ? $content . wpmem_inc_registration() : $content;
			}
	

		// Protects comments if expiration module is used and user is expired
		} elseif( is_user_logged_in() && wpmem_block() == true ){

			$content = ( WPMEM_USE_EXP == 1 && function_exists( 'wpmem_do_expmessage' ) ) ? wpmem_do_expmessage( $content ) : $content;
			
		}
		
	}
	
	/**
	 * Filter the value of $content after wpmem_securify has run.
	 *
	 * @since 2.7.7
	 *
	 * @param string $content The content after securify has run.
	 */
	$content = apply_filters( 'wpmem_securify', $content );
	
	if( strstr( $content, '[wpmem_txt]' ) ) {
		// fix the wptexturize
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', 'wptexturize' );
		add_filter( 'the_content', 'wpmem_texturize', 99 ); 
	}

	return $content;
	
} // end wpmem_securify
endif;


if ( ! function_exists( 'wpmem_do_sc_pages' ) ):
/**
 * Builds the shortcode pages (login, register, user-profile, user-edit, password)
 *
 * Some of the logic here is similar to the wpmem_securify() function. But whereas that function handles general
 * content, this function handles building specific pages generated by shortcodes.
 *
 * @since 2.6
 *
 * @param  string $page
 * @global string $wpmem_regchk
 * @global string $wpmem_themsg
 * @global string $wpmem_a
 * @return string $content 
 */
function wpmem_do_sc_pages( $page )
{
	global $wpmem_regchk, $wpmem_themsg, $wpmem_a;
	include_once( WPMEM_PATH . 'wp-members-dialogs.php' );
	
	$content = '';
	
	// deprecating members-area parameter to be replaced by user-profile
	$page = ( $page == 'user-profile' ) ? 'members-area' : $page;

	if ( $page == 'members-area' || $page == 'register' ) { 
		
		if( $wpmem_regchk == "captcha" ) {
			global $wpmem_captcha_err;
			$wpmem_themsg = __( 'There was an error with the CAPTCHA form.' ) . '<br /><br />' . $wpmem_captcha_err;
		}
		
		if( $wpmem_regchk == "loginfailed" ) {
			return wpmem_inc_loginfailed();
		}
		
		if( ! is_user_logged_in() ) {
			if( $wpmem_a == 'register' ) {

				switch( $wpmem_regchk ) {

				case "success":
					$content = wpmem_inc_regmessage( $wpmem_regchk,$wpmem_themsg );
					$content = $content . wpmem_inc_login();
					break;

				default:
					$content = wpmem_inc_regmessage( $wpmem_regchk,$wpmem_themsg );
					$content = $content . wpmem_inc_registration();
					break;
				}

			} elseif( $wpmem_a == 'pwdreset' ) {

				$content = wpmem_page_pwd_reset( $wpmem_regchk, $content );

			} else {

				$content = ( $page == 'members-area' ) ? $content . wpmem_inc_login( 'members' ) : $content;
				$content = ( $page == 'register' || WPMEM_NO_REG != 1 ) ? $content . wpmem_inc_registration() : $content;
			}

		} elseif( is_user_logged_in() && $page == 'members-area' ) {
			
			/**
			 * Filter the default heading in User Profile edit mode.
			 *
			 * @since 2.7.5
			 *
			 * @param string The default edit mode heading.
			 */
			$heading = apply_filters( 'wpmem_user_edit_heading', __( 'Edit Your Information', 'wp-members' ) );
		
			switch( $wpmem_a ) {

			case "edit":
				$content = $content . wpmem_inc_registration( 'edit', $heading );
				break;

			case "update":

				// determine if there are any errors/empty fields

				if( $wpmem_regchk == "updaterr" || $wpmem_regchk == "email" ) {

					$content = $content . wpmem_inc_regmessage( $wpmem_regchk,$wpmem_themsg );
					$content = $content . wpmem_inc_registration( 'edit', $heading );

				} else {

					//case "editsuccess":
					$content = $content . wpmem_inc_regmessage( $wpmem_regchk,$wpmem_themsg );
					$content = $content . wpmem_inc_memberlinks();

				}
				break;

			case "pwdchange":

				$content = wpmem_page_pwd_reset( $wpmem_regchk, $content );
				break;

			case "renew":
				$content = wpmem_renew();
				break;

			default:
				$content = wpmem_inc_memberlinks();
				break;					  
			}

		} elseif( is_user_logged_in() && $page == 'register' ) {

			//return wpmem_inc_memberlinks( 'register' );
			
			$content = $content . wpmem_inc_memberlinks( 'register' );
		
		}
			
	}
	
	if( $page == 'login' ) {
		$content = ( $wpmem_regchk == "loginfailed" ) ? wpmem_inc_loginfailed() : $content; 
		$content = ( ! is_user_logged_in() ) ? $content . wpmem_inc_login( 'login' ) : wpmem_inc_memberlinks( 'login' );
	}
	
	if( $page == 'password' ) {
		$content = wpmem_page_pwd_reset( $wpmem_regchk, $content );
	}
	
	if( $page == 'user-edit' ) {
		$content = wpmem_page_user_edit( $wpmem_regchk, $content );
	}
	
	return $content;
} // end wpmem_do_sc_pages
endif;


if ( ! function_exists( 'wpmem_block' ) ):
/**
 * Determines if content should be blocked
 *
 * @since 2.6
 *
 * @return bool $block 
 */
function wpmem_block()
{
	global $post; 
	
	$unblock_meta = get_post_custom_values( 'unblock', $post->ID );
	$block_meta   = get_post_custom_values( 'block',   $post->ID );

	$block = false;
	
	if( is_single() ) {
		//$not_mem_area = 1; 
		if( WPMEM_BLOCK_POSTS == 1 && ! get_post_custom_values( 'unblock' ) ) { $block = true; }	
		if( WPMEM_BLOCK_POSTS == 0 &&   get_post_custom_values( 'block' ) )   { $block = true; }
	}

	if( is_page() && ! is_page( 'members-area' ) && ! is_page( 'register' ) ) { 
		//$not_mem_area = 1; 
		if( WPMEM_BLOCK_PAGES == 1 && ! get_post_custom_values( 'unblock' ) ) { $block = true; }
		if( WPMEM_BLOCK_PAGES == 0 &&   get_post_custom_values( 'block' ) )   { $block = true; }
	}
	
	/**
	 * Filter the block boolean.
	 *
	 * @since 2.7.5
	 *
	 * @param bool $block
	 */
	return apply_filters( 'wpmem_block', $block );
}
endif;


if ( ! function_exists( 'wpmem_shortcode' ) ):
/**
 * Executes various shortcodes 
 *
 * This function executes shortcodes for pages (settings, register, login, user-list, 
 * and tos pages), as well as login status and field attributes when the wp-members tag
 * is used.  Also executes shortcodes for login status with the wpmem_logged_in tags 
 * and fields when the wpmem_field tags are used.
 *
 * @since 2.4 
 *
 * @param  array  $attr page|url|status|msg|field|id
 * @param  string $content
 * @param  string $tag
 * @return string returns the result of wpmem_do_sc_pages|wpmem_list_users|wpmem_sc_expmessage|$content
 */
function wpmem_shortcode( $attr, $content = null, $tag = 'wp-members' )
{
	// set all default attributes to false
	$defaults = array(
		'page'      => false,
		'url'       => false,
		'status'    => false,
		'msg'       => false,
		'field'     => false,
		'id'        => false
	);

	// merge defaults with $attr and extract
	extract( shortcode_atts( $defaults, $attr, $tag ) );

	// handles the 'page' attribute
	if( $page ) {
		if( $page == 'user-list' ) {
			//return ( function_exists( 'wpmem_list_users' ) ) ? do_shortcode( wpmem_list_users( $attr, $content ) ) : '';
			if( function_exists( 'wpmem_list_users' ) ) {
				$content = do_shortcode( wpmem_list_users( $attr, $content ) );
			}
		} elseif( $page == 'tos' ) {
			return $url;
		} else {
			//return do_shortcode( wpmem_do_sc_pages( $page ) ); 
			$content = do_shortcode( wpmem_do_sc_pages( $page ) );
		}
		
		// resolve any texturize issues...
		if( strstr( $content, '[wpmem_txt]' ) ) {
			// fix the wptexturize
			remove_filter( 'the_content', 'wpautop' );
			remove_filter( 'the_content', 'wptexturize' );
			add_filter( 'the_content', 'wpmem_texturize', 99 ); 
		}
		return $content;
	}
	
	// handles the 'status' attribute
	if( ( $status ) || $tag == 'wpmem_logged_in' ) {
	
		$do_return = false;
		
		// if using the wpmem_logged_in tag with no attributes & the user is logged in
		if( $tag == 'wpmem_logged_in' && ( ! $attr ) && is_user_logged_in() )
			$do_return = true;
		
		// if there is a status attribute of "in" and the user is logged in
		if( $status == 'in' && is_user_logged_in() )
			$do_return = true;
	
		// if there is a status attribute of "out" and the user is not logged in
		if( $status == 'out' && ! is_user_logged_in() ) 
			$do_return = true;
		
		// if there is a status attribute of "sub" and the user is logged in
		if( $status == 'sub' && is_user_logged_in() ) {
			if( WPMEM_USE_EXP == 1 ) {	
				if( ! wpmem_chk_exp() ) { 
					$do_return = true;
				} elseif( $msg == true ) {
					$do_return = true;
					$content = wpmem_sc_expmessage();
				}
			}
		}
		
		// return content (or empty content) depending on the result of the above logic
		return ( $do_return ) ? do_shortcode( $content ) : '';
	}
	
	// handles the wpmem_logged_out tag with no attributes & the user is not logged in
	if( $tag == 'wpmem_logged_out' && ( ! $attr ) && ! is_user_logged_in() ) {
		return do_shortcode( $content );
	}

	// handles the 'field' attribute
	if( $field || $tag == 'wpmem_field' ) {
		if( $id ) {
			// we are getting some other user
			if( $id == 'get' ) {
				$the_user_ID = ( isset( $_GET['uid'] ) ) ? $_GET['uid'] : '';
			} else {
				$the_user_ID = $id;
			}
		} else {
			// get the current user
			$the_user_ID = get_current_user_id();
		}
		$user_info = get_userdata( $the_user_ID );	

		// @todo - check this change
		return ( $user_info ) ? htmlspecialchars( $user_info->$field ) . do_shortcode( $content ) : do_shortcode( $content );
		// return ( $user_info ) ? htmlspecialchars( $user_info->$field ) . do_shortcode( $content ) : '';
	}
	
	// logout link shortcode
	if( is_user_logged_in() && $tag == 'wpmem_logout' ) {
		$link = ( $url ) ? wpmem_chk_qstr( $url ) . 'a=logout' : wpmem_chk_qstr( get_permalink() ) . 'a=logout';
		$text = ( $content ) ? $content : __( 'Click here to log out.', 'wp-members' );
		return do_shortcode( "<a href=\"$link\">$text</a>" );
	}
	
}
endif;


if( ! function_exists( 'wpmem_check_activated' ) ):
/**
 * Checks if a user is activated
 *
 * @since 2.7.1
 *
 * @param  int $user
 * @param  string $username
 * @param  string $password
 * @uses   wp_check_password
 * @return int $user
 */ 
function wpmem_check_activated( $user, $username, $password ) 
{
	// password must be validated
	$pass = ( ( ! is_wp_error( $user ) ) && $password ) ? wp_check_password( $password, $user->user_pass, $user->ID ) : false;
	
	if( ! $pass ) { 
		return $user; 
	}

	// activation flag must be validated
	$active = get_user_meta( $user->ID, 'active', 1 );
	if( $active != 1 ) {
		return new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: User has not been activated.', 'wp-members' ) );
	}

	// if the user is validated, return the $user object
	return $user;
}
endif;


if( ! function_exists( 'wpmem_login' ) ):
/**
 * Logs in the user
 *
 * Logs in the the user using wp_signon (since 2.5.2). If login is
 * successful, it will set a cookie using wp_set_auth_cookie (since 2.7.7),
 * then it redirects and exits; otherwise "loginfailed" is returned.
 *
 * @since 0.1
 *
 * @uses   wp_signon
 * @uses   wp_set_auth_cookie
 * @uses   wp_redirect Redirects to $redirect_to if login is successful
 * @return string Returns "loginfailed" if the login fails
 */
function wpmem_login()
{
	if( $_POST['log'] && $_POST['pwd'] ) {
		
		/** get username and sanitize */
		$user_login = sanitize_user( $_POST['log'] );
		
		/** are we setting a forever cookie? */
		$rememberme = ( isset( $_POST['rememberme'] ) == 'forever' ) ? true : false;
		
		/** assemble login credentials */
		$creds = array();
		$creds['user_login']    = $user_login;
		$creds['user_password'] = $_POST['pwd'];
		$creds['remember']      = $rememberme;
		
		/** wp_signon the user and get the $user object */
		$user = wp_signon( $creds, false );

		/** if no error, user is a valid signon. continue */
		if( ! is_wp_error( $user ) ) {
			
			/** set the auth cookie */
			wp_set_auth_cookie( $user->ID, $rememberme );
			
			/** determine where to put the user after login */			
			$redirect_to = ( isset( $_POST['redirect_to'] ) ) ? $_POST['redirect_to'] : $_SERVER['REQUEST_URI'];
			
			/** 
			 * Filter the redirect url.
			 *
			 * @since 2.7.7
			 *
			 * @param string $redirect_to The url to direct to.
			 * @param int    $user->ID    The user's primary key ID.
			 */
			$redirect_to = apply_filters( 'wpmem_login_redirect', $redirect_to, $user->ID );
			
			/** and do the redirect */
			wp_redirect( $redirect_to );
			
			/** wp_redirect requires us to exit() */
			exit();
			
		} else {
		
			return "loginfailed";
		}
	
	} else {
		//login failed
		return "loginfailed";
	}	

} // end of login function
endif;


if ( ! function_exists( 'wpmem_logout' ) ):
/**
 * Logs the user out then redirects
 *
 * @since 2.0
 *
 * @uses wp_clearcookie
 * @uses wp_logout
 * @uses nocache_headers
 * @uses wp_redirect
 */
function wpmem_logout()
{
	/**
	 * Filter the where the user goes when logged out.
	 *
	 * @since 2.7.1
	 *
	 * @param string The blog home page.
	 */
	$redirect_to = apply_filters( 'wpmem_logout_redirect', get_bloginfo( 'url' ) );

	wp_clear_auth_cookie();
	do_action( 'wp_logout' );
	nocache_headers();

	wp_redirect( $redirect_to );
	exit();
}
endif;


if ( ! function_exists( 'wpmem_login_status' ) ):
/**
 * Displays the user's login status
 *
 * @since 2.0
 *
 * @uses wpmem_inc_memberlinks()
 */
function wpmem_login_status()
{
	include_once('wp-members-dialogs.php');
	if (is_user_logged_in()) { echo wpmem_inc_memberlinks( 'status' ); }
}
endif;


if ( ! function_exists( 'wpmem_inc_sidebar' ) ):
/**
 * Displays the sidebar
 *
 * @since 2.0
 *
 * @uses wpmem_do_sidebar()
 */
function wpmem_inc_sidebar()
{
	include_once('wp-members-sidebar.php');
	wpmem_do_sidebar();
}
endif;


if ( ! function_exists( 'widget_wpmemwidget_init' ) ):
/**
 * Initializes the widget
 *
 * @since 2.0
 *
 * @uses register_widget
 */
function widget_wpmemwidget_init()
{
	include_once( 'wp-members-sidebar.php' );
	register_widget( 'widget_wpmemwidget' );
}
endif;


if ( ! function_exists( 'wpmem_change_password' ) ):
/**
 * Handles user password change (not reset)
 *
 * @since 2.1
 *
 * @global $user_ID
 * @return string the value for $wpmem_regchk
 */
function wpmem_change_password()
{
	global $user_ID;
	if( isset( $_POST['formsubmit'] ) ) {

		$pass1 = $_POST['pass1'];
		$pass2 = $_POST['pass2'];
		
		if ( ! $pass1 && ! $pass2 ) { // check for both fields being empty
		
			return "pwdchangempty";

		} elseif ( $pass1 != $pass2 ) { // make sure the fields match

			return "pwdchangerr";

		} else { // update password in db (wp_update_user hashes the password)

			wp_update_user( array ( 'ID' => $user_ID, 'user_pass' => $pass1 ) );
			
			/**
			 * Password change action
			 *
			 * @since 2.9.0
			 *
			 * @param int $user_ID The user's numeric ID
			 */
			do_action( 'wpmem_pwd_change', $user_ID );
			
			return "pwdchangesuccess";

		}
	}
	return;
}
endif;


if( ! function_exists( 'wpmem_reset_password' ) ):
/**
 * Resets a forgotten password
 *
 * @since 2.1
 *
 * @uses   wp_generate_password
 * @uses   wp_update_user
 * @return string value for $wpmem_regchk
 */
function wpmem_reset_password()
{ 
	if( isset( $_POST['formsubmit'] ) ) {

		/**
		 * Filter the password reset arguments.
		 *
		 * @since 2.7.1
		 *
		 * @param array The username and email.
		 */
		$arr = apply_filters( 'wpmem_pwdreset_args', array( 
			'user'  => ( isset( $_POST['user']  ) ) ? $_POST['user']  : '', 
			'email' => ( isset( $_POST['email'] ) ) ? $_POST['email'] : ''
		) );

		if( ! $arr['user'] || ! $arr['email'] ) { 

			// there was an empty field
			return "pwdreseterr";

		} else {

			if( username_exists( $arr['user'] ) ) {

				$user = get_user_by( 'login', $arr['user'] );
				
				if( strtolower( $user->user_email ) !== strtolower( $arr['email'] ) || ( ( WPMEM_MOD_REG == 1 ) && ( get_user_meta( $user->ID,'active', true ) != 1 ) ) ) {
					// the username was there, but the email did not match OR the user hasn't been activated
					return "pwdreseterr";
					
				} else {
					
					// generate a new password
					$new_pass = wp_generate_password();
					
					// update the users password
					wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $new_pass ) );

					// send it in an email
					require_once( 'wp-members-email.php' );
					wpmem_inc_regemail( $user->ID, $new_pass, 3 );
					
					/**
					 * Password reset action
					 *
					 * @since 2.9.0
					 *
					 * @param int $user_ID The user's numeric ID
					 */
					do_action( 'wpmem_pwd_reset', $user->ID );
					
					return "pwdresetsuccess";
				}
			} else {

				// username did not exist
				return "pwdreseterr";
			}
		}
	}
	return;
}
endif;


if( ! function_exists( 'wpmem_no_reset' ) ):
/**
 * Keeps users not activated from resetting their password 
 * via wp-login when using registration moderation.
 *
 * @since 2.5.1
 *
 * @return bool
 */
function wpmem_no_reset() {

	if( strpos( $_POST['user_login'], '@' ) ) {
		$user = get_user_by( 'email', trim( $_POST['user_login'] ) );
	} else {
		$username = trim( $_POST['user_login'] );
		$user     = get_user_by( 'login', $username );
	}

	if( WPMEM_MOD_REG == 1 ) { 
		if( get_user_meta( $user->ID, 'active', true ) != 1 ) { 			
			return false;
		}
	}
	
	return true;
}
endif;


/**
 * Anything that gets added to the the <html> <head>
 *
 * @since 2.2
 */
function wpmem_head() { 
	echo "<!-- WP-Members version ".WPMEM_VERSION.", available at http://rocketgeek.com/wp-members -->\r\n";
}


/**
 * Add registration fields to the native WP registration
 *
 * @since 2.8.3
 */
function wpmem_wp_register_form() {
	include_once( 'native-registration.php' );
	wpmem_do_wp_register_form();
}


/**
 * Validates registration fields in the native WP registration
 *
 * @since 2.8.3
 *
 * @param $errors
 * @param $sanatized_user_login
 * @param $user_email
 * @return $errors
 */
function wpmem_wp_reg_validate( $errors, $sanitized_user_login, $user_email )
{
	$wpmem_fields = get_option( 'wpmembers_fields' );
	$exclude = wpmem_get_excluded_meta( 'register' );

	foreach( $wpmem_fields as $field ) {
		$is_error = false;
		if( $field[5] == 'y' && $field[2] != 'user_email' && ! in_array( $field[2], $exclude ) ) {
			if( ( $field[3] == 'checkbox' ) && ( ! isset( $_POST[$field[2]] ) ) ) {
				$is_error = true;
			} 
			if( ( $field[3] != 'checkbox' ) && ( ! $_POST[$field[2]] ) ) {  
				$is_error = true;
			}
			if( $is_error ) { $errors->add( 'wpmem_error', sprintf( __('Sorry, %s is a required field.', 'wp-members'), $field[1] ) ); }
		}
	}

	return $errors;
}


/**
 * Inserts registration data from the native WP registration
 *
 * @since 2.8.3
 *
 * @param $user_id
 */
function wpmem_wp_reg_finalize( $user_id )
{
	$native_reg = ( isset( $_POST['wp-submit'] ) && $_POST['wp-submit'] == esc_attr( __( 'Register' ) ) ) ? true : false;
	$add_new  = ( isset( $_POST['action'] ) && $_POST['action'] == 'createuser' ) ? true : false;
	if( $native_reg || $add_new ) {
		// get the fields
		$wpmem_fields = get_option( 'wpmembers_fields' );
		// get any excluded meta fields
		$exclude = wpmem_get_excluded_meta( 'register' );
		foreach( $wpmem_fields as $meta ) {
			if ( isset( $_POST[$meta[2]] ) && ! in_array( $meta[2], $exclude ) ) {
				update_user_meta( $user_id, $meta[2], sanitize_text_field( $_POST[$meta[2]] ) );
			}
		}
	}
	return;
}


/**
 * Loads the stylesheet for backend registration
 *
 * @since 2.8.7
 */
function wpmem_wplogin_stylesheet() {
    echo '<link rel="stylesheet" id="custom_wp_admin_css"  href="' . WPMEM_DIR . 'css/wp-login.css" type="text/css" media="all" />';
}

/** End of File **/