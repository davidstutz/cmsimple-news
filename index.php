<?php
/* utf8-marker = äöüß */
/**
 * @file index.php
 * @brief Containing the functions used by the user.
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
 
if (!class_exists('News')) require dirname(__FILE__) . "/news.php";
 
/* Include newsticker and JQuery if newsticker is used in template. */
if (strtolower($plugin_cf['news']['template_newsticker']) == 'true')
{
	/* Use JQuery plugin if possible. */
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}
		
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('newsticker', 'newsticker/js/jquery.ticker.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('newsticker', News::$plugin . 'newsticker/js/jquery.ticker.js');
	}
}
 
/* Include bxslider and JQuery if newsticker is used in template. */
if (strtolower($plugin_cf['news']['template_newsslider']) == 'true')
{
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}

	/* Use JQuery plugin if possible. */
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('bxslider', 'bxslider/js/jquery.bxSlider.min.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('bxslider', News::$plugin . 'bxslider/js/jquery.bxSlider.min.js');
	}
	
	/* Init newsticker. */
	News::init_bxslider_once();
}

/* Include liscroll and JQuery if newsticker is used in template. */
if (strtolower($plugin_cf['news']['template_newsscroller']) == 'true')
{
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}

	/* Use JQuery plugin if possible. */
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('liscroll', 'liscroll/js/jquery.liscroll.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('liscroll', News::$plugin . 'liscroll/js/jquery.liscroll.js');
	}
}

/**
 * Plugins main function to output news in frontend.
 * 
 * Usage examples
 *     news('categoryname', '-1 week'); // Shows all entries of one week ago until now.
 *     news('categoryname', '-5 days'); // Shows all entries of the last five days.
 *     news('categoryname', '-1 month'); // All entries of the last month.
 *     news('categoryname', '-3 weeks'); // All entries of the last three weeks.
 *     news('', '-13 days'); // All entries of the last 13 days in all categories.
 *     news(array('categoryname1', 'categoryname2'), '-21 days'); // All entries of categoryname1 and categoryname2 of the last 21 days
 *     news('categoryname', 5); // The last five entries, independant of their publishing dates.
 *     news('categoryname', 11); // The last 11 entries.
 *     news(array('categoryname1', 'categoryname2'), 3); // Three entries of the categories: categoryname1 and categoryname2
 * 
 * @example help_en.htm#usage-calling-plugin
 * 
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @param <string/array> category/categories
 * @param <mixed>
 * @return <string> output
 */
function news($category, $mixed = 5)  
{
	News::check_dir();
	
	/**
	 * Check if a certain entry is requested.
	 * If a entry is requested $_GET['category'] and $_GET['entry'] is set correctly.
	 */
	if (isset($_GET['entry'])
		AND isset($_GET['category'])
		AND News_Category::category_exists($_GET['category']))
	{
		$category = new News_Category($_GET['category']);
		$entry = new News_Entry($_GET['entry'], $category);
		
		if ($category->entry_exists($_GET['entry']))
		{
			return '<div class="news-entry">'
				. '<div class="news-entry-title">' . $entry->title() . '<span class="news-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>'
				. '<div class="news-entry-short">' . evaluate_scripting($entry->short()) . '</div>'
				. '<div class="news-entry-description">' . evaluate_scripting($entry->description()) . '</div>'
				. '</div>';
		}
	}
	
	/**
	 * Gets all entries from selected categories.
	 */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
	}
	
	/**
	 * Will get a given fixed number of entries.
	 */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/**
	 * Will get all entries in a certain time period.
	 */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	$o = '';
	
	/**
	 * Print output of each entry.
	 * Every property will be given own divs etc.
	 */
	foreach ($entries as $entry)
	{
		$o .= '<div class="news-entry">'
			// Bugfix: http://cmsimpleforum.com/viewtopic.php?f=16&t=5530&p=32511#p32511
			. '<div class="news-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '<span class="news-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>' 
			// . '<div class="news-entry-title">' . (News::blog() ? '<a href="' . page_url() . '&category=' . $entry->category()->name() . '&entry=' . $entry->id() . '">' . $entry->title() . '</a>' : $entry->title() ) . '<span class="news-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>'
			. '<div class="news-entry-short">' . evaluate_scripting($entry->short()) . '</div>'
			. '</div>';
	}
	
	return $o;
}

/**
 * Newsbox.
 * 
 * The newsbox shows the latest news of the given categories and references a given page where the compelte news site is referenced.
 * 
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses News::include_script
 * @global sn
 * @param <string/array> category/categories
 * @param <mixed>
 * @return <string> output
 */
function newscase($site, $category, $mixed)
{
	global $sn, $u;
	
	News::check_dir();
	
	/**
	 * Gets all entries from selected categories.
	 */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
	}
	
	/**
	 * Will get a given fixed number of entries.
	 */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/**
	 * Will get all entries in a certain time period.
	 */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	$o = '';
	
	/**
	 * Print output of each entry.
	 * Every property will be given own divs etc.
	 */
	foreach ($entries as $entry)
	{
		$o .= '<div class="newscase-entry">'
                . '<div class="newscase-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '<span class="newscase-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>'
                . '<div class="newscase-entry-short">' . evaluate_scripting($entry->short()) . '</div>'
			. '</div>';
	}
	
	return $o;
}

/**
 * jQuery based newsticker.
 * 
 * @example help_en.htm#usage-calling-newsticker
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses News::include_script
 * @uses News::init_newsticker
 * @uses include_JQuery
 * @uses include_JQueryPlugin
 * @global pth
 * @param <string/array> category/categories
 * @param <mixed>
 * @return <string> output
 */
function newsticker($category, $mixed = 5, $template = FALSE)
{
	global $pth;
	
	News::check_dir();
	
    $category_name = '';
    
	/**
	 * Gets all entries from selected categories.
	 */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
        $category_name = $category->name();
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
        
        $category_name = implode('-', $categories);
	}

	/**
	 * Will get a given fixed number of entries.
	 */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/**
	 * Will get all entries in a certain time period.
	 */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}
	
	/* Use JQuery plugin if possible. */
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('newsticker', 'newsticker/js/jquery.ticker.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('newsticker', News::$plugin . 'newsticker/js/jquery.ticker.js');
	}
	
    $o = '';
    
    $time = gettimeofday();
    $id = $category_name . '-' . $time['sec'] . $time['usec'];
    if (TRUE === $template)
    {
        $o .= News::init_newsticker($id, TRUE);
    }
    else
    {
        News::init_newsticker($id);
    }
    
	/* Create HTML. */
	/* Note: Newsticker only with IDs. */
	$o .= '<ul class="newsticker" id="' . $id . '">';
	foreach ($entries as $entry)
	{
		$o .= '<li class="newsticker-entry">'
                . '<span class="newsticker-entry-date">' . date(News::$cf['date_format'], $entry->state()) . '</span>'
                . '<span class="newsticker-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '</span>'
			. '</li>';
	}
	
	$o .= '</ul>';
	
	return $o;
}

/**
 * Newsticker/scroller using liScroller.
 * 
 * @example help_en.htm#usage-calling-newsticker
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses News::include_script
 * @uses News::init_newsticker
 * @uses include_JQuery
 * @uses include_JQueryPlugin
 * @global pth
 * @param <string/array> category/categories
 * @param <mixed>
 * @return <string> output
 */
function newsscroller($category, $mixed = 5, $template = FALSE, $title = FALSE)
{
	global $pth;
	
	News::check_dir();
	
    $category_name = '';
    
	/**
	 * Gets all entries from selected categories.
	 */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
        $category_name = $category->name();
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
        
        $category_name = implode('-', $categories);
	}

	/**
	 * Will get a given fixed number of entries.
	 */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/**
	 * Will get all entries in a certain time period.
	 */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}
	
	/* Use JQuery plugin if possible. */
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('liscroll', 'liscroll/js/jquery.liscroll.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('liscroll', News::$plugin . 'liscroll/js/jquery.liscroll.js');
	}
	
    $o = '';
    
    $time = gettimeofday();
    $id = $category_name . '-' . $time['sec'] . $time['usec'];
    if (TRUE === $template)
    {
        $o .= News::init_newsscroller($id, TRUE);
    }
    else
    {
        News::init_newsscroller($id);
    }
    
	$o .= '<div class="newsscroller-wrap">';
    
    if ($title)
    {
        $o .= '<span class="newsscroller-title">' . $title . '</span>';
    }
    
    $o .= '<ul class="newsscroller" id="' . $id . '">';
	foreach ($entries as $entry)
	{
		$o .= '<li class="newsscroller-entry">'
                . '<span class="newsscroller-entry-date">' . date(News::$cf['date_format'], $entry->state()) . '</span>'
                . '<span class="newsscroller-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '</span>'
			. '</li>';
	}
	
	$o .= '</ul></div>';
	
	return $o;
}

/**
 * News archive.
 * 
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses page_url
 * @example help_en.htm#usage-archive
 * @param <string/array> category/categories
 * @return <string> output
 */
function newsarchive($category, $title = FALSE)
{
	News::check_dir();
	
	if (!News_Category::category_exists($category))
	{
		return News::$tx["Category not found."];
	}
	
	$category = new News_Category($category);
	
	/**
	 * Check if a certain entry is requested.
	 * If a entry is requested $_GET['entry'] is set correctly.
	 */
	if (isset($_GET['entry'])
		AND $category->entry_exists($_GET['entry']))
	{
		$entry = new News_Entry($_GET['entry'], $category);
		
		return '<div class="news-entry">'
			. '<div class="news-entry-title">' . $entry->title() . '<span class="news-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>'
			. '<div class="news-entry-short">' . $entry->short() . '</div>'
			. '<div class="news-entry-description">' . $entry->description() . '</div>'
			. '</div>';
	}
	
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	
	/* Get entries. */
	$entries = $category->entries();
	usort($entries, 'news_sort_desc_created');
	
	$offset = News::$cf['pagination_archive']*($page-1);
	if ($offset < sizeof($entries))
	{
		$entries = array_slice($entries, $offset, News::$cf['pagination_archive']);
	}
	else
	{
		$entries = array_slice($entries, sizeof($entries)-(News::$cf['pagination_archive']+1), News::$cf['pagination_archive']);
	}
	
    if (FALSE === $title)
    {
        $title = ucwords(str_replace('-', ' ', $category->name()));
    }
    
	$o = '<div class="news-archive">';
	$o .= '<div class="news-archive-header">' . $title . '</div>';
	
	foreach ($entries as $entry)
	{
		/* If published. */
		if ($entry->published())
		{
			$o .= '<div class="news-entry">'
				. '<div class="news-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '<span class="news-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span></div>'
				. '<div class="news-entry-short">' . evaluate_scripting($entry->short()) . '</div>'
				. '</div>';
		}
	}
	
	if (empty($entries))
	{
		$o .= '<div class="news-entry">' . News::$tx["No entries found."] . '</div>';
	}
	
	$url = page_url();
	$url = preg_replace('#&page=\d+#', '', $url);

	$o .= '<table class="news-archive-footer">'
		. '<tr>'
		. '<td align="left" width="10%"><a href="' . $url . '&page=' . ($page-1) . '"><img src="' . News::$images . '/previous.png" /></a></td>'
		. '<td align="center" width="80%">' . $page . '</td>'
		. '<td align="right" width="10%"><a href="' . $url . '&page=' . ($page+1) . '"><img src="' . News::$images . '/next.png" /></a></td>'
		. '</tr>'
		. '</table>';

	$o .= '</div>';
	return $o;
}

/**
 * News entries using blockquotes.
 * 
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses News::include_script
 * @uses News::init_bxslider_once
 * @uses include_JQuery
 * @uses include_JQueryPlugin
 * @global pth
 * @param <string/array> category/categories
 * @param <string/int> 
 * @example help_en.htm#usage-calling-newsslider
 */
function blockquotes($category, $mixed = 5)
{
	News::check_dir();
	
	/**
	 * Gets all entries from selected categories.
	 */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
	}
	
	/**
	 * Will get a given fixed number of entries.
	 */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/**
	 * Will get all entries in a certain time period.
	 */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	$o = '';
	
	/**
	 * Print output of each entry.
	 * Every property will be given own divs etc.
	 */
	foreach ($entries as $entry)
	{
		$o .= '<blockquote class="blockquotes-entry">'
				. '<p>'
					. '<h4 class="blockquotes-entry-title">' . ($entry->has_link() ? '<a href="' . $entry->link() . '">' . $entry->title() . '</a>' : $entry->title()) . '</h4>'
					. '<div class="blockquotes-entry-short">' . evaluate_scripting($entry->short()) . '</div>'
				. '</p>'
				. '<small class="blockquotes-entry-date">' . date(News::$cf['date_format'], $entry->state()) . '</small>'
			. '</blockquote>';
	}
	
	return $o;
}

/**
 * Newsslider using bxslider.
 * 
 * @uses News::check_dir
 * @uses News_Category::category_exists
 * @uses News::include_script
 * @uses News::init_bxslider_once
 * @uses include_JQuery
 * @uses include_JQueryPlugin
 * @global pth
 * @param <string/array> category/categories
 * @param <string/int> 
 * @example help_en.htm#usage-calling-newsslider
 */
function newsslider($category, $mixed = 5)
{
	global $pth;
	
	/* Check directory. */
	News::check_dir();
	
	/* Get entries. */
	$all = array();
	if (is_string($category)
		AND !empty($category)
		AND News_Category::category_exists($category))
	{
		$category = new News_Category($category);
		$all = $category->entries();
	}
	elseif (is_array($category))
	{
		foreach ($category as $c)
		{
			if (News_Category::category_exists($c))
			{
				$c = new News_Category($c);
				$entries = $c->entries();
				$all = array_merge($all, $entries);
			}
		}
	}
	
	/* Get fixed number of entries. */
	usort($all, 'news_sort_desc_published');
	$entries = array();
	if (is_numeric($mixed))
	{
		$i = 1;
		foreach ($all as $entry)
		{
			if ($i <= $mixed
				AND $entry->published())
			{
				$entries[] = $entry;
			}
			
			$i += 1;
		}
	}
	/* Relative number of entries. */
	else
	{
		$past = strtotime($mixed, time());
		foreach ($all as $entry)
		{
			if ($entry->state() >= $past
				AND $entry->published())
			{
				$entries[] = $entry;
			}
		}
	}
	
	if (file_exists($pth['folder']['plugins'] . 'jquery/jquery.inc.php'))
	{
		include_once($pth['folder']['plugins'] . 'jquery/jquery.inc.php'); 
	}
	
	/* Use JQuery plugin if possible. */
	if(!function_exists('include_jQuery'))
	{
		/* Include JQuery and Newsticker. */
		News::include_script('jquery', 'jquery/js/' . News::$cf['jquery_core']);
		News::include_script('bxslider', 'bxslider/js/jquery.bxSlider.min.js');
	}
	else
	{
		/* Include JQuery. */
		include_jQuery();
		
		/* Inlcude newsticker. */
		include_jQueryPlugin('bxslider', News::$plugin . 'bxslider/js/jquery.bxSlider.min.js');
	}
	
	News::init_bxslider_once();
	
	/* Create HTML. */
	$o = '<ul class="newsslider">';
	foreach ($entries as $entry)
	{
		$o .= '<li class="newsslider-entry">'
			. '<div class="newsslider-entry-title">'
			. $entry->title().'<span class="newsslider-entry-title-date">' . date(News::$cf['date_format'], $entry->state()) . '</span>'
			. '</div>';
			
		$o .= '<div class="newsslider-entry-short">' . evaluate_scripting($entry->short()) . '</div>';
		
		$o .= '</li>';
	}
	$o .= '</ul>';
	
	/* Return output. */
	return $o;
}
?>