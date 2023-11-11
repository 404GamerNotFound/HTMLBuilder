<?php
/**
 * 
 * Class HTMLBuilder
 * @author Tony Brüser
 * 
 * A utility class for generating HTML elements and building them together.
 */
class HTMLBuilder {
    /**
     * @var array The array of HTML elements.
     */
    private $elements = [];

    /**
     * Create an HTML element.
     *
     * @param string $tag The HTML tag.
     * @param string $content The content of the element.
     * @param array $attributes The attributes of the element.
     * @return HTMLBuilder The HTMLBuilder instance.
     */
    public function createElement(string $tag, string $content = '', array $attributes = []): HTMLBuilder {
        $element = "<$tag";

        foreach ($attributes as $key => $value) {
            $element .= " $key=\"$value\"";
        }

        if (empty($content)) {
            $element .= "/>";
        } else {
            $element .= ">$content</$tag>";
        }

        $this->elements[] = $element;
        return $this;
    }

    /**
     * Add an existing HTML element to the builder.
     *
     * @param string $element The HTML element.
     * @return HTMLBuilder The HTMLBuilder instance.
     */
    public function addElement(string $element): HTMLBuilder {
        $this->elements[] = $element;
        return $this;
    }

    /**
     * Add a child element to a parent element.
     *
     * @param string $parentTag The tag name of the parent element.
     * @param string $childTag The tag name of the child element.
     * @return HTMLBuilder The HTMLBuilder instance.
     */
    public function addChild(string $parentTag, string $childTag): HTMLBuilder {
        $parentIndices = $this->findElementIndicesByTag($parentTag);
        $childIndex = $this->findElementIndexByTag($childTag);

        if (!empty($parentIndices) && $childIndex !== false) {
            $parentIndex = end($parentIndices);
            $this->elements[$parentIndex] = str_replace(">", ">$childTag", $this->elements[$parentIndex]);
            unset($this->elements[$childIndex]);
        }

        return $this;
    }

    /**
     * Find the indices of elements with a specific tag.
     *
     * @param string $tag The tag name to search for.
     * @return array The array of indices.
     */
    private function findElementIndicesByTag(string $tag): array {
        $indices = [];
        foreach ($this->elements as $index => $element) {
            if (strpos($element, "<$tag") === 0) {
                $indices[] = $index;
            }
        }
        return $indices;
    }

    /**
     * Find the index of the element with a specific tag.
     *
     * @param string $tag The tag name to search for.
     * @return int|false The index of the element if found, otherwise false.
     */
    private function findElementIndexByTag(string $tag) {
        foreach ($this->elements as $index => $element) {
            if (strpos($element, "<$tag") === 0) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Build the HTML structure from the elements.
     *
     * @return string The final HTML structure.
     */
    public function build(): string {
        return implode('', $this->elements);
    }
}
