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
      new Twig_SimpleFunction('drupal_render_children', function($element, $children_keys = NULL) {return drupal_render_children($element, $children_keys);}),
      new Twig_SimpleFunction('file_get_contents', function($a) {return file_get_contents($a);}),
      new Twig_SimpleFunction('node_load', function($nid = NULL, $vid = NULL, $reset = FALSE) {return node_load($nid, $vid, $reset);}),
      new Twig_SimpleFunction('url', function($path = NULL, $options = array()) {return url($path, $options);}),
      new Twig_SimpleFunction('views_embed_view', function($name, $display_id = 'default', $args = array()) {return views_embed_view($name, $display_id, $args);}),
      new Twig_SimpleFunction('block_embed', function($module, $delta) {
        $block = block_load($module, $delta);
        $render_array = _block_get_renderable_array(_block_render_blocks(array($block)));
        $output = drupal_render($render_array);
        print $output;
      }),
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
