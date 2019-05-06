<?php


namespace App\Services\Stateless;


use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService extends AbstractModelService
{
	public function getModelClass(): string
	{
		return Category::class;
	}

	/**
	 * @return Collection|Category[]
	 */
	public function getActive()
	{
		return $this->getAll()->filter(function(Category $category){
			return $category->active;
		});
	}

	/**
	 * @return mixed
	 */
	public function getAll(): Collection
	{
		$categories = $this->cache()->rememberForever('categories.all', function(){
			return $this->get(Category::orderBy('sort'));
		});

		return $categories;
	}

	/**
	 * @param $category
	 * @return Category
	 * @throws \App\Exceptions\ModelServiceException
	 */
	public function getCategoryOrFail($category): Category
	{
		return $this->findOrFail($category);
	}
}