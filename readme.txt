=== World Cup Predictor ===
Contributors: ianhaycox, landoweb
Donate link: http://www.wcp.net.br/donate/
Tags: prediction, fantasy, football, competition, soccer, game, ranking
Requires at least: 2.8
Tested up to: 5.2.3
Stable tag: 1.9.6

Plugin to manage soccer predictions and present a fantasy football competition for the UEFA Champions League 2019/2020™.

== Description ==

This plugin is designed to collect users predictions for each match in UEFA Champions League 2019/2020™.

Users predict the score for each match in the group stage and knockout stage and are awarded points based on the accuracy of their prediction. Match results may also be displayed using this plugin. 

To view examples of usage, visit the website [wcp.net.br](http://www.wcp.net.br/)

**New in V1.7**

Inclusion of single pages for show Predictions by Match and Predictions of User.
New widget for show standings of Stage Groups.
Show the widget WorldCupPredictions only to logged users.
Fix the kickoff times for browsers' timezone


**New in V1.5**

Inclusion of matches, teams and locations of the 2014 FIFA World Cup Brazil™.
Ability for users to toggle match kickoff times between Brazilian and browser local time.
Manually order Group tables in the event that FIFA draw lots.
Countdown timer message can be customized to use local language conventions.
Display all users predictions for all matches with one shortcode.
Option to show/hide avatar in ranking table.
Marking a match as finished automatically updates the users prediction scores.

**Features**

* easy adding of matches, venues, results
* entry form for users to make predictions on each World Cup games
* sidebar widget to display prediction standings
* sidebar widget to display currently logged in users' predictions
* countdown timer to next prediction deadline
* display of match results and group tables
* configurable scoring system to award points to users' predictions
* toggle match times to local users browser time

**Translations**

Thank you to the following for language translations available in:-

* German. - Fred Kuhl
* French - Robert Maculewicz
* Spanish - Domingo Robaina
* Brazilian Portuguese - Ed Torres
* Dutch - Deborah Kerkhof
* Swedish - Axel
* Arabic - [Modar Soos](http://www.sada-sy.com/)
* Italian - [Giulio Giorgetti](http://www.sviluppoiphoneitalia.com/)
* Russian - Oles Lukas
* Serbian - [Marko Relic](http://marcrelic.wordpress.com/)

Other translations may be available at [Translations](http://www.wcp.net.br/translations/)

If you would like to help with translations please visit the page above and give a comment.


== Installation ==

To install the plugin, complete the following steps:

1. Unzip the zip-file and upload the content to your Wordpress Plugin directory. Usually `/wp-content/plugins`
2. Activate the plugin via the Admin plugin page.
3. Import Teams and Matches via the Admin page.
4. Create or edit a post/page and add the shortcode `[world-cup-predictor]`


For more details on configuration see [Other Notes](http://wordpress.org/extend/plugins/world-cup-predictor/other_notes/).

== Screenshots ==
1. Prediction entry form
2. Group table results display
3. Admin Screens
4. Overview
5. Predictions

== Upgrade Notice ==

The database structure has changed between V1.1 and V1.2. After upgrading to 1.2, ensure you deactivate and reactivate if the auto upgrade procedure is not used.

== Frequently Asked Questions ==

= Why are there more teams than the tournament participants? =
There are extra dummy 'placeholder' teams to fill-in the knockout stage tables. Once a team has progressed through the group
stage to the knockout stage edit the match schedule to change the placeholder team to the actual team.

= How do update match scores ? =
In the Teams and Matches tab - select the Id link in the table below, update score and check 'Match Finished'

= The countdown clock just shows 'Clock' and not a countdown =
The Javascript file (js/wcp.js) for the clock has not been included correctly. Check that there are not any Javascript
optimizations plugins that may have removed the code. Also, verify in the page source that the JS file has
been included.

= Can I change match times to my local time ? =
Yes you can, by modifying each match to your local time. Please ensure you also modify the Timezone difference setting on the Overview
screen to reflect the change with respect to your server time. However, this is not very satisfactory, as your users may be in a different timezone to you.

To allow users to toggle between Russian time and users local time, see details in the setup section.

= How do I limit the prediction form to just the Group stages ? =

Use the shortcode `[world-cup-predictor group=true]`. For more options see the usage section.

= How does the scoring system work for the knockout stages ? =

At the knockout stage if a user predicts a win e.g. 3-2 they get either the exact points if the result is 3-2 or the win points if the result is 1-0

Penalties are included in either of the above, so if a user predicts 1(5)-1(4), and result is 1(5)-1(4) then exact points, if the result is 2(5)-2(4) it’s a correctly predicted win, however if the result is 1(4)-(1)5 they get points for the goal draw. It’s a reward for getting it ‘half-right’. If you don’t like that behaviour then once all the group match results have been entered and all the predictions scored, then modify the scoring configuration to change draw point to 0 (zero).

The bonus points for correct goals and goal difference ignore penalties.

There is nothing for extra time or golden goals as such.

= Teams in the Group tables are not ordered the same as on the FIFA site =

In the Admin->Teams matches modify the Group Order field to sort the teams manually for those with equal points, matches, goal difference etc.

If at the end of the Group stage FIFA draw lots to decide the winner of a group in the event of a tie, use this option to manually arrange the group table order.

See http://www.fifa.com/confederationscup/groups/index.html

= On my French language blog dates and times are displayed in English =

How do I change the kickoff time: “Jun 22 16:00″ to “22 Juin 16:00″

The locale for dates and times is determined by the MySQL Database settings

See http://dev.mysql.com/doc/refman/5.0/en/locale-support.html

Day and month names are displayed in French if

`mysql> SELECT @@lc_time_names;`

returns fr_FR. Please see the MySQL docs above. You may need to add an appropriate setting to your wp-config.php file to set the database locale.

You may wish to consider the 'toggle timezone' feature described in the usage section to allow the users' browser to perform
the conversion.

== Usage ==

After installation, use Import button on the Overview menu of 'WCP Cup' to load the teams and match schedules.

Verify the timezone offset on this screen. Any difference should reflect the difference in time between your servers' local time and Russian time. If this setting is incorrect then users may be able to make predictions after kickoff.

The date and times of matches are in Russian local time (UTC+3)

In order to manage the teams, matches, etc. the logged user must be have the Wordpress capability `wcup_manager`. By default the plugin adds this capability to the Administrator and Editor roles. If the 'WCP Cup' menu option is not available, verify that the current user has the `wcup_manager` role. You may need to install a capability manager plugin to manage roles and capabilities.

Users of the blog do not need any special role or capability to make predictions.

Once imported you may wish to update the URL setting via the Admin->World Cup menu for each team or venue to add links to pages on your site.

= Predictions =

Create a post or page with the shortcode `[world-cup-predictor]` to display an entry form. Other shortcodes are available to display prediction results and match results. See below for more details.

Users can only predict on matches that have not yet started. Once a match has started it is removed from the entry form preventing further predictions.

= Match Results =

As each match is complete, use the Matches admin menu option to enter the goals scored and check the 'Match Finished' checkbox.
The group tables and match results displayed by the shortcodes will be updated.

Once a team is confirmed as coming first or second in the group stage, edit the Matches to change the placeholder team to the team that has qualified to the next stage.  For example, if Russia win Group A, edit Match Number 13 and change the team from 'WA' to 'Russia'.

This change is then reflected in the knockout results table.


= Options =

None as yet.

= Points Calculations =

Once a match has finished, enter the final score in the Matches tab and check the 'Match Finished' box. The displayed group tables
will be updated with the latest team standings.

To update the users' prediction, select the match in the 'Predictions' menu and click 'Score Selected'. This updates the
users' scores in the widget and on results tables.

Points are assigned according to the settings in the 'Configure Scoring' tab.

NOTE - The bonus points are cumulative (added to the scores for exact, win and draw).

Therefore be aware that both the bonus goal and bonus goal difference points amounts will always be added
to the points for a win because the goals and goal difference are the same.
The bonus goal difference points are always added to a draw because the goal difference will match.
Adjust the win and draw points appropriately if either of the bonus points are non-zero. 


= Shortcodes =

Use the following shortcodes to display a prediction form, match results, users' rankings.

**Prediction Form**

You can display an entry form for predictions and the results of matches and predictions with the following shortcodes in a post or page.

`[world-cup-predictor]`

Display an entry form for all the configured matches where the kickoff time is before the current date and time.
The form will display a countdown to the next match deadline. Once the match kickoff time has passed the match will
no longer be displayed and users cannot make predictions.

You may optionally supply the attribute `stage=n` where `n` is the stage id to limit the matches to a particular group.

To display just the matches in the group stages, not the knockout stages, use the attribute `group=true`,
e.g. `[world-cup-predictor group=true]`.

To display matches sorted by kickoff time, rather than by each stage, use the attribute `kickoff=true` and optionally a
limit, e.g. `[world-cup-predictor kickoff=true limit=5]` to display the next 5 matches.

To remove the ability to predict penalties during the knockout stages use the attribute `predict_penalties=0`.

**Group Tables**

`[world-cup-predictor tables=1 stage=n show_results=n]`

Display the group tables with the match results once the match has finished and the results have been entered.
You may optionally supply the attribute `stage=n` where `n` is the stage id to limit the matches to a particular group.
You may optionally supply the attribute `show_results=n` where `n` = 0 or 1 to hide or show the match results below the group table.

**Match Results**

`[world-cup-predictor results=1 stage=n team=n]`

Display a table of match results. You may optionally supply the attribute `stage=n` where `n` is the stage id to limit the matches to a particular group or use attribute `team=n` where `n` is the team id to limit the matches to a particular team.

**Users Predictions**

`[world-cup-predictor scores=n]`

For each match ID, specified by `n`, display a table of every users' predictions and the points awarded. Note - users predictions will not be shown
if the match kickoff time has not yet passed.  This is to prevent users viewing each others predictions before the match has started.

Use the option `[world-cup-predictor scores=-1]` to display all predictions for all matches in one table.

Optionally add the attribute `highlight="css-styles"` to add a CSS
style to the table row of the currently logged in user. For example `[world-cup-predictor scores=1 highlight="background:red;font-weight:bold"]`.

**Logged in Users Predictions**

`[world-cup-predictor user=1 show_total=n show_results=m]`

For the currently logged in user display the list of predictions for that user. If `n` is non-zero then show the total points for this user
at he foot of the table. If `m` is zero do not show match results against each prediction. Also available via the Widgets.

You may also display just the users total via `[world-cup-predictor show_total=1]` without the table.

**Rankings**

`[world-cup-predictor ranking=1 limit=999]`

Display a summary of all the users' points as a ranking table. Optionally add the attribute `highlight="css-styles"` to add a CSS
style to the table row of the currently logged in user. For example `[world-cup-predictor ranking=1 highlight="background:red;font-weight:bold"]`.

Also available via the Widgets.

= User Rankings Widget =

The widget displays similar output to the shortcode `[world-cup-predictor ranking=1 limit=9999]`

Drag and drop the widget to a sidebar and configure. Multiple instances of the widget, each configured separately, may be
placed on a sidebar.

If not blank, the URL option adds a link to the full results page using the title below as the link text.

= User Predictions Widget =

The widget displays the list of the currently logged in users' predictions similar in output to the shortcode `[world-cup-predictor user=1]`

Drag and drop the widget to a sidebar and configure.

= Toggle timezones =

All match kickoff times and dates are displayed in Brazilian local time.  If you have changed the match times in the admin screens
to another timezone, this will NOT work.

To allow a user to toggle between BRST and local time you need to add the following code in your blog post, or sidebar, that contains
one of the plugin shortcodes: For example,

`<div class="tzcContainer">
  <p id="tzLocal" style="display:none">Match times are currently set to match local time, please click here to convert to your time zone.</p>
  <p id="tzClient">Match times are currently set to <strong>your timezone</strong>, please click here to revert to local time.</p>
</div>`

The text can be changed to anything appropriate and include images etc. The key items are: `id="tzLocal"` and `id="tzClient"` with the
second item having `style="display:none"`. Users clicking on either of these sentences will toggle all match kickoff times between
BRST and browser local time. It does rely on the users' browser being configured correctly for their timezone and locale.

By default the plugin will also attempt to display match kickoff times in the users local language.

To disable this `auto-translate` feature uncheck the setting on the overview screen.


== Changelog ==

= 1.9.6 - 08th Sep 2019 =
* Include matches, teams, venues and stages of UEFA Champions League 2019/2020.
* Added option to created needed pages automatically.
* Bug Fix - Allow to use plugin with multi sites sub-directories.

= 1.9.3 - 19th May 2016 =
* Include matches, teams, venues and stages of Euro 2016.
* Remove the option to display knockout table.

= 1.9.2 - 19th June 2014 =
* Change display of Widget My Predictions.
* New widget for show total points of current user.
* Order the score of matches most recents.
* Include option to display ranking of knockout stage.
* Show the position of player in ranking page.
* Remove the option to display the author link.

= 1.9.1 - 14th June 2014 =
* Bug Fix - Remove auto update from table predictions and update the date of predictions stored in BD.

= 1.9 - 05th June 2014 =
* Bug Fix - Set a list of valid locales to lc_time_names.
* Include option to customize the match separator.
* Option to show ranking of a specific stage.
* Remove fixed width to allow layout responsive.

= 1.8 - 28th April 2014 =
* Include option to show results of a specific team.
* Remove auto update from table predictions in BD.

= 1.7 - 16th April 2014 =
* Inclusion of single pages for show Predictions by Match and Predictions by User.
* New widget for show standings of World Cup Groups.
* Show the widget WorldCupPredictions only to logged users.
* Option to adjust knockout table for the theme Twenty Fourteen.
* Fix the kickoff times for browsers' timezone

= 1.6 - 2nd April 2014 =
* Bug fix - Correction kickoff times of the matches started after 24 hours GMT.

= 1.5 - 16th January 2014 =
* Add shortcode option to show/hide avatar in ranking table.
* Make the interface clearer for updating a match to enter the final result.
* Display ranking table for all matches, not just those that have finished
* Removed blank lines around &lt;script&gt; tags to prevent over zealous themes adding &lt;p&gt; tags
* Prevent deletion of venues, teams and matches if it would leave orphaned data
* Marking a match as finished automatically updates the users prediction scores.
* Users predictions are now shown once the kickoff time has passed without requiring the match to be marked as finished.

= 1.3 - 23rd June 2010 =
* Bug fix - Allow entry of penalty scores for 0-0 full time score.

= 1.2 - 2nd June 2010 =
* Added option to display all scores via [world-cup-predictor scores=-1]
* Slightly more flexibility for locale support
* Option to display kickoff times in browsers' timezone
* Ability to manually sort Group Tables

= 1.1 - 23rd May 2010 =
* Added Swedish translation - Thanks Axel
* Corrected typo in the documentation
* Updated some language files
* Correct mispelt color name in CSS
* Added optional total to users' predictions and highlight current user in rankings
* Added option to display just total points for the current user
* Added option to sort matches by kickoff time rather than by Group
* Indicate matches already scored in Prediction menu
* Added option to show match results alongside users' predictions
* Predictions for a draw in the knockout stage now get awarded points
* Added Arabic translation - Thanks Modar Soos
* Bug Fix - Prevent user entry of Penalty Shootout unless the prediction is a Draw
* Bug Fix - Prevent administrators from entering duplicate predictions.
* Bug Fix - Show correct avatars for each user

= 1.0 - 12th May 2010 =
* Added Spanish translation - Thanks Domingo Robaina
* Added Brazilian Portuguese - Thanks Ed Torres
* Added Dutch - Thanks Deborah Kerkhof
* Removed unnecessary scoring option 'Predict a loss'
* Added new scoring option to award bonus points for correct goal difference
* Changed default prediction entry form goals 0 to space
* Bug Fix - Clear group results cache and re-calculate when modifying a team, match or venue
* Bug Fix - Fix minor HTML error in footer and table display
* Bug Fix - Report winning teams in bold consistently

= 0.4.1 - 2nd May 2010 =
* Added German translation - Thanks Fred Kuhl
* Added French Translation - Thanks Robert Maculewicz
* Fixed a couple of missing translatable strings.
* Workaround for BuddyPress plugin error - http://trac.buddypress.org/ticket/2361
* Fix for blank screen after team/match update for some users

= 0.4 - 29th Apr 2010 =
* First stable release
* Added bonus points to scoring configuration
* Modified prediction form, result tables and admin screens to manage penalties and extra time
* Added uninstall option
* Extra documentation

= 0.3 - 25th Apr 2010 =
* Match results now include extra time and penalties
* New shortcodes for match results and knockout results
* Added stadium to Venue table
* Added placeholder teams for knockout stage
* Optionally show group match results below group tables

= 0.2 - 20th Apr 2010 =
* Tidied up various screens
* Added 'Score Matches' and 'User Rankings' options.
* Added basic scoring configuration screens.
* Added extra filters to admin screens

= 0.1 - 19th Apr 2010 =
* Initial Alpha Version

[ChangeLog](http://svn.wp-plugins.org/world-cup-predictor-2014/trunk/changelog.txt)