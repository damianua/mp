<?php


namespace App\Services\Stateless;


use App\Models\Section;

class SectionService extends AbstractModelService
{
    public function getModelClass(): string
    {
        return Section::class;
    }

    public function getByExternalId($externalId)
    {
        return Section::where('external_id', $externalId)->first();
    }

    public function createSection(array $attributes)
    {
        $section = Section::create($attributes);

        return $section;
    }

    public function updateSection($section, array $attributes)
    {
        $section = $this->getSectionOrFail($section);

        $saved = $section
            ->fill($attributes)
            ->save();

        return $saved;
    }

    /**
     * @param $section
     * @return \App\Models\BaseModel
     * @throws \App\Exceptions\ModelServiceException
     */
    public function getSectionOrFail($section)
    {
        return $this->findOrFail($section);
    }
}