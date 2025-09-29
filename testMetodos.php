<?php
require_once 'sistemaBancario.php'; // pega o codigo principal


// Os valores passados aqui serão usados por todos os métodos.
$valorBB = 15.50;
$dataVencimentoBB = '2025-06-30';
$boletoBB = new BoletoBancoBrasil($valorBB, $dataVencimentoBB);

// método gerarCodigoBarras
echo "Código de Barras: " . $boletoBB->gerarCodigoBarras();
echo "\n";


echo "O boleto é válido? ";
var_dump($boletoBB->validar()); // se o boleto for valido retorna true
echo "\n";

// renderização em HTML
echo "Renderização em HTML: ";
echo $boletoBB->renderizar('html');
echo "\n";

// enderização em PDF
echo "Renderização em PDF: ";
echo $boletoBB->renderizar('pdf');
echo "\n";


// CÓDIGO DE TESTE para o Itaú

echo "Testando a classe BoletoItau";
echo "\n";

$valorItau = 50.00;
$dataVencimentoItau = '2025-12-25';
$boletoItau = new BoletoItau($valorItau, $dataVencimentoItau);

// método validar()
echo "O boleto Itaú é válido? ";
var_dump($boletoItau->validar()); //se o boleto for valido retorna true
echo "\n";

// método aplicarJuros()
echo "Valor original do boleto Itaú: R$ " . $boletoItau->getValor();
echo "\n";
$boletoItau->juros(10); // Aplica o juros passado nos parametros
echo "Valor após juros: R$ " . $boletoItau->getValor();
echo "\n";

// renderização
echo "Renderização em HTML: " . $boletoItau->renderizar('html');
echo "\n";
echo "Renderização em PDF: " . $boletoItau->renderizar('pdf');

?>
