<?php


namespace App\Services;


use App\Models\Category;
use App\Services\Stateless\CategoryService;
use Illuminate\Support\Collection;

class CategoryPropertiesAssociationService
{
	/**
	 * @var CategoryService
	 */
	private $categoryService;
	/**
	 * @var Category[]|Collection
	 */
	private $categories = null;

	public function __construct(Category $category = null)
	{
		$this->categoryService = app('CategoryService');
		$this->category = $category ?? $this->getDefaultCategory();
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function getDefaultCategory()
	{
		return $this->getCategories()->first();
	}

	/**
	 * @return Category[]|Collection
	 */
	public function getCategories()
	{
		if(is_null($this->categories)){
			$this->categories = $this->categoryService->getActive();
		}

		return $this->categories;
	}
}