<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?> xml:lang="<?php echo $lang; ?>" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/<?php echo ($this->config->get('tg_colorthemes_default_color')); ?>.css" media="screen" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>



<link rel="stylesheet" href="catalog/view/javascript/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="catalog/view/javascript/bootstrap/css/bootstrap-responsive.min.css">

<script src="catalog/view/javascript/shownetwork.js"></script>

<script src="catalog/view/javascipt/sigma.js/src/sigma.core.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/conrad.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/utils/sigma.utils.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/utils/sigma.polyfills.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/sigma.settings.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/classes/sigma.classes.dispatcher.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/classes/sigma.classes.configurable.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/classes/sigma.classes.graph.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/classes/sigma.classes.camera.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/classes/sigma.classes.quad.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/captors/sigma.captors.mouse.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/captors/sigma.captors.touch.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/sigma.renderers.canvas.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/sigma.renderers.webgl.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/sigma.renderers.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/webgl/sigma.webgl.nodes.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/webgl/sigma.webgl.nodes.fast.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/webgl/sigma.webgl.edges.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/webgl/sigma.webgl.edges.fast.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/webgl/sigma.webgl.edges.arrow.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.labels.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.hovers.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.nodes.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.edges.def.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.edges.curve.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.edges.arrow.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/renderers/canvas/sigma.canvas.edges.curvedArrow.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/middlewares/sigma.middlewares.rescale.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/middlewares/sigma.middlewares.copy.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/misc/sigma.misc.animation.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/misc/sigma.misc.bindEvents.js"></script>

    <script src="catalog/view/javascipt/sigma.js/src/misc/sigma.misc.drawHovers.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.parsers.gexf/gexf-parser.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.parsers.gexf/sigma.parsers.gexf.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.plugins.neighborhoods/sigma.plugins.neighborhoods.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.plugins.filter/sigma.plugins.filter.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.layout.forceAtlas2/worker.js"></script>

    <script src="catalog/view/javascipt/sigma.js/plugins/sigma.layout.forceAtlas2/supervisor.js"></script>





<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie8.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>
</head>
<body>
<div id="wrapper">
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img height="70px" src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <div id="name"><?php echo $text_name; ?></div>
  <?php } ?>
  <div id="search">
    <div class="button-search"></div>
    <input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
  </div>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php } else { ?>
    <?php } ?>
  </div>	
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a><a href="<?php echo $advance_search; ?>"><?php echo $text_advance_search; ?></a><a href="<?php echo $admin; ?>" target="_blank"><?php echo $text_admin; ?></a></div>
</div>


<div id="main-top"><!-- -->
		
		<div id="main-menu">
			
			<div id="menu-inner">
				
				<div class="menu-left"><!-- --></div>
				<div class="menu-right"><!-- --></div>
				<div class="menu-middle">
					

              						<?php if ($categories) { ?>
										<div id="menu">
  											<ul>
   												 <?php foreach ($categories as $category) { ?>
    											 <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      												<?php if ($category['children']) { ?>
      													<div>
        													<?php for ($i = 0; $i < count($category['children']);) { ?>
        													  <ul>
         														 <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          															<?php for (; $i < $j; $i++) { ?>
          																<?php if (isset($category['children'][$i])) { ?>
         																	 <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          																<?php } ?>
          															<?php } ?>
       										 				  </ul>
        													<?php } ?>
      													</div>
     											   <?php } ?>
    											 </li>
    											<?php } ?>
  											</ul>
									  	</div> <!-- menu (end) -->
									<?php } ?>
				</div><!-- .menu-middle (end) -->
			</div><!-- .menu-inner (end) -->
		
			
		</div><!-- #main-menu (end) -->
</div><!-- #main-top (end) -->

</div><!-- #container (end) -->

<div id="container2">

<div class="inner"> 
<div id="notification"></div>