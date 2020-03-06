<?php

namespace App\Repositories;

use App\Library\CacheUtil;
use App\Note;
use App\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class NoteRepository implements RepositoryInterface
{
    public function create(array $data)
    {
        $note = new Note();
        $note->fill($data);

        DB::beginTransaction();
        try {
            if (!$note->save()) {
                throw new \Exception('Note could not create.');
            }

            $tags = $data['tags'];
            $note->syncTags($tags);
        } catch (\Exception $exception) {
            // There is no logging mechanism for now
            DB::rollBack();
            throw new \Exception('Record could not create.' . $exception->getMessage(), 0, $exception);
        }

        DB::commit();

        return $note;
    }

    public function update(array $data, ?array $criteria = null)
    {
        $note = $this->findOneBy($criteria);
        $note->fill($data);

        DB::beginTransaction();
        try {
            if (!$note->save()) {
                throw new \Exception('Note could not update.');
            }

            $tags = $data['tags'];
            $note->syncTags($tags);
        } catch (\Exception $exception) {
            // There is no logging mechanism for now
            DB::rollBack();
            throw new \Exception('Record could not update.', 0, $exception);
        }

        DB::commit();

        return $note;
    }

    public function delete($id)
    {
        return Note::destroy($id);
    }

    public function findAll(?array $criteria = null)
    {
        if (CacheUtil::isCacheEnabled()) {
            return $this->findAllFromCache($criteria);
        }
        return $this->findAllFromDb($criteria);
    }

    private function findAllFromDb(?array $criteria = null)
    {
        return Note::where($criteria)->with('tags')->get()->all();
    }

    private function findAllFromCache(?array $criteria = null)
    {
        $ttl = CacheUtil::getTtl();
        $cacheKey = CacheUtil::getCacheKey($criteria);

        return Cache::remember($cacheKey, $ttl, function () use ($criteria) {
            return $this->findAllFromDb($criteria);
        });
    }

    public function findOneBy(array $criteria)
    {
        if (CacheUtil::isCacheEnabled()) {
            return $this->findOneByFromCache($criteria);
        }

        return $this->findOneByFromDb($criteria);
    }

    public function findOneByFromDb(array $criteria)
    {
        return Note::where($criteria)->firstOrFail();
    }

    public function findOneByFromCache(array $criteria)
    {
        $ttl = CacheUtil::getTtl();
        $cacheKey = CacheUtil::getCacheKey($criteria);

        return Cache::remember($cacheKey, $ttl, function () use ($criteria) {
            return $this->findOneByFromDb($criteria);
        });
    }
}
