<?php
require 'autoload.php';

$empresa = new \RedeCauzzoMais\Pagamento\Pessoa( [
    'nome'      => 'ACME',
    'endereco'  => 'Rua UM',
    'numero'    => '123',
    'bairro'    => 'Bairro',
    'cep'       => '99999-999',
    'uf'        => 'UF',
    'cidade'    => 'Cidade',
    'documento' => '99.999.999/0001-99',
] );

$favorecido = new \RedeCauzzoMais\Pagamento\Pessoa( [
    'nome'      => 'Favorecido',
    'endereco'  => 'Rua Um',
    'numero'    => '123',
    'bairro'    => 'Bairro',
    'cep'       => '00000-000',
    'uf'        => 'UF',
    'cidade'    => 'Cidade',
    'documento' => '999.999.999-99',
] );

$pagamento = new RedeCauzzoMais\Pagamento\Pagamento\Banco\Sicredi( [
    'data'            => new \Carbon\Carbon(),
    'finalidade'      => '00011',
    'valor'           => 10,
    'numeroDocumento' => 1,
    'banco'           => 237,
    'agencia'         => 9999,
    'conta'           => 999999,
    'contaDv'         => 9,
    'favorecido'      => $favorecido
] );

$remessa = new \RedeCauzzoMais\Pagamento\Cnab\Remessa\Cnab240\Banco\Sicredi( [
    'agencia'       => 9999,
    'agenciaDv'     => 9,
    'carteira'      => '1',
    'conta'         => 99999,
    'contaDv'       => 9,
    'idremessa'     => 1,
    'beneficiario'  => $empresa,
    'codigoCliente' => '99AA'
] );

$remessa->addPagamento( $pagamento );

$hoje = new \DateTime();

$nomeclatura = $remessa->getCodigoCliente() . $hoje->format( 'd' ) . '1' . '00.REM';

echo $remessa->save( __DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . $nomeclatura );

