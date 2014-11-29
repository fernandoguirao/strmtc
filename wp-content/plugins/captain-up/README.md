# Captain Up WordPress Plugin

Captain Up is an engagement platform for your WordPress blog. Quickly add badges, levels and leaderboards to your site and start rewarding your users.

## Features

[Captain Up](http://captainup.com/) is a User Engagement platform for your WordPress site. After a quick install your users will be able to connect with Facebook or Twitter, earn points, progress through the levels, achieve badges and compete in leaderboards.

Captain Up helps you engage with your users. You will see more likes, more tweets, more comments and more repeated visits to your site. You can learn more about your users and see who is passionate about your site and who has the most influence in social networks.

You will need to associate your WordPress site with a free Captain Up account. If you don't have one yet - [sign up now](http://captainup.com/).

- **Completely Customizable Experience** - You can create new badges that can be achieved by visiting specific pages or categories on your WordPress site, or for liking your Facebook page. The appearance and conditions of each badge and level is completely customizable.

- **Works out of the Box** - With over 70 different badges and 30 levels we give your users a challenging game experience while keeping the learning curve small and keeping them entertained.

- **Deep Insights and Statistics** - Get to know your most passionate users. See who has the most followers on Twitter and who brought the most visitors to your site. Understand how users are engaging with your WordPress site and how to improve your game.

- **Widgets!** - The Activity Widget shows all the recent activities on your site and brings a sense of vibrancy, action and community to your WordPress site. The Leaderboard Widget will drive users to compete and compare with one another.

- **Viral Actions** - Players can get more points for having a large followers base on Twitter or a lot of Friends on Facebook. Badges and Levels can be shared easily with a link to your WordPress site. When other people visit your site from the players links the players get even more points!

- **Tons of Actions** - Players can earn points for visiting your site consistently, for visiting specific pages on your site, for commenting or liking on Facebook or Tweeting about you or even watching videos on your site.


### Installing the Plugin

For detailed install instructions with screenshots visit [Captain Up Wordpress Help Center](http://captainup.com/help/wordpress).

###### Automatic Install through WordPress

1. Go to the _Plugins -> Add New_ screen in your WordPress Admin Panel.

2. Search for 'CaptainUp'.

3. Click 'Install Now' and activate the plugin.

###### Manual Install through WordPress

1. Download the Captain Up plugin from the WordPress plugin directory.

2. Go to the _Plugins -> Add New_ screen in your WordPress Admin Panel and click on _upload_ tab.

3. Pick the Captain Up downloaded zip file and upload it.

4. Activate the plugin.

###### Manual Install with FTP

1. Download the Captain Up plugin from the WordPress plugin directory.

2. Extract the zip file you downloaded to a folder.

3. Upload the folder to your server, place it inside your WordPress install under `/wp-content/plugins/` directory.

4. Go to the _Plugins_ tab in your WordPress Admin Panel and activate the plugin.

###### After you Activate Captain Up

1. Go to the new _Captain Up_ tab in your WordPress Admin Panel.

2. Add your Captain Up API Key and Save. You can find your API key in the [Settings tab in your Captain Up Admin Panel](http://captainup.com/manage#settings). If you don't have a Captain Up account yet you just need to [sign up](http://captainup.com/).

Check out the [Captain Up Wordpress Help Center](http://captainup.com/help/wordpress) for more information.

## Frequently Asked Questions

###### Do I need to create an account?
Yes. In order for the plugin to work you need to [sign up to Captain Up](http://captainup.com/). It's completely free.

###### Is JavaScript required?
Yes. Captain Up will not work if JavaScript is disabled.

###### Does Captain Up work on mobile and tablet versions of my site?

Yes. When users visit your site from mobile and tablet devices they will see Captain Up in a in a device-optimized version.

###### I got more Questions!

For more information visit the [Captain Up Wordpress Help Center](http://captainup.com/help/wordpress) or [contact us](mailto:team@captainup.com).

## Shortcodes

You can add the Captain Up leaderboard widget or activity widget inside your posts using a shortcode:

* `[captain-leaderboard width="250px" height="400px" title="Leaderboard" leaderboard="all-time-ranking"]` - adds the leaderboard widget. All attributes are optional. By default the width of the widget will be 300 pixels, the height 400 pixels and the title will be "Leaderboard". The leaderboard option selects the default leaderboard to show, can be either one of `"all-time-ranking"`, `"monthly-ranking"`, `"weekly-ranking"` or `"daily-ranking"`.

* `[captain-activity width="250px" height="400px" title="Activity Feed"]` - adds the activity widget. All attributes are optional. By default the width of the widget will be 300 pixels, the height 400 pixels and the title will be "Activity".

* `[captain-sign-up text="Join the Game"]` - adds a link to join the Captain Up game. It will open the sign up modal, incentivizing your users to start playing. By default the text of the link will be "Sign Up Now".

## Contributing Code

The source code for this plugin is available on [Captain Up's GitHub account](https://github.com/CaptainUp/wordpress-captainup). Pull Requests and issues are welcome.

## Changelog

###### 2.0.2

* Support for mobile and tablet devices
* Support for WordPress 4.0
* Added an app icon
* Small code modifications

###### 2.0.1

* Support for WordPress 3.9.1

###### 2.0.0

* Brand new design!

* Customize where the Captain Up widget appears on your site, including a new bottom bar widget.

* Configure on which pages of the site Captain Up will appear on using either a blacklist or a whitelist.

* Support for WordPress 3.8.2

###### 1.4.4

* Support for custom actions.

* Updated links to the Captain Up admin panel.

* Support for WordPress 3.8.1

###### 1.4.3

* Support for WordPress 3.8

###### 1.4.2

* Support for WordPress 3.7.1

* Fixed issue with MU installs and language selection.

###### 1.4.1

* Support for WordPress 3.6.1

* Better indication of a successful install.

* Fixed issue with `cptup_widgets_edit.js`.

###### 1.4.0

* Support for WordPress 3.5.2 and 3.6.0

* You can now select the default leaderboard for the Leaderboard widget. There are four options: All Time, Monthly, Weekly and Daily. This can be set in the Widgets tab or inside the Shortcodes.

* Fixed an issue with Cloudflare's Rocket Loader.

###### 1.3.1

* Hotfix: cleared a reference to a development version of Captain Up.

###### 1.3

* Beta: Added an option to add the API Secret in the Captain Up tab. This can be used by advanced users to connect user IDs between the Captain Up platform and your site for more customized user profiles.

* Fixed redirect after submitting the settings form in the admin panel.

* Added support for WordPress comments. Users can now be rewarded with points and badges for commenting with the native WordPress comments along with Disqus and Facebook comments.

###### 1.2

* Fixed a caching bug with Internet Explorer 10

* We no longer to release new WordPress versions for additional languages

* Added French, German, Portuguese (Brazil) and Thai support.

###### 1.1

* Removed the width option from the widgets

* Added Shortcodes for adding the Leaderboard Widget, the Activity Widget and a sign up link.

* Added Localization Options (Hebrew, English, Italian, Russian)

* Added missing semicolon in the embed script;

###### 1.0
* First Release.