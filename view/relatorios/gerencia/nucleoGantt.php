<html>
<head>
<link type="text/css" rel="stylesheet" href="codebase/dhtmlxgantt.css">
<script type="text/javascript" language="JavaScript" src="codebase/dhtmlxcommon.js"></script>
<script type="text/javascript" language="JavaScript" src="codebase/dhtmlxgantt.js"></script>
<script language="JavaScript" type="text/javascript">
    /*<![CDATA[*/
    function createChartControl(htmlDiv1)
    {
		<?
			//require_once '../../../conf/config.php';
			require_once '../../../util/ConectorBD.php';
			require_once '../../../control/RelatorioController.php';
			
			$controller = new RelatorioController;
			
			echo $controller->geraGantt();
		
		?>
		
    }
    /*]]>*/
</script>
<style>
	body {font-size:12px}
	.{font-family:arial;font-size:12px}
	h1 {cursor:hand;font-size:16px;margin-left:10px;line-height:10px}
	xmp {color:green;font-size:12px;margin:0px;font-family:courier;background-color:#e6e6fa;padding:2px}
	.hdr{
		background-color:lightgrey;
		margin-bottom:10px;
		padding-left:10px;
	}
</style>
</head>
<body onload="createChartControl('GanttDiv');">
<div style="width:860px;height:520px;position:absolute;" id="GanttDiv"></div>
</body>
</html>