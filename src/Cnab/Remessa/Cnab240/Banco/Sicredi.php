<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Remessa\Cnab240\Banco;

use AllowDynamicProperties;
use Exception;
use RedeCauzzoMais\Pagamento\Cnab\Remessa\Cnab240\AbstractRemessa;
use RedeCauzzoMais\Pagamento\Contracts\Pagamento\Pagamento as PagamentoContract;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Remessa as RemessaContract;
use RedeCauzzoMais\Pagamento\Util;

#[AllowDynamicProperties] class Sicredi extends AbstractRemessa implements RemessaContract
{
    const DM_DUPLICATA_MERCANTIL                    = 02;
    const DS_DUPLICATA_DE_SERVICO                   = 04;
    const LC_LETRA_DE_CAMBIO_SOMENTE_PARA_BANCO_353 = 07;
    const LC_LETRA_DE_CAMBIO_SOMENTE_PARA_BANCO_008 = 30;
    const NP_NOTA_PROMISSORIA                       = 12;
    const NR_NOTA_PROMISSORIA_RURAL                 = 13;
    const RC_RECIBO                                 = 17;
    const AP_APOLICE_DE_SEGURO                      = 20;
    const CH_CHEQUE                                 = 97;
    const ND_NOTA_PROMISSORIA_DIRETA                = 98;

    protected $somatorioValores;

    public function __construct( array $params = [] )
    {
        parent::__construct( $params );
        $this->addCampoObrigatorio( ['codigoCliente', 'agenciaDv', 'idremessa'] );
        $this->somatorioValores = 0;
    }

    protected $codigoBanco = PagamentoContract::COD_BANCO_SICREDI;
    protected $tipoInscricaoEmpresa;
    protected $numeroInscricaoEmpresa;
    protected $carteiras   = [1];
    protected $fimLinha    = "\r\n";
    protected $fimArquivo  = "\r\n";
    protected $codigoCliente;


    protected $agenciaDv;

    public function getCodigoCliente()
    {
        return $this->codigoCliente;
    }

    public function setCodigoCliente( $codigoCliente )
    {
        $this->codigoCliente = $codigoCliente;

        return $this;
    }

    public function getAgenciaDv()
    {
        return $this->agenciaDv;
    }

    public function setAgenciaDv( $agenciaDv )
    {
        $this->agenciaDv = $agenciaDv;

        return $this;
    }

    public function addPagamento( PagamentoContract $pagamento, $nSequencialLote = null )
    {
        $this->segmentoA( $nSequencialLote + $nSequencialLote + 1, $pagamento );
        $this->segmentoB( $nSequencialLote + $nSequencialLote + 2, $pagamento );

        return $this;
    }

    public function addPagamentoPix( PagamentoContract $pagamento, $nSequencialLote = null )
    {
        $this->segmentoAPix( $nSequencialLote + $nSequencialLote + 1, $pagamento );
        $this->segmentoBPix( $nSequencialLote + $nSequencialLote + 2, $pagamento );

        return $this;
    }

    protected function segmentoA( $nSequencialLote, PagamentoContract $pagamento )
    {
        $this->somatorioValores += $pagamento->getValor();

        $this->iniciaDetalhe();
        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Código do Banco
        $this->add( 4, 7, Util::formatCnab( 9, 0001, 4 ) ); // Numero do lote remessa
        $this->add( 8, 8, Util::formatCnab( 9, 3, 1 ) ); // Numero do lote remessa
        $this->add( 9, 13, Util::formatCnab( 9, $nSequencialLote, 5 ) ); // Nº sequencial do registro de lote
        $this->add( 14, 14, Util::formatCnab( '9', 'A', 1 ) ); // Código de segmento do reg. detalhe
        $this->add( 15, 15, Util::formatCnab( '9', $pagamento->getTipoMovimento(), 1 ) ); // Código de segmento do reg. detalhe
        $this->add( 16, 17, Util::formatCnab( 9, $pagamento->getTipoMovimento(), 2 ) ); // Código de movimento remessa
        $this->add( 18, 20, Util::formatCnab( 9, '888', 3 ) );
        $this->add( 21, 23, Util::formatCnab( 9, $pagamento->getBanco(), 3 ) ); // Numero da conta corrente
        $this->add( 24, 28, Util::formatCnab( 9, $pagamento->getAgencia(), 5 ) ); // Numero da conta corrente
        $this->add( 29, 29, Util::formatCnab( 'X', $pagamento->getAgenciaDv(), 1 ) ); // Numero da conta corrente
        $this->add( 30, 41, Util::formatCnab( 9, $pagamento->getConta(), 12 ) ); // Numero da conta corrente
        $this->add( 42, 42, Util::formatCnab( 'X', $pagamento->getContaDv(), 1 ) ); // Numero da conta corrente
        $this->add( 43, 43, '' ); // Reservado (Uso Banco)
        $this->add( 44, 73, Util::formatCnab( 'X', $pagamento->getFavorecido()
                                                             ->getNome(), 30 ) ); // Nome do pagador/Sacado
        $this->add( 74, 93, Util::formatCnab( 'X', $pagamento->getNumeroDocumento(), 20 ) ); // Nome do pagador/Sacado
        $this->add( 94, 101, Util::formatCnab( 9, $pagamento->getData()->format( 'dmY' ), 8 ) ); //Data pagamento
        $this->add( 102, 104, Util::formatCnab( 'X', $pagamento->getTipoMoeda(), 3 ) ); //Data pagamento
        $this->add( 105, 119, Util::formatCnab( 9, 0, 15 ) ); //Quantidade da moeda
        $this->add( 120, 134, Util::formatCnab( 9, $pagamento->getValor(), 15, 2 ) ); // Valor do pagamento/Valor nominal do título
        $this->add( 135, 154, Util::formatCnab( 'X', '', 20 ) ); //No do docum. atribuído pelo banco
        $this->add( 155, 162, Util::formatCnab( 9, 0, 8 ) ); //Data real/Data real da efetivação pagto
        $this->add( 163, 177, Util::formatCnab( 9, 0, 15 ) ); //Valor real/Valor real da efetivação do pagto
        $this->add( 178, 217, '' ); // Reservado (Uso Banco)/Outras informações – vide formatação em G031 para identificação de depósito judicial e pagto. salários de servidores pelo SIAPE
        $this->add( 218, 219, '' ); // Reservado (Uso Banco)/Compl. tipo serviço
        $this->add( 220, 224, Util::formatCnab( 'X', $pagamento->getFinalidade(), 5 ) ); // Reservado (Uso Banco)/Código finalidade da TED
        $this->add( 225, 226, '' ); // Reservado (Uso Banco)/Complemento de finalidade pagto
        $this->add( 227, 229, '' ); // Reservado (Uso Banco)/Uso exclusivo SICREDI
        $this->add( 230, 230, '0' ); // Reservado (Uso Banco)/Aviso ao favorecido
        $this->add( 231, 240, '' ); // Reservado (Uso Banco)/Códigos das ocorrências p/ retorno

        return $this;
    }

    protected function segmentoAPix( $nSequencialLote, PagamentoContract $pagamento )
    {
        $this->somatorioValores += $pagamento->getValor();

        $this->iniciaDetalhe();
        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) );
        $this->add( 4, 7, Util::formatCnab( 9, 0001, 4 ) );
        $this->add( 8, 8, Util::formatCnab( 9, 3, 1 ) );
        $this->add( 9, 13, Util::formatCnab( 9, $nSequencialLote, 5 ) );
        $this->add( 14, 14, Util::formatCnab( '9', 'A', 1 ) );
        $this->add( 15, 15, Util::formatCnab( '9', $pagamento->getTipoMovimento(), 1 ) );
        $this->add( 16, 17, Util::formatCnab( 9, $pagamento->getTipoMovimento(), 2 ) );
        $this->add( 18, 20, Util::formatCnab( 9, '009', 3 ) );
        $this->add( 21, 23, Util::formatCnab( 9, $pagamento->getBanco(), 3 ) );
        $this->add( 24, 28, Util::formatCnab( 9, $pagamento->getAgencia(), 5 ) );
        $this->add( 29, 29, Util::formatCnab( 'X', $pagamento->getAgenciaDv(), 1 ) );
        $this->add( 30, 41, Util::formatCnab( 9, $pagamento->getConta(), 12 ) );
        $this->add( 42, 42, Util::formatCnab( 'X', $pagamento->getContaDv(), 1 ) );
        $this->add( 43, 43, '' );
        $this->add( 44, 73, Util::formatCnab( 'X', $pagamento->getFavorecido()->getNome(), 30 ) );
        $this->add( 74, 93, Util::formatCnab( 'X', $pagamento->getNumeroDocumento(), 20 ) );
        $this->add( 94, 101, Util::formatCnab( 9, $pagamento->getData()->format( 'dmY' ), 8 ) );
        $this->add( 102, 104, Util::formatCnab( 'X', $pagamento->getTipoMoeda(), 3 ) );
        $this->add( 105, 119, Util::formatCnab( 9, 0, 15 ) );
        $this->add( 120, 134, Util::formatCnab( 9, $pagamento->getValor(), 15, 2 ) );
        $this->add( 135, 154, Util::formatCnab( 'X', '', 20 ) );
        $this->add( 155, 162, Util::formatCnab( 9, 0, 8 ) );
        $this->add( 163, 177, Util::formatCnab( 9, 0, 15 ) );
        $this->add( 178, 217, '' );
        $this->add( 218, 219, '' );
        $this->add( 220, 224, Util::formatCnab( 'X', $pagamento->getFinalidade(), 5 ) );
        $this->add( 225, 226, '' );
        $this->add( 227, 229, '' );
        $this->add( 230, 230, '0' );
        $this->add( 231, 240, '' );

        return $this;
    }

    public function segmentoB( $nSequencialLote, PagamentoContract $pagamento )
    {
        $this->qtyRegistrosLote = $nSequencialLote;
        $this->iniciaDetalhe();

        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Código do Banco
        $this->add( 4, 7, Util::formatCnab( 9, 0001, 4 ) ); // Numero do lote remessa
        $this->add( 8, 8, Util::formatCnab( 9, 3, 1 ) ); // Numero do lote remessa
        $this->add( 9, 13, Util::formatCnab( 9, $nSequencialLote, 5 ) ); // Nº sequencial do registro de lote
        $this->add( 14, 14, Util::formatCnab( 'X', 'B', 1 ) ); // Nº sequencial do registro de lote
        $this->add( 15, 17, '' ); // Reservado (Uso Banco)
        $this->add( 18, 18, strlen( Util::onlyNumbers( $pagamento->getFavorecido()
                                                                 ->getDocumento() ) ) == 14 ? '2' : '1' );
        $this->add( 19, 32, Util::formatCnab( 9, Util::onlyNumbers( $pagamento->getFavorecido()
                                                                              ->getDocumento() ), 14 ) ); // Número de inscrição do sacado
        $this->add( 33, 62, Util::formatCnab( 'X', $pagamento->getFavorecido()
                                                             ->getEndereco(), 30 ) ); // Endereço do pagador/Sacado
        $this->add( 63, 67, Util::formatCnab( 9, $pagamento->getFavorecido()
                                                           ->getNumero(), 5 ) ); // Endereço do pagador/Sacado
        $this->add( 68, 82, Util::formatCnab( 'X', $pagamento->getFavorecido()
                                                             ->getComplemento(), 15 ) ); // Endereço do pagador/Sacado
        $this->add( 83, 97, Util::formatCnab( 'X', $pagamento->getFavorecido()
                                                             ->getBairro(), 15 ) ); // Bairro do pagador/Sacado
        $this->add( 98, 117, Util::formatCnab( 'X', $pagamento->getFavorecido()
                                                              ->getCidade(), 20 ) ); // cidade do sacado
        $this->add( 118, 122, Util::formatCnab( 9, Util::onlyNumbers( $pagamento->getFavorecido()
                                                                                ->getCep() ), 5 ) ); // CEP do pagador/Sacado
        $this->add( 123, 125, Util::formatCnab( 9, Util::onlyNumbers( substr( $pagamento->getFavorecido()
                                                                                        ->getCep(), 6, 9 ) ), 3 ) ); //SUFIXO do cep do pagador/Sacado
        $this->add( 126, 127, Util::formatCnab( 'X', $pagamento->getFavorecido()->getUf(), 2 ) ); // Uf do sacado
        $this->add( 128, 135, Util::formatCnab( 9, 0, 8 ) ); // Data do vencimento (nominal)
        $this->add( 136, 150, Util::formatCnab( 9, 0, 15 ) ); // Valor do documento (nominal)
        $this->add( 151, 165, Util::formatCnab( 9, 0, 15 ) ); // Valor do abatimento
        $this->add( 166, 180, Util::formatCnab( 9, 0, 15 ) ); // Valor do desconto
        $this->add( 181, 195, Util::formatCnab( 9, 0, 15 ) ); // Valor da mora
        $this->add( 196, 210, Util::formatCnab( 9, 0, 15 ) ); // Valor da multa
        $this->add( 211, 225, Util::formatCnab( 'X', Util::onlyNumbers( $pagamento->getNumeroDocumento() ), 15 ) ); // Tipo de inscrição do sacado
        $this->add( 226, 226, Util::formatCnab( 9, 0, 1 ) ); // Identificador de carne 000 - Não possui, 001 - Possui Carné
        $this->add( 227, 232, Util::formatCnab( 9, 0, 6 ) ); // Sequencial da parcela
        $this->add( 233, 240, Util::formatCnab( 9, self::ISPB[$pagamento->getBanco()], 8 ) ); // Reservado (Uso Banco)
    }

    public function segmentoBPix( $nSequencialLote, PagamentoContract $pagamento )
    {
        $this->qtyRegistrosLote = $nSequencialLote;
        $this->iniciaDetalhe();

        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) );
        $this->add( 4, 7, Util::formatCnab( 9, 0001, 4 ) );
        $this->add( 8, 8, Util::formatCnab( 9, 3, 1 ) );
        $this->add( 9, 13, Util::formatCnab( 9, $nSequencialLote, 5 ) );
        $this->add( 14, 14, Util::formatCnab( 'X', 'B', 1 ) );
        $this->add( 15, 17, Util::formatCnab( 'X', $pagamento->getPixTipo(), 3 ) );
        $this->add( 18, 18, Util::formatCnab( 9, 0, 1 ) );
        $this->add( 19, 32, Util::formatCnab( 9, Util::onlyNumbers( $pagamento->getFavorecido()
                                                                              ->getDocumento() ), 14 ) );
        $this->add( 33, 62, '' );
        $this->add( 63, 127, Util::formatCnab( 'X', 'Ref Nov-2023', 65 ) );
        $this->add( 128, 226, Util::formatCnab( 'X', '', 99 ) ); // Chave pix email, telefone ou chave aleatoria
        $this->add( 227, 232, Util::formatCnab( 9, 0, 6 ) );
        $this->add( 233, 240, Util::formatCnab( 9, 0, 8 ) );
    }

    protected function header()
    {
        $this->iniciaHeader();

        /**
         * HEADER DE ARQUIVO
         */
        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Codigo do banco
        $this->add( 4, 7, '0000' ); // Lote de Serviço
        $this->add( 8, 8, '0' ); // Tipo de Registro
        $this->add( 9, 17, '' ); // Reservados (Uso Banco)
        $this->add( 18, 18, strlen( Util::onlyNumbers( $this->getBeneficiario()
                                                            ->getDocumento() ) ) == 14 ? '2' : '1' ); // Tipo de inscrição da empresa
        $this->add( 19, 32, Util::formatCnab( '9L', $this->getBeneficiario()
                                                         ->getDocumento(), 14 ) ); // Numero de inscrição da empresa
        $this->add( 33, 52, Util::formatCnab( 'X', $this->getCodigoCliente(), 20 ) ); // Código do convênio no banco
        $this->add( 53, 57, Util::formatCnab( '9', $this->getAgencia(), 5 ) ); // Agência mantenedora da conta
        $this->add( 58, 58, Util::formatCnab( 'X', $this->getAgenciaDv(), 1 ) ); // Dígito verificador da agência (Uso Branco)
        $this->add( 59, 70, Util::formatCnab( '9', $this->getConta(), 12 ) ); // Número da conta corrente
        $this->add( 71, 71, Util::formatCnab( '9', $this->getContaDv(), 1 ) ); // Dígito verificador da conta
        $this->add( 72, 72, '' ); // Dígito verificador da Ag/conta (Uso Banco)
        $this->add( 73, 102, Util::formatCnab( 'X', $this->getBeneficiario()->getNome(), 30 ) ); // Nome da empresa
        $this->add( 103, 132, Util::formatCnab( 'X', 'Sicredi', 30 ) ); // Nome do Banco
        $this->add( 133, 142, '' ); // Reservados (Uso Banco)
        $this->add( 143, 143, '1' ); // Codigo remessa
        $this->add( 144, 151, date( 'dmY' ) ); // Data de Geracao do arquivo
        $this->add( 152, 157, date( 'His' ) ); // Reservado (Uso Banco)
        $this->add( 158, 163, Util::formatCnab( 9, $this->getIdremessa(), 6 ) ); // Numero Sequencial do arquivo
        $this->add( 164, 166, Util::formatCnab( '9', '082', 3 ) ); // Versão do layout
        $this->add( 167, 171, Util::formatCnab( '9', '1600', 5 ) ); // Versão do layout
        $this->add( 172, 240, '' ); // Reservado (Uso Banco)

        return $this;
    }

    protected function headerLote()
    {
        $this->iniciaHeaderLote();

        /**
         * HEADER DE LOTE
         */
        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Codigo do banco
        $this->add( 4, 7, '0001' ); // Lote de Serviço
        $this->add( 8, 8, '1' ); // Tipo de Registro
        $this->add( 9, 9, 'C' ); // Tipo de operação
        $this->add( 10, 11, Util::formatCnab( 9, 20, 2 ) ); // Tipo de serviço
        $this->add( 12, 13, Util::formatCnab( 9, 45, 2 ) ); // 01 - Transferência entre contas, 41 - TED, 45 - Pix
        $this->add( 14, 16, Util::formatCnab( '9', '042', 3 ) ); // Versão do layout
        $this->add( 17, 17, '' ); // Reservados (Uso Banco)
        $this->add( 18, 18, strlen( Util::onlyNumbers( $this->getBeneficiario()
                                                            ->getDocumento() ) ) == 14 ? '2' : '1' ); // Tipo de inscrição da empresa
        $this->add( 19, 32, Util::formatCnab( '9L', $this->getBeneficiario()
                                                         ->getDocumento(), 14 ) ); // Numero de inscrição da empresa
        $this->add( 33, 52, Util::formatCnab( 'X', $this->getCodigoCliente(), 20 ) ); // Código do convênio no banco
        $this->add( 53, 57, Util::formatCnab( '9', $this->getAgencia(), 5 ) ); // Agência mantenedora da conta
        $this->add( 58, 58, Util::formatCnab( 'X', $this->getAgenciaDv(), 1 ) ); // Dígito verificador da agência (Uso Branco)
        $this->add( 59, 70, Util::formatCnab( '9', $this->getConta(), 12 ) ); // Número da conta corrente
        $this->add( 71, 71, Util::formatCnab( '9', $this->getContaDv(), 1 ) ); // Dígito verificador da conta
        $this->add( 72, 72, '' ); // Dígito verificador da Ag/conta (Uso Banco)
        $this->add( 73, 102, Util::formatCnab( 'X', $this->getBeneficiario()->getNome(), 30 ) ); // Nome do cedente
        $this->add( 103, 142, '' ); // Mensagem 1
        $this->add( 143, 172, Util::formatCnab( 'X', $this->getBeneficiario()->getEndereco(), 30 ) ); // Logradouro
        $this->add( 173, 177, Util::formatCnab( '9', $this->getBeneficiario()->getNumero(), 5 ) ); // Numero
        $this->add( 178, 192, Util::formatCnab( 'X', $this->getBeneficiario()->getComplemento(), 15 ) ); // Numero
        $this->add( 193, 212, Util::formatCnab( 'X', $this->getBeneficiario()->getCidade(), 20 ) ); // Numero
        $this->add( 213, 217, Util::formatCnab( 9, Util::onlyNumbers( $this->getBeneficiario()
                                                                           ->getCep() ), 5 ) ); // CEP
        $this->add( 218, 220, Util::formatCnab( 9, Util::onlyNumbers( substr( $this->getBeneficiario()
                                                                                   ->getCep(), 6, 9 ) ), 3 ) ); //SUFIXO do cep
        $this->add( 221, 222, Util::formatCnab( 'X', $this->getBeneficiario()->getUf(), 2 ) ); // Uf do sacado
        $this->add( 223, 240, '' ); // Reservados (Uso Banco)

        return $this;
    }

    protected function trailerLote()
    {
        $this->iniciaTrailerLote();

        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Codigo do banco
        $this->add( 4, 7, '0001' ); // Numero do lote remessa
        $this->add( 8, 8, Util::formatCnab( 9, 5, 1 ) ); //Tipo de registro
        $this->add( 9, 17, '' ); // Reservado (Uso Banco)
        $this->add( 18, 23, Util::formatCnab( 9, ( $this->qtyRegistrosLote + 2 ), 6 ) ); // Quantidade de registros do lote
        $this->add( 24, 41, Util::formatCnab( 9, $this->somatorioValores, 18, 2 ) );
        $this->add( 42, 59, Util::formatCnab( 9, 0, 18 ) );
        $this->add( 60, 65, Util::formatCnab( 9, 0, 6 ) );
        $this->add( 66, 230, '' ); // Reservado (Uso Banco)
        $this->add( 231, 240, '' ); // Codigo das ocorrencias p/ retorno

        return $this;
    }

    protected function trailer()
    {
        $this->iniciaTrailer();

        $this->add( 1, 3, Util::onlyNumbers( $this->getCodigoBanco() ) ); //Codigo do banco
        $this->add( 4, 7, '9999' ); // Numero do lote remessa
        $this->add( 8, 8, Util::formatCnab( 9, 9, 1 ) ); //Tipo de registro
        $this->add( 9, 17, '' ); // Reservado (Uso Banco)
        $this->add( 18, 23, Util::formatCnab( 9, 1, 6 ) ); // Qtd de lotes do arquivo
        $this->add( 24, 29, Util::formatCnab( 9, ( $this->qtyRegistrosLote + 4 ), 6 ) ); // Qtd de registros do arquivo
        $this->add( 30, 35, '000000' ); // Numero do lote remessa
        $this->add( 36, 240, '' ); // Reservado (Uso Banco)

        return $this;
    }
}
