<?php
require 'autoload.php';

use Illuminate\Console\Command;
use RedeCauzzoMais\Pagamento\Contracts\Pagamento\Pagamento as PagamentoContract;
use RedeCauzzoMais\Pagamento\Util;

class RemessaSicredi extends Command
{
    protected $signature   = 'play';
    protected $description = '';

    public function __construct()
    {
        parent::__construct();

        $this->handle();
    }

    public function handle()
    {
        try {

            function assertEquals( $esperado, $resultado )
            {
                dump( "$esperado = $resultado" );
                if ( $esperado !== $resultado ) {
                    throw new Exception( "Esperado $esperado, mas obtido $resultado" );
                }
            }

            $empresa = new \RedeCauzzoMais\Pagamento\Pessoa( [
                'nome'        => 'Cauzzo Pagamentos LTDA',
                'endereco'    => 'Rua General Neto ',
                'numero'      => '788',
                'complemento' => 'Apt 401',
                'bairro'      => 'Centro',
                'cep'         => '97050-240',
                'uf'          => 'RS',
                'cidade'      => 'Santa Maria',
                'documento'   => '42.187.442/0001-39',
            ] );

            $remessa = new \RedeCauzzoMais\Pagamento\Cnab\Remessa\Cnab240\Banco\Sicredi( [
                'agencia'       => '0434',
                'agenciaDv'     => 0,
                'carteira'      => '1',
                'conta'         => 21909,
                'contaDv'       => 1,
                'idremessa'     => 38,
                'beneficiario'  => $empresa,
                'codigoCliente' => '3Q5C'
            ] );

            $favorecido = new \RedeCauzzoMais\Pagamento\Pessoa( [
                'nome'      => 'Cauzzo Serviços Assistenciais LTDA',
                'endereco'  => 'Av Medianeira',
                'numero'    => '1899',
                'bairro'    => 'Centro',
                'cep'       => '97060-003',
                'uf'        => 'RS',
                'cidade'    => 'Santa Maria',
                'documento' => '01.717.223/0001-37',
            ] );

            //$favorecido = new \RedeCauzzoMais\Pagamento\Pessoa( [
            //    'nome'        => 'Cauzzo Pagamentos LTDA',
            //    'endereco'    => 'Rua General Neto',
            //    'numero'      => '788',
            //    'complemento' => 'APT 401',
            //    'bairro'      => 'Centro',
            //    'cep'         => '97050-240',
            //    'uf'          => 'RS',
            //    'cidade'      => 'Santa Maria',
            //    'documento'   => '42.187.442/0001-39',
            //] );

            //$pagamento = new \RedeCauzzoMais\Pagamento\Pagamento\Banco\Sicredi( [
            //    'data'            => new \Carbon\Carbon(),
            //    'finalidade'      => '00010',
            //    'valor'           => 100000,
            //    'numeroDocumento' => 999,
            //    'banco'           => 748,
            //    'agencia'         => '0434',
            //    'conta'           => 64082,
            //    'contaDv'         => 4,
            //    'favorecido'      => $favorecido
            //] );

            $pagamento = new \RedeCauzzoMais\Pagamento\Pagamento\Banco\Sicredi( [
                'data'            => new \Carbon\Carbon(),
                'valor'           => 500000,
                'numeroDocumento' => 33354,
                'pixTipo'         => PagamentoContract::CHAVE_PIX_CNPJ,
                'pixChave'        => '01.717.223/0001-37',
                'favorecido'      => $favorecido,
            ] );

            // Adicionar um pagamento
            $remessa->addPagamentoPix( $pagamento );

            // Ou para adicionar um array de pagamentos
            //            $pagamentos = [];
            //            $pagamentos[] = $pagamento1;
            //            $pagamentos[] = $pagamento2;
            //            $pagamentos[] = $pagamento3;
            //            $remessa->addPagamentos($pagamentos);

            // Gerar remessa
            echo $remessa->gerar();

            // Salvar remessa
            echo $remessa->save( __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . $remessa->getCodigoCliente() . date( 'd' ) . '00.REM' );


            //$retorno = new \Murilo\Pagamento\Cnab\Retorno\Cnab240\Banco\Sicredi( __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . '3Q5C1312164600.ret' );
            //$retorno = $retorno->processar();

            // Retorno implementa \SeekableIterator, sendo assim, podemos utilizar o foreach da seguinte forma:
            //            foreach ( $retorno as $registro ) {
            //                dump( $registro->toArray() );
            //            }

            //            // Ou também podemos:
            //            $detalheCollection = $retorno->getDetalhes();
            //            foreach ( $detalheCollection as $detalhe ) {
            //                dump( $detalhe->toArray() );
            //            }
            //
            //            // Ou até mesmo do jeito laravel
            //            $detalheCollection->each( function ( $detalhe, $index ) {
            //                dump( $detalhe->toArray() );
            //            } );

            //dump( $retorno->getDetalhes() );

            //dump( $retorno->getHeader() );

            //dump( $retorno->getTrailer() );

            //dd($retorno->getDetalhes());
        } catch ( Throwable $e ) {
            dd( '===================', $e->getMessage(), '===================' );
        }
    }
}

new RemessaSicredi();
