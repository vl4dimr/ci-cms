# What was changed from blaze

# Introduction #

These are the things that I changed from blaze.

# Changes #
  * **Loader** I just use the matchbox library to load modules
  * **views** To load a view in modules is now like the normal load view in CI
  * ~~**area** there can be more content from one or more modules in one area. In blaze one area is for one content~~
  * **theme** Themes are now put together under application/views, for example all files about the default theme is not in the default directory (statics etc...)
  * **theme** I changed also the directory structure of the theme
  * **views** Views in each module also have a slightly different structure

  * **blocks** Areas were completely removed and changed into blocks to be written in templates