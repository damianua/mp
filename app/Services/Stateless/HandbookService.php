<?php


namespace App\Services\Stateless;


use App\Models\Handbook;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HandbookService extends AbstractModelService
{
    public function getModelClass(): string
    {
        return Handbook::class;
    }

    public function getByExternalId($externalId): ?Handbook
    {
        return Handbook::where('external_id', $externalId)->first();
    }

    public function createHandbook(array $attributes): Handbook
    {
        $handbook = Handbook::create($attributes);
        $this->cache()->forget('handbooks.all');

        return $handbook;
    }

    public function updateHandbook($handbook, array $attributes): bool
    {
        $handbook = $this->findHandbookOrFail($handbook);

        $attributes[Handbook::UPDATED_AT ] = Carbon::now();
        $saved = $handbook
            ->fill($attributes)
            ->save();

        $this->cache()->forget('handbooks.all');

        return $saved;

    }

    /**
     * @return Handbook[]
     */
    public function getAll()
    {
        $handbooks = $this->cache()->remember('handbooks.all', 3600, function(){
            return $this->get(Handbook::query());
        });

        return $handbooks;
    }

    /**
     * @param Handbook|int $handbook
     * @return Handbook
     * @throws \App\Exceptions\ModelServiceException
     */
    public function findHandbookOrFail($handbook)
    {
        return $this->findOrFail($handbook);
    }
}