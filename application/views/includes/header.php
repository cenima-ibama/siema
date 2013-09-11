<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SIEMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de monitoramento de desmatamento">
    <meta name="author" content="Helmuth Saatkamp <helmuthdu@gmail.com>">

    <!-- Leaflet -->
    <link href="//cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" rel="stylesheet" />
    <link href="<?= base_url()?>assets/css/leaflet-markercluster.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/leaflet-minimap.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/leaflet-fullscreen.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/leaflet-control-switch.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/leaflet-control-geosearch.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/leaflet-control-locate.css" rel="stylesheet">
    <!-- <link href="<?= base_url()?>assets/css/leaflet-draw.css" rel="stylesheet"> -->

    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link href="<?= base_url()?>assets/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/bootstrap-switch.css" rel="stylesheet">
    <link href="<?= base_url()?>assets/css/bootstrap-jqueryui.css" rel="stylesheet">

    <!-- Hash5 styles -->
    <link href="<?= base_url()?>assets/css/hash5-charts.css" rel="stylesheet" media="screen">
    <link href="<?= base_url()?>assets/css/hash5-map.css" rel="stylesheet" media="screen">
    <!-- <link href="<?= base_url()?>assets/less/hash5-map.less" rel="stylesheet/less" type="text/css" /> -->
    <!-- <link href="<?= base_url()?>assets/less/hash5-charts.less" rel="stylesheet/less" type="text/css" /> -->

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="<?= base_url()?>assets/css/global.css" rel="stylesheet" media="all">
    <link href="<?= base_url()?>assets/css/layout.css" rel="stylesheet" media="all">

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
  </head>
  <body>
