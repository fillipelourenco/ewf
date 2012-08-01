<?
/**
 * Show Progress Bar
 * Função para mostrar uma barra de progresso.
 *
 * @param int $width		-> Largura total da barra (em pixels)
 * @param float $percent	-> Porcentagem a ser exibida
 * @param str $type		-> Cor da barra: green / red / blue (Padrão: green)
 * @param str $color		-> Cor do texto da barra (Padrão: #000)
 * @return str			-> Retorna uma string com todo o código da barra formatada
 */
function show_prog_bar($width, $percent, $type = 'green', $color = '#000') {
	$font =			'Tahoma';
	$font_size =		'8px';
	$font_weight =		'bold';	// bold, normal
	$imgs_folder =		'http://'.$_SERVER['HTTP_HOST'].'/ewf/view/ci/imagens';

	// == Don't edit below ==
	$percent = min($percent, 100);
	$width -= 2;
	$result = (($percent*$width) / 100);
	$return = '';
	$return .= '<div name="progress" align="center" style="width:300px;">';
	$return .= '<div align="center" style="background: url(\''.$imgs_folder.'/progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
	$return .= '<div align="center" style="background: url(\''.$imgs_folder.'/bg.gif\'); height: 13px; width: '.$width.'px; display: block; float: left">';

	$return .= '<span style="background: url(\''.$imgs_folder.'/on_'.strtolower($type).'.gif\'); display: block; float: left; width: '.$result.'px; height: 11px; margin: 1px 0; font-size: '.$font_size.'; font-family: \''.$font.'\'; line-height: 11px; font-weight: '.$font_weight.'; text-align: right; color: '.$color.'; letter-spacing: 1px;">&nbsp;'.$percent.'%&nbsp;</span>';
	//$return .= '<span align="center" style="background: url(\''.$imgs_folder.'/on_'.strtolower($type).'.gif\'); display: block; float: left; width: '.$result.'px; height: 11px; margin: 1px 0; font-size: '.$font_size.'; font-family: \''.$font.'\'; line-height: 11px; font-weight: '.$font_weight.'; text-align: right; color: '.$color.'; letter-spacing: 1px;">&nbsp; &nbsp;</span>';

	$return .= '</div>';
	$return .= '<div align="center" style="background: url(\''.$imgs_folder.'/progress.gif\') no-repeat; height: 13px; width: 1px; display: block; float: left"><!-- --></div>';
	$return .= '</div>';
	return $return;
}
?>