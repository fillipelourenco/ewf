<?
	include('../../lib/phplot/phplot.php');
	include('../../conf/config.php');
	include('../../util/ConectorBD.php');
	
	session_start();
	
	$erros = pg_query('select count(r.id_requisicao) as qtd, c.nome
							from requisicoes r
							left join componentes c on (c.id_componente=r.id_componente)
						where
							r.id_projeto='.$_SESSION['id_projeto_logado'].' and r.tipo='.$_SESSION['read_tipo'].' group by
							c.id_componente, c.nome order by c.id_componente');
	$data = array();
	$count = 0;
	while($row = pg_fetch_object($erros)) {
		if ($row->nome == null) $row->nome = 'Não Especificado';
		$data[$count] = array($row->nome, $row->qtd);
		$count++;
	}
	
	$plot = new PHPlot(700,400);
	$plot->SetPlotType('pie');
	$plot->SetDataType('text-data-single');
	$plot->SetDataValues($data);
	
	switch ($_SESSION['read_tipo']) {
		case '1':
			$titulo = "Erros Por Componente";
			break;
		case '2':
			$titulo = "Melhorias Por Componente";
			break;
		case '3':
			$titulo = "Sugestões Por Componente";
			break;
	}
	
	$plot->SetTitle($titulo);
	$plot->SetImageBorderType('plain');
	foreach ($data as $row) $plot->SetLegend($row[0]); // Copy labels to legend
	$plot->DrawGraph();

?>
