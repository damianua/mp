<?php


namespace App\Http\Adapters\Settings;


use App\Http\Adapters\AbstractControllerAdapter;
use App\Models\Category;
use App\Services\CategoryPropertiesAssociationService;

class CategoryPropertiesAdapter extends AbstractControllerAdapter
{
	/**
	 * @var CategoryPropertiesAssociationService
	 */
	private $service;

	public function __construct(CategoryPropertiesAssociationService $service)
	{
		$this->service = $service;
	}

	public function getData()
	{
		return [
			'categories' => $this->service->getCategories()->map(\Closure::fromCallable([$this, 'hydrateCategory']))
		];
	}

	protected function hydrateCategory(Category $category)
	{
		return [
			'id' => $category->id,
			'name' => $category->name,
			'is_current' => $category->id === $this->service->getCategory()->id
		];
	}
}