<?php namespace Stillat\Menu;

use Stillat\Menu\AttributableInterface;
use Stillat\Menu\Renderers\RendererInterface;
use Stillat\Menu\Renderers\SimpleRendererInterface;

abstract class BaseObject implements AttributableInterface, SimpleRendererInterface {

	/**
	 * The renderer implementation.
	 * 
	 * @var \Stillat\Menu\Renderers\RendererInterface
	 */
	protected $renderer;

	/**
	 * The menu attributes.
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * The attribute format to use.
	 *
	 * @var string
	 */
	protected $attributeFormat = '{{ attributeName }}="{{ attributeValue }}" ';

	/**
	 * Returns an attributes value by name.
	 *
	 * @return mixed
	 */
	public function getAttribute($attributeName)
	{
		if ($this->hasAttribute($attributeName))
		{
			return $this->attributes[$attributeName];
		}

		return null;
	}

	/**
	 * Sets an attributes value by name.
	 *
	 * @param string $attributeName
	 * @param string $attributeValue
	 */
	public function setAttribute($attributeName, $attributeValue)
	{
		if (can_be_valid_string($attributeValue, true))
		{
			$this->attributes[$attributeName] = $attributeValue;
		}
	}

	/**
	 * Checks to see if a attribute exists by name.
	 *
	 * @param  string $attributeName
	 * @return bool
	 */
	public function hasAttribute($attributeName)
	{
		return array_key_exists($attributeName, $this->attributes);
	}

	/**
	 * Merges a new attribute value with existing values.
	 *
	 * @param string $attributeName
	 * @param string $attributeValue
	 */
	public function mergeAttribute($attributeName, $additionalValue)
	{
		$this->setAttribute($attributeName, array_merge((array)$this->getAttribute($attributeName), (array)$additionalValue));
	}

	/**
	 * Formats the given key and value according to the attribute format.
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
	 * Converts the attribute array to a string.
	 *
	 * @return string
	 */
	public function getAttributeString()
	{
		$attributeString = '';

		$attributes = $this->attributes;

		// Remove some things that don't need to be in the output.
		unset($attributes['text']);

		foreach ($attributes as $attributeName => $value)
		{
			$attributeString .= $this->formatAttributeValues($attributeName, $value);
		}

		return $attributeString;
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
	 * Gets the renderer implementation used.
	 * 
	 * @return \Stillat\Menu\Renderers\RendererInterface
	 */
	public function getRenderer()
	{
		return $this->renderer;
	}
	
	/**
	 * Renders the given object to a string
	 * 
	 * @param  string array $options 
	 * @return string
	 */
	abstract function render(array $options = array());

}