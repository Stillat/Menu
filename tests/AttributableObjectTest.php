<?php

use Stillat\Menu\BaseObject;
use Stillat\Menu\MenuManager;

class AttributableObjectTest extends \PHPUnit_Framework_TestCase {

	protected $baseObject;

	public function setUp()
	{
		$this->baseObject = new ObjectTest;
		parent::setUp();
	}

	public function tearDown()
	{
		unset($this->baseObject);
		parent::tearDown();
	}

	public function provider_attributeTests()
	{
		return [
			['test', 'this is a test value'],
			['class', ['multiple', 'values']],
			['objectTest', new ObjectTest]
		];
	}

	/**
	 * @dataProvider provider_attributeTests
	 */
	public function testSettingAttributeWorks($attributeName, $attributeValue)
	{
		$this->baseObject->setAttribute($attributeName, $attributeValue);

		$this->assertEquals($attributeValue, $this->baseObject->getAttribute($attributeName));
	}

}

class ObjectTest extends BaseObject {

	public function render(array $options = array()) { return ''; }

	public function __toString()
	{
		return 'test';
	}

}