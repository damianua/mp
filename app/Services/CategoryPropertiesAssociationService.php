<?php


namespace App\Services;


use App\Models\Category;
use App\Models\ProductProperty;
use App\Services\Stateless\CategoryService;
use App\Services\Stateless\ProductPropertyService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryPropertiesAssociationService
{
	/**
	 * @var Category
	 */
	protected $category;
	/**
	 * @var CategoryService
	 */
	private $categoryService;
	/**
	 * @var ProductPropertyService
	 */
	private $productPropertyService;
	/**
	 * @var Category[]|Collection
	 */
	private $categories = null;
	/**
	 * @var ProductProperty[]|Collection
	 */
	private $productProperties;
	/**
	 * @var ProductProperty[]|Collection
	 */
	private $categoryProperties;

	public function __construct(Category $category = null)
	{
		$this->categoryService = app('CategoryService');
		$this->productPropertyService = app('ProductPropertyService');
		$this->category = $category ?? $this->getDefaultCategory();
	}

	public function getCategoryProperties()
	{
		if(is_null($this->categoryProperties)){
			$this->categoryProperties = $this->getCategory()->productProperties->keyBy('id');
		}

		return $this->categoryProperties;
	}

	public function getCategory(): Category
	{
		return $this->category;
	}

	public function getDefaultCategory(): Category
	{
		return $this->getCategories()->first();
	}

	/**
	 * @return Category[]|Collection
	 */
	public function getCategories(): Collection
	{
		if(is_null($this->categories)){
			$this->categories = $this->categoryService->beforeQuery(function (Builder $query){
				$query->with(['productProperties']);
			})->getActive();
		}

		return $this->categories;
	}

	public function getProductProperties(): Collection
	{
		if(is_null($this->productProperties)){
			$this->productProperties = $this->productPropertyService->getActive();
		}

		return $this->productProperties;
	}

	public function syncCategoryProperties(array $syncData)
	{
		$this->getCategory()->productProperties()->sync($syncData);
	}
}