# pyro-popular_posts-plugin
plugin for PyroCMS. Show most popular blog posts

* Version 1.0
* Compatible with PyroCMS v2.x
* [Author - Dmitry Kosenkov]

# Install
before install have to create custom field "counter" in the blog.

# Usage

__Add to the view.php template file:__

	{{ popular_posts:counter }}


__To show popular posts, example:__

	{{ popular_posts:posts limit="10"}}
		<li>
			<div class="item-thumbnail-only">
				<div class="item-title"><a href="{{ url }}">{{ title }}</a></div>
			</div>
			<div style="clear: both;"></div>
		</li>
	{{ /popular_posts:posts }}
