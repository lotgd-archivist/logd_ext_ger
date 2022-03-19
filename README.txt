Legend of the Green Dragon
by Eric "MightyE" Stevens
http://www.mightye.org

Software Project Page:
http://sourceforge.net/projects/lotgd

Primary game server:
http://lotgd.net

########################
This german release is brought to you by:
anpera - http://www.anpera.net

It is translated into German and contains a lot
of modifications by anpera and others. All of them were released
by their author under the GNU GPL and so is this package.
It is the third and last release of LoGD 0.9.7+jt ext (GER).

Find more modifications on DragonPrime http://www.dragonprime.net

NOW READ THE INSTRUCTIONS...
########################

For a new installation, see INSTALLATION below.

UPGRADING to the current version:

########################
 Upgrades von früheren Versionen werden von dieser Version
 LoGD 0.9.7+jt ext (GER) 3
 NICHT unterstützt. Bitte lies im Abschnitt INSTALLATION weiter.
 (Eine Anleitung ist inzwischen im anpera.net/forum verfügbar.)
########################

The logd.sql.tar.gz tarball contains multiple SQL files.
These are organized in to upgrades from version to version named
logd-0.9.x-to-0.9.y.sql.  Locate your current version, and run
all sql scripts from that version on.  For example, if you currently
run 0.9.0, run logd-0.9.0-to-*.sql.  This will bring your current
database up to running order.

Some upgrade versions come with an logd-0.9.x-optional.sql file.  
These updates contains optional changes to the database which should
be only content changes.  Running the optional script usually means
that existing data in the database will get overwritten.  That means
that if you modified things like the taunts, weapons and armor, etc.
that you should look at the upgrade script to make sure that it is 
something you want to do.

After that has been done, extract the logd-0.9.x-php.tar.gz archive
in to a new directory, and copy all files in to your site home directory.
If you already have a dbconnect.php, you are done and everything should
work fine.  If you don't have a dbconnect.php, copy dbconnect.php.dist to
dbconnect.php and configure it as appropriate for your setup.

----------------------------------------------
-- INSTALLATION: -----------------------------
----------------------------------------------
These instructions cover a new LoGD installation.
You will need access to a MySQL database and a PHP hosting
location to run this game.  

MySQL Setup:
Setup should be pretty straightforward, create the database, create
an account to access the database on behalf of the site; this account 
should have at least "Select Table Data", "Insert Table Data", 
"Update Table Data", "Delete Table Data", I'm not sure about
"Reference Operations", and "Manage indexes", I run them on my server
but they may not be necessary.  It should be safe to run with full
permissions.

########################
Diese Version benötigt "ALL PRIVILEGES"
(Lock Table)
########################

After you have the database created, run all the sql scripts in the
logd-0.9.x-sql.tar.gz archive to create your database.

########################
Der Dateiname in dieser Version ist:
LoGD097extGER_12092004.sql
########################

PHP Setup:
Extract the files form the logd-0.9.x-php.tar.gz in to your site
home directory, or the directory from which you wish to run the game.

########################
Nachdem du das hier lesen kannst, sind die Dateien bereits entpackt.
Du kannst sofort damit beginnen, sie hochzuladen.
########################

After you have the scripts in place, copy the dbconnect.php.dist file to
dbconnect.php.  You must edit then dbconnect.php file to specify your
database username, database password, database hostname, and database
name.  You may also add any specific code to this file that is required
to customize the site for running on your system.  Once you have this file
set up, I suggest you 
   chmod -w dbconnect.php
on Linux systems (on Windows, remove write permissions from yourself)
This is to keep you from making unintentional changes to this file.

New to 0.9.6, the script no longer makes any assumptions about
magic_quotes_gpc, magic_quotes_runtime, register_globals, or error 
reporting.

Once you have set up dbconnect.php, you can log in to your new site
with the user account ADMIN with a password of CHANGEME.  You should
do what the password suggests, and change your admin password.  You
should also change the login field, and display name field to something
more suitable.  Your display name and login name should be identical
aside from coloration to prevent someone else from masquerading as you.
(This is different advice than the 0.9.1 installation instructions)
You may edit the account in the user editor found in the superuser
grotto from the village square.


----------------------------------------------
-- CHANGELOG: --------------------------------
----------------------------------------------
CHANGES in version 0.9.7 (...)
Minors:
- You will no longer be told that you have a new master when you reach 
  level 15.
- When you finish up at the Healer's hut, you'll be presented with the 
  forest navs again so you can avoid a click.
- On new days, you'll now lose all of the previous days' buffs.  For
  real this time.
- Creatures' "in graveyard" status can now be updated.
- Added a comments area in the grassy field forest special event.
- Color codes in subjects of Ye Olde Mail to which you reply will now
  be preserved.
- Getting a specialty change or race change slated for next new day
  then getting a resurrection from Ramius will now still grant you the
  partial day that you deserve.
- Added a gardens area for roleplaying.
- Added a veteran's club for those with a dragon kill under their belt.
- Transmutation potions will no longer kill you.  But they won't exactly
  leave you feeling like a million bucks either.
- Many typos cleaned up

General:
- Added indexes to a number of database fields against which searches 
  are made.  This *drastically* improves response time on heavily 
  loaded servers.  This represents a slightly higher disk space
  consumption on the database though, if disk space is at a premium
  for you, you might consider dropping these indexes.
- When you get your buffs stripped because of pvp or a master fight,
  they will be restored upon completion of the fight.
- Relatedly, the superuser "God Mode" now only lasts 1 round as this
  makes it so that there is no longer any way to knock God Mode off
  of yourself.  If you need multiple rounds of God Mode, just keep 
  hitting it.
- As you kill the dragon more times, you will require more experience
  to advance through each level.
- Tweaked the battle damage code, cleaned it up, and made it more
  closely resemble what I intended it to be (there was a mistake in 
  the calculations that I had made).
- Added OPTIMIZE TABLE to the newday script, which will run once a (real) 
  day cleaning up old deleted content from the database tables and
  defragmenting them.
- Fixed battle code so that you don't lose a round of buffs if they
  aren't being used (during surprise round)
- There is code to automatically ban someone if they fail to log in to
  the same account too many times in a row.  The ban starts out at about
  30 hours.  Server admins will be notified if the user is failing to log
  in to superuser accounts.

Buffs:
- Completely revamped the underlying buff system to provide a LOT
  of flexibility to generate new buffs on the fly with out having
  to do any code changes for new and interesting buffs.
- Horse attack is now only applied to you once per day.  The duration
  of the buff is greatly increased, and those who have lesser horses
  will get this buff as well, just for significantly shorter time
  periods.

PvP:
- Listing warriors in the field and in the inn will now permit you to
  peruse their bio, or attack them (also making it clear that you are 
  attacking them, not looking at their bio).
- Users are immune from PvP attacks for their first X (gm configurable) game
  days in LOGD or until they have gained a certain amount of experience (also
  GM configurable).  Unless they choose to attack another player, in which
  case they lose their immunity, and become a viable target for others. 
  Added a field of whether or not the player has initiated PK, so that the
  hack of setting their age to 6 days isn't needed.
- Added a bounty system with GM configurable controls on how much bounty can
  be set and how often.

Superuser Pages:
- When viewing a petition submitted by a character who was logged in,
  you now have a direct link to edit their account, and when you're done
  you should be brought back to the petition you left.  You're welcome 
  JCP.
- Petitions now display their number, seems at least one guy who I won't 
  name here wanted to be able to locate a petition whose comments he saw 
  in Recent Commentary.  You're welcome JCP.
- Navs are now categorized.  You're welcome JCP.
- Viewing source on the user editor will no longer reveal the user's 
  password.
- The words in the swear word filter can now be edited with the "Nasty
  Words List" link.

Preferences Page:
- Users can now specify a short (255 char) bio to display on their bio 
  page.  You're welcome Sweetie.

Races:
- Users can now choose a race for themselves.  You're welcome Sweetie.

Graveyard:
- It should cost less favor with Ramius to try to haunt someone.
- When getting a resurrection, your soul points and graveyard fights
  will not reset, and you subtract 100 from your favor, rather than
  setting it to 0.
- If you manage to be in the land of the shades while alive, you'll
  get booted to the village.
- Grave fights revised so that they run faster, but are still approximately
  the same overall difficulty.
- Tailored the graveyard fights and graveyard exp (favor) based on user
  level.
- Fleeing in graveyard now allowed, but it costs you some favor.
	
Dragon:
- Killing the dragon should no longer cause the game to forget your email
  address.  No, seriously.  For real this time.
- Stop laughing.  I mean it.  I tested it, and this time it really works.

Inn:
- Cedrik will allow you to change your specialty when you bribe him now,
  rather than promising that he will, and not panning out.
- C'mon now, the dragon email address thing isn't that funy any more.  
  Seriously, you can stop laughing.

Stables:
- Rewrote the stables system, and included a mount editor so that installs
  can have their own mounts.

All Pages:
- addnav() now supports a new syntax.  If the second character in the nav
  is a ?, it will try to use the first character as the hotkey for that
  nav.  If it's already taken, then it falls under the old rules, but if
  it's not found in the text at all, it'll add (K) in front of the text
  where K is the hotkey you asked for.  So if you want to default "Restore
  Your Soul" to the S key, make it "S?Restore Your Soul".  The character 
  that is hilighted IS case sensitive, but the hotkey pressed is not.  This
  lets you hilight a character deeper in the string.

CHANGES in version 0.9.6 (06-04-03)
All Pages:
- Hugely enhanced Windows compatibility, and reduced dependance on PHP
  configuration options.  This should make the game run out of the box
  on most setups.
- Introducing a site translator to the project.  If you are interested 
  in providing a translation, please send an email to trash@mightye.org
  and I'll send you what you need to build a translation.  Be advised
  that running a non-English translation represents a higher load on 
  your server as pages are translated in runtime with string replacements.
  This translator should be flexible about new content in future 
  distributions; all existing content should still be translated, and only
  new or modified content will appear in English.  Plugging in a new 
  version of the appropriate translation file will be able to capture all
  new content then.  Language releases will be separate from the main
  LoGD release.
- Anywhere there is a commentary section, all comments new since your last
  new day will have a little red and white arrow next to them to help you
  pick them out.
- Logging in after timing out should now attempt to refresh your last page
  visited, so if you were in the village square, you'll see a new copy of
  the comments there rather than getting sent directly to badnav.php.  If
  you were doing something where merely hitting refresh in your browser
  wouldn't have been a viable option (such as in a forest fight), then
  you'll get sent to badnav.php.

Forest:
- Flawless fights will now only occur when you take 0 damage over the
  course of the fight, not when you end the fight at max hitpoints.
- The darkhorse tavern will now present you with a search dialog when
  you go to learn about your enemies, this should fix the problem with
  not being able to click a name the first time through, and having to
  sort through potentially thousands of names.
	
PvP:
- When slain in PvP, the message telling you of this will now contain
  some additional information regarding your attacker (the same 
  information they would get about you from attacking you) so that you
  can more appropriately plan your revenge.
	
Masters:
- If a user has defeated their master once today, they can challenge him
  again later in the day so long as they have enough experience to do so.
- Masters who hear of young warriors who think themselves too important
  to challenge their master might get jealous and demand a fight!
  (as an admin configurable option, when users get 2x the exp required
  for a level, their master will meet them in the village and force them
  to fight.  This prevents users from getting very powerful for a level
  and harrassing other players of the same level.)

All Fights:
- Hitting 0 or below hitpoints on a ripost from your victim, THEN
  killing your victim in the same round with a riposte will no longer
  happen, if you hit 0 hitpoints before your victim, you lose.
- A few other little wierd bugs have been cleaned up.
	
LoGDNet:
- Registering servers no longer defaults to on.

Gypsy Woman:
- The dead may be cheap, but they ain't that cheap: if you have exactly
  the right amount of gold, you will be able to talk to the dead, you
  no longer require the cost +1 gold.
	
Superuser Grotto:
- The petition view now hides page details by default, and has a commentary
  area for each petition.  Also, the petition list page will show the comment
  count for each petition.
- The Ban list will show you who each ban applies to now.
- Admins can now set a maximum number of colors that can be used in any one
  comment.  Default is 10.
- New creatures will now have their author attribute set correctly, not just on
  edited creatures.
- The site now tracks offsite referers coming in, along with count, last time that
  referer was seen, and the URL.  It also groups them by site so you can see who
  your biggest fans are.
- Recent Commentary is now sorted by section.  You're welcome JCP.
- Added a stats page to let you see various tidbits of information.
	
Bank:
- You can no longer pay off your debt in the bank by having 0 gold on hand, and
  entering no value in to the "borrow more" box.
  
Ye Olde Mail:
- Hitting Delete Checked with no messages will no longer throw a SQL error.
- Addressing messages is now done in a typein format, rather than selecting
  a name form a possibly very lengthy dropdown list.
- Replying to a message with color codes in it will no longer reply with HTML 
  formatting for the color codes, but rather the original codes in `# format.
  
Graveyard:
- Added a graveyard which contains fights for dead players, allowing them to 
  gather favor with Ramius, Overlord of Death.  With enough favor, a player can
  try to haunt a foe, costing them a forest fight on the next day if they are 
  successful, or resurrect themselves to hopefully gain back some of the exp
  they lost.

Login:
- Logging in and out (or timing out) will no loger cost you your buffs.

Dragon:
- Killing the dragon will no longer cause the system to forget your email 
  address.

List Warriors:
- The list warriors page now defaults to showing you who is currently online,
  an option that was not otherwise available to logged in players until now.

Hunter's Lodge:
- Added a Hunter's Lodge designed to provide benefits to those who donate to 
  the project.  By default, this will be disabled on new servers, as you will
  have to establish how you would accept donations, and I didn't want there
  to be confusion with people sending ME a donation, and not getting rewarded
  for it on some other server.
- Within the Hunter's Lodge is an option to give credit to others for referrals,
  when a person they referred reaches level 4, the referring party will receive
  25 points in the lodge.
	
Motd:
- Added a new motd type: a poll.  Enjoy!

CHANGES in version 0.9.5 (05-07-03)
Mail:
- Added an option in the preferences page to run the swearword filter
  on messages to you.
- Added a button which checks all the checkboxes so that a mass 
  deletion is easier :-)  (dunno about the rest of you, but I get tons
  of messages in my box)

Bank:
- Added an option to transfer money to other players.  By default,
  players under level 3 with no dragon kills cannot transfer money,
  and only 25 gold per the recipient's level may be transferred.
  Also by default, only 3 transfers may be received in one game
  day.  These options are available to the admin in the game settings
  page, including the ability to turn the entire transfer feature off.

PvP:
- Finally fixed the "You have been slain in the fields" message when
  you were actually slain in the inn.  This is just in time for:
- Moved the "You have been slain in..." message to a private post 
  message so that the few times that a player might log in while
  currently being attacked, they get the message right away instead
  of the next time they log in or get a new day.
- Added a PvP flag to accounts which is actually a timestamp of the
  last time that they were attacked in PvP.  Hopefully it will no
  longer be possible 

LoGDnet:
- Introduced LoGDnet, which will allow servers to register with the
  central sourceforge server.

Special Events (forest):
- Added two new special events by Joe Naylor, a gnome who asks you
  riddles (they're tough, and there are a LOT of them), and a prince
  / princess rescue such as was found in the original LoRD.  Both
  are exceptionally well done!  Thanks Joe!!
- Added a grassy fields special event as proposed by Sean McKillion.
- Added a find gold event as proposed by Sean McKillion.
- Fixed a bug with special event inclusion in the forest where it 
  would give the user a "Return to forest" link if there were no 
  *visible* links but the user still had nav options (such as via
  a form).  Now so long as there is some nav that is allowed, the
  return to forest link will be up to the event author to add in.

Stables:
- Added upgraded stables which allow the player to buy 3 levels of
  horse, each with benefits the previous lacked.  This idea was
  Sean McKillion's, and he implemented it first.

All Pages:
- The javascript that handles key presses no longer catches alt
  or ctrl combinations so that standard browser functionality should
  be less impeded (access keys will still work though, however your
  browser deals with them).
- Introduced new skinning abilities, the default skin is the design
  by Chris Yarbrough.  Included a "Classic" skin which looks much 
  like the original design.  Future plans for this will give skinners
  more control over repeated entry items such as player stats, nav
  areas, etc.  New Skins will be quite welcome!

Land of the Shades:
- Added this area, which is a place that the dead can talk.
- Added a gypsy woman in the village who will allow you to commune
  with the dead while still alive.

FAQ:
- Added a FAQ contributed by Pegasus and FoilWench.

Character Bios:
- Added a character bio page which gives a few interesting items
  regarding a character, including recent news items for the
  player so you can look at their history.  This is linked from
  each time their name appears in commentary or in the list
  warriors pages.

List Warriors:
- Broke this page up to 50 accounts per page with links to switch
  between pages.

News:
- Broke this page up in to 50 news items per page with links to 
  switch between pages of the same day.

Superuser Grotto:
- Added a petition viewer so that admins can delegate permissions
  and multiple people can handle petitions with ease and with out
  conflict.
- Broke user listing up to 100 entries per page, with a search
  button at the top to help you easily locate a particular user.

Battle:
- Fixed several minor bugs in the battle script, mostly by including
  fixes proposed to me by Gunnar Kreitz.

CHANGES in version 0.9.4 (4-17-03)
All Pages:
- Incorporated an awesome new design by Chris Yarbrough.
- The way that game times are calculated has changed, I no longer
  simply multiply the current time by the game days per real day
  modifier, as that causes the date to integer overflow if you have
  more than 2 in that setting.  Which worked ok until you tried
  to do any further math on that value.  I now take the modulo
  of Jan 1, 1971, which is 1 year after the time stamp 0, and then
  proceed to do math, so unless you have more than about 60 game
  days per real day, you won't hit int max.  This was necessary 
  to enable the game day offset.  (hey PHP, where was my warning
  that I overflowed an integer?  You know how long I spent trying
  to figure that out? :-))
- Added a nice April Fools surprise -- Sweedish Chef talk on all
  the pages just when the server date is Apr 1.
- Added a View Source link to the bottom of each page, most pages
  can be viewed (a list of all site files is presented to the user
  here), but a few are protected (like the dbconnect.php script)
- Hilighted keys in navigations may now simply be pressed to access
  that navigattion if the browser supports it.  Such links are also
  set up with access keys in case a user does not have javascript.

Mail Page:
- Added a Ye Olde Mail box.  Users can send and receive private
  messages here.  Also, when you are victorious after someone 
  attacks you, you will now receive a message telling you that
  you won, and how much exp and gold you got out of the deal.
- Users can elect to receive a real email when they get new
  game mail, and can turn off system messages such as "You were
  victorious when you were attacked".

Preferneces Page:
- See Mail Page above for new options added here.  
- Added a prefs[] array which admins can tap in to if they wish 
  to permit users to have settings custom to their game.

Bad Nav:
- Put in logic to detect a condition that happens apparently randomly
  *ONLY* on sourceforge servers, where a user's entire page gets 
  slash-escaped like magic_quotes_gpc does.  This leaves the user
  in an unusable state.  This logic removes the slash escaping when
  there exist no ' or " marks that are not preceeded by a \.

Inn:
- Seth will no longer call men the most beautiful woman ever when
  they have very high charm.  We got him a new pair of glasses.

Superuser Grotto:
- Provided a control to allow the admin to move the time that new
  days start.
- Added config options to control user mailboxes.
- User petitions are now sent both to the game admin's email, and
  to whichever account matches that email.  If no account matches
  that email, then it sends it to all users with superuser level 3
  or higher.
- Fixed the banning system when banning someone by ID (previously
  any ID banning banned everyone).  Also enhanced the system to lock
  accounts that match a ban, even if the user's browser trying
  to log in to the account doesn't match, so if someone gets 
  their work access banned, their account will still be banned when
  they get home.

Dragon Script:
- Immediately after defeating the dragon, users are no longer set
  so that they are seen as "last on" about the time that Christ
  was born in the year 0.


CHANGES in version 0.9.3 (03/31/03)
All Pages:
- Corrected a pile of spelling mistakes pointed out by Mike Seppy.

Forest:
- Players who are under a set level no longer have an option to slum.
  This prevents an exploit allowing users to gain almost unlimited
  wealth and turns in one day by pulling off flawless victories.
- Changed the "Minimum level to allow slumming" option to instead
  mean "Minimum level to grant a new turn when slumming".

Badnav
- Hopefully addressed users getting dropped mid-redirect, which 
  caused their navs to get out of sync with their page.
- Addressed a problem that is only known to occur on Sourceforge
  servers, and even then very rarely where users' pages are somehow
  getting slash-escaped, and having & turned in to &amp;.  The code
  now checks for this condition and reverses the escaping -- not 
  flawlessly, but well enough to prevent users from getting stuck.

Superuser Grotto:
- Added buttons to fix broken navs (still happens sometimes, good 
  grief!) on user edit pages.  This just clears allowed navs and 
  on their next badnav.php view, their navs will be rebuilt based
  on links found in the output.
- Added the game option to specify the minimum level that players
  begin to be able to slum.
- Added the game option to specify whether creatures always drop
  at least 1/4 of possible gold.  Average gold dropped will remain
  the same, turning this on selects random gold from
  (possible_gold * 1/4 to possible_gold * 3/4)
  instead of
  (0 to possible_gold)
- Added 3 options to require users to enter an email address, 
  have the game verify their email address before they can 
  log in, and allow only 1 account per email address.
- Changed the way new days are calculated.  Upon installation of
  this version, more than likely all users will experience a new
  day.  Added a new day offset in the game configuration page
  which will allow you to change the time that a new day starts.

MoTD:
- MoTD's are now kept in a table in the database.  You will want
  to replace your existing motd.php file with the new one if you
  are upgrading, which is different advice than was given for
  previous releases.  The MoTD page has interfaces added to 
  facilitate the modification of MoTD's.

PvP:
- Removed a dupe bug where a user would attack a player in the 
  field that they owned, use a different browser to deposit that
  character's gold in the bank, then complete the fight, winning
  the gold from the other character, but not losing gold on that
  other character as they can't have negative gold.  Someone who
  manages to have less gold at the time their slaying is complete
  than when their slaying began will have a negative balance in
  the bank.
- Players who are killed in the inn will receive a message telling
  them so, rather than being told they were slain in the fields.

Village
- Added a link to a preferences page where users can change their
  email address and password.

Bank:
- Enabled the bank to deal with debts owed to the bank as a direct
  result of the PvP cheat.
- Added an optional borrowing feature in the bank, configured from
  the superuser grotto.

Login Page:
- Added a list of online players where character stats will later
  appear.
- Added a link to the List Warriors page so you can see rankings
  while offline.  Thanks to Sean McKillion for the idea.
- Added a forgotten password interface.

About Page:
- Added a link to the about page from the login page.
- Added a table of game settings to the bottom of the about page
  so players can see the rules under which they are operating.

CHANGES in version 0.9.2 (03/22/03)
Installation Process:
- Changed the advice about making your admin login and display name
  different from each other, you should make them identical aside
  from coloration, to prevent someone else masquerading as you.
- Introduced an logd-0.9.2-optional.sql file to replace existing
  copyrighted taunts (many belonged to the original LoRD), existing
  weapons and existing armor.  The weapon and armor upgrades include
  195 new weapons and 195 new armor, a different set for each time
  a player kills the dragon, up to 12 kills.  Running this script
  is optional, the game will run fine if you don't, but you should
  probably change the taunts as they are not in the public domain.

Superuser Grotto:
- Added configuration options to specify how long new (never logged
  on) accounts stay around, how long level 1 accounts with 0 dragon
  kills stay around, and how long all other accounts stay around.
  All counters are from last signon, so a user resets their counter
  when they sign on (aka, applies to inactive accounts).
- Added a configuration option to specify how long news and
  commentary stay around.
- Added the user idle timeout as a setting on the game settings page.
- Added an interface to edit armor and weapons.

MightyE's Weaponry:
- Each dragon kill will introduce a new set of weapons to wield.  
  They only have a different name, and do not reflect any difference
  in power.

Pegasus' Armor:
- Each dragon kill will introduce a new set of armor to wear.
  They only have a different name, and do not reflect any difference
  in power.

Character Creation:
- Repaired a bug preventing account creation.

Forest:
- Added a new special event which allows users to gain a skill level
  in their specialty in exchange for a gem.
- Attempted to increase the randomness of creatures that you encounter
  in the forest.  This may not work, as it does still rely on a rather
  unreliable rand() function in MySQL (it's not terribly random), but
  I now seed it with a random value from PHP's mt_rand() function, 
  which is about as random as I can make it (should be as random as
  possible unless MySQL's random function is not statistically 
  normal).  On some versions of MySQL, it was extremely common for
  users to encounter the same creature all 10 rounds in the forest.
  We put a lot of work in to those creatures, lets make sure they're
  encountered, eh?!?

Inn:
- Cedrik will now change your specialty for 2 gems (potion).
- If you buy a room, and log in to the game later in the day, you do
  not have to pay for the room again.
- If you logged out from the inn (you bought a room), when you log
  back in, you return to the inn (unless it's a new day, haven't
  figured out how to handle that yet).

PvP:
- Being attacked and killed in PvP now costs you 5% exp.
- Being attacked but victorious in PvP now gives you your attacker's
  gold as well as 10% of their exp.
- It is no longer possible to attack yourself when you have timed out.
  Thanks to Feron.

Login Page:
- Similar account names will no longer conflict with each other
  for logging in (why was this this way?  /boggle)  
  Thanks to Aery.

Misc:
- Users who have no specialty (such as after drinking one of 
  Cedrik's potions) and encounter an event that increments their
  specialty will have wasted the opportunity to increase their
  specialty.
- Sessions will forcibly expire when a user has been idle long enough
  to be sent to the fields.
- Taunts have been changed, running the 0.9.1 to 0.9.2 sql script will
  delete all old taunts, including custom entered taunts.
- Introduced Dragon Points.  Players earn one dragon point per dragon
  kill, and they can spend it to permanently advance their attributes
  sometimes even attributes not otherwise advancable.
- Fixed up the randomness by switching to a more substantially random
  generator in PHP, this should reduce the number of times that you 
  encounter the same creature 8 times in a row, and things like that.
  Also wrapped the random function to prevent warnings from coming up
  on older PHP versions when the min value is higher than the max
  value.
- Added refresh links to the bottom of commentary areas.
- Fights: every fight round, some damage will be dealt, no more of 
  those repeated annoying Miss/Miss rounds, particularly at low levels.
- Taunts that are selected should be a bit more random now, see the
  comment on MySQL rand() function related to the forest above.

CHANGES in version 0.9.1 (03/20/03)

Superuser Grotto:
- Added "Game Settings" page which will allow you to control many 
- Added the ability to turn off player fights
- Added the ability to grant more than 1 play day per real day 
  (game days become 24/# hours long, 2 play days per real day
  equals 12 hour game days)  New day events occur at game
  midnight.
- Added the ability to turn off "soap" cleanup of player
  commentary.

Login Page:
- Added a game clock so dead players can know how long until the
  new day occurs.

Village Square:
- Added a clock to the village square so players can know how long
  until a new day occurs.

All Fights:
- Fixed a bug that caused fight navs to be created incorrectly when
  the game is run under a subdirectory instead of site root.
- Fixed the spelling of the Thievery skill "Insulut" to be "Insult".

All Pages:
- Forced navigation is much more robust now, it should no longer
  be possible to present a user a link, and not have that link
  appear in the allowed navs list.

CHANGES in version 0.9.0 (3/15/03)

Village Square:
- Moved admin pages to the new "Superuser Grotto"

Superuser Grotto:
- Added Banning functionality to the user editor
- Added the taunt editor
- Added the ability to add new creatures in the creature editor.
- Added recent commentary page to let superusers see all commentary
  in the game in one location to be able to check for abuse easily,
  and to delete entries that they find unfavorable.

News Page:
- Added the ability for superusers to delete news items.

All Pages:
- The language filter on user comments is far less ornery.  It 
  should no longer match partial words unless specifically
  instructed to.  That'll keep words like "hello" from getting
  filtered.  Also, the language filter will now detect long
  unbroken strings, and inject a space every 45 characters to 
  keep useres from blowing out the page width.
- Users may not monopolize more than 50% of the commentary 
  in one area unless that 50% spans multiple real days.
  Users are notified when they are running out of posts for 
  the day in that area.
