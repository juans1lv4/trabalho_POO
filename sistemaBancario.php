<?php

use Symfony\Component\VarDumper\Cloner\Data;

interface BoletoInterface
{
    public function gerarCodigoBarras(): string;
    public function validar(): bool;
    public function renderizar(string $formato): string;
}

abstract class BoletoAbstrato implements BoletoInterface
{
    protected float $valor;
    protected string $dataVencimento;

    public function __construct(float $valor, string $dataVencimento)
    {
        $this->valor = $valor;
        $this->dataVencimento = $dataVencimento;
    }

    public function getValor(): float
    {
        return $this->valor;
    }
    public function getDataVencimento(): string
    {
        return $this->dataVencimento;
    }

    public function renderizar(string $formato): string
    {
        if ($formato === 'html') {
            return $this->renderizarHtml();
        } elseif ($formato === 'pdf') {
            return $this->renderizarPdf();
        }
        throw new InvalidArgumentException('Formato n o suportado');
    }

    abstract protected function renderizarHtml(): string;
    abstract protected function renderizarPdf(): string;
}

class BoletoBancoBrasil extends BoletoAbstrato
{
    public function renderizarPdf(): string
    {
        return '[PDF] Boleto BB R$' . $this->valor . 'Data de Vencimento' . $this->dataVencimento;
    }
    public function renderizarHtml(): string
    {
        return '<div>Boleto BB - R$' . $this->valor . 'Data de Vencimento' . $this->dataVencimento . '<div>';
    }

    public function gerarCodigoBarras(): string
    {

        //str_replace é responsavel por substituir as virgulas e pontos.
        // o number_format pega o valor e garante que ele tenha duas casas decimais
        $valorFormatado = str_replace([',', '.'], '', number_format($this->valor, 2));

        //DateTime mostra a data atual.
        $dataFormat = (new DateTime($this->dataVencimento))->format('Ymd');

        return '001' . $valorFormatado . $dataFormat;
    }

    public function validar(): bool
    {
        //verica se o valor é maior que zero
        $valorValido = ($this->valor > 0);
        //objeto para pega a data atual
        $dateAtual = new DateTime();
        //objeto para data de vencimento
        $dataVencimento = new DateTime($this->dataVencimento);
        //confere se a data de vencimento é maior que a data atual
        $dataFutury = ($dataVencimento > $dateAtual);
        //retorna verdadeiro se as duas condições forem verdadeieras
        return $valorValido && $dataFutury;
    }
}

interface BoletoJuros
{
    public function juros(float $taxa): void;
}

class BoletoItau extends BoletoAbstrato implements BoletoJuros
{
    public function juros(float $taxa): void
    {
        $this->valor += $this->valor * ($taxa / 100);
    }
    public function renderizarPdf(): string
    {
        return '[PDF] Boleto Itau R$' . $this->valor . 'Data de Vencimento' . $this->dataVencimento;
    }

    public function renderizarHtml(): string
    {
        return '<div>Boleto BB - R$' . $this->valor . 'Data de Vencimento' . $this->dataVencimento . '<div>';
    }

    public function gerarCodigoBarras(): string
    {
        return 'Itau - ' . $this->valor . '-' . $this->dataVencimento;;
    }

    public function validar(): bool
    {
        $dateAtual = new DateTime();
        $dataVencimento = new DateTime($this->dataVencimento);
        return ($this->valor>0 && $dataVencimento > $dateAtual);
    }
}
