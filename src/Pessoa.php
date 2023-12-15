<?php

namespace RedeCauzzoMais\Pagamento;

use RedeCauzzoMais\Pagamento\Contracts\Pessoa as PessoaContract;
use Exception;

class Pessoa implements PessoaContract
{
    protected string $nome;
    protected string $endereco;
    protected string $numero;
    protected string $complemento;
    protected string $bairro;
    protected string $cep;
    protected string $uf;
    protected string $cidade;
    protected string $documento;

    public function __construct( $params = [] )
    {
        Util::fillClass( $this, $params );
    }

    public static function create( $nome, $documento, $endereco = null, $bairro = null, $cep = null, $cidade = null, $uf = null ): static
    {
        return new static( [
            'nome'      => $nome,
            'endereco'  => $endereco,
            'bairro'    => $bairro,
            'cep'       => $cep,
            'uf'        => $uf,
            'cidade'    => $cidade,
            'documento' => $documento,
        ] );
    }

    public function setCep( $cep ): Pessoa
    {
        $this->cep = $cep;

        return $this;
    }

    public function getCep()
    {
        return Util::toMask( Util::onlyNumbers( $this->cep ), '#####-###' );
    }

    public function setCidade( $cidade ): Pessoa
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function getCidade(): string
    {
        return $this->cidade;
    }

    /**
     * @throws Exception
     */
    public function setDocumento( $documento ): Pessoa
    {
        $documento = substr( Util::onlyNumbers( $documento ), -14 );

        if ( !in_array( strlen( $documento ), [10, 11, 14, 0] ) ) {
            throw new Exception( 'Documento inválido' );
        }

        $this->documento = $documento;

        return $this;
    }

    public function getDocumento()
    {
        if ( $this->getTipoDocumento() == 'CPF' ) {
            return Util::toMask( Util::onlyNumbers( $this->documento ), '###.###.###-##' );
        }

        if ( $this->getTipoDocumento() == 'CEI' ) {
            return Util::toMask( Util::onlyNumbers( $this->documento ), '##.#####.#-##' );
        }

        return Util::toMask( Util::onlyNumbers( $this->documento ), '##.###.###/####-##' );
    }

    public function setEndereco( $endereco ): Pessoa
    {
        $this->endereco = $endereco;

        return $this;
    }

    public function getEndereco(): string
    {
        return $this->endereco;
    }

    public function setBairro( $bairro ): Pessoa
    {
        $this->bairro = $bairro;

        return $this;
    }

    public function getBairro(): string
    {
        return $this->bairro;
    }

    public function setNome( $nome ): Pessoa
    {
        $this->nome = $nome;

        return $this;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setUf( $uf ): Pessoa
    {
        $this->uf = $uf;

        return $this;
    }

    public function getUf(): string
    {
        return $this->uf;
    }

    public function getNomeDocumento(): string
    {
        if ( !$this->getDocumento() ) {
            return $this->getNome();
        }

        return $this->getNome() . ' / ' . $this->getTipoDocumento() . ': ' . $this->getDocumento();
    }

    public function getTipoDocumento(): string
    {
        $cpf_cnpj_cei = Util::onlyNumbers( $this->documento );

        if ( strlen( $cpf_cnpj_cei ) == 11 ) {
            return 'CPF';
        }

        if ( strlen( $cpf_cnpj_cei ) == 10 ) {
            return 'CEI';
        }

        return 'CNPJ';
    }

    public function getCepCidadeUf()
    {
        $dados = array_filter( [$this->getCep(), $this->getCidade(), $this->getUf()] );

        return implode( ' - ', $dados );
    }

    public function setNumero( $numero ): Pessoa
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function setComplemento( $complemento ): Pessoa
    {
        $this->complemento = $complemento;

        return $this;
    }

    public function getComplemento(): string
    {
        return $this->complemento;
    }

    public function toArray(): array
    {
        return [
            'nome'           => $this->getNome(),
            'endereco'       => $this->getEndereco(),
            'bairro'         => $this->getBairro(),
            'cep'            => $this->getCep(),
            'uf'             => $this->getUf(),
            'cidade'         => $this->getCidade(),
            'documento'      => $this->getDocumento(),
            'nome_documento' => $this->getNomeDocumento(),
            'endereco2'      => $this->getCepCidadeUf(),
        ];
    }
}
