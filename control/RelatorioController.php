<?

	class RelatorioController {
	
		function RelatorioController() {}
		
		/**
		* Gera o Diagrama de Gantt
		* Retorno: String
		*/
		function geraGantt(){
			
			session_start();
			
			$inicio = pg_query("select inicio from projetos where id_projeto=".$_SESSION['id_projeto_logado']."");
			$inicio = pg_fetch_object($inicio);
			$data = explode('-', $inicio->inicio);
			
			$result .= 'var '.$_SESSION['login_projeto_logado'].' = new GanttProjectInfo(1, "'.$_SESSION['nome_projeto_logado'].'", new Date('.$data[0].','.($data[1]-1).','.$data[2].'));';
			
			$filhas = pg_query("select id_tarefa, titulo, prazo, estimativa, chave, dependencia, situacao, pai from tarefas where id_projeto=".$_SESSION['id_projeto_logado']." and (pai <> '') order by prazo");
			
			$ignore = array();
			$pais = array();
			$c = 1;
			
			while ($row = pg_fetch_object($filhas)) {
				$ignore[$c] = $row->id_tarefa;
				$pais[$row->pai] = $row;
				$c++;
			}
			
			foreach ($pais as $row) {
				$pai = pg_query("select id_tarefa, titulo, prazo, estimativa, chave, dependencia, situacao from tarefas where id_projeto=".$_SESSION['id_projeto_logado']." and (chave = '".$row->pai."') order by prazo");
				$pai =  pg_fetch_object($pai);
				$horas = (int)($pai->estimativa/8);
				$data = date('d/m/Y', strtotime("-".(($horas-1)*24)." hours",strtotime($pai->prazo)));
				$data = explode('/', $data);
				if ($pai->situacao == 4) 
					$percent = 100;
				else
					$percent = 0;

				$result .= 'var task'.substr($pai->chave, 1).' = new GanttTaskInfo('.substr($pai->chave, 1).', "'.$pai->titulo.'", new Date('.$data[2].','.($data[1]-1).','.$data[0].'), '.$pai->estimativa.', '.$percent.', "'.substr($pai->dependencia, 1).'");';
				$nPai = 'task'.substr($pai->chave, 1).'';
				$ignore[$c] = $pai->id_tarefa;
				$filhas = pg_query("select id_tarefa, titulo, prazo, estimativa, chave, dependencia, situacao from tarefas where id_projeto=".$_SESSION['id_projeto_logado']." and (pai = '".$row->pai."') order by prazo");
				while ($filha =  pg_fetch_object($filhas)) {
					if ($filha->situacao == 4) 
					$percent = 100;
				else
					$percent = 0;
					$horas = (int)($filha->estimativa/8);
					$data = date('d/m/Y', strtotime("-".(($horas-1)*24)." hours",strtotime($filha->prazo)));
					$data = explode('/', $data);
					$result .= $nPai.'.addChildTask(new GanttTaskInfo('.substr($filha->chave, 1).', "'.$filha->titulo.'", new Date('.$data[2].','.($data[1]-1).','.$data[0].'), '.$filha->estimativa.', '.$percent.', "'.substr($filha->dependencia, 1).'"));';
				}
				$result .= $_SESSION['login_projeto_logado'].'.addTask('.$nPai.');';
				$c++;
			}
			$notIn = '(';
			foreach ($ignore as $i) {
				$notIn .= $i.',';
			}
			$notIn .= '0)';
			
			$query = pg_query("select id_tarefa, titulo, prazo, estimativa, chave, dependencia, situacao from tarefas where id_projeto=".$_SESSION['id_projeto_logado']." and id_tarefa not in ".$notIn." order by prazo");
			while($row = pg_fetch_object($query)) {
				$horas = (int)($row->estimativa/8);
				$data = date('d/m/Y', strtotime("-".(($horas-1)*24)." hours",strtotime($row->prazo)));
				$data = explode('/', $data);
				if ($row->situacao == 4) 
					$percent = 100;
				else
					$percent = 0;
				$result .= 'var task'.substr($row->chave, 1).' = new GanttTaskInfo('.substr($row->chave, 1).', "'.$row->titulo.'", new Date('.$data[2].','.($data[1]-1).','.$data[0].'), '.$row->estimativa.', '.$percent.', "'.substr($row->dependencia, 1).'");';
				$result .= $_SESSION['login_projeto_logado'].'.addTask(task'.substr($row->chave, 1).');';
			}
		
		
			$result .= 'var ganttChartControl = new GanttChart();';
			$result .= 'ganttChartControl.setImagePath("codebase/imgs/");';
			$result .= 'ganttChartControl.setEditable(false);';
			$result .= 'ganttChartControl.addProject('.$_SESSION['login_projeto_logado'].');';
			$result .= 'ganttChartControl.create(htmlDiv1);';
			return $result;
		}
		
		/**
		* Exibe Relatorio de Riscos
		* Retorno: PDF
		*/
		function printRelRiscos(){
			session_start();
	
			$xml =  simplexml_load_file($_SESSION['relatorio']);
			
			$momento = FormataDataHora(date("Y-m-d H:m:s"));
			include_once('../../../lib/phpjasper/setting.php');
			$PHPJasperXML = new PHPJasperXML();
			$PHPJasperXML->connect($server,$user,$pass,$db);	
			$PHPJasperXML->debugsql=false;
			$PHPJasperXML->arrayParameter = array(
												"id_projeto"=>$_SESSION['id_projeto_logado'],
												"empresa"=>$_SESSION["nome_empresa_logada"],
												"projeto"=>$_SESSION["nome_projeto_logado"],
												"momento"=>$momento,
												"probabilidade"=>$_SESSION["probabilidade"], 
												"efeito"=>$_SESSION["efeito"],
												"tipo"=>$_SESSION["tipo"]
											);
			$PHPJasperXML->xml_dismantle($xml);
			
			$query = pg_query($PHPJasperXML->sql);
			$qtd = pg_num_rows($query);
			
			$PHPJasperXML->sql .= $_SESSION["ordem"];
			if ($qtd > 0) {
				$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
				$PHPJasperXML->outpage("I");
			}
			else {
				echo "<script language=Javascript>alert('Não há dados para os filtros selecionados!');</script>";
				echo "<script language=\"javascript\">history.back(1);</script> ";
				exit;
			}
		}
		
	}

?>