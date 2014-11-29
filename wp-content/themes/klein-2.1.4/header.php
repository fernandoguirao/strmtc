<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package klein
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<!-- favicon -->
<?php $favicon_default = get_template_directory_uri() . '/favicon.ico'; ?>
<?php $favicon = ot_get_option( 'favicon', $favicon_default ); ?>
<link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
<!-- favicon end -->
<!-- custom background css -->
<?php klein_custom_background(); ?>
<!-- custom background css end -->
<!-- custom typography settings -->
<?php klein_custom_typography(); ?>
<!-- custom typography settings end -->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- PERSONALIZACIÓN FER -->

<?php
include_once "wp-content/themes/klein-2.1.4/getscores.php";
include_once "wp-content/themes/klein-2.1.4/savescores.php"; // esto incluirá a.php
?>


<a href="http://localhost:8888/wp-login.php?loginFacebook=1&redirect=http://localhost:8888" onclick="window.location = 'http://localhost:8888/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;">Click here to login or register with Facebook</a>
<?php echo do_shortcode('[bps_display form=3453]' );

echo do_shortcode( '[mycred_my_balance]' );

echo do_shortcode( '[mycred_buy_form]' );

?>
<!-- FIN PERSONALIZACIÓN -->
<?php
	$container_class = ot_get_option( 'container', 'fluid' );
?>
<div id="page" class="hfeed site <?php echo $container_class; ?>">
	<?php do_action( 'before' ); ?>
	<div id="bp-klein-top-bar">
		<nav id="bp-klein-user-bar" class="container" role="navigation">
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<p class="remove-bottom site-description">
						<?php bloginfo('description'); ?>
					</p>
				</div>
				<div id="bp-klein-user-bar-action" class="col-md-4 col-sm-4">
					<div class="pull-right">
						<?php if( is_user_logged_in() ){ ?>
							<?php
								global $current_user;
								get_currentuserinfo();
								$user_login = $current_user->user_login;
							?>
							<?php if( function_exists('bp_core_get_user_domain' ) ){ ?>
								<a id="klein-top-bp-profile-link" class="btn btn-primary" href="<?php echo bp_core_get_user_domain( $current_user->ID );?>" title="<?php _e('My Profile','klein'); ?>">
									<?php echo $current_user->display_name; ?>
								</a>
								<?php klein_user_nav(); ?>
							<?php } ?>
						<?php }else{ ?>
							<a data-toggle="modal" id="klein-login-btn" class="btn btn-primary" href="#klein_login_modal" title="<?php _e( 'Login', 'klein' ); ?>"><i class="icon-lock"></i> <?php _e( 'Login', 'klein' ); ?></a>
							<?php echo str_replace( '<a', '<a id="klein-register-btn" title="'.__('Register','klein').'" class="btn btn-primary" ', wp_register('', '', false)); ?>
							<div class="modal fade" id="klein_login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title">
												<?php _e( sprintf( 'Login to %s', get_bloginfo( 'name' ) ),'klein' ); ?>
											</h4>
										</div>
										<div class="modal-body">
											
											<div id="klein-modal-body">
												<?php do_action( 'klein_before_login_form_modal_body' ); ?>
													<?php wp_login_form(); ?>
													<?php do_action( 'login_form' ); //3rd party applications/plugins support ?>
												<?php do_action( 'klein_after_login_form_modal_body' ); ?>
											</div>
												<?php // support 3rd party plugins ?>
											<div class="clearfix">
												<?php do_action( 'login_footer' ); ?>
											</div>
											
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
						<?php } ?>
					</div>
				</div>
			</div>
		</nav>
	</div>

	
	<header id="header" class="site-header" role="banner">
		
		<div class="container">
			<div class="row">
				<div class="site-branding col-md-2 col-sm-2" id="site-name">
					<a id="logo-anchor" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) . ' - ' . get_bloginfo( 'description' ) ); ?>" rel="home">
						<?php
							$default_logo = get_template_directory_uri() . '/logo.png';
							$logo = ot_get_option( 'logo', $default_logo );
						?>
						<h1><?php bloginfo( 'name' ); ?></h1>
						<img src="<?php echo $logo; ?>" alt="<?php echo get_bloginfo( 'name' ); ?> - <?php echo get_bloginfo( 'description' ); ?>"/>
					</a>
				</div>
				
				<div class="desktop-menu col-md-10 col-sm-10 show-on-desktops">
					<div class="pull-right">
					<?php
						
						$defaults = array(
							'theme_location'  => 'primary',
							'container'       => 'div',
							'container_class' => 'navigation-desktop',
							'container_id'    => 'main-menu-desktop',
							'menu_class'      => 'menu desktop',
						);
					?>
					<?php wp_nav_menu( $defaults ); ?>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="small-screen-device-nav hide-on-desktops col-md-8 col-sm-8">
					<nav id="site-navigation" class="main-navigation pull-right" role="navigation">
						<div id="menu">
							<div data-dropdown=".menu.mobile" class="nav-btn glyphicon glyphicon-align-justify icon-32" aria-hidden="true"></div>
						</div>
					</nav><!-- #site-navigation -->
					<?php
						$defaults = array(
							'theme_location'  => 'primary',
							'container'       => 'ul',
							'container_class' => 'navigation',
							'container_id'    => 'main-menu',
							'menu_class'      => 'menu mobile container row',
						);
						?>
					<?php wp_nav_menu( $defaults ); ?>
				</div>	
			</div>	
		</div>
	</header><!-- #masthead -->
		
	<div class="clearfix"></div>
	
	<?php 
	// Revolution Slider Support
	// for front-page-rev-slider.php
	// page template
	if( klein_has_rev_slider() ){ ?>
		<div id="klein-rev-slider">
			<?php $rev_slider_id = ot_get_option( 'front_page_slider_id', '' ); ?>
			<?php putRevSlider( $rev_slider_id ); ?>
		</div>	
	<?php }?>
	<div id="main" class="container site-main">
		