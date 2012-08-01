<?
  $nom = 'calendario.php'; // nome deste arquivo

  $tmp = $_GET["tmpx"];

  if($tmp){
    $var = checkdate(date("m",$tmp),date("d",$tmp),date("Y",$tmp));
    if($var == 1){
      $data = date("d/m/Y",$tmp);
    } else {
      $data = date("d/m/Y");
    }
  } else {
    $data = date("d/m/Y");
  }

  $dxx = substr($data,0,2);
  $mxx = substr($data,3,2);
  $axx = substr($data,6,4);
  
  $formulario = $_GET["formulario"];
  $campo      = $_GET["campo"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
  <TITLE>Calendário</TITLE>
  <META http-equiv=Content-Type content="text/html; charset=windows-1252">
  <LINK href="../ci/css/calendario.css" type="text/css" rel="stylesheet">

<SCRIPT language="javascript">
  function valor(valor){
    opener.document.<? echo $formulario; ?>.<? echo $campo; ?>.value = valor;
    window.self.close();
  }
</SCRIPT>
</HEAD>
<BODY>

<link rel="stylesheet" type="text/css" href="../ci/css/calendario.css">
  <table align="center" border="0" cellspacing="0" cellpadding="0" BGCOLOR="#999999">
    <tr>
      <td>
<?
class calendario{
  var $mes = array(
                   '01' => 'JANEIRO',
                   '02' => 'FEVEREIRO',
                   '03' => 'MARÇO',
                   '04' => 'ABRIL',
                   '05' => 'MAIO',
                   '06' => 'JUNHO',
                   '07' => 'JULHO',
                   '08' => 'AGOSTO',
                   '09' => 'SETEMBRO',
                   '10' => 'OUTUBRO',
                   '11' => 'NOVEMBRO',
                   '12' => 'DEZEMBRO'
                  );

  function mes_anterior($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    if($mes == 1){
       $man = 12;
       $aan = $ano - 1;
    } else {
       $man = $mes - 1;
       $aan = $ano;
    }
    $val = checkdate($man,$dia,$aan);
    if($val == 0){
      $dia = 1;
    }
    $tmp = mktime(0,0,0,$man,$dia,$aan);
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">«</a>';
  }

  function mes_proximo($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    if($mes == 12){
       $mpr = 1;
       $apr = $ano + 1;
    } else {
       $mpr = $mes + 1;
       $apr = $ano;
    }

    $val = checkdate($mpr,$dia,$apr);
    if($val == 0){
      $dia = 1;
    }
    $tmp = mktime(0,0,0,$mpr,$dia,$apr);
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">»</a>';
  }

  function ano_anterior($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    $aan = $ano - 1;

    $val = checkdate($mes,$dia,$aan);
    if($val == 0){
      $dia = 1;
    }

    $tmp = mktime(0,0,0,$mes,$dia,$aan);
    
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">«</a>';
  }

  function ano_proximo($dia,$mes,$ano){
    global $nom, $formulario, $campo;
    $apr = $ano + 1;

    $val = checkdate($mes,$dia,$apr);
    if($val == 0){
      $dia = 1;
    }

    $tmp = mktime(0,0,0,$mes,$dia,$apr);
    
    echo '<a href="'.$nom.'?tmpx='.$tmp.'&formulario='.$formulario.'&campo='.$campo.'">»</a>';
  }

  function cria($data){
    global $nom, $dxx, $mxx, $axx, $formulario, $campo;
    $arr = explode("/",$data);
    $dia = $arr[0];
    $mes = $arr[1];
    $ano = $arr[2];

    if(($dia == '') OR ($mes = '') OR ($ano = '')){
      $data = date("d/m/Y");
      $arr = explode("/",$data);
      $dia = $arr[0];
      $mes = $arr[1];
      $ano = $arr[2];
    }

    $arr = explode("/",$data);
    $dia = $arr[0];
    $mes = $arr[1];
    $ano = $arr[2];

    $val = checkdate($mes,$dia,$ano); // Verifica se a data é válida
    if($val == 1){
      $ver = date('d/m/Y', mktime(0,0,0,$mes,$dia,$ano));
    } else {
      $ver = date('d/m/Y', mktime(0,0,0,date(m),date(d),date(Y)));
    }

    $arr = explode("/",$ver);
    $dia = $arr[0];
    $mes = $arr[1];
    $ano = $arr[2];

    $ult = date("d", mktime(0,0,0,$mes+1,0,$ano));
    $dse = date("w", mktime(0,0,0,$mes,1,$ano));

    $tot = $ult+$dse;
    if($tot != 0){
      $tot = $tot+7-($tot%7);
    }

    for($i=0;$i<$tot;$i++){
      $dat = $i-$dse+1;
      if(($i >= $dse) AND ($i < ($dse+$ult))){
        $aux[$i]  = '
          <td ';

        if(($dat == $dxx) AND ($mes == $mxx) AND ($ano == $axx)){
          $aux[$i] .= 'class="calendario_dias_hoje"';
        } else {
          $aux[$i] .= 'class="calendario_dias"';
        }

        $aux[$i] .= '><a href="'.$nom.'?data='.sprintf("%02.0f",$dat).'/'.$mes.'/'.$ano.'&formulario='.$formulario.'&campo='.$campo.'" onclick="valor(\''.sprintf("%02.0f",$dat).'/'.$mes.'/'.$ano.'\')">'.$dat.'</a>
          </td>
        ';
      } else {
        $aux[$i] = '
          <td>
          </td>
        ';
    }

    if(($i%7) == 0){
      $aux[$i] = '<tr align="center">'.$aux[$i];
    }

    if(($i%7) == 6){
      $aux[$i] .= '</tr>';
    }
  }

  echo '
  <table cellspacing="0" cellpadding="0" class="calendario_tabela">
    <tr>
      <td>
        <table cellspacing="1" cellpadding="1">
          <tr class="calendario_mes_ano">
            <td>
  ';
  $this->mes_anterior($dia,$mes,$ano);
  echo '
            </td>
            <td colspan="5">'.$this->mes[$mes].'</td>
            <td>
  ';
  $this->mes_proximo($dia,$mes,$ano);
  echo '
</td>
          </tr>

          <tr class="calendario_mes_ano">
            <td>
  ';
  $this->ano_anterior($dia,$mes,$ano);
  echo '
            </td>
            <td colspan="5">'.$ano.'</td>
            <td>
  ';
  $this->ano_proximo($dia,$mes,$ano);
  echo '
            </td>
          </tr>

          <tr class="calendario_semana">
            <td WIDTH="30">D</td>
            <td WIDTH="30">S</td>
            <td WIDTH="30">T</td>
            <td WIDTH="30">Q</td>
            <td WIDTH="30">Q</td>
            <td WIDTH="30">S</td>
            <td WIDTH="30">S</td>
          </tr>
  ';
  echo implode(' ',$aux);
  if(count($aux) == 35){
    echo '
          <tr>
            <td colspan="7">&nbsp;</td>
          </tr>
    ';
  };
  echo '
          <tr>
            <td class="calendario_mes_ano" colspan="7" align="center">[ <a href="'.$nom.'?data='.date(d).'/'.date(m).'/'.date(Y).'&formulario='.$formulario.'&campo='.$campo.'" onclick="valor(\''.date(d).'/'.date(m).'/'.date(Y).'\');">Hoje</a> ]</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  ';
   }
}

$teste = new calendario;
$teste->cria($data);
?>
      </td>
    </tr>
  </table>
</body>
</html>
