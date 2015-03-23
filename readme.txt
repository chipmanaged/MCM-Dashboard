
THIS README FILE IS STILL UNDER CONSTRUCTION
(Sending me questions would be a really helpful way to help me realize what’s still missing)

-------
General
-------
1. Place these files in the same directory as one another, along with the PHP API library
2. Open the “settings-userinfo.php” file and enter your username and key (and the folder in which you will store jpgs for each of the goals.)
3. Open the “settings-[...].php files and enter the info relevant to your goals. See below or in the files themselves for more.

-------------------------------------------------
If you want to use goal pictures in the dashboard
-------------------------------------------------
1. Create a directory somewhere (either on your server or in your public dropbox folder)
2. Replace “http://path/to/goalpics/” in settings-userinfo.php with that directory
3. Put a jpg that represents each goal in that folder with the name slug.jpg (where you replace "slug" with the actual slug of the related goal.
4. I recommend resizing the images to no larger than 450 pixels in either direction, to keep the size the page has to load down.

------------------------------------------------------
If you want to use the dashboard without goal pictures
------------------------------------------------------
1. Rename "dashboard.php" to anything you want (like "dashboard-withpics.php")
2. Rename "dashboard-nopics.php" to "dashboard.php"

-----------------------------------
To set up the groups for your goals
-----------------------------------
1. Open the “settings-groups.php”
2. in the “$groupsArray” area, list the arrays in the order you want them to be displayed using a short name (no spaces) and a title (to be displayed). Follow the template of those already there.
3. Then, create an array, for each of the groups (follow the existing example) and list the slugs that belong in each group. (A slug can be in more than one group.)

-----------------------------------------------
Goals that need at least one datapoint each day
-----------------------------------------------
1. Open the “settings-groups.php”
2. in the “$enterDaily” area, list the slugs for the goals that need a datapoint entered every day (e.g.: anything with pess. presumptive turned on)

--------------------------------------------------------------------------------------
If you want data entered into one goal to be entered into other goals at the same time
--------------------------------------------------------------------------------------
1. Open the “settings-umbrellas.php”
2. in the “$umbrellaArray” area, create an array name (no spaces) for each goal into which you want to receive datapoints from other goals, followed by the actual slug for the goal. (See example. I use "slugUA, slug” so that it's not at risk of being used elsewhere)
3. Then, create an array, for each of the groups created (follow the existing example) and list the slugs that should be sending duplicate datapoints to the above-listed goal. (A slug can be in more than one group.)
* This applies only to the points entered via the dashboard. Points entered through Beeminder.com will still only be entered in one place at a time. *

E.g.: If you want to count the number of hours you work on a particular work project (project1) in your work hours goal (work) AND in your goals for total project hours (projects), list it under both places. Whenever a datapoint is entered for project1 through the dashboard, it will also enter that datapoint in work and projects. (Note that if you have project1 under projects, and projects under work, you still have to enter project1 under work if you want its datapoints to be entered into work. It won’t take any kind of “nesting” into consideration.”)
