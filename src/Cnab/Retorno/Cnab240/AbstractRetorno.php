<?php

namespace RedeCauzzoMais\Pagamento\Cnab\Retorno\Cnab240;

use Exception;
use Illuminate\Support\Collection;
use RedeCauzzoMais\Pagamento\Cnab\Retorno\AbstractRetorno as AbstractRetornoGeneric;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\HeaderLote as HeaderLoteContract;
use RedeCauzzoMais\Pagamento\Contracts\Cnab\Retorno\Cnab240\TrailerLote as TrailerLoteContract;

abstract class AbstractRetorno extends AbstractRetornoGeneric
{
    private $headerLote;
    private $trailerLote;

    public function __construct( $file )
    {
        parent::__construct( $file );

        $this->header      = new Header();
        $this->headerLote  = new HeaderLote();
        $this->trailerLote = new TrailerLote();
        $this->trailer     = new Trailer();
    }

    public function getHeaderLote(): HeaderLote
    {
        return $this->headerLote;
    }

    public function getTrailerLote(): TrailerLote
    {
        return $this->trailerLote;
    }

    abstract protected function processarHeader( array $header ): bool;

    abstract protected function processarHeaderLote( array $headerLote ): bool;

    abstract protected function processarDetalhe( array $detalhe ): bool;

    abstract protected function processarTrailerLote( array $trailer ): bool;

    abstract protected function processarTrailer( array $trailer ): bool;

    protected function incrementDetalhe(): void
    {
        $this->increment++;
        $detalhe                         = new Detalhe();
        $this->detalhe[$this->increment] = $detalhe;
    }

    public function processar()
    {
        if ( $this->isProcessado() ) {
            return $this;
        }

        if ( method_exists( $this, 'init' ) ) {
            call_user_func( [$this, 'init'] );
        }

        foreach ( $this->file as $linha ) {
            $recordType = $this->rem( 8, 8, $linha );

            if ( $recordType == '0' ) {
                $this->processarHeader( $linha );
            } elseif ( $recordType == '1' ) {
                $this->processarHeaderLote( $linha );
            } elseif ( $recordType == '3' ) {
                if ( $this->getSegmentType( $linha ) == 'T' || ( $this->getSegmentType( $linha ) == 'A' && $this->getHeader()
                                                                                                                ->getCodigoRemessaRetorno() == 2 ) ) {
                    $this->incrementDetalhe();
                }

                if ( $this->processarDetalhe( $linha ) === false ) {
                    unset( $this->detalhe[$this->increment] );
                    $this->increment--;
                }
            } elseif ( $recordType == '5' ) {
                $this->processarTrailerLote( $linha );
            } elseif ( $recordType == '9' ) {
                $this->processarTrailer( $linha );
            }
        }

        if ( method_exists( $this, 'finalize' ) ) {
            call_user_func( [$this, 'finalize'] );
        }

        return $this->setProcessado();
    }

    public function toArray(): array
    {
        $array = [
            'header'      => $this->header->toArray(),
            'headerLote'  => $this->headerLote->toArray(),
            'trailerLote' => $this->trailerLote->toArray(),
            'trailer'     => $this->trailer->toArray(),
            'detalhes'    => new Collection()
        ];

        foreach ( $this->detalhe as $detalhe ) {
            $arr = [
                'ocorrenciaTipo'      => $detalhe->getOcorrenciaTipo(),
                'ocorrenciaDescricao' => $detalhe->getOcorrenciaDescricao(),
                'segmentoT'           => $detalhe->getSegmentoT()->toArray(),
                'segmentoU'           => $detalhe->getSegmentoU()->toArray(),
                'segmentoY'           => $detalhe->getSegmentoY()->toArray(),
            ];

            if ( $detalhe->getOcorrenciaTipo() == 9 ) {
                $arr['error'] = [
                    'message' => $detalhe->getError(),
                    'code'    => $detalhe->getErrorCode(),
                ];
            }

            $array['detalhes']->push( $arr );
        }

        return $array;
    }

    protected function getSegmentType( $line ): string
    {
        return strtoupper( $this->rem( 14, 14, $line ) );
    }
}
