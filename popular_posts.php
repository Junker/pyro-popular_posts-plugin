<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Popular Posts
 *
 * Show most popular posts
 *
 * @package		PyroCMS
 * @author		Dmitry Kosenkov <dk-junker@ya.ru>
 * @copyright	Copyright (c) 2015 Dmitry Kosenkov
 *
 */


class Plugin_Popular_Posts extends Plugin
{
	/**
	 * Posts
	 *
	 * Usage:
	 * {{ related_posts:posts limit="5"}}
	 *
	 * @return posts array 
	 */
	function posts()
	{

		$limit = $this->attribute('limit');

		$params = array(
			'stream'        => 'blog',
			'namespace'     => 'blogs',
			'where'         => array("`status` = 'live'"),
			'order_by'      => 'counter',
			'sort'          => 'desc',
			'date_by'       => 'created_on',
			'limit'         => $limit,
		);

		$data = $this->streams->entries->get_entries($params);
		$posts = $data['entries'];

		if (!empty($posts))
		{        
			foreach ($posts as &$post)
			{

				$this->load->helper('text');

				// Keywords array
				$keywords = Keywords::get($post['keywords']);
				$formatted_keywords = array();
				$keywords_arr = array();

				foreach ($keywords as $key)
				{
					$formatted_keywords[]   = array('keyword' => $key->name);
					$keywords_arr[]         = $key->name;
				}

				$post['keywords'] = $formatted_keywords;
				$post['keywords_arr'] = $keywords_arr;

				// Full URL for convenience.
				$post['url'] = site_url('blog/'.date('Y/m', $post['created_on']).'/'.$post['slug']);

				// What is the preview? If there is a field called intro,
				// we will use that, otherwise we will cut down the blog post itself.
				$post['preview'] = (isset($post['intro'])) ? $post['intro'] : $post['body'];

			}
	   }

	   return $posts;
	}


	function counter()
	{
		$show = $this->attribute('show');

		$this_url = trim(site_url($this->uri->uri_string()));
		
		$CI =& get_instance();

		$slug = $CI->uri->rsegment(3);

		$result = $this->db
			->select($this->db->dbprefix('blog').'.counter')
			->where('slug', $slug)
			->get($this->db->dbprefix('blog'))
			->row();

        $counter = intval($result->counter) + 1;

	    $this->db->where('slug', $slug);
	    $this->db->update('blog', array('counter' => $counter)); 
		

		return $show ? $counter : NULL;
	}
}