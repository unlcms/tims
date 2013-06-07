<?php

class Tims_Extension extends Twig_Extension
{
  public function getName() {
    return 'tims';
  }

  public function getFunctions() {
    return array(
      new Twig_SimpleFunction('render', function($a) {return drupal_render($a);}),
    );
  }
}
