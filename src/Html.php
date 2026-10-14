<?php

namespace Behatch;

use Behat\Mink\Session;

/**
 * No abstract getSession() here: no signature is compatible with both
 * mink-extension 2 (untyped) and mink-extension 3 (typed).
 *
 * @method Session getSession(?string $name = null)
 */
trait Html
{
    protected function countElements($element, $index, $parent)
    {
        $page = $this->getSession()->getPage();

        $parents = $page->findAll('css', $parent);
        if (!isset($parents[$index - 1])) {
            throw new \Exception("The $index element '$parent' was not found anywhere in the page");
        }

        $elements = $parents[$index - 1]->findAll('css', $element);

        return \count($elements);
    }

    protected function findElement($selector, $locator, $index)
    {
        $page = $this->getSession()->getPage();

        $nodes = $page->findAll($selector, $locator);

        if (!isset($nodes[$index - 1])) {
            throw new \Exception("The $index $selector '$locator' was not found anywhere in the page");
        }

        return $nodes[$index - 1];
    }
}
