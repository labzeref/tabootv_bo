<?php

namespace App\SpatieMedia;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

trait InteractsWithHashedMedia
{
    use InteractsWithMedia {
        InteractsWithMedia::addMedia as parentAddMedia;
    }

    public function addMedia($file): FileAdder
    {
        $name = md5(Str::uuid());
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($ext) {

            return $this->parentAddMedia($file)
                ->usingFileName("$name.$ext");
        } else {

            return $this->parentAddMedia($file)
                ->usingFileName($file->hashName());
        }
    }

    public function mediaUploadData($collection)
    {

        return [
            'model' => encrypt($this->id),
            'class' => encrypt(get_class($this)),
            'collection' => encrypt($collection),
            'signature' => encrypt(\Carbon\Carbon::now()),
        ];
    }

    public function mediaMetaData($collection_name)
    {
        $collection = $this->getFirstMedia($collection_name);
        if ($collection) {
            return [
                'id' => $collection->id,
                'url' => getSignedUrl($this->getFirstMediaPath($collection_name)),
                'name' => $collection->file_name,
                'mime_type' => $collection->mime_type,
            ];
        }

        return [];

    }
}
