
# Twig pages

##### **Note!** Twig support requires PHP 5.3 or newer.

#### File extension: `.twig`, `.tw`

**Twig** is a templating language built to make it easier to write PHP into HTML. It has a proprietary syntax that matches PHP.

Read more about it on [Twig's site](http://twig.sensiolabs.org/doc/templates.html).

##### **Note!** Since Servant always renders one page at a time, Twig's template inheritance is not used.



## Basic example

##### pages/some-category/some-page.twig

	<h1>{{ servant.page().name() }}</h1>

	<ul id="navigation">
	{% for id in servant.page().siblings() %}
		<li><a href="{{ id }}">{{ servant.format().name(id) }}</a></li>
	{% endfor %}
	</ul>
