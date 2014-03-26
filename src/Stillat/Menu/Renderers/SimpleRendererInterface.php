<?php namespace Stillat\Menu\Renderers;

interface SimpleRendererInterface {

	/**
	 * Renders the given object to a string
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	public function render(array $options = array());

}