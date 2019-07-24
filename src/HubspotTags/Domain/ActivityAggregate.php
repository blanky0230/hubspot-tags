<?php

declare(strict_types=1);

namespace HubspotTags\Domain;

final class ActivityAggregate
{
    /**
     * @var array
     */
    private $items;

    public function __construct()
    {
        $this->items = [];
    }

    /**
     * @param string $dateString
     * @param bool   $refreshSort
     */
    public function addNewDateKey(string $dateString, bool $refreshSort = true): self
    {
        if (!$this->dateHasTagsValues($dateString)) {
            $this->items[$dateString] = ['DEMO' => 0, 'CLOSE' => 0];
        }

        if ($refreshSort) {
            $this->refreshSorting();
        }

        return $this;
    }

    /**
     * @param string $dateString
     *
     * @return ActivityAggregate
     */
    public function incrementDemo(string $dateString): self
    {
        return $this->incrementNameAtDate($dateString, 'DEMO');
    }

    /**
     * @param string $dateString
     *
     * @return ActivityAggregate
     */
    public function incrementClose(string $dateString): self
    {
        return $this->incrementNameAtDate($dateString, 'CLOSE');
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param string $dateString
     *
     * @return bool
     */
    public function dateHasTagsValues(string $dateString): bool
    {
        return array_key_exists($dateString, $this->items);
    }

    /**
     * Copy all elements from $other into this Aggregate.
     *
     * @param ActivityAggregate $other
     */
    public function extendWithAggregate(ActivityAggregate $other): self
    {
        foreach ($other->getItems() as $date => &$values) {
            $this->addNewDateKey($date, false);

            while ($values['DEMO']) {
                $this->incrementDemo($date);
                --$values['DEMO'];
            }

            while ($values['CLOSE']) {
                $this->incrementClose($date);
                --$values['CLOSE'];
            }
        }
        $this->refreshSorting();

        return $this;
    }

    /**
     * Simple ksort to keep everything in order nicely.
     */
    private function refreshSorting(): void
    {
        uksort($this->items, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
    }

    /**
     * @param string $dateString
     * @param string $name
     *
     * @return ActivityAggregate
     */
    private function incrementNameAtDate(string $dateString, string $name): self
    {
        if (!$this->dateHasTagsValues($dateString)) {
            $this->addNewDateKey($dateString);
        }

        if (!array_key_exists($name, $this->items[$dateString])) {
            $this->items[$dateString][$name] = 0;
        }

        ++$this->items[$dateString][$name];

        return $this;
    }
}
