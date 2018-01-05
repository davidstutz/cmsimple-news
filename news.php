<?php
/* utf8-marker = äöüß */
/**
 * @file news.php
 * @brief Containing class News, including News_Category, News_Entry, calling News::init().
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
/*! \mainpage CMSimple News Plugin
 *
 * This plugin simplifies publishing news on your CMSimple Website. News can be published in different categories.
 *
 * This is  a generated documentation of the plugin.
 * 
 * \mainpage
 */
 
 
/* Require CSV. */
if (!class_exists('CSV', FALSE)) require_once dirname(__FILE__).'/helper/csv.php';
 
/* Require HTML. */
if (!class_exists('HTML', FALSE)) require_once dirname(__FILE__).'/helper/html.php';
 
/* Require gallery class. */
if (!class_exists('News_Category', FALSE)) require_once dirname(__FILE__).'/news/category.php';

/* Require image class. */
if (!class_exists('News_Entry', FALSE)) require_once dirname(__FILE__).'/news/entry.php';

/* Init. */
News::init();

if (!function_exists('page_url'))
{
	/**
	 * Detect root.
	 * 
	 * @return <string> root
	 */
	function page_url()
	{
		$url = 'http';
		if (isset($_SERVER["HTTPS"])
			AND $_SERVER["HTTPS"] == "on")
		{
			$url .= "s";
		}
		$url .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}
		else
		{
			$url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $url;
	}
}

/**
 * @class News
 * 
 * Main news class for general functionality.
 * Provides plugin's configuration, translation and all paths needed and methods to include and init used JQuery plugins.
 * 
 * @author David Stutz
 * @since 1.1.0
 * @package news
 */
class News {
	
	/**
	 * Version.
	 */
	const VERSION = '1.1.2';
	
	/**
	 * @static
	 * @public
	 * Plugin config.
	 */
	public static $cf;
	
	/**
	 * @static
	 * @public
	 * Plugin translation.
	 */
	public static $tx;
	
	/** 
	 * @static
	 * @public
	 * Images path.
	 */
	public static $images;
	
	/**
	 * @static
	 * @public
	 * CSV path.
	 */
	public static $csv;
	
	/**
	 * @static
	 * @public
	 * RSS file.
	 */
	public static $rss;
	
	/**
	 * @static
	 * @public
	 * Plugin path.
	 */
	public static $plugin;
	
	/**
	 * @static
	 * @public
	 * Init plugin. Loads Plugin translation and configuration, sets all needed paths.
	 * 
	 * @global pth
	 * @global plugin
	 * @global plugin_cf
	 * @global plugin_tx
	 */
	public static function init()
	{
		/* Globals. */
		global $pth,$plugin,$plugin_cf,$plugin_tx;
		$plugin = basename(dirname(__FILE__),"/");
		
		News::$cf = $plugin_cf[$plugin];
		News::$tx = $plugin_tx[$plugin];
		News::$csv = $pth['folder']['base'] . News::$cf['csv_filepath'] . '/';
		News::$plugin = $pth['folder']['plugins'] . $plugin . '/';
		News::$rss = $pth['folder']['base'] . News::$cf['rss_file'];
		News::$images = $pth['folder']['plugins'] . $plugin . '/images/';
	}
	
	/**
	 * @static
	 * @public
	 * Get plugin's name.
	 * 
	 * @return <string> name
	 */
	public static function name()
	{
		return "News Plugin";
	}

	/**
	 * @static
	 * @public
	 * Get plugin's release date.
	 * 
	 * @return <string> release date.
	 */
	public static function release_date() 
	{
	   return "January 6th 2018";
	}

	/**
	 * @static
	 * @public
	 * Get plugin's author.
	 * 
	 * @return <string> author.
	 */
	public static function author()
	{
		return "David Stutz";
	}
	
	/**
	 * @static
	 * @public
	 * Get plugin's website.
	 * 
	 * @return <string> website link
	 */
	public static function website()
	{
		return '<a href="http://davidstutz.de/cmsimple/?News" target="_blank">Project Webpage</a>';
	}
	
        /**
         * @public
         * @static
         * Get plugin's GitHub repo.
         * 
         * @return <string> GitHub link
         */
        public static function github()
        {
		return '<a target="_blank" href="https://github.com/davidstutz/cmsimple-news" target="_blank">GitHub Project</a>';
        }
        
	/**
	 * @static
	 * @public
	 * Get plugin's description.
	 * 
	 * @return <string> description
	 */
	public static function description()
	{
		return 'This is a simple news module. News can be published in different categories, which can be shown in seperate newsboxes or merged in one.';
	}
	
	/**
	 * @static
	 * @public
	 * Get plugin's legal.
	 * 
	 * @return <string> legal
	 */
	public static function legal()
	{
		return 'This plugin is published under the GNU Public License version 3. See <a href="http://www.gnu.org/licenses/">Licenses</a> for more information.';
	}

	/**
	 * @static
	 * @public
	 * Determines if the current plugin is runnign as blog version.
	 * 
	 * @return <boolean> blog version
	 */
	public static function blog()
	{
		return strtolower(News::$cf['blog']) == 'true';
	}

	/**
	 * @static
	 * @public
	 * Checks plugin data dir specified by 'csv_filepath' configuration key.
	 * 
	 * The method will automatically create the directory and make chmod() if possible.
	 */
	public static function check_dir()
	{
		if (!is_dir(News::$csv))
		{
			mkdir(News::$csv, 0777, TRUE);
		}
		
		/* Chmod. */
		chmod(News::$csv, 0777);
		
		/* Open dir. */
		$dir = dir(News::$csv);
		if (is_object($dir))
		{
			while (FALSE !== ($file = $dir->read()))
			{
				if (is_dir(News::$csv . $file))
					continue;
				
				// if (empty($file))
				// {
				//	@unlink(News::$csv . $file);
				// }
				
				@chmod(News::$csv . $file, 0777);
			}
		}
	}
	
	/**
	 * @static
	 * @public
	 * Init newsticker JQuery plugin for the given id.
	 * 
     * @param <string> id
     * @param <boolean> template
	 * @global hjs
	 */
	public static function init_newsticker($id, $template = FALSE)
	{
		global $hjs;
		
        $script = '<script type="text/javascript">
                $(document).ready(function(){
                    $("#' . $id . '").ticker({
                        speed: ' . (empty(News::$cf['newsticker_speed'])? 0.1 : News::$cf['newsticker_speed']) . ',
                        debugMode: false,
                        direction: "ltr",
                        controls: ' . (empty(News::$cf['newsticker_controls'])? 'false' : News::$cf['newsticker_controls']) . ',
                        titleText: "' . (empty(News::$cf['newsticker_title_text'])? News::$tx['Latest'] : News::$cf['newsticker_title_text']) . '",
                        displayType: "' . (empty(News::$cf['newsticker_effect'])? 'reveal' : News::$cf['newsticker_effect']) . '",
                        pauseOnItems: ' . (empty(News::$cf['newsticker_delay'])? 2000 : (int)News::$cf['newsticker_delay']) . ',
                        fadeInSpeed: ' . (empty(News::$cf['newsticker_fade_in_speed'])? 600 : (int)News::$cf['newsticker_fade_in_speed']) . ',
                        fadeOutSpeed: ' . (empty(News::$cf['newsticker_fade_out_speed'])? 300 : (int)News::$cf['newsticker_fade_out_speed']) . ',
                    });
                });
            </script>';
        
        if (TRUE === $template)
        {
            return $script;
        }
        else
        {
            $hjs .= $script;
        }
        
	}

    /**
	 * @static
	 * @public
	 * Init liScroll jQuery plugin.
	 * 
     * @param <string> id
     * @param <boolean> template
	 * @global hjs
	 */
	public static function init_newsscroller($id, $template = FALSE)
	{
		global $hjs;
		
        $script = '<script type="text/javascript">
                $(document).ready(function(){
                    $("#' . $id . '").liScroll({
                        travelocity: ' . (empty(News::$cf['newsscroller_speed'])? '0.07' : News::$cf['newsscroller_speed']) . '
                    });
                });
            </script>';
        
        if (TRUE === $template)
        {
            return $script;
        }
        else
        {
            $hjs .= $script;
        }
        
	}
    
	/**
	 * @static
	 * @public
	 * Init bxslider JQuery plugin once.
	 * 
	 * @global hjs
	 */
	public static function init_bxslider_once()
	{
		global $hjs;
		
		static $newsslider_init = FALSE;
		if (!$newsslider_init)
		{
			/* Init coinslider. */
			$hjs .= '<script type="text/javascript">
						$(document).ready(function(){
							$(".newsslider").css({
								"padding": 0,
								"margin": 0,
							});
							$(".newsslider li").css({
								"margin": 0,
							});
							$(".newsslider").bxSlider({
								mode: "horizontal",
								ticker: true,
								tickerSpeed: ' . (empty(News::$cf['newsslider_speed'])? 5000 : (int)News::$cf['newsslider_speed']) . ',
								tickerHover: ' . (empty(News::$cf['newsslider_pause_hover'])? 'true' : News::$cf['newsslider_pause_hover']) . ',
							});
						});
					</script>';
					
			$newsslider_init = TRUE;
		}
	}

	/**
	 * @static
	 * @public
	 * Include a script once.
	 * 
	 * @param <string> name
	 * @param <string> path relative to this plugin
	 * @global hjs
	 */
	public static function include_script($name, $path)
	{
		global $hjs;
			
		static $scripts_included = array();
		if (FALSE === array_search($name, $scripts_included))
		{
			$hjs .= '<script src="' . News::$plugin . $path . '" type="text/javascript"></script>';
			$scripts_included[] = $name;
		}
	}
	
	/**
	 * @public
	 * @static
	 * Include a style once.
	 * 
	 * @param <string> name
	 * @param <string> path relative to this plugin
	 * @global hjs
	 */
	public static function include_style($name, $path, $media = 'screen')
	{
		global $hjs;
			
		static $styles_included = array();
		if (FALSE === array_search($name, $styles_included))
		{
			$hjs .= '<link rel="stylesheet" href="' . News::$plugin . $path . '" media="' . $media . '" type="text/css" />';
			$styles_included[] = $name;
		}
	}
	
	/**
	 * @static
	 * @public
	 * Determine if RSS is supported.
	 * 
	 * @return <boolean> rss
	 */
	public static function rss()
	{
		return file_exists(News::$rss);
	}
}

if (!function_exists('news_sort_asc_created'))
{
	/**
	 * Function to order news asc by their date. 
	 * 
	 * @param array a
	 * @param array b
	 * 
	 * @return <int> order
	 */
	function news_sort_asc_created($a, $b)
	{
	    if ($a->id() == $b->id())
		{
	        return 0;
	    }
	    return ($a->id() < $b->id()) ? -1 : 1;
	}
}

if (!function_exists('news_sort_asc_published'))
{
	/**
	 * Function to order news desc by their date. 
	 * 
	 * @param array a
	 * @param array b
	 * 
	 * @return <int> order
	 */
	function news_sort_asc_published($a, $b)
	{
	    if ($a->state() == $b->state())
		{
	        return 0;
	    }
	    return ($a->state() < $b->state()) ? -1 : 1;
	}
}

if (!function_exists('news_sort_desc_created'))
{
	/**
	 * Function to order news asc by their date. 
	 * 
	 * @param array a
	 * @param array b
	 * 
	 * @return <int> order
	 */
	function news_sort_desc_created($a, $b)
	{
	    if ($a->id() == $b->id())
		{
	        return 0;
	    }
	    return ($a->id() < $b->id()) ? 1 : -1;
	}
}

if (!function_exists('news_sort_desc_published'))
{
	/**
	 * Function to order news desc by their date. 
	 * 
	 * @param array a
	 * @param array b
	 * 
	 * @return <int> order
	 */
	function news_sort_desc_published($a, $b)
	{
	    if ($a->state() == $b->state())
		{
	        return 0;
	    }
	    return ($a->state() < $b->state()) ? 1 : -1;
	}
}

if (!function_exists('news_categories_sort_asc'))
{
    /**
     * Function to order galleries asc by their names.
     * 
     * @param gallery a
     * @param gallery b
     * 
     * @return <int> order
     */
    function news_categories_sort_asc($a, $b)
    {
        if ($a->name() == $b->name())
        {
            return 0;
        }
        return strcmp($a->name(), $b->name()) < 0 ? -1 : 1;
    }
}

if (!function_exists('news_categories_sort_desc'))
{
    /**
     * Function to order galleries desc by their names.
     * 
     * @param gallery a
     * @param gallery b
     * 
     * @return <int> order
     */
    function news_categories_sort_desc($a, $b)
    {
        if ($a->name() == $b->name())
        {
            return 0;
        }
        return strcmp($a->name(), $b->name()) < 0 ? 1 : -1;
    }
}
?>