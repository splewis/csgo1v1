csgo-multi-1v1
=======================================

This is home of my CS:GO multi-1v1 arena plugin. It sets up any number of players in 1v1-situations on specially made maps and they fight in a ladder-type system. The winners move up, the losers go down.

Work toward a stable 1.0.0 is underway. Note that is readme reflects the 1.0.0 development version, rather than the [latest release's readme](https://github.com/splewis/csgo-multi-1v1/blob/0.5.2/README.md).

Also see the [AlliedModders thread](https://forums.alliedmods.net/showthread.php?t=241056).

## Features
- Round types: there are 3 round types: rifle, pistol, and awp
- Player selection: players can select to allow pistol and awp rounds or ban them, rifle rounds are always allowed
- Player preference: players can also select a preference of round type, if player preferences match they will play that type
- Weapon selection: players can select their primary (i.e. their rifle) and their pistol
- Armor on pistol rounds: helmets are taken away, and kevlar is also taken away if the player selected an upgraded pistol
- ELO ranking system: optionally, player statistics can be stored in a database, see below for details

## For plugin developers
Work to make the plugin extensible is currently underway (and **not released**). For a preview, check [multi1v1.inc](scripting/include/multi1v1.inc).

Though there may seem to a lot of natives, I've found some use from almost all of them. Most were added to meet a specific need.


## Extra plugins
Sometimes it's easier to add something in a seperate plugin than add more convars, thus some features may be in support plugins. These are all optional.

- **multi1v1_flashbangs**: if both players in an arena say "yes" to getting flashbangs, a flashbang is given to each player
- **multi1v1_kniferounds**: adds unranked knife rounds
- **multi1v1_online_stats_viewer**: adds the !stats and related commands that open up a stats webpage in a MOTD panel


## Download
Stable releases are in the [GitHub Releases](https://github.com/splewis/csgo-multi-1v1/releases) section.

I **strongly** recommend using the [Updater](https://forums.alliedmods.net/showthread.php?t=169095) plugin which can automatically update the plugin for bug fixes.
Any changes made through an automatic update will be backwards compatible.


## Installation

#### Requirements

**Only Sourcemod 1.7 is supported.** Releases are compiled using the 1.7 compiler and will not work on a server using an older version.

If you must use sourcemod 1.6, you will have to use [0.5.2](https://github.com/splewis/csgo-multi-1v1/releases/tag/0.5.2).

#### Instructions
Download the archive and extract the files to the game server. From the download, you should have installed the following (to the ``csgo`` directory):
- ``addons/sourcemod/plugins/multi1v1.smx``
- ``addons/sourcemod/translations``
- ``cfg/sourcemod/multi1v1``

The file ``cfg/sourcemod/multi1v1/multi1v1.cfg`` will be autogenerated when the plugin is first run and you can tweak it if you wish.

You may also tweak the values in ``cfg/sourcemod/multi1v1/game_cvars.cfg``, which is executed by the plugin each map start.

If you are going to use a web-stats interface, you should also add the ``multi1v1_online_stats_viewer.smx`` plugin, which is under the ``plugins/disabled`` directory by default.


## Web Interface
There is a work-in-progress open-source web interface being developed under the [web](https://github.com/splewis/csgo-multi-1v1/tree/master/web) directory. Check its [readme](https://github.com/splewis/csgo-multi-1v1/blob/master/web/readme.md) for more details.


## Building
The build process is managed by my [smbuilder](https://github.com/splewis/sm-builder) project. You can still compile multi1v1.sp without it, however.

To compile, you will need:
- [SMLib](https://github.com/bcserv/smlib)
- [Updater](https://forums.alliedmods.net/showthread.php?t=169095)

You should make sure you have a relatively recent version of smlib - some changes were made to accommodate sourcemod 1.7 changes.


## Maps
I have a [workshop collection](http://steamcommunity.com/sharedfiles/filedetails/?id=249376192) of maps I know of. The "am_" prefix stands for aim_multi, reflecting the fact that the maps are similar to aim_ maps but there are multiple copies of them.

Guidelines for making a multi-1v1 map:
- Create 1 arena and test it well, and when are you happy copy it
- Create a bunch of arenas, I'd recommend making at least **16**
- The players shouldn't be able to see each other on spawn
- Each group of spawns (e.g. all CT spawns in arena 1) must be within 1600.0 units of each other, this is required to cluster spawns into the arenas and not configurable
- Ensure that the arenas are sufficiently far apart so players don't hear shooting in other arenas
- If you want to edit your map, it's easiest to delete all but 1 arena and re-copy them. Be warned this can cause issues with the game's lighting and clients may crash the first time they load the new map if they had downloaded the old one previously
- You should avoid areas where it's easy for 1 player to hide; ideally they should have to cover multiple angles if they sit in one spot
- Here is an example map: [am_grass2.vmf](https://dl.dropboxusercontent.com/u/76035852/am_grass2.zip)
- The cvar ``sm_multi1v1_verbose_spawns`` can be set to 1 to log information about how the spawns were partitioned into arenas on map changes


#### Using the statistics database
You should add a database named mult1v1 to your databases.cfg file like so:

    "multi1v1"
    {
        "driver"            "mysql"
        "host"              "123.123.123.123"   // localhost works too
        "database"          "game_servers_database"
        "user"              "mymulti1v1server"
        "pass"              "strongpassword"
        "timeout"           "10"
        "port"          "3306"  // whatever port MySQL is set up on, 3306 is default
    }

To create a MySQL user and database on the database server, you can run:

    CREATE DATABASE game_servers_database;
    CREATE USER 'mymulti1v1server'@'123.123.123.123' IDENTIFIED BY 'strongpassword';
    GRANT ALL PRIVILEGES ON game_servers_database.multi1v1_stats TO 'mymulti1v1server'@'123.123.123.123';
    FLUSH PRIVILEGES;

Make sure to change the IP, the username, and the password. You should probably change the database as well, especially if you already have one set up you can use.

Schema:

    mysql> describe multi1v1_stats;
    +--------------+-------------+------+-----+---------+-------+
    | Field        | Type        | Null | Key | Default | Extra |
    +--------------+-------------+------+-----+---------+-------+
    | accountID    | int(11)     | NO   | PRI | 0       |       |
    | serverID     | int(11)     | NO   | PRI | 0       |       |
    | auth         | varchar(64) | NO   |     |         |       |
    | name         | varchar(64) | NO   |     |         |       |
    | wins         | int(11)     | NO   |     | 0       |       |
    | losses       | int(11)     | NO   |     | 0       |       |
    | rating       | float       | NO   |     | 1500    |       |
    | lastTime     | int         | NO   |     | 0       |       |
    | recentRounds | int         | NO   |     | 0       |       |
    +--------------+-------------+------+-----+---------+-------+


Note that the ``accountID`` field is what is returned by [GetSteamAccountID](https://wiki.alliedmods.net/SourceMod_1.5.0_API_Changes#Clients), which is "the lower 32 bits of the full 64-bit Steam ID (referred to as community id by some) and is unique per account."

``auth`` is the steam ID auth string, and the ``lastTime`` field is the last time the player connected to the server.
The time comes from [GetTime](http://docs.sourcemod.net/api/index.php?fastload=show&id=601&), which returns the "number of seconds since unix epoch".

``recentRounds`` is simply incremented each time the player completes a round. This can be used, for example, to check the rounds played on a daily basis and lower ratings if a player didn't play a certain number of rounds.


## Clientprefs Usage/Cookies
Player choices (round type preferences, weapon choices) can be saved so they persist across maps for players (via the SourceMod clientprefs API). Installing SQLite should be sufficient for this to work.

If you have a game-hosting specific provider, they may already have SQLite installed


## Custom Round Types
[multi1v1.inc](scripting/include/multi1v1.inc) contains a few very useful forwards and natives for adding new round types. To get a simple example, check [multi1v1_kniferounds.sp](scripting/multi1v1_kniferounds.sp). The key is calling ``Multi1v1_AddRoundType`` within the ``Multi1v1_OnRoundTypesAdded`` forward.

```
typedef RoundTypeWeaponHandler = function void (int client);
typedef RoundTypeMenuHandler = function void (int client);

// Registers a new round type by the plugin.
native int Multi1v1_AddRoundType(const char displayName[],
                                 const char internalName[],
                                 RoundTypeWeaponHandler weaponsHandler,
                                 RoundTypeMenuHandler menuHandler,
                                 bool optional=true, bool ranked=false);
```

More advanced usage would involve passing a real function as the 4th parameter instead of ``Multi1v1_NullChoiceMenu``.
You could pass a function, for example, that lets you choose some option that goes along with the round type.
The menu-handler callback should call ``ReturnMenuControl`` once the client has finished the selection.

Note that the multi1v1 plugin will
- create and update the column for the round-type stats if you set the round type as ranked
- create and update the "allow x rounds" clientprefs cookie for you


## Contribution and Suggestions
First, check the [issue tracker](https://github.com/splewis/csgo-multi-1v1/issues?state=open) to ask questions or make a suggestion.
If you have a suggestion you can mark it as an enhancement.

Guidelines
- Create a fork on github, clone that, then create a branch to work on ``git checkout -b myfeature``
- Follow the code-style already used as much as you can
- Submit a pull request when you're happy with the new feature/enhancement/bugfix
- Favor readability and correctness over all else
