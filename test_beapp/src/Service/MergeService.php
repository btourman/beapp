<?php

// src/Service/MergeService.php
namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class MergeService
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function mergeObjects($source, $target)
    {
        // Serialize the source object
        $data = $this->serializer->normalize($source);

        // Deserialize into the target object
        $this->serializer->denormalize($data, get_class($target), null, ['object_to_populate' => $target]);

        return $target;
    }
}
