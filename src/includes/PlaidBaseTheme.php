<?php
/** @file
 *
 * Defines the class PlaidBaseTheme.
 *
 * Created by Matt Butcher on 2011-06-02.
 */

/**
 * Defines PlaidBaseTheme.
 */
class PlaidBaseTheme extends BaseThemePackage {
  
  public function templates() {
    print "registering..." . PHP_EOL;
    return array(
      'head' => 'top.php',
      'body' => 'bottom.php',
      'filter-form' => 'filter-form.php',
    );
  }
  
  public function functions() {
    return array(
      //'target' => callback,
    );
  }
  
  public function preprocessors() {
    return array(
      // 'target' => preprocessor_callback
    );
  }
  
  public function postprocessors() {
    return array(
      //'target' => postprocessor_callback,
    );
  }
}