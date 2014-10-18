# CMSimple News

CMSimple News is a CMSimple plugin for publishing and managing news. The plugin may also be used to provide blog-like functionality.

Project page: [http://davidstutz.de/cmsimple/?News](http://davidstutz.de/cmsimple/?News).

Documentation: [http://davidstutz.de/cmsimple/plugins/news/help/help_en.htm](http://davidstutz.de/cmsimple/plugins/news/help/help_en.htm).

**Outdated** Sourceforge project: [https://sourceforge.net/projects/cmsimplenews/](https://sourceforge.net/projects/cmsimplenews/).

## Changelog

**Beta 17.**

* Bugfix concerning the blog option: [https://sourceforge.net/p/cmsimplenews/discussion/help/thread/0bf9ea05/?limit=25#c615](https://sourceforge.net/p/cmsimplenews/discussion/help/thread/0bf9ea05/?limit=25#c615)

**Beta 16.**

* News category configuration option news_link.
* Minor bug fixes.
* Update to support CMSimple XH 1.6.x.

**Beta 15.**

* Added a newsscroller based on liScroller.
* Updated ID for calling the newsscroller or newsticker to use both for the same category on the same page.

**Beta 14.**

* Updated newsticker to use multiple newsticker on one page including newsticker within templates, see Calling the newsticker in the template.

**Beta 13.**

* Updated RSS feed.
* Added category configuration for rss link.

**Beta 12.**

* Sorting of categories. See 'news_categories_sort_asc' configuration option.

**Beta 11.**

* Minor fixes.
* Added version.nfo.

**Beta 9 and 10.**

* Minor changes (mostly refactoring) and updated documentation.

**Beta 8.**

* Updated to jQuery 1.9.
* Added new call "blockquotes" which displays the entries using the blockquote html tag.
* Used jQuery files in the case jQuery4CMSimple is not installed can be configured.
* Added missing configuration help.
* Minor changes and updated documentation.

**Up to beta 7.**

* Added the 'blog' option to enable/disable the fulltext of entries.
* Added possibility to fix some publishing date in the future.
* Fixed a magic_quotes_gpc issue.
* Got rid of str_getcsv().
* Migrated from functional programming to object-oriented programming.
* Added RSS feed (rss.php).
* Added possibility of category configuration.
* Added pagination in Backend.
* Added instant publishing and taking out of entries.
* Added quick help for some pages.
* Added newsslider.
* Added news archive with pagination.
* Added newscase.
* A lot of minor changes.

## License

Copyright 2012 - 2014 David Stutz

The plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

See [http://www.gnu.org/licenses/](http://www.gnu.org/licenses/).