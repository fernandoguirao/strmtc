<?php
/*
Plugin Name: Captain Up 
Plugin URI: http://www.captainup.com
Description: Add Game Mechanics to your site and increase your engagement and retention. 2 minutes install: Simply add your free Captain Up API Key and you are good to go. The plugin also adds widgets you can use to show leaderboards and activities within your site.
Version: 2.0.2
Author: Captain Up Team
License: GPL2
*/

// WordPress Admin Menu
// --------------------------------------------------------------------------------
function cptup_settings() {

	if (isset($_POST['submit'])) {
		
		// Mark whether this is a new install of Captain Up. We'll
		// later display different messages based on this.
		if (get_option('captain-api-key') == "") {
			update_option('captain-first-install', true);
		} else {
			update_option('captain-first-install', false);
		}

		// Save the Captain Up options on POST
		update_option('captain-api-key', $_POST['captain-api-key']);
		update_option('captain-api-secret', $_POST['captain-api-secret']);
		update_option('captain-locale', $_POST['captain-locale']);

		
		// Only update the disabled paths if they are set, to prevent us from
		// erasing the data if the input was disabled.
		if (isset($_POST['captain-disabled-paths'])) {
			update_option('captain-disabled-paths', $_POST['captain-disabled-paths']);
		}
		if (isset($_POST['captain-enabled-paths'])) {
			update_option('captain-enabled-paths', $_POST['captain-enabled-paths']);
		}

		// Save the path configuration option, whether we are using a whitelist
		// or a blacklist for the paths.
		if (isset($_POST['captain-show-paths-type']) &&
			$_POST['captain-show-paths-type'] == 'whitelist') {
			update_option('captain-show-paths-type', 'whitelist');
		} else {
			update_option('captain-show-paths-type', 'blacklist');
		}

		// Whether we should hide Captain Up on the homepage
		if (isset($_POST['captain-hide-on-homepage-checkbox']) &&
			$_POST['captain-hide-on-homepage-checkbox'] == 'Yes') {
			update_option('captain-hide-on-homepage-checkbox', 'checked');
		} else {
			update_option('captain-hide-on-homepage-checkbox', '');
		}

		// We're using JavaScript to redirect back to the page as a GET
		// request. The reason we do that in JS instead of PHP is that
		// the headers were already sent. This is a hack, like everything
		// in WordPress.
		?>
			<script>
				var url = location.href;
				url +=  ~url.indexOf('submitted=true') ? '' : '&submitted=true';
				location.replace(url);
			</script>
		<?php
	}

	// Get the Captain Up API Key
	$captain_api_key = get_option('captain-api-key');

	// Get the Captain Up API Secret
	$captain_api_secret = get_option('captain-api-secret');

	// Get the status of the radio button that controls whether we show
	// the blacklist or the whitelist as enabled.
	if (get_option('captain-show-paths-type') == 'whitelist') {
		$captain_whitelist_check = 'checked';
		$captain_blacklist_check = '';
	} else {
		$captain_whitelist_check = '';
		$captain_blacklist_check = 'checked';
	}

	// get the `hide-on-homepage-checkbox` status
	$captain_hide_on_homepage = get_option('captain-hide-on-homepage-checkbox');

	// Get the enabled and disabled paths and convert them to a stringified
	// JSON array, then post them under the `captain` JS namespace.
	$captain_disabled_paths = get_option('captain-disabled-paths');
	$captain_disabled_paths = explode(',', $captain_disabled_paths);
	$captain_disabled_paths = json_encode($captain_disabled_paths);
	$captain_enabled_paths = get_option('captain-enabled-paths');
	$captain_enabled_paths = explode(',', $captain_enabled_paths);
	$captain_enabled_paths = json_encode($captain_enabled_paths);

	?>
	<script>
	window.captain = {};
	window.captain.disabled_paths = <?php echo($captain_disabled_paths); ?>;
	window.captain.enabled_paths = <?php echo($captain_enabled_paths); ?>;
	</script>
	<?php

	// Get the Captain Up Locale
	$captain_locale = get_option('captain-locale');

	// Get the first-install status
	$captain_first_install = get_option('captain-first-install');

	// Add a message to the page, indicating that the form has
	// been submitted successfully, either (1) For enabling
	// Captain Up (2) Disabling Captain Up or (3) For changing
	// the settings.
	if (isset($_GET['submitted'])) {
		if ($captain_first_install == true && $captain_api_key != "") {
			echo "<div id='update' class='updated'><p>Rock on! Captain Up is now available on your site, <a target='_blank' href='".get_home_url()."'>go check it out ⇒</a></p></div>\n";
		} else if ($captain_api_key == "") {
			echo "<div id='update' class='updated'>".
				"<p>Captain Up has been <em>disabled</em>. If any problem ".
				"occurred or you have any questions, ".
				"<a href='mailto:team@captainup.com'>".
				"contact our support team</a></p></div>\n";
		} else {
			echo "<div id='update' class='updated'><p>Your settings have been updated, <a target='_blank' href='".get_home_url()."'>see how everything looks ⇒</a></p></div>\n";
		}
	}

	$pwd = dirname(__FILE__) . '/'; # Plugin Directory

	?>

	<div class="wrap" id="cpt-wrap">
		<div class="cpt-stripe cpt-colors">
			<img id="cpt-logo" src="<?php echo plugins_url('img/cptup_logo.png', __FILE__); ?>" />
			<h1>Captain Up - Game Mechanics</h1>
		</div>

		<div class="postbox-container ">
			<div class="metabox-holder ">	
				<div class="meta-box-sortables">
					<form action="" method="post">
						<div class="postbox cpt-colors">
							<div class="inside">
								<h2>Configure Captain Up</h2>

								<p>Copy your API key from the <a href='http://captainup.com/manage#settings' target='_blank'>Settings tab</a> in your Captain Up admin panel and paste it here. You need to <a href='http://captainup.com/users/sign_up' target='_blank'>Sign Up</a> if you don't have a Captain Up account.</p>

								<div id='cpt-api'>
									<label for='captain-api-key'>Your API Key:</label>
									<input id='captain-api-key' name='captain-api-key' type='text' size='50' value='<?php echo $captain_api_key; ?>' />
								</div>

								<script src='http://captainup.com/assets/web-available-languages.js'></script>

								<script>
									// Add all the language options, the web-available-languages
									// script loaded them into __cpt_available_languages.
									(function($) {
										// grab the selected language, default to english
										var selected_language = "<?php echo get_option('captain-locale', 'en'); ?>";
										$(function() {
											// Break execution if `web-available-languages` failed to
											// load on the page. In that odd case, only English will
											// be available for selection.
											if (! window.__cpt_available_languages) return;

											// Grab the language <select> options and empty it
											var $select = $('select#captain-locale').empty();
											// Run on all the languages
											for (code in __cpt_available_languages) {
												var lang = __cpt_available_languages[code];
												// Add an option for each language, its value
												// is the key of __cpt_available languages and
												// its text is the value of it.
												var $option = "<option value='" + code + "'";
												// Add a 'selected' attribute if needed
												if (selected_language === code) {
													$option += " selected";
												}
												$option += ">" + lang + "</option>";
												// Append it to the select box
												$select.append($option);
											}
										});
									})(jQuery);
								</script>

								<div id="cpt-language">
									<label for="captain-language">Language:</label>
									<select id='captain-locale' name='captain-locale'>
										<option value='en' selected>English</option>
									</select>
								</div>

								<div id='cpt-submit'>
									<input type="submit" class="cpt-button padded" name="submit" value="Save" />
								</div>

								<hr />

								<h2>Advanced Options</h2>
								<p>You will need to add your API Secret to enable advanced options. Copy the API Secret from the <a href='http://captainup.com/manage/settings' target='_blank'>Settings tab</a> in your Captain Up admin panel and paste it here.
								<div id='cpt-secret'>
									<label for='captain-api-secret'>API Secret:</label>
									<input id='captain-api-secret' name='captain-api-secret' type='text' size='50' value='<?php echo $captain_api_secret; ?>' />
								</div>

								<div id='cpt-submit'>
									<input type="submit" class="cpt-button padded" name="submit" value="Save" />
								</div>

								<hr />

								<h2>Choose on which pages you want Captain Up to appear</h2>

								<p class="captain-hide-on-homepage">
									<label>
										<input type="checkbox" name="captain-hide-on-homepage-checkbox" class="captain-hide-on-homepage-checkbox" value='Yes' <?php echo($captain_hide_on_homepage); ?>>
										Hide Captain Up on your Homepage
									</label>
								</p>

								<div class="captain-show-paths-box">
									<p>
										<label>
											<input type="radio" name="captain-show-paths-type" class="captain-show-paths-type" value='blacklist' <?php echo($captain_blacklist_check); ?>>
											Hide Captain Up on these URLs:
										</label>
										<input type='text' name='captain-disabled-paths' id='captain-disabled-paths' class='captain-disabled-paths'>
										<div class='cpt-help'>
										Add full links to pages you don't want captain up to show on, e.g. http://mysite.com/some-page <br />
										Use an asterisk to include subpages, e.g. http://mysite.com/guest-posts/*
										</div>
									</p>

									<span class="captain-or-rule">or</span>

									<p>
										<label>
											<input type="radio" name="captain-show-paths-type" class="captain-show-paths-type" value='whitelist' <?php echo($captain_whitelist_check); ?>>
											Show Captain Up only on these URLs:
										</label><br>
										<input type='text' name='captain-enabled-paths' id='captain-enabled-paths' class='captain-enabled-paths'>
										<div class='cpt-help'>
										Add full links to pages you want captain up to show on, e.g. http://mysite.com/some-page) <br />
										Use an asterisk to include subpages, e.g. http://mysite.com/guest-posts/*
										</div>
									</p>
								</div>


								<div id='cpt-submit'>
									<input type="submit" class="cpt-button padded" name="submit" value="Save" />
								</div>

								<hr />

								<div id='cpt-quick-links'>
									<h2>Quick Links and Support</h2>
									<div id='cpt-footer'>
										<a href='http://captainup.com/manage' target='_blank'>Dashboard</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/help' target='_blank'>Help & Support</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/manage/badges' target='_blank'>Edit Badges</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/manage/levels' target='_blank'>Edit Levels</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/manage/users' target='_blank'>View Users</a>
										<span class='cpt-sep'>|</span>

										<a href='http://captainup.com/blog' target='_blank'>Blog</a>
										<span class='cpt-sep'>|</span>

										<a href='http://twitter.com/cptup' target='_blank'>Twitter</a>
										<span class='cpt-sep'>|</span>

										<a href='mailto:team@captainup.com' target='_blank'>Contact Us</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
} 


// Add Admin Panel CSS and JS Files
// ------------------------------------------------------------------------------
function cptup_settings_files($page) {
	// I swear to god this is what Wordpress Codex suggests to do
	if ($page != "toplevel_page_cptup-config-menu") return;

	// Add the scripts
	wp_enqueue_style('cpt-css');
	wp_enqueue_script('jquery');
	wp_enqueue_script('cpt-js');
}

// Setup Admin Panel Resources
// -------------------------------------------------------------------------------
function cptup_config() {
	// Add Captain Up to the Menu
	add_menu_page('Captain Up Settings - Game Mechanics', 'Captain Up', 'manage_options', 'cptup-config-menu', 'cptup_settings');

	// Register additional files
	wp_register_style('cpt-css', plugins_url('css/captainup.css', __FILE__));
	wp_register_script('cpt-js', plugins_url('captainup.js', __FILE__), '', '1.5', true);
	add_action('admin_enqueue_scripts', 'cptup_settings_files');
}

// Add Captain Up to the main menu
add_action('admin_menu', 'cptup_config'); 



// Add the Captain Up Script to the Site
// -------------------------------------------------------------------------------

// cptup_print() initializes the Captain Up script,
// unless the API Key wasn't set yet.
function cptup_print() {
	$captain_key = get_option('captain-api-key');
	if ($captain_key != '') {
		add_action('wp_footer', 'cptup_start', 1000);
	}
}

// cptup_start() adds the Captain Up script
// asynchronously to the footer. It's only
// called from cptup_print if the API Key was
// set.
function cptup_start() {
	// Grab a reference to the API Key and Secret
	$captain_api_key = get_option('captain-api-key');
	$api_secret = get_option('captain-api-secret', false);

	// Check if the API secret is valid - 64 hexadecimal characters
	$valid_api_secret = preg_match("/^[0-9A-Fa-f]{64,64}$/", $api_secret) == 1;

	// Add a language suffix to the Embed Script. 
	// get_option's default parameter only affects values which
	// were not already set in the database. So if an empty 
	// string was saved, make sure to handle it gracefully.
	$current_lang = get_option('captain-locale', 'en');
	if (empty($current_lang)) $current_lang = 'en';
	$lang = "." . $current_lang;

	// Grab the current user ID
	$current_user_id = get_current_user_id();

	// Only hash the user id if the user is signed in and the api secret
	// is valid. Otherwise we want need it and won't add it to the script
	// configuration options below.
	if ($current_user_id != 0 && $valid_api_secret) {
		// Hash the WordPress User ID with with the API Secret using SHA512
		// We need this to connect WordPress user IDs and Captain Up IDs. The
		// $hashed_user_id makes sure the user_id is not being tampered on
		// the client side.
		$hashed_user_id = hash_hmac('sha512', $current_user_id, $api_secret);
		// Encode it as Base64
		$hashed_user_id = base64_encode($hashed_user_id);
		// remove new lines and and the '=' part at the end.
		$hashed_user_id = preg_replace('/(\n|=+\n?$)/', '', $hashed_user_id);
	}

	?>

	<div id='cptup-ready'></div> 
	<script data-cfasync='false' type='text/javascript'>
		window.captain = {up: function(fn) { captain.topics.push(fn) }, topics: []};
		captain.up({
			api_key: '<?php echo $captain_api_key; ?>',
			platform: 'wordpress',
			app: {
				<?php if($current_user_id != 0 && $valid_api_secret) { ?>
				user_id: '<?php echo $current_user_id; ?>',
				hashed_user_id: '<?php echo $hashed_user_id; ?>'
				<?php } ?>
			}
		});
	</script>
	<script data-cfasync='false' type='text/javascript'>
		(function() {
				var cpt = document.createElement('script'); cpt.type = 'text/javascript'; cpt.async = true;
				cpt.src = 'http' + (location.protocol == 'https:' ? 's' : '') + '://captainup.com/assets/embed<?php echo $lang; ?>.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(cpt);
		 })();
	</script>

	<?php
}


// Given the current `$page_path`, `is_in_path_list` goes over the paths
// in `$path_list` and determines whether that path is listed there.
// @param $page_path - {String} the URL to check
// @param $path_list - {Array} list of URLs to check against
// @return {Boolean} indicating whether the `$page_path` is on the list.
function is_in_path_list($page_path, $path_list) {

		foreach ($path_list as $path) {
				// handle the case where the URI is with the trailing wildcard '*'
				if (substr($path, -1) === "*") {
					// check if the current path starts with this path.
					// substr the disabled path to check without the asterisk *.
					if (strpos($page_path, substr($path, 0, -1)) === 0) {
							return true;
					}
				} else {
					// if it's not a wildcard, check if the disabled path is strictly
					// equal to the current path.
					if ($page_path === $path) {
							return true;
					}
				}
		}
		// if the current path wasn't found in the `$path_list` array,
		// return false.
		return false;
}


// Determine whether we should display Captain Up on the page or not, using
// either the whitelist or the blacklist (whichever is enabled).
if (get_option('captain-show-paths-type') === 'whitelist') {
	// Get the enabled paths
	$enabled_paths = explode(',', get_option('captain-enabled-paths'));
	// Check if we should display Captain Up on the current page
	$should_display = is_in_path_list($_SERVER["REQUEST_URI"], $enabled_paths);
} else {
	// Get the disabled paths
	$disabled_paths = explode(',', get_option('captain-disabled-paths'));
	// If the 'hide on homepage' checkbox was checked, add '/' to
	// the `disabled_path` array.
	if (get_option('captain-hide-on-homepage-checkbox') === 'checked') {
			$disabled_paths[] = '/';
	}
	// Check if we should display Captain Up on the current page
	$should_display = !is_in_path_list($_SERVER["REQUEST_URI"], $disabled_paths);
}

// Add the Captain Up script unless (1) we're inside the admin panel;
// (2) the page is on the blacklist (3) the page is not on the whitelist.
if (!is_admin() && $should_display) {
	add_action('wp_print_scripts', 'cptup_print');
}


// Widgets
// ---------------------------------------------------------------------------

// Enqueue scripts to handle editing the Widgets options in
# the widgets admin panel tab.
function widgets_edit_script($hook) {
	// Only enqueue the script in the widgets tab
	if('widgets.php' != $hook) return;

	wp_enqueue_script(
		'cptup_widgets_edit',
		plugins_url('/js/cptup_widgets_edit.js', __FILE__),
		array('jquery')
	);
}
add_action('admin_enqueue_scripts', 'widgets_edit_script');


class Captainup_Widget extends WP_Widget {

	/** constructor */
	function __construct() {
		parent::WP_Widget('cptup_widget', 'Captain Up Widget', array(
			'description' => 'Captain Up Leaderboards and Recent Activity'
		));
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract($args);
		$type = $instance['type'];
		$height = $instance['height'];
		$default_leaderboard = $instance['default_leaderboard'];
		
		echo $before_widget;
		?>

		<div class='captain-<?php echo $type; ?>-widget' <?php if($type=='leaderboard') echo 'data-cpt-leaderboard='.$default_leaderboard ?> style='width: auto; height: <?php echo $height; ?>px; display: none;'>
		</div>
		
		<?php 
		echo $after_widget;
	}

	/** @see WP_Widget::update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['type']   = strip_tags($new_instance['type']);
		$instance['css']    = strip_tags($new_instance['css']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['default_leaderboard'] = strip_tags($new_instance['default_leaderboard']);
		return $instance;
	}

	/** @see WP_Widget::form */
	function form( $instance ) {
		$type   = esc_attr($instance['type']);
		$css    = esc_attr($instance['css']);
		$height = esc_attr($instance['height']);

		if (!isset($type)) $type = 'leaderboard';
		if (!isset($css)) $css = 'height: 300px; margin-top: 20px;';
		if (!isset($height)) $height = '350';
		if (!isset($default_leaderboard)) $default_leaderboard = 'monthly_ranking';
		
		?>

		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>">
				<?php _e('Widget type'); ?>
			</label>
			<select id="<?php echo $this->get_field_id('type'); ?>" class="cpt-widget-type-select" name="<?php echo $this->get_field_name('type'); ?>">

				<option <?php if($type == "activity") { echo "selected"; }; ?> value="activity">
					Activity Widget
				</option>
				<option <?php if($type == "leaderboard") { echo "selected"; }; ?> value="leaderboard">
					Leaderboard Widget
				</option>

			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>">
				<?php _e('Height:'); ?>
			</label> 
			<input size="4" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />px
		</p>

		<p class='cpt-select-leaderboard-type'>
			<label for="<?php echo $this->get_field_id('default_leaderboard'); ?>">
				<?php _e('Default view:') ?>
			</label>

			<select id="<?php echo $this->get_field_id('default_leaderboard'); ?>" name="<?php echo $this->get_field_name('default_leaderboard'); ?>">
				
				<option value="all-time-ranking" <?php if($default_leaderboard == 'all-time-ranking') echo 'selected'?>>
					All Time
				</option>

				<option value="monthly-ranking" <?php if($default_leaderboard == 'monthly-ranking') echo 'selected'?>>
					Monthly
				</option>

				<option value="weekly-ranking" <?php if($default_leaderboard == 'weekly-ranking') echo 'selected'?>>
					Weekly
				</option>

				<option value="daily-ranking" <?php if($default_leaderboard == 'daily-ranking') echo 'selected'?>>
					Daily
				</option>

			</select>
		</p>

		<script>
			jQuery(function() {
				// Run over all the Captain Up widgets and call the
				// `toggle_leaderboard_option` on them. The function is
				// defined under `cptup_widgets_edit.js`. We call it here as
				// well as the widget form gets re-rendered every time it is
				// saved.
				jQuery('.cpt-widget-type-select').each(function(index, element) {
					toggle_leaderboard_option(element);
				});
			});
		</script>
		
		<?php
	}
}

// Initialize the Widget
add_action('widgets_init', create_function('', 'register_widget("CaptainUp_Widget");') );


// Shortcodes
// -------------------------------------------------------------------------------

// Leaderboard Widget Shortcode
// [captain-leaderboard width="300" height="400" title="Hello" leaderboard="all-time-ranking"]
// Options:
// - width - css attribute. by default 300px
// - height - css attribute. by default 500px
// - leaderboard - the default ranking view (all time, monthly, weekly, daily),
//                 by default set to the monthly leaderboard.
// - title - the title of the widget, by default 'Leaderboard'
//           in the current locale language.
function captain_leaderboard_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'width' => '300',
			'height' => '500',
			'title' => false,
			'leaderboard' => 'monthly-ranking'
		), $atts
	));
	return "<div style='margin: 20px auto; width: $width"."px; height: $height"."px;' class='captain-leaderboard-widget' data-cpt-leaderboard='" . str_replace("-", "_", $leaderboard) . "' data-cpt-title='$title'></div>";
}
add_shortcode('captain-leaderboard', 'captain_leaderboard_shortcode' );

// Activity Widget Shortcode
// [captain-activitiy width="500" height="400" title="Hello"]
// Options:
// - width - css attribute. by default 300px
// - height - css attribute. by default 500px
// - title - the title of the widget, by default 'Activities'
//           in the current locale language
function captain_activity_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'width' => '300',
			'height' => '500',
			'title' => false
		), $atts
	));
	return "<div style='margin: 20px auto; width: $width"."px; height: $height"."px;' class='captain-activity-widget' data-cpt-title='$title'></div>";
}
add_shortcode('captain-activity', 'captain_activity_shortcode' );

// Sign Up Link Shortcode
// [captain-sign-up text="Hello"]
// Options:
// - text - the text of the link, by default "Sign Up Now"
function captain_sign_up_link_shortcode($atts) {
	extract(shortcode_atts(
		array(
			'text' => 'Sign Up Now',
		), $atts
	));
	return "<a style='cursor: pointer' class='captain-sign-up-link'>$text</a>";
}
add_shortcode('captain-sign-up', 'captain_sign_up_link_shortcode' );


// WordPress Comments Integration
// -----------------------------------------------------------------------------
// 
// The flow for detecting a new wordpress comment and sending
// to Captain Up goes like this: First, we add an action hook to
// `comment_post` that notifies us when comments are saved to the
// database. Then, if the comment was approved, we create a cookie
// called `wp_cpt_new_comment`. We do this since after a comment
// is POSTed wordpress redirects back to the post. It's like a bad
// man's flash messaging. We check if this cookie exists on every
// request. If it does, we remove it, hook to the <head> element
// and add a JavaScript variable there called `_cpt_wordpress_events`
// that stores the new comment event. The Captain Up embed code
// does the rest of the work.
//
// NOTE: This process -will- change and will be moved to a server-side
// flow.

// Setup a hook to get a notification after a new comment has been posted.
add_action('comment_post', 'captain_mark_new_comment', 10, 2);

// `captain_mark_new_comment` is called from the `comment_post` wordpress
// hook. It receives $comment_id and the $approval status of the comment,
// and stores a cookie telling us in the follow up request (after the
// redirection) that a comment was created.
function captain_mark_new_comment($comment_id, $approval) {
	// $approval can either be 'spam', 0 for disapproved or 1 for approved.
	// We give points for approved and disapproved (held for moderation)
	// comments but not for spam.
	if ($approval == 1 || $approval == 0) {
		// we need to mark this in a cookie since wordpress has no built-in
		// session or flash support and after a comment is posted wordpress
		// redirects the user.
		setcookie("wp_cpt_new_comment", $comment_id, time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
	}
}

// `captain_add_new_comment` adds a new JS snippet to the page with
// the `_cpt_wordpress_events` variable. The Captain Up embed picks
// this up later and then syncs the new comment action to our servers.
function captain_add_new_comment() {
	?>
	<script data-cfasync='false' type='text/javascript'>
		window._cpt_wordpress_events = {
			new_comment: true
		};
	</script>
	<?php
}

// On every request, check if the new comment cookie is set
if (isset($_COOKIE['wp_cpt_new_comment'])) {
	// Clean the Cookie
	setcookie("wp_cpt_new_comment", "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
	// hook into the <head> of the page to insert our JS snippet
	// that tells the Captain Up embed a new comment was created.
	add_action('wp_head', 'captain_add_new_comment');
}

?>