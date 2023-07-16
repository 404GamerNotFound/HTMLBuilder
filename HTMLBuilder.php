<?php
/**
 * Description of HTMLBuilder
 *
 * @author 
 */
class HTMLBuilder {
    private $elements = [];

    public function createElement($tag, $content = '', $attributes = []) {
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

    public function addChild($parentTag, $childTag) {
        $parentIndices = $this->findElementIndicesByTag($parentTag);
        $childIndex = $this->findElementIndexByTag($childTag);
        
        if (!empty($parentIndices) && $childIndex !== false) {
            $parentIndex = end($parentIndices);
            $this->elements[$parentIndex] = str_replace(">", ">$childTag", $this->elements[$parentIndex]);
            unset($this->elements[$childIndex]);
        }
        
        return $this;
    }

    private function findElementIndicesByTag($tag) {
        $indices = [];
        foreach ($this->elements as $index => $element) {
            if (strpos($element, "<$tag") === 0) {
                $indices[] = $index;
            }
        }
        return $indices;
    }

    private function findElementIndexByTag($tag) {
        foreach ($this->elements as $index => $element) {
            if (strpos($element, "<$tag") === 0) {
                return $index;
            }
        }
        return false;
    }

    public function build() {
        return implode('', $this->elements);
    }
}


$htmlBuilder = new HTMLBuilder();
$html = $htmlBuilder
    ->createElement('div')
    ->createElement('h1', 'Hallo, Welt!')
    ->createElement('div')
    ->addChild('div', 'h1') // Hinzufügen von h1 als Kind des letzten div-Tags
    ->build();

echo $html;


$htmlBuilder2 = new HTMLBuilder();
$html2 = $htmlBuilder
    ->createElement('a', 'Klick mich', ['href' => 'https://www.example.com', 'target' => '_blank'])
    ->build();

echo $html2;
