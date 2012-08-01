function AbreCalendario(largura,altura,formulario,campo,tmpx) {
	vertical   = (screen.height/2) - (altura/2);
    horizontal = (screen.width/2) - (largura/2);
    var jan = window.open('calendario.php?formulario='+formulario+'&campo='+campo+'&tmpx='+tmpx,'','toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=yes,resizable=0,copyhistory=0,screenX='+screen.width+',screenY='+screen.height+',top='+vertical+',left='+horizontal+',width='+largura+',height='+altura);
    jan.focus();
}