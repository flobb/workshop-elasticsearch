<?php

namespace App\Transformer;

use FOS\ElasticaBundle\Transformer\ModelToElasticaAutoTransformer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class NewsTransformer extends ModelToElasticaAutoTransformer
{
    public function __construct(PropertyAccessorInterface $propertyAccessor, EventDispatcherInterface $dispatcher = null)
    {
        parent::__construct([], $dispatcher);

        $this->setPropertyAccessor($propertyAccessor);
    }
}
