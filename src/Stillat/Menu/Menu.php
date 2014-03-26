<?php namespace Stillat\Menu;

use Stillat\Menu\MenuItem;
use Stillat\Menu\BaseObject;
use Stillat\Common\Collections\Collection;
use Stillat\Common\Exceptions\InvalidArgumentException;

class Menu extends BaseObject {

	/**
	 * The menu items.
	 *
	 * @var \Illuminate\Support\Collection
	 */
	protected $items;

	/**
	 * The name of the menu.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Creates a new instance of Menu
	 *
	 * @param array        $attributes
	 * @var   Stillat\Menu
	 */
	public function __construct($menuName, array $attributes = array())
	{
		// Create our menu items collection.
		$this->items = new Collection;

		$this->name = $menuName;

		$this->attributes = $attributes;
	}

	/**
	 * Determines if a menu has child elements.
	 *
	 * @return bool
	 */
	public function hasChildren()
	{
		if ($this->items->count() > 0)
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the number of child items.
	 *
	 * @return int
	 */
	public function childrenCount()
	{
		return $this->items->count();
	}

	/**
	 * Gets the name of the menu.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Gets a menu item by name.
	 *
	 * @return mixed
	 */
	public function getItem($itemName)
	{
		return $this->items->get($itemName, null);
	}

	/**
	 * Determines if a menu item exists by name.
	 *
	 * @return bool
	 */
	public function hasItem($itemName)
	{
		return $this->items->has($itemName);
	}

	/**
	 * Adds a new menu item
	 *
	 * @param  string 					$itemName
	 * @param  mixed 					$itemText
	 * @param  array 					$attributes
	 * @return \Stillat\Menu\MenuItem
	 */
	public function addItem($itemName, $itemText, array $attributes = array())
	{
		if ($this->hasItem($itemName) == false)
		{
			$attributes['text'] = $itemText;

			$newMenuItem = new MenuItem($itemName, $attributes);
			$newMenuItem->setRenderer($this->getRenderer());

			$this->items->put($itemName, $newMenuItem);
		}
		
		return $this;
	}

	/**
	 * Adds an existing menu as a new menu item
	 * 
	 * @param  \Stillat\Menu\MenuItem $menu 
	 * @return \Stillat\Menu\Menu
	 */
	public function addMenu($menu)
	{
		$this->items->put($menu->getName(), $menu);
		
		return $this;
	}

	/**
	 * Adds a new sub menu after an existing menu.
	 * 
	 * @param  string $afterMenu 
	 * @param  \Stillat\Menu\Menu $menu 
	 * @return void
	 */
	public function addMenuAfter($afterMenu, $menu)
	{
		if (!$this->hasItem($afterMenu))
		{
			throw new InvalidArgumentException("The menu item '{$afterMenu}' does not exist.");
		}

		$this->items->insertAfter($afterMenu, array($menu->getName(), $menu));
	}

	/**
	 * Adds a new sub menu before an existing menu.
	 * 
	 * @param  string $afterMenu 
	 * @param  \Stillat\Menu\Menu $menu 
	 * @return void
	 */
	public function addMenuBefore($beforeMenu, $menu)
	{
		if (!$this->hasItem($beforeMenu))
		{
			throw new InvalidArgumentException("The menu item '{$beforeMenu}' does not exist.");
		}

		$this->items->insertBefore($beforeMenu, array($menu->getName(), $menu));
	}

	/**
	 * Takes an existing sub menu item and renders it
	 * 
	 * @param  \Stillat\Menu\MenuItem $menu
	 * @param  array 				 $options 
	 * @return string
	 */
	protected function renderSubMenu(Menu $menu, array $options = array())
	{
		$menu->setAttribute('child', true);
		$menu->setAttribute('level', intval($options['level']) + 1);

		return $menu->render($options);
	}

	/**
	 * Takes an existing menu item and renders it
	 * 
	 * @param  \Stillat\Menu\MenuItem $item 
	 * @return string
	 */
	protected function renderMenuItem(MenuItem $item, $options)
	{
		$options['isActive'] = false;

		return $item->render($options);
	}

	/**
	 * Renders the menu and it's items and menus
	 * 
	 * @param  array $options
	 * @return string
	 */
	public function render(array $options = array())
	{
		$renderedMenu = '';

		$options = array_merge($options, $this->buildOptionsArray($options));

		unset($options['class']);

		$renderedMenu .= $this->renderer->renderMainOpen($options);

		foreach ($this->items as $itemName => $item)
		{
			if (is_a($item, '\Stillat\Menu\Menu'))
			{
				$renderedMenu .= $this->renderSubMenu($item, $options);
			}
			elseif (is_a($item, '\Stillat\Menu\MenuItem'))
			{
				$renderedMenu .= $this->renderMenuItem($item, $options);
			}
		}

		$renderedMenu .= $this->renderer->renderMainClose($options);

		return $renderedMenu;
	}

	/**
	 * Builds the default rendering options
	 * 
	 * @return array
	 */
	protected function buildOptionsArray(array $defaultOptions)
	{
		$options = array();

		$options['attributeString'] = $this->getAttributeString();
		$options['childCount'] = $this->childrenCount();
		$options['href'] = $this->getName();
		$options['attributes'] = $this->attributes;

		$options['hasActiveChild'] = $this->hasItem($defaultOptions['activeRoute']);

		return array_merge($this->attributes, $options);
	}

}