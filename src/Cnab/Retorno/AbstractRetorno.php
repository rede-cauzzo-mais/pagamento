<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno;

use Countable;
use Exception;
use Illuminate\Support\Collection;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\Detalhe as Detalhe240Contract;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\Header as Header240Contract;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\Trailer as Trailer240Contract;
use RedeCauzzoMais\Pagamento\Util;
use OutOfBoundsException;
use SeekableIterator;

abstract class AbstractRetorno implements Countable, SeekableIterator
{
    protected $processado = false;
    protected $codigoBanco;
    protected $increment  = 0;
    protected $file;
    protected $header;
    protected $trailer;
    protected $detalhe    = [];
    protected $totais     = [];
    private   $_position  = 1;

    public function __construct( $file )
    {
        $this->_position = 1;

        if ( !$this->file = Util::file2array( $file ) ) {
            throw new Exception( 'Arquivo: não existe' );
        }

        $r                 = new \ReflectionClass( '\RedeCauzzoMais\Pagamento\Contracts\Boleto\Boleto' );
        $constantNames     = $r->getConstants();
        $bancosDisponiveis = [];
        foreach ( $constantNames as $constantName => $codigoBanco ) {
            if ( preg_match( '/^COD_BANCO.*/', $constantName ) ) {
                $bancosDisponiveis[] = $codigoBanco;
            }
        }

        if ( !Util::isHeaderRetorno( $this->file[0] ) ) {
            throw new Exception( sprintf( 'Arquivo de retorno inválido' ) );
        }

        $banco = Util::isCnab400( $this->file[0] ) ? substr( $this->file[0], 76, 3 ) : substr( $this->file[0], 0, 3 );
        if ( !in_array( $banco, $bancosDisponiveis ) ) {
            throw new Exception( sprintf( 'Banco: %s, inválido', $banco ) );
        }
    }

    public function getCodigoBanco(): string
    {
        return $this->codigoBanco;
    }

    public function getBancoNome()
    {
        return Util::$bancos[$this->codigoBanco];
    }

    public function getDetalhes(): Collection
    {
        return new Collection( $this->detalhe );
    }

    public function getDetalhe( $i ): ?Detalhe240Contract
    {
        return array_key_exists( $i, $this->detalhe ) ? $this->detalhe[$i] : null;
    }

    public function getHeader(): Header240Contract
    {
        return $this->header;
    }

    public function getTrailer(): Trailer240Contract
    {
        return $this->trailer;
    }

    protected function detalheAtual(): Detalhe240Contract
    {
        return $this->detalhe[$this->increment];
    }

    protected function isProcessado(): bool
    {
        return $this->processado;
    }

    protected function setProcessado()
    {
        $this->processado = true;

        return $this;
    }

    abstract protected function incrementDetalhe();

    abstract protected function processar();

    abstract protected function toArray(): array;

    protected function rem( $i, $f, &$array ): string
    {
        return Util::removeInPosition( $i, $f, $array );
    }

    public function current()
    {
        return $this->detalhe[$this->_position];
    }

    public function next()
    {
        ++$this->_position;
    }

    public function key()
    {
        return $this->_position;
    }

    public function valid()
    {
        return isset( $this->detalhe[$this->_position] );
    }

    public function rewind()
    {
        $this->_position = 1;
    }

    public function count(): int
    {
        return count( $this->detalhe );
    }

    public function seek( $position )
    {
        $this->_position = $position;
        if ( !$this->valid() ) {
            throw new OutOfBoundsException( '"Posição inválida "$position"' );
        }
    }
}
