<?php
/* utf8-marker = äöüß */
/**
 * @file entry.php
 * @brief Containing class News_Entry.
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
 * @class News_Entry
 * @public
 * 
 * Entry class.
 * 
 * @author David Stutz
 * @since 1.2.0
 * @package news
 */
class News_Entry {
	
	/**
	 * @private
	 * ID.
	 * 
	 * @var <double>
	 */
	private $_id;
	
	/**
	 * @private
	 * Title.
	 * 
	 * @var <string>
	 */
	private $_title;
	
	/**
	 * @private
	 * Description.
	 * 
	 * @var <string>
	 */
	private $_description;
	
	/**
	 * @private
	 * Short.
	 * 
	 * @var <string>
	 */
	private $_short;
	
	/**
	 * @private
	 * State.
	 * 
	 * @var <double>
	 */
	private $_state;
	
	/**
	 * @private
	 * Category.
	 * 
	 * @var <object>
	 */
	private $_category;
	
	/**
	 * @public
	 * Constructs a new entry.
	 * 
	 * @param <string> id
	 * @param <object> category
	 * @return <object> entry
	 */
	public function __construct($id, $category)
	{
		/* Get content of category. */
		$this->_category = $category;
		$this->_id = (double)$id;
		
		$array = CSV::parse(News::$csv.$this->_category->name().'.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
		foreach ($array as $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				$this->_title = $content[1];
				$this->_description = $content[2];
				$this->_short = $content[3];
				$this->_state = $content[4];
			}
		}
	}
	
	/**
	 * @public
	 * Getter for ID.
	 * 
	 * @return <integer> id
	 */
	public function id()
	{
		return $this->_id;
	}
	
	/**
	 * @public
	 * Getter and setter for title.
	 * 
	 * @param <string> title
	 * @return <string> title
	 */
	public function title($title = FALSE)
	{
		if ($title === FALSE)
		{
			return $this->_title;
		}
		else
		{
			$this->_title = HTML::chars($title, ENT_QUOTES);
		}
	}
	
	/**
	 * @public
	 * Getter and setter for description.
	 * 
	 * @param <string> description
	 * @return <string> description
	 */
	public function description($description = FALSE)
	{
		if ($description === FALSE)
		{
			return HTML::decode_entities($this->_description);
		}
		else
		{
			$this->_description = preg_replace("#[\n\r\t]#", "", HTML::chars($description, ENT_QUOTES));
		}
	}
	
	/**
	 * @public
	 * Getter and setter for short.
	 * 
	 * @param <string> short
	 * @return <string> short
	 */
	public function short($short = FALSE)
	{
		if ($short === FALSE)
		{
			return HTML::decode_entities($this->_short);
		}
		else
		{
			$this->_short = preg_replace("#[\n\r\t]#", "", HTML::chars($short, ENT_QUOTES));
		}
	}
	
	/**
	 * @public
	 * Getter and setter for state.
	 * 
	 * @param <integer> state
	 * @return <integer> state
	 */
	public function state($state = FALSE)
	{
		if ($state === FALSE)
		{
			return $this->_state;
		}
		else
		{
			$this->_state = (double)$state;
		}
	}
	
	/**
	 * @public
	 * Getter for category.
	 * 
	 * @return <object> category
	 */
	public function category()
	{
		return  $this->_category;
	}
	
	/**
	 * @public
	 * Detemernies if entry is published.
	 * 
	 * @return <boolean> published
	 */
	public function published()
	{
		return $this->_state <= (strtotime('today') + 86399) AND $this->_state != 0;
	}
	
    /**
     * @public
     * Determine the link to this entry to use within the RSS feed.
     * 
     * If in blog mode the link to the news page will be set in category configuration and the link to to the whole article will be returned.
     * If not in blog mode the user can set an arbitrary link within the category configuration (for example to the news page).
     * 
     * If the configuration option is empty the root page will be returned.
     * 
     * @return <string> link
     */
    public function rss_link()
    {
        $config = $this->category()->config();
        $link = $config['rss_link'];
        
        if (!empty($link))
        {
            if (News::blog())
            {
                return $link . (preg_match('#\?#', $link)? '' : '?') . '&category=' . $this->category()->name() . '&entry=' . $this->id();
            }
            else
            {
                return $link;
            }
        }
        else
        {
            return page_url();
        }
    }
    
    /**
     * @public
     * Check whether the entry has a link or not.
     * 
     * This may either be the link when used in blog mode, or the page explicitely given in the category configuration.
     * 
     * @return <boolean>
     */
    public function has_link() {
        $config = $this->category()->config();
        
        return News::blog() || ((isset($config['news_link'])) AND !empty($config['news_link']));
    }
    
    /**
     * @public
     * Determine the link to the entry if given.
     * 
     * This will be the blog link if in blog mode or the link given in the category config.
     * 
     * @return <string> link
     */
    public function link() {
        $config = $this->category()->config();
        
        if (News::blog())
        {
            return page_url() . (empty($_SERVER['QUERY_STRING']) ? '?' : '') . '&category=' . $this->category()->name() . '&entry=' . $this->id();
        }
        elseif (isset($config['news_link']) AND !empty($config['news_link'])) 
        {
            return $config['news_link'];
        }
        
        return '';
    }
    
	/**
	 * @public
	 * Delete the entry.
	 */
	public function delete()
	{
		$array = CSV::parse(News::$csv.$this->_category->name().'.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
		
		foreach ($array as $key => $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				unset($array[$key]);
			}
		}
		
		CSV::write($array, News::$csv.$this->_category->name().'.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
	}
			
	/**
	 * @public
	 * Edit entry.
	 * 
	 * The changes can be made using the setters.
	 */
	public function save()
	{
		$entry = array(
			$this->_id,
			$this->_title,
			$this->_description,
			$this->_short,
			$this->_state,
		);
		
		$array = CSV::parse(News::$csv . $this->_category->name() . '.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
		
		foreach ($array as $key => $content)
		{
			if ((double)$content[0] == $this->_id)
			{
				unset($array[$key]);
			}
		}
		
		$array[] = $entry;
		CSV::write($array, News::$csv.$this->_category->name().'.csv', News::$cf['csv_delimiter'], News::$cf['csv_enclosure']);
	}
}
?>