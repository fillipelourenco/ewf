<style type="text/css">
<!--
.pgoff {
	font-family: Verdana, Arial, Helvetica; 
	font-size: 11px; 
	color: #000; 
	text-decoration: none
}
a.pg {
	font-family: Verdana, Arial, Helvetica; 
	font-size: 11px; 
	color: #8a8a8a; 
	text-decoration: none
}
a:hover.pg {
	font-family: Verdana, Arial, Helvetica; 
	font-size: 11px; 
	color: #8a8a8a; 
	text-decoration:underline}
-->
</style>
<?
        $quant_pg = ceil($quantreg/$numreg);
        $quant_pg++;
        
        // Verifica se esta na primeira p�gina, se nao estiver ele libera o link para anterior
        if ( $_GET['pg'] > 0) { 
                $result .= "<a href=".$PHP_SELF."?pg=".($_GET['pg']-1) ." class=pg><b>&laquo; anterior</b></a>";
        } else { 
                //$result .= "<a class=pgoff>&laquo; anterior</a>";
        }
        
        // Faz aparecer os numeros das p�gina entre o ANTERIOR e PROXIMO
        for($i_pg=1;$i_pg<$quant_pg;$i_pg++) { 
                // Verifica se a p�gina que o navegante esta e retira o link do n�mero para identificar visualmente
                if ($_GET['pg'] == ($i_pg-1)) { 
                        $result .= "&nbsp;<span class=pgoff>[$i_pg]</span>&nbsp;";
                } else {
                        $i_pg2 = $i_pg-1;
                        $result .= "&nbsp;<a href=".$PHP_SELF."?pg=$i_pg2 class=pg><b>$i_pg</b></a>&nbsp;";
                }
        }
        
        // Verifica se esta na ultima p�gina, se nao estiver ele libera o link para pr�xima
        if (($_GET['pg']+2) < $quant_pg) { 
                $result .= "<a href=".$PHP_SELF."?pg=".($_GET['pg']+1)." class=pg><b>pr�ximo &raquo;</b></a>";
        } else { 
                //$result .= "<a class=pg>pr�ximo &raquo;</a>";
        }
?>