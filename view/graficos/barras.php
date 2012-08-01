<?php
	
	include('../../lib/phplot/phplot.php');
	include('../../conf/config.php');
	include('../../util/ConectorBD.php');
	
	session_start();
	
	$pessimo = 'select count(*) as qtd
					from formularios f
					join perguntas p on (p.id_formulario=f.id_formulario)
					join respostas r  on (p.id_pergunta=r.id_pergunta)';
					
	if ($_SESSION['id_componente'] != '0') 
		$pessimo .= ' join componentes c on (c.id_componente=p.id_componente)';
	
	$pessimo .= ' where r.resposta like \'1\'
					and p.tipo=0
					and f.id_formulario='.$_SESSION['read_formulario'].'';
	
	if ($_SESSION['id_componente'] != '0')
		$pessimo .= ' and c.id_componente='.$_SESSION['id_componente'].'';
		
	$pessimo = pg_query($pessimo);
	$pessimo = pg_fetch_object($pessimo);
	
	$regular = 'select count(*) as qtd
					from formularios f
					join perguntas p on (p.id_formulario=f.id_formulario)
					join respostas r  on (p.id_pergunta=r.id_pergunta)';
					
	if ($_SESSION['id_componente'] != '0') 
		$regular .= ' join componentes c on (c.id_componente=p.id_componente)';
	
	$regular .= ' where r.resposta like \'2\'
					and p.tipo=0
					and f.id_formulario='.$_SESSION['read_formulario'].'';
	
	if ($_SESSION['id_componente'] != '0')
		$regular .= ' and c.id_componente='.$_SESSION['id_componente'].'';
	
	$regular = pg_query($regular);
	$regular = pg_fetch_object($regular);
	
	$bom = 'select count(*) as qtd
					from formularios f
					join perguntas p on (p.id_formulario=f.id_formulario)
					join respostas r  on (p.id_pergunta=r.id_pergunta)';
					
	if ($_SESSION['id_componente'] != '0') 
		$bom .= ' join componentes c on (c.id_componente=p.id_componente)';
	
	$bom .= ' where r.resposta like \'3\'
					and p.tipo=0
					and f.id_formulario='.$_SESSION['read_formulario'].'';
	
	if ($_SESSION['id_componente'] != '0')
		$bom .= ' and c.id_componente='.$_SESSION['id_componente'].'';
	
	$bom = pg_query($bom);
	$bom = pg_fetch_object($bom);
	
	$otimo = 'select count(*) as qtd
					from formularios f
					join perguntas p on (p.id_formulario=f.id_formulario)
					join respostas r  on (p.id_pergunta=r.id_pergunta)';
					
	if ($_SESSION['id_componente'] != '0') 
		$otimo .= ' join componentes c on (c.id_componente=p.id_componente)';
	
	$otimo .= ' where r.resposta like \'4\'
					and p.tipo=0
					and f.id_formulario='.$_SESSION['read_formulario'].'';
	
	if ($_SESSION['id_componente'] != '0')
		$otimo .= ' and c.id_componente='.$_SESSION['id_componente'].'';
	
	$otimo = pg_query($otimo);
	$otimo = pg_fetch_object($otimo);
	
	$excelente = 'select count(*) as qtd
					from formularios f
					join perguntas p on (p.id_formulario=f.id_formulario)
					join respostas r  on (p.id_pergunta=r.id_pergunta)';
					
	if ($_SESSION['id_componente'] != '0') 
		$excelente .= ' join componentes c on (c.id_componente=p.id_componente)';
	
	$excelente .= ' where r.resposta like \'5\'
					and p.tipo=0
					and f.id_formulario='.$_SESSION['read_formulario'].'';
	
	if ($_SESSION['id_componente'] != '0')
		$excelente .= ' and c.id_componente='.$_SESSION['id_componente'].'';
	
	$excelente = pg_query($excelente);
	$excelente = pg_fetch_object($excelente);
	
	$formulario = new Formulario;
	$formulario->get($_SESSION['read_formulario']);
	
	$versao = new Versao;
	$versao->get($formulario->id_versao);
	
	if ($_SESSION['id_componente'] != '0') {
		$componente = new Componente;
		$componente->get($_SESSION['id_componente']);
	}
	
	
	#Matriz utilizada para gerar os graficos
	$data = array(
	  array('', $excelente->qtd, $otimo->qtd, $bom->qtd, $regular->qtd, $pessimo->qtd)
	);
	#Instancia o objeto e setando o tamanho do grafico na tela
	$plot = new PHPlot(700,400);
	#Tipo de borda, consulte a documentacao
	$plot->SetImageBorderType('plain');
	#Tipo de grafico, nesse caso barras, existem diversos(pizza…)
	$plot->SetPlotType('bars');
	#Tipo de dados, nesse caso texto que esta no array
	$plot->SetDataType('text-data');
	#Setando os valores com os dados do array
	$plot->SetDataValues($data);
	#Titulo do grafico
	
	$titulo = $_SESSION['chave_formulario'].' Versão '.$versao->master_version.'.'.$versao->great_version.'.'.$versao->average_version.'.'.$versao->small_version.'';
	if ($_SESSION['id_componente'] != '0')
		$titulo .= ' / '.$componente->nome;
	$total = ($excelente->qtd)+($otimo->qtd)+($bom->qtd)+($regular->qtd)+($pessimo->qtd);
	$titulo .= ' Total: '.$total.' respostas';
	$plot->SetTitle($titulo);
	#Legenda, nesse caso serao tres pq o array possui 3 valores que serao apresentados
	$plot->SetLegend(array('Excelente','Ótimo','Bom', 'Regular','Péssimo'));
	#Utilizados p/ marcar labels, necessario mas nao se aplica neste ex. (manual) :
	$plot->SetXTickLabelPos('none');
	$plot->SetXTickPos('none');
	#Gera o grafico na tela
	$plot->DrawGraph();
?>