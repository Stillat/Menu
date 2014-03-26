<?php namespace Stillat\Menu\Renderers;

interface RendererInterface {

	/**
	 * Renders the main menu opening block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderMainOpen(array $options);

	/**
	 * Renders the main menu closing block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderMainClose(array $options);

	/**
	 * Renders an item's opening block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemOpen(array $options);

	/**
	 * Renders an item's body
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemBody(array $options);

	/**
	 * Renders an item's closing block
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function renderItemClose(array $options);

}