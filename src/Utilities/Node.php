<?php

namespace MathSolver\Utilities;

use Illuminate\Support\Collection;

class Node
{
    /**
     * The value of this node.
     */
    protected mixed $value;

    /**
     * The direct parent node.
     */
    protected ?Node $parent = null;

    /**
     * A collection of all direct children of this node.
     *
     * @var Collection<Node>
     */
    protected Collection $children;

    /**
     * Instantiate a new node.
     */
    public function __construct(string|int|float $value)
    {
        $this->value = $value;
        $this->children = collect([]);
    }

    /**
     * Instantiate a new node from a string.
     */
    public static function fromString(string $string): self
    {
        return StringToTreeConverter::run($string);
    }

    /**
     * Get the node's direct parent.
     */
    public function parent(): self|null
    {
        return $this->parent;
    }

    /**
     * Get the node's value.
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /**
     * Return a collection of all the node's direct children.
     *
     * @return Collection<Node>
     */
    public function children(): Collection
    {
        return $this->children;
    }

    /**
     * Get all numeric children.
     *
     * @return Collection<Node>
     */
    public function numericChildren(): Collection
    {
        return $this->children->filter(fn ($node) => is_numeric($node->value()))->values();
    }

    /**
     * Get all non-numeric children.
     *
     * @return Collection<Node>
     */
    public function nonNumericChildren(): Collection
    {
        return $this->children->filter(fn ($node) => !is_numeric($node->value()))->values();
    }

    /**
     * Return the highest parent node of this node.
     */
    public function root(): self|null
    {
        if (empty($this->parent)) {
            return $this;
        }

        $node = $this->parent;
        while (!is_null($node->parent)) {
            $node = $node->parent;
        }
        return $node;
    }

    /**
     * Get the string representation of the node and its children.
     */
    public function toString(): string
    {
        return TreeToStringConverter::run($this);
    }

    /**
     * Append a new child to this node.
     */
    public function appendChild(self $node, bool $top = false): self
    {
        $top ? $this->children->prepend($node) : $this->children->push($node);
        $node->setParent($this);
        return $node;
    }

    /**
     * Remove a child from the $children collection.
     */
    public function removeChild(self $node): void
    {
        $this->children = $this->children->filter(fn ($nodeToCheck) => $nodeToCheck !== $node);
    }

    /**
     * Replace a node with another node, but keep the indexes.
     */
    public function replaceChild(self $nodeToReplace, self $replacement): void
    {
        $index = $this->children->search($nodeToReplace, $strict = true);

        $this->children = $this->children->replace([$index => $replacement]);

        $nodeToReplace->parent = null;
        $replacement->parent = $this;
    }

    /**
     * Set the children of this note.
     *
     * @param Collection<Node> $children
     */
    public function setChildren(Collection $children): self
    {
        $this->children = $children;
        $this->children()->each(fn ($child) => $child->setParent($this));
        return $this;
    }

    /**
     * Set the node's value.
     */
    public function setValue(string|int|float $value): void
    {
        $this->value = $value;
    }

    /**
     * Set the node's parent.
     */
    public function setParent(self|null $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Remove all children from a node.
     */
    public function removeAllChildren(): void
    {
        $this->setChildren(new Collection());
    }

    /**
     * @return Collection<Node>
     */
    public function nestedChildren(): Collection
    {
        $nodes = collect();

        foreach ($this->children as $child) {
            $nodes->push($child);
            $nodes->push($child->callChildren());
        }

        return $nodes->flatten();
    }

    /**
     * @return Node[]
     */
    protected function callChildren(): array
    {
        $nodes = [];

        foreach ($this->children as $child) {
            if ($child->children()->count() > 0) {
                $child->callChildren();
            }

            $nodes[] = $child;
            $nodes[] = $child->children;
        }

        return $nodes;
    }
}
