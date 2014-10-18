<?php
/* utf8-marker = äöüß */
/**
 * @file category.php
 * @brief Containing News_Category class.
 * 
 * @author David Stutz
 * @license GPLv3
 * @package news
 * @see http://sourceforge.net/projects/cmsimplenews/
 * 
 *  Copyright 2012 - 2014 David Stutz
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
 * @class News_Category
 * Category class.
 * 
 * @author David Stutz
 * @since 1.1.0
 * @package news
 */
class News_Category {
	
	/**
	 * @private
	 * Gallery name.
	 * 
	 * @var <string>
	 */
	private $_name;
	
	/**
	 * @private
	 * Images.
	 * 
	 * @var <array>
	 */
	private $_entries = FALSE;
	
	/**
	 * @private
	 * Constructor for a new category.
	 * 
	 * @param <string> name
	 * @return <object> category
	 */
	public function __construct($name)
	{
		$name = preg_replace("#[\t\n\r]#", '', str_replace(' ', '-', trim($name)));
		
		$this->_name = $name;
		
		/* Check for file. */
		if (!file_exists(News::$csv . $name . '.csv'))
		{
			/* Open list CSV. */
			$list = fopen(News::$csv . $name . '.csv', "w+");
			
			/* Created list? */
			if (FALSE !== $list) 
			{
				/* Close file. */
				fclose($list);
			}
		}
			
		if (!file_exists(News::$csv . $name . '.cf'))
		{
			$this->edit(array(
				'rss_title',
				'rss_description',
				'rss_editor',
                'rss_link',
                // Empty news_link to deactivate this feature if not in blog mode.
                '',
			));
		}
	}
	
	/**
	 * @private
	 * Get category name.
	 * 
	 * @return <string> name
	 */
	public function name()
	{
		return $this->_name;
	}
	
	/**
	 * @private
	 * @static
	 * Get all categories.
	 * 
	 * @return <array> categories
	 */
	public static function categories()
	{
		$categories = array();
		
		/* Open dir. */
		$dir = dir(News::$csv);
		while (FALSE !== ($file = $dir->read()))
		{
			if (is_dir($file))
				continue;
			
			if (!preg_match('#.*\.csv$#', $file))
				continue;
			
			if (empty($file))
				continue;
			
			$categories[] = new News_Category(preg_replace('#\.csv$#', '', $file));
		}
		
        /* Sort categories. */
        usort($categories, News::$cf['categories_sort_function']);
                
		return $categories;
	}
	 
	/**
	 * @public
	 * @static
	 * Checks whether category exists.
	 * 
	 * @param <string> name.
	 * @return <boolean> exists
	 */
	public static function category_exists($name)
	{
		$name = preg_replace("#[\t\n\r]#", '', str_replace(' ', '-', trim($name)));
		return file_exists(News::$csv . $name . '.csv');
	}
	 
	/**
	 * @public
	 * Check for entry in this category.
	 * 
	 * @param <string> id
	 * @return <boolean> exist
	 */
	public function entry_exists($id)
	{
		$entries = $this->entries();
		
		foreach ($entries as $entry)
		{
			if ($entry->id() == $id)
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	 
	/**
	 * @public
	 * Gets all entries of a category.
	 * 
	 * @uses CSV::parse
	 * @return <array> entries
	 */
	public function entries() 
	{
		$array = CSV::parse(News::$csv . $this->_name . '.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
		
		$entries = array();
		foreach ($array as $content)
		{
			$entries[] = new News_Entry($content[0], $this);
		}
		
		return $entries;
	}
	
	/**
	 * @public
	 * Edit category configuration.
	 * 
	 * @uses CSV::write
	 * @param <array> config
	 */
	public function edit($array)
	{
		/* Get content. */
		CSV::write(array($array), News::$csv.$this->_name.'.cf', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
	}
	
	/**
	 * @public
	 * Get category config.
	 * 
	 * @uses CSV::parse
	 * @return <array> config
	 */
	public function config()
	{
		$array = array(
			'rss_title' => '',
			'rss_description' => '',
			'rss_editor' => '',
            'rss_link' => '',
            'news_link' => '',
		);
		
		if (file_exists(News::$csv.$this->_name.'.cf'))
		{
			$config = CSV::parse(News::$csv.$this->_name.'.cf', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
			$config = array_shift($config);
			
			$array = array(
				'rss_title' => $config[0],
				'rss_description' => $config[1],
				'rss_editor' => $config[2],
			);
            
            if (isset($config[3])) {
                $array['rss_link'] = $config[3];
            }
            else {
                $array['rss_link'] = '';
            }
            
            if (isset($config[4])) {
                $array['news_link'] = $config[4];
            }
            else {
                $array['news_link'] = '';
            }
		}
		
		return $array;
	}
	
	/**
	 * @public
	 * @static
	 * Get all entries independent of category.
	 * 
	 * @uses News_Category::categories
	 * @return <array> entries.
	 */
	public static function entries_all()
	{
		$categories = News_Category::categories();
		$entries = array();
		
		foreach ($categories as $category)
		{
			$array = $category->entries();
			$entries = array_merge($array, $entries);
		}
		
		return $entries;
	}
	
	/**
	 * @public
	 * Remove gallery.
	 * 
	 * @param <string> name
	 */
	public function remove()
	{
		/* Remove CSV file. */
		chmod(News::$csv.$this->_name.'.csv', 0777);
		unlink(News::$csv.$this->_name.'.csv');
		
		/* Remove cf file. */
		if (file_exists(News::$csv.$this->_name.'.cf'))
		{
			chmod(News::$csv.$this->_name.'.cf', 0777);
			unlink(News::$csv.$this->_name.'.cf');
		}
	}
}
?>