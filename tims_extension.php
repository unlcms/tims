<?php

/**
 * A Twig Extension to call drupal_render() either explicitly as a function,
 * or implicitly when an array would otherwise be printed as "Array".
 */
class Tims_Extension extends Twig_Extension
{
  public function getName() {
    return 'tims';
  }

  public function getFunctions() {
    return array(
      new Twig_SimpleFunction('render', function($a) {return drupal_render($a);}),
      new Twig_SimpleFunction('file_get_contents', function($a) {return file_get_contents($a);}),
    );
  }

  public function getNodeVisitors()
  {
    return array(new Tims_NodeVisitor());
  }
}

/**
 * Alters the Twig parser to convert all Twig_Node_Print nodes to Tims_NodePrint.
 */
class Tims_NodeVisitor implements Twig_NodeVisitorInterface
{
  /**
   * Replace all Twig_Node_Print nodes with our own version.
   */
  public function enterNode(Twig_NodeInterface $node, Twig_Environment $env) {
    if (!$node instanceof Twig_Node_Print) {
      return $node;
    }
    return new Tims_NodePrint($node->getNode('expr'), $node->getLine(), $node->getNodeTag());
  }

  /**
   * Stub implementation required by interface
   */
  public function leaveNode(Twig_NodeInterface $node, Twig_Environment $env) {
    return $node;
  }

  /**
   * Stub implementation required by interface
   */
  public function getPriority() {
    return 0;
  }
}

/**
 * Overrides the default Twig_Node_Print::complie() to first drupal_render() any arrays.
 */
class Tims_NodePrint extends Twig_Node_Print {
  public function compile(Twig_Compiler $compiler) {
    $compiler
      ->addDebugInfo($this)
      ->write('$dataToPrint = ')
      ->subcompile($this->getNode('expr'))
      ->raw(";\n")
      ->write('echo is_array($dataToPrint) ? drupal_render($dataToPrint) : $dataToPrint')
      ->raw(";\n")
    ;
  }
}
