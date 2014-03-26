<?php namespace Stillat\Menu;

interface AttributableInterface {

	/**
	 * Returns an attributes value by name.
	 *
	 * @return mixed
	 */
	public function getAttribute($attributeName);

	/**
	 * Sets an attributes value by name.
	 *
	 * @param string $attributeName
	 * @param string $attributeValue
	 */
	public function setAttribute($attributeName, $attributeValue);

	/**
	 * Checks to see if a attribute exists by name.
	 *
	 * @param  string $attributeName
	 * @return bool
	 */
	public function hasAttribute($attributeName);

	/**
	 * Merges a new attribute value with existing values.
	 *
	 * @param string $attributeName
	 * @param string $attributeValue
	 */
	public function mergeAttribute($attributeName, $additionalValue);

	/**
	 * Converts the attribute array to a string.
	 *
	 * @return string
	 */
	public function getAttributeString();

}