<?php namespace Stillat\Menu\Renderers;

/*
|--------------------------------------------------------------------------
| Default Renderer
|--------------------------------------------------------------------------
|
| This the default renderer used by the menu system. This can be used as
| a starting point for creating your own customized renderer. This renderer
| will output items in simple unordered lists and list items.
|
| Rendering engine implementations are completely decoupled from the actual
| menu system. Because of this, you never get to work directly on any object
| from the menu system. This allows for some interesting possibilities.
|
*/

use Stillat\Menu\Renderers\RendererInterface;

class DefaultRenderer implements RendererInterface {

	/**
	 * The attribute format to use.
	 *
	 * @var string
	 */
	protected $attributeFormat = '{{ attributeName }}="{{ attributeValue }}" ';

	/**
	 * A helper function to format HTML attributes.
	 *
	 * @param  string $key
	 * @param  string $value
	 * @return string
	 */
	protected function formatAttributeValues($key, $value)
	{
		$attributeString = $this->attributeFormat;
		$attributeString = str_replace('{{ attributeName }}', $key, $attributeString);

		if (is_array($value))
		{
			$attributeString = str_replace('{{ attributeValue }}', implode(' ', $value), $attributeString);
		}
		else
		{
			$attributeString = str_replace('{{ attributeValue }}', (string) $value, $attributeString);
		}

		return $attributeString;
	}

	/**
	 * Renders the main menu opening block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderMainOpen(array $attributes)
	{

		// Grab the original attributes we set on the menu. This
		// way we can manipulate them ourselves and add some of our
		// classes that we may want.
		$originalAttributes = $attributes['attributes'];

		// Grab the original CSS rules.

		$cssRules = array();

		// Just merge the original CSS rules array or string with
		// our new CSS rules array.
		if (array_key_exists('class', $originalAttributes))
		{
			$cssRules = (array) $originalAttributes['class'];
		}

		// Remove some of the properties or attributes that we really
		// do not want to have show up automatically in the menu's output.
		unset($originalAttributes['class']);
		unset($originalAttributes['text']);
		unset($originalAttributes['child']);
		unset($originalAttributes['level']);

		// This checks to see if the menu has any active children. If it
		// does, we will add a new class to the menu element 'active-child'.
		if ($attributes['hasActiveChild'])
		{
			$cssRules = array_merge($cssRules, array('active-child'));
		}

		// This variable will hold the HTML output for our attributes and
		// properties.
		$elementAttributes = '';

		// This loops through all the attributes and properties and generates
		// the corresponding HTML code for them.
		foreach ($originalAttributes as $name => $value)
		{
			$elementAttributes .= $this->formatAttributeValues($name, $value);
		}

		
		// Simply construct our HTML element.
		$menu = '';

		if ($attributes['level'] == 0)
		{
			// This generates the HTML code for applying CSS rules to an element.
			$cssRules = $this->formatAttributeValues('class', implode(' ', $cssRules));

			// If the 'level' is set to 0, we can be assured that the menu
			// we are generating is the base, or root, menu.
			$menu = '<ul '.$elementAttributes.' '.$cssRules.'>';
		}
		else
		{
			// Here are going to add the Bootstrap 'dropdown' class to our list of classes.
			$cssRules[] = 'dropdown';

			// This generates the HTML code for applying CSS rules to an element.
			$cssRules = $this->formatAttributeValues('class', implode(' ', $cssRules));

			// Since the 'level' is not 0, we know we are creating a child
			// or nested menu. We can modify the output here to reflect this.
			$menu = '<li '.$elementAttributes.' '.$cssRules.'>
			<a href="'.$attributes['href'].'" class="dropdown-toggle" data-toggle="dropdown">'.$attributes['text'].'</a><ul class="dropdown-menu">';
		}

		// Here we are going to add a 

		// Return our newly created HTML element.
		return $menu;
	}

	/**
	 * Renders the main menu closing block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderMainClose(array $attributes)
	{
		if ($attributes['level'] == 0)
		{
			// If we are building a root level menu, we are are just going
			// to close it with an un-ordered list closing tag.
			return '</ul>';
		}
		else
		{
			// If are building a nested menu, we know that it is contained in
			// a list item element of its own, so we need to close that first,
			// and then close the un-ordered list.
			return '</ul></li>';
		}
	}

	/**
	 * Renders an item's opening block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemOpen(array $attributes)
	{
		if ($attributes['isActive'])
		{
			// If the current menu item is active, lets create
			// an opening list-item element and set its class to active.
			return '<li class="active">';
		}

		// If the current menu item is not active,
		// let's just create an opening list-item element.
		return '<li>';
	}

	/**
	 * Renders an item's body
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemBody(array $attributes)
	{
		return '<a href="'.$attributes['href'].'">'.$attributes['text'].'</a>';
	}

	/**
	 * Renders an item's closing block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemClose(array $attributes)
	{
		return '</li>';
	}

}