<?php

declare(strict_types=1);

namespace CowshedWorks\Trees\UnitValues;

/**
 * @method Weight        weight($value, $unit = null)
 * @method SmallWeight   smallweight($value, $unit = null)
 * @method Height        height($value, $unit = null)
 * @method Circumference circumference($value, $unit = null)
 * @method Age           age($value, $unit = null)
 * @method Diameter      diameter($value, $unit = null)
 * @method Length        length($value, $unit = null)
 */
class UnitValueFactory
{
    protected array $unitValues = [
        'age'                 => Age::class,
        'circumference'       => Circumference::class,
        'diameter'            => Diameter::class,
        'height'              => Height::class,
        'length'              => Length::class,
        'weight'              => Weight::class,
        'smallweight'         => SmallWeight::class,
    ];

    public function __call($method, $params)
    {
        if (is_array($params[0])) {
            $unitValueData = $params[0];

            return new $this->unitValues[$method]($unitValueData['value'], $unitValueData['unit']);
        }

        if (count($params) === 2) {
            return new $this->unitValues[$method]($params[0], $params[1]);
        }

        return new $this->unitValues[$method]($params[0], $this->unitValues[$method]::getDefault());
    }
}
