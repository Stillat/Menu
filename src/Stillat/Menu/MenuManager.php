<?php namespace Stillat\Menu;

use Stillat\Menu\Menu;
use Illuminate\Support\Collection;
use Stillat\Menu\Renderers\RendererInterface;
use Stillat\Common\Exceptions\InvalidArgumentException;

class MenuManager {

	/**
	 * A collection of menus.
	 *
	 * @var Illuminate\Support\Collection
	 */
	protected $menuCollection;

	/**
	 * The currently active route path.
	 *
	 * @var string
	 */
	protected $activeRoutePath = '';

	/**
	 * The renderer implementation.
	 * 
	 * @var \Stillat\Menu\Renderers\RendererInterface
	 */
	protected $renderer;

	/**
	 * Returns a new instance of MenuManager
	 *
	 * @param  \Stillat\Menu\Renderers\RendererInterface $rendererInterface
	 * @return \Stillat\MenuManager
	 */
	public function __construct(RendererInterface $rendererInterface)
	{
		// Create our menu collection.
		$this->menuCollection = new Collection;

		$this->renderer = $rendererInterface;
	}

	/**
	 * Determines if a menu exists by name.
	 *
	 * @param  string $menuName
	 * @return bool
	 */
	public function hasMenu($menuName)
	{
		return $this->menuCollection->has($menuName);
	}

	/**
	 * Get a menu from the collection by name.
	 *
	 * @param  string $menuName
	 * @return mixed
	 */
	public function getMenu($menuName)
	{
		return $this->menuCollection->get($menuName, null);
	}

	/**
	 * Handles the creation of a menu, or returns it if it exists
	 *
	 * @param  string       $menuName
	 * @param  string 		$menuText
	 * @param  array 		$attributes
	 * @return Stillat\Menu
	 */
	public function handle($menuName, $menuText, array $attributes = array())
	{
		if ($this->hasMenu($menuName) == false)
		{
			$attributes['text'] = $menuText;

			$menu = new Menu($menuName, $attributes);
			$menu->setRenderer($this->renderer);

			$this->menuCollection->put($menuName, $menu);
		}

		return $this->getMenu($menuName);
	}

	/**
	 * Sets the renderer implementation.
	 * 
	 * @param  \Stillat\Menu\Renderers\RendererInterface $renderer
	 * @return void
	 */
	public function setRenderer(RendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * Renders a menu by name.
	 *
	 * @return mixed
	 */
	public function render($menuName)
	{

		if ($this->hasMenu($menuName))
		{
			$menu = $this->getMenu($menuName);

			$renderOptions = $this->buildRenderOptions();

			if ($menu->childrenCount() == 0)
			{
				$renderOptions['hasActiveChildren'] = false;
			}

			return $menu->render($renderOptions);
		}

		return null;
	}

	/**
	 * Sets the active route path.
	 *
	 * @param  string $routePath
	 * @return void
	 */
	public function setRoutePath($routePath)
	{
		if (can_be_valid_string($routePath))
		{
			$this->activeRoutePath = (string)$routePath;
		}
		else
		{
			throw new InvalidArgumentException("The route path '{$routePath}' is not a valid string.");
		}
	}

	/**
	 * Gets the active route path.
	 *
	 * @return string
	 */
	public function getRoutePath()
	{
		return (string) $this->activeRoutePath;
	}

	/**
	 * Builds the initial rendering options array.
	 *
	 * @return array
	 */
	protected function buildRenderOptions()
	{
		$options = array();

		// Set the current route.
		$options['activeRoute'] = $this->getRoutePath();

		// Set the initial menu level.
		$options['level'] = 0;

		// The main level will not have an active parent.
		$options['parentIsActive'] = false;

		return $options;
	}

	/**
	 * Returns the string 'MenuManager'
	 *
	 * @return string
	 */
	public function __toString()
	{
		return 'MenuManager';
	}

}