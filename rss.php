<?php header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
/* utf8-marker = äöüß */
/**
 * @file rss.php
 * @brief File for RSS feed.
 * 
 * @author David Stutz
 * @license GPLv3
 * @package news
 * @see http://sourceforge.net/projects/cmsimplenews/
 * 
 *  Copyright 2012 - 2018 David Stutz
 * 
 * 	This file is part of the news plugin for CMSimple.
 *
 *  The plugin is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The plugin is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  @see <http://www.gnu.org/licenses/>.
 */

/**
 * Help functions.
 */
if (!function_exists('sv'))
{
	function sv($s)
	{
	    if (!isset($_SERVER))
	    {
	        global $_SERVER;
	        $_SERVER = $GLOBALS['HTTP_SERVER_VARS'];
	    }
	    if (isset($_SERVER[$s]))
		{
	        return $_SERVER[$s];
		}
	    else
		{
	        return '';
		}
	}
}

/**
 * Init required CMSimple configuration.
 */
 
$pth['folder']['base'] = '';
$pth['folder']['cmsimple'] = $pth['folder']['base'] . 'cmsimple/';

$pth['folder']['language'] = $pth['folder']['cmsimple'] . 'languages/';
$pth['folder']['langconfig'] = $pth['folder']['cmsimple'] . 'languages/';

$pth['file']['config'] = $pth['folder']['cmsimple'] . 'config.php';

if (file_exists($pth['folder']['cmsimple'].'defaultconfig.php'))
{
    include($pth['folder']['cmsimple'].'defaultconfig.php');
}
if (!include($pth['file']['config']))
{
	exit;
}

if (preg_match('/\/[A-z]{2}\/[^\/]*/', sv('PHP_SELF')))
{
    $sl = strtolower(preg_replace('/.*\/([A-z]{2})\/[^\/]*/', '\1', sv('PHP_SELF')));
}

if (!isset($sl))
{
    $sl = $cf['language']['default'];
}

/* Plugins folder. */
$pth['folder']['plugins'] = $pth['folder']['base'] . $cf['plugins']['folder'] . '/';

/* Used plugin. */
$plugin = 'news';

/* Include news config. */
require_once $pth['folder']['plugins'].$plugin.'/config/config.php';
/* Include news function file. */
require_once $pth['folder']['plugins'].$plugin.'/'.$plugin.'.php';

/* If news.php could not laoded. */
if (!class_exists('News')) exit;

/**
 * RSS Generation start.
 */

/* Get category. */
$category = isset($_GET['category'])? $_GET['category'] : FALSE;

if (!News_Category::category_exists($category))
{
	exit;
}

$category = new News_Category($category);

/* Define current link. */
$link = page_url();
$root = preg_replace('#rss\.php.*$#', '', $link);

$entries = $category->entries();
usort($entries, 'news_sort_desc_published');

$config = $category->config(); ?>

<rss version="2.0">
	<channel>
        <title><?php echo $config['rss_title']; ?></title>
		<language><?php echo $sl; ?></language>
		<description><?php echo $config['rss_description']; ?></description>
		<link><?php echo $link; ?></link>
		<managingEditor><?php echo $config['rss_editor']; ?></managingEditor>
		<category><?php echo $category->name(); ?></category>
		<lastBuildDate><?php echo date('D, l M Y G:i:s', time()); ?></lastBuildDate>
		<generator><?php echo News::name() . ' ' . News::VERSION; ?></generator>
	<?php foreach ($entries as $entry): ?>
		<item>
			<title><?php echo $entry->title(); ?></title>
			<description><?php echo $entry->short(); ?></description>
			<link><?php echo HTML::chars(preg_match('#http://#', $entry->rss_link())? $entry->rss_link() : 'http://' . $entry->rss_link()); ?></link>
			<pubDate><?php echo date('D, l M Y G:i:s', $entry->state()); ?></pubDate>
			<guid><?php echo $entry->id(); ?></guid>
		</item>
	<?php endforeach; ?>
	</channel>
</rss>
