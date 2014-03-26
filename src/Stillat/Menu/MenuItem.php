<?php namespace Stillat\Menu;

use Stillat\Menu\BaseObject;

class MenuItem extends BaseObject {

	/**
	 * The name of the menu item.
	 * 
	 * @var string
	 */
	protected $name = '';

	/**
	 * Returns a new instance of MenuItem
	 * 
	 * @param string $itemName 
	 * @param array array $attributes 
	 * @return \Stillat\Menu\MenuItem
	 */
	public function __construct($itemName, array $attributes = array())
	{
		$this->name = $itemName;

		$this->attributes = $attributes;
	}

	/**
	 * Returns the name of the menu item.
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Renders the current menu item.
	 * 
	 * @param  array $options 
	 * @return string
	 */
	public function render(array $options = array())
	{
		$options = array_merge($this->attributes, $options);
		$options['href'] = $this->getName();
		$options['text'] = $this->getAttribute('text');

		$parentHasActiveChild = $options['hasActiveChild'];
		unset($options['hasActiveChild']);
		$options['parentActive'] = $parentHasActiveChild;

		if ($options['activeRoute'] == $this->getName())
		{
			$options['isActive'] = true;
		}
		else
		{
			$options['isActive'] = false;
		}
		
		$renderedItem = '';

		$renderedItem .= $this->renderer->renderItemOpen($options);
		$renderedItem .= $this->renderer->renderItemBody($options);
		$renderedItem .= $this->renderer->renderItemClose($options);

		return $renderedItem;
	}

}