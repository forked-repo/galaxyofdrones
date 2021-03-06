<?php

namespace Koodilab\Models\Transformers;

use Koodilab\Models\Movement;
use Koodilab\Models\Planet;
use Koodilab\Models\Resource;
use Koodilab\Models\Unit;

class MovementTransformer extends Transformer
{
    /**
     * {@inheritdoc}
     *
     * @param Movement $item
     */
    public function transform($item)
    {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'remaining' => $item->remaining,
            'start' => $this->planet($item->start),
            'end' => $this->planet($item->end),
            'resources' => $this->resources($item),
            'units' => $this->units($item),
        ];
    }

    /**
     * Get the planet.
     *
     * @param Planet $planet
     *
     * @return array
     */
    protected function planet(Planet $planet)
    {
        return [
            'id' => $planet->id,
            'resource_id' => $planet->resource_id,
            'display_name' => $planet->display_name,
        ];
    }

    /**
     * Get the units.
     *
     * @param Movement $item
     *
     * @return array
     */
    protected function units(Movement $item)
    {
        return $item->findUnitsOrderBySortOrder()
            ->transform(function (Unit $unit) {
                return [
                    'id' => $unit->id,
                    'name' => $unit->translation('name'),
                    'description' => $unit->translation('description'),
                    'quantity' => $unit->pivot->quantity,
                ];
            });
    }

    /**
     * Get the resources.
     *
     * @param Movement $item
     *
     * @return array
     */
    protected function resources(Movement $item)
    {
        return $item->findResourcesOrderBySortOrder()
            ->transform(function (Resource $resource) {
                return [
                    'id' => $resource->id,
                    'name' => $resource->translation('name'),
                    'description' => $resource->translation('description'),
                    'quantity' => $resource->pivot->quantity,
                ];
            });
    }
}
