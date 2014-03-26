<?php

use Stillat\Menu\MenuManager;
use Stillat\Menu\Renderers\DefaultRenderer;

class MenuManagerTest extends \PHPUnit_Framework_TestCase {

	protected $menuManager;

	protected function setUp()
	{
		$this->menuManager = new MenuManager(new DefaultRenderer);
		parent::setUp();
	}

	public function tearDown()
	{
		unset($this->menuManager);
		parent::tearDown();
	}

	public function provider_routePaths()
	{
		return [
			['test', 'test'],
			[1, '1'],
			[new MenuManager(new DefaultRenderer), 'MenuManager']
		];
	}

	/**
	 * @dataProvider provider_routePaths
	 */
	public function testSettingActiveRoutePathWorks($test, $expectedValue)
	{
		$this->menuManager->setRoutePath($test);

		$this->assertEquals($expectedValue, $this->menuManager->getRoutePath());
	}

	/**
	 * @expectedException \Stillat\Common\Exceptions\InvalidArgumentException
	 */
	public function testSettingInvalidRouteThrowsException()
	{
		$this->menuManager->setRoutePath(null);
	}

	public function testCreateMenu()
	{
		$this->menuManager->handle('test', 'A simple test');

		$this->assertEquals(true, $this->menuManager->hasMenu('test'));
	}

	public function testMenuManagerDoesntCreateDuplicateMenus()
	{
		$this->menuManager->handle('test', 'testName');
		$this->menuManager->handle('test', 'fakeName');

		$this->assertEquals('testName', $this->menuManager->getMenu('test')->getAttribute('text'));
	}

}