<?php
// auto-generated by sfRoutingConfigHandler
// date: 2018/01/19 15:02:11
return array(
'jobeet_category' => new sfPropelRouteCollection(array (
  'model' => 'JobeetCategory',
  'module' => 'category',
  'prefix_path' => 'category',
  'column' => 'id',
  'with_wildcard_routes' => true,
  'name' => 'jobeet_category',
  'requirements' => 
  array (
  ),
)),
'jobeet_job' => new sfPropelRouteCollection(array (
  'model' => 'JobeetJob',
  'module' => 'job',
  'prefix_path' => 'job',
  'column' => 'id',
  'with_wildcard_routes' => true,
  'name' => 'jobeet_job',
  'requirements' => 
  array (
  ),
)),
'homepage' => new sfRoute('/', array (
  'module' => 'job',
  'action' => 'index',
), array (
), array (
)),
'default_index' => new sfRoute('/:module', array (
  'action' => 'index',
), array (
), array (
)),
'default' => new sfRoute('/:module/:action/*', array (
), array (
), array (
)),
);
