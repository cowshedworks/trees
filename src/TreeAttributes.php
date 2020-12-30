<?php

declare(strict_types=1);

namespace CowshedWorks\Trees;

use Exception;

class TreeAttributes
{
    public static array $requiredvalidParameters = ['circumference', 'age', 'height'];
    private array $speciesData;
    private $age;
    private $circumference;
    private $height;

    public function __construct(array $speciesData, array $treeData)
    {
        $this->speciesData = $speciesData;
        $this->init($treeData);
    }

    private function init(array $treeData): void
    {
        $this->resolveCircumference($treeData);
        $this->resolveAge($treeData);
        $this->resolveHeight($treeData);
        $this->resolveMissing();
    }

    private function resolveMissing(): void
    {
        if ($this->age === null) {
            if ($this->circumference) {
                $this->age = new Age($this->circumference->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'years');
            }

            if ($this->height) {
                $this->age = new Age($this->height->getValue() / $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'years');
            }
        }

        if ($this->age === null) {
            throw new Exception('Unable to resolve tree age');
        }

        if ($this->circumference === null) {
            if ($this->age) {
                $this->circumference = new Circumference($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-circumference.value'), 'cm');
            }
        }

        if ($this->height === null) {
            if ($this->age) {
                $this->height = new Height($this->age->getValue() * $this->getSpeciesData('attributes.growth-rate.annual-average-height.value'), 'cm');
            }
        }
    }

    private function resolveCircumference(array $treeData): void
    {
        if (false === isset($treeData['circumference'])) {
            return;
        }

        $values = [];
        
        if (isset($treeData['circumference'])) {
            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData['circumference']);
        }

        if (count($values) === 2) {
            // No id set, use cm
            $this->circumference = new Circumference($values[0], $values[1]);
        }

        if (count($values) === 1) {
            // No id set, use cm
            $this->circumference = new Circumference($values[0], 'cm');
        }
    }

    private function resolveAge(array $treeData): void
    {
        if (false === isset($treeData['age'])) {
            return;
        }

        $values = [];
        
        if (isset($treeData['age'])) {
            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData['age']);
        }

        if (count($values) === 2) {
            // No id set, use cm
            $this->age = new Age($values[0], $values[1]);
        }

        if (count($values) === 1) {
            // No id set, use cm
            $this->age = new Age($values[0], 'years');
        }
    }

    private function resolveHeight(array $treeData): void
    {
        if (false === isset($treeData['height'])) {
            return;
        }

        $values = [];
        
        if (isset($treeData['height'])) {
            $values = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $treeData['height']);
        }

        if (count($values) === 2) {
            // No id set, use cm
            $this->height = new Height($values[0], $values[1]);
        }

        if (count($values) === 1) {
            // No id set, use cm
            $this->height = new Height($values[0], 'cm');
        }
    }

    public function getAge(): float
    {
        return $this->age->getValue();
    }

    public function getCircumference(): float
    {
        return $this->circumference->getValue();
    }

    public function getHeight(): float
    {
        return $this->height->getValue();
    }

    public function describeAge(): string
    {
        return $this->age->getDescription();
    }

    public function describeCircumference(): string
    {
        return $this->circumference->getDescription();
    }

    public function describeHeight(): string
    {
        return $this->height->getDescription();
    }

    public function getSpeciesData(string $dataName)
    {
        $currentValue = $this->speciesData;

        foreach (explode('.', $dataName) as $key) {
            $currentValue = $currentValue[$key];
        }

        return $currentValue;
    }

    public static function validateTreeParameters(array $treeParameters): bool
    {
        $totalValidParams = count(self::$requiredvalidParameters);

        $validParamsNotProvided = array_diff(self::$requiredvalidParameters, array_keys($treeParameters));

        return count($validParamsNotProvided) != $totalValidParams;
    }
}
