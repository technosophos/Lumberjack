<?php
/** @page commands.php
 * The commands.php file is the main configuration file for Fortissimo.
 *
 * There are (at least) six different facilities you can configure in this file:
 *
 * - requests: This is how you instruct Fortissimo on how to handle inbound requests. Essentially,
 *   you map a request to a chain of commands. The default request is 'default'. For more on 
 *   requests, check out doc/Command-examples.mdown and doc/QUICKSTART.mdown.
 * - groups: Groups are an abstraction that allows you to declare a particular chain of commands, 
 *   but not assign them to a request. You can import groups into requests wherever you want.
 *   Effectively, this allows you to create a group of related commands that you can later use
 *   in multiple requests.
 * - datasources: Does your appplication use MySQL, MongoDB or some other data source? You can 
 *   declare the datasource here, which then makes it available throughout the application. See
 *   the FortissimoMongoDatasource class for a simple example of declaring a datasource.
 * - loggers: Fortissimo provides a lightweight and extensible logging framework. You can tell it 
 *   where to log messages by declaring a logger. The built in FAIL and FOIL loggers provide good
 *   starting points for displaying logged messages in the browser.
 * - caches: Fortissimo supports caching at the highest levels. You can declare your caches in the
 *   configuration file, and Fortissimo will try to handle all of the actual caching for you. This
 *   is more advanced, and is generally only needed on high-traffic apps.
 * - include paths: Fortissimo uses a PHP autoloader to find and include classes. You can tell
 *   Fortissimo what paths to use when seeking for classes.
 */

/**
 * @section include_path_config Include Paths
 *
 * To declare a new include path, you will want to use code like this:
 *
 * @code
 * <?php
 * Config::includePath('path/to/some/classes');
 * ?>
 * @endcode
 *
 * By default, Fortissimo uses a flat namespace (no deeply nested directories) because Fortissimo 
 * itself is a thin framework.
 */
// Config::includePath('includes/MyClasses');

Config::includePath('core/Fortissimo/Theme');
//Config::includePath('theme/plaid');

/**
 * @section datasource_config Datasources
 * Fortissimo provides a very thin database abstraction layer.
 *
 * To use it with MongoDB, simply customize the setup below. To use another
 * database, implement FortissimoDatasource, and then use the implementing
 * class in the invoke method here.
 *
 * @code
 * Config::datasource('db') // Name of datasource
 *   ->invokes('FortissimoMongoDatasource') // The class it uses
 *   // Parameters for the FortissimoMongoDatasource:
 *   ->withParam('server')->whoseValueIs('mongodb://localhost:27017')
 *   ->withParam('defaultDB')->whoseValueIs('my_db_name')
 *   ->withParam('isDefault')->whoseValueIs(TRUE) // Only datasource one can be default.
 * ;
 * @endcode
 *
 * You can use as many datasources as you want. Just give each one a different
 * name.
 */
Config::datasource('db') // Name of datasource
  ->whichInvokes('FortissimoMongoDatasource') // The class it uses
  ->withParam('server')->whoseValueIs('mongodb://localhost:27017')
  ->withParam('defaultDB')->whoseValueIs('Lumberjack')
  ->withParam('isDefault')->whoseValueIs(TRUE) // Only datasource one can be default.
;


/**
 * @section group_config Groups
 * A group is a grouping of commands that cannot be executed as a request.
 *
 * While they are not directly executed (ever), they can be included into a request. See the 
 * example in the section on requests.
 *
 * Example:
 *
 * @code
 * <?php
 * Config::group('bootstrap')
 *   ->doesCommand('some_command')
 *     ->whichInvokes('SomeCommandClass')
 *     ->withParam('some_param')
 *       ->whoseValueIs('some value');
 *   ->doesCommand('some_other_command')->whichInvokes('SomeOtherCommandClass')
 * ;
 * ?>
 * @endcode
 * 
 * The above defines a group with a chain of two commands. The first has a single parameter. The 
 * second has no parameters.
 */
Config::group('web_boot')
  ->doesCommand('initTheme')
    ->whichInvokes('InitializeTheme')
    ->withParam('path')->whoseValueIs('theme/plaid')
    ->withParam('register')->whoseValueIs(array(
      'PlaidBaseTheme',
    ))
    ->withParam('settings')->whoseValueIs(array(
      'site_title' => 'Lumberjack',
    ));
;

/**
 * @section request_config Requests
 *
 * This part of the configuration file is used for mapping an inbound request to a 
 * chain of commands. Fortissimo will begin with the first command and process commands
 * one at a time until the chain has completed (or some error condition has occurred.)
 *
 * @code
 * <?php
 * Config::request('default')
 *  // Bootstrap
 *   ->usesGroup('bootstrap')
 *   // Initialize the context with some values.
 *   ->doesCommand('initContext')
 *     ->whichInvokes('FortissimoAddToContext')
 *     ->withParam('title')
 *       ->whoseValueIs('Lumberjack')
 *     ->withParam('welcome')
 *       ->whoseValueIs('Fortissimo has been successfully installed.')
 *   // Use the template engine to generate a welcome page.
 *   ->doesCommand('tpl')
 *     ->whichInvokes('FortissimoTemplate')
 *     ->withParam('template')
 *       ->whoseValueIs('example.twig')
 *     ->withParam('templateDir')
 *       ->whoseValueIs('theme/vanilla')
 *     ->withParam('templateCache')
 *       ->whoseValueIs('.cache')
 *     ->withParam('disableCache')
 *       ->whoseValueIs(FALSE)
 *     // ->withParam('debug')->whoseValueIs(FALSE)
 *     // ->withParam('trimBlocks')->whoseValueIs(TRUE)
 *     // ->withParam('auto_reload')->whoseValueIs(FALSE)
 *
 *   // Send the rendered welcome page to the browser.
 *   ->doesCommand('echo')
 *     ->whichInvokes('FortissimoEcho')
 *     ->from('context:tpl')
 * ;
 * @endcode
 *
 * A request can have two things in its chain: commands and groups.
 */
Config::request('default')
  // Bootstrap
  //->usesGroup('bootstrap')
  ->usesGroup('web_boot')
  // Initialize the context with some values.
  ->doesCommand('initContext')
    ->whichInvokes('FortissimoAddToContext')
    ->withParam('title')
      ->whoseValueIs('Lumberjack')
    ->withParam('welcome')
      ->whoseValueIs('Fortissimo has been successfully installed.')
  
  // Header: Send it early, because data processing may take a while.
  ->doesCommand('createHeader')
    ->whichInvokes('RenderTheme')
    ->withParam('theme')->whoseValueIs('head')
    ->withParam('variables')->whoseValueIs(array('title' => 'Lumberjack'))
  ->doesCommand('echoHeader')
    ->whichInvokes('FortissimoEcho')
    ->withParam('text')
      ->from('context:createHeader')
  
  ->doesCommand('createfooter')
    ->whichInvokes('RenderTheme')
    ->withParam('theme')->whoseValueIs('foot')
    ->withParam('variables')->whoseValueIs(array())
  ->doesCommand('echoFooter')
    ->whichInvokes('FortissimoEcho')
    ->withParam('text')
      ->from('context:createFooter')
  // ->doesCommand('dump')
  //   ->whichInvokes('FortissimoContextDump')
    //->withParam('collection')->from('cxt:facilities_report')
;

Config::request('recent')
  ->doesCommand('recent_entries')
    ->whichInvokes('RecentEntriesQuery')
    ->withParam('filter')->whoseValueIs(array('level' => 0))
  ->doesCommand('print')
    ->whichInvokes('PrintRawLog')
    ->withParam('cursor')->from('cxt:recent_entries')
;

Config::request('drupal-recent')
  ->doesCommand('recent_entries')
    ->whichInvokes('RecentEntriesQuery')
    ->withParam('filter')->whoseValueIs(array('type' => 'drupal'))
  ->doesCommand('print')
    ->whichInvokes('PrintDrupalLog')
    ->withParam('cursor')->from('cxt:recent_entries')
;

Config::request('drupal-summary')
  ->doesCommand('facilities_report')
    ->whichInvokes('DrupalFacilitiesReport')
    ->withParam('since')->whoseValueIs('May 30, 2011')
  /*
  ->doesCommand('dump')
    ->whichInvokes('DumpMongoCollection')
    ->withParam('collection')->from('cxt:facilities_report')
  */
  ->doesCommand('query')
    ->whichInvokes('SimpleLogQuery')
    ->withParam('collection')->from('cxt:facilities_report')
    ->withParam('filter')->whoseValueIs(array()) // Match all records.
    ->withParam('sort')->whoseValueIs(array('value' => -1))
  ->doesCommand('print')
    ->whichInvokes('PrintEachRow')
    ->withParam('iterable')->from('cxt:query')
    ->withParam('format')->whoseValueIs("%s: %s\n")
    ->withParam('fields')->whoseValueIs(array('_id', 'value'))
;

Config::request('date2time')
  ->doesCommand('d2t')->whichInvokes('ParseDate')->withParam('date')->from('argv:2')
  ->doesCommand('print')->whichInvokes('FortissimoEcho')->withParam('text')->from('cxt:d2t')
  ->doesCommand('print2')->whichInvokes('FortissimoEcho')->withParam('text')->whoseValueIs(PHP_EOL)
;

Config::request('query-log')
  ->doesCommand('filter')
    ->whichInvokes('ParseJSON')
    ->withParam('json')->from('argv:2')
  ->doesCommand('query')
    ->whichInvokes('SimpleLogQuery')
    ->withParam('filter')->from('cxt:filter')
    ->withParam('sort')->whoseValueIs(array('date' => 1))
    ->withParam('fields')->whoseValueIs(array('raw'))
  ->doesCommand('print')
    ->whichInvokes('PrintRawLog')
    ->withParam('cursor')->from('cxt:query')
  /*->doesCommand('dump')
    ->whichInvokes('DumpMongoCollection')
    ->withParam('collection')->whoseValueIs('logs')
    */

;

Config::request('apachelog')
  ->doesCommand('apachelog')
    ->whichInvokes('ApacheErrorLogParser')
    ->withParam('file')
      ->from('argv:2')
    //  ->whoseValueIs('/Users/mbutcher/Code/ConsumerSearch/logs/error-web1.log')
;

Config::request('drupallog')
  ->doesCommand('drupallog')
    ->whichInvokes('DrupalSyslogParser')
    ->withParam('file')
      ->from('argv:2')
      //->whoseValueIs('/Users/mbutcher/Code/ConsumerSearch/logs/drupal-nycsweb1.log')
;

Config::request('varnishncsalog')
  ->doesCommand('varnishlog')
    ->whichInvokes('VarnishNCSALogParser')
    ->withParam('file')
      ->from('argv:2')
      //->whoseValueIs('/Users/mbutcher/Code/ConsumerSearch/logs/nycscache1.log.0')
;

/**
 * @section logger_config Loggers
 * 
 * You can configure Fortissimo to log to one or more logging backends.
 *
 * @code
 * Config::logger('foil')
 *  ->whichInvokes('FortissimoOutputInjectionLogger')
 * ;
 * @endcode
 *
 * The code above configures Fortissimo's FOIL logger, which simply logs all errors into Standard
 * Output. Another built-in logger is FortissimoArrayInjectionLogger (FAIL), which logs messages
 * into an array for later retrieval.
 *
 * @code
 * Config::logger('fail')
 *   ->whichInvokes('ForitissimoArrayInjectionLogger')
 * ;
 * @endcode
 *
 * New loggers can be created very easily. See the FortissimoOutputInjectionLogger code for an 
 * example.
 */
Config::logger('foil')
  ->whichInvokes('FortissimoOutputInjectionLogger')
;
