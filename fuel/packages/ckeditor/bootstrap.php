<?php
/**
 * Ck (CK Editor) Package By Ben Corlett
 * 
 * The Ck Fuel Package is an open-source
 * fuel package that acts as a wrapper for
 * the popular CK Editor jQuery plugin
 * 
 * @package    Fuel
 * @subpackage Ck
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/spark
 */

Autoloader::add_core_namespace('Ck');

Autoloader::add_classes(array(
	'Ck\\Ck'			=> __DIR__ . '/classes/ck.php',
	'CKEditor'			=> __DIR__ . '/vendor/ckeditor.php',
));