<?php

namespace App\Services\Gala;

use App\Repositories\gala\ClienteRepository;
use App\Repositories\Gala\InstalacionRepository;
use App\Repositories\Gala\UsuarioRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstalacionService
{
    protected $instalacionRepository;
    protected $clienteRepository;
    protected $usuarioRepository;

    public function __construct(
        InstalacionRepository $InstalacionRepository,
        ClienteRepository $ClienteRepository,
        UsuarioRepository $UsuarioRepository
    ) {
        $this->instalacionRepository = $InstalacionRepository;
        $this->clienteRepository = $ClienteRepository;
        $this->usuarioRepository = $UsuarioRepository;
    }

    public function registrarNuevaInstalacion ( $datosNuevaInstalacion ) {
        $validarCliente = $this->clienteRepository->validarClienteExistentePorNombre('instalacion', $datosNuevaInstalacion['direccionPersonal']['poblacionCliente'], $datosNuevaInstalacion['informacionPersonal'] );

        if( $validarCliente > 0 ){
            return response()->json(
                [
                    'message' => 'Upss! Al parecer ya existe un Cliente el mismo nombre y población. Por favor valida la información',
                    'status' => 409
                ],
                200
            );
        }

        $validarTelefono = $this->clienteRepository->validarClienteExistentePorTelefono('instalacion', $datosNuevaInstalacion['informacionPersonal']['telefonoCliente']);

        if( $validarTelefono > 0 ){
            return response()->json(
                [
                    'message' => 'Upss! Al parecer ya existe un Cliente con el mismo número de teléfono. Por favor valida la información',
                    'status' => 409
                ],
                200
            );
        }

        DB::beginTransaction();
            $sesion = $this->usuarioRepository->obtenerInformacionPorToken( $datosNuevaInstalacion['token'] );
            $pkCliente = $this->clienteRepository->crearNuevoCliente( $datosNuevaInstalacion['informacionPersonal'], $sesion[0]->PkTblUsuario );
            $this->clienteRepository->crearDireccionCliente( $datosNuevaInstalacion['direccionPersonal'], $pkCliente);
            $pkInstalacion = $this->instalacionRepository->registrarNuevaInstalcion( $pkCliente, $sesion[0]->PkTblUsuario );
            $this->instalacionRepository->registroDetalleNuevaInstalacion( $datosNuevaInstalacion['instalacion'], $pkInstalacion );
        DB::commit();

        return response()->json(
            [
                'message' => 'Se registró la nueva instalación con éxito'
            ],
            200
        );
    }

    public function consultarInstalacionesPorStatus ( $status ) {
        $instalaciones = $this->instalacionRepository->consultarInstalacionesPorStatus( $status );

        return response()->json(
            [
                'instalaciones'  => $instalaciones,
                'message'   => 'Se consultarón los reportes con éxito'
            ],
            200
        );
    }

    public function cargaComponenteModificacionInstalacion ( $pkInstalacion ) {
        $dataInstalacion = [];
        $dataInstalacion['datosInstalacion'] = $this->instalacionRepository->consultarDatosInstalacionModificacionPorPK( $pkInstalacion );
        
        if ( count($dataInstalacion['datosInstalacion']) == 0 ) {
            return response()->json(
                [
                    'message'   => 'No se ha encontrado registro de la información proporcionada',
                    'status'    => 204
                ],
                200
            );
        }

        $dataInstalacion['datosDetalleInstalacion'] = $this->instalacionRepository->obtenerDetalleInstalacionPorPK( $dataInstalacion['datosInstalacion'][0]->PkTblInstalacion );
        $dataInstalacion['datosUsuarioAtencion']    = $this->usuarioRepository->obternerUsuarioPorPK( $dataInstalacion['datosDetalleInstalacion'][0]->FkTblUsuarioAtencion );
        $dataInstalacion['datosUsuarioAtendiendo']  = $this->usuarioRepository->obternerUsuarioPorPK( $dataInstalacion['datosDetalleInstalacion'][0]->FkTblUsuarioAtendiendo );
        $dataInstalacion['datosCliente']            = $this->clienteRepository->obtenerClientePorPK( $dataInstalacion['datosInstalacion'][0]->FkTblCliente );
        $dataInstalacion['datosUsuarioRecibio']     = $this->usuarioRepository->obternerUsuarioPorPK( $dataInstalacion['datosInstalacion'][0]->FkTblUsuarioRecibio );

        return response()->json(
            [
                'data'  => $dataInstalacion,
                'message'   => 'Se consultarón los datos con éxito'
            ],
            200
        );
    }

    public function validarInstalacionExistente ( $datosInstalacionModificada ) {
        $validarCliente = $this->clienteRepository->validarClienteExistentePorNombre('instalacion', $datosInstalacionModificada['direccionPersonal']['poblacionCliente'], $datosInstalacionModificada['informacionPersonal'], $datosInstalacionModificada['informacionPersonal']['pkCliente'] );

        if( $validarCliente > 0 ){
            return response()->json(
                [
                    'message' => 'Upss! Al parecer ya existe un Cliente el mismo nombre y población. Por favor valida la información',
                    'status' => 409
                ],
                200
            );
        }

        $validarTelefono = $this->clienteRepository->validarClienteExistentePorTelefono('instalacion', $datosInstalacionModificada['informacionPersonal']['telefonoCliente'], $datosInstalacionModificada['informacionPersonal']['pkCliente']);

        if( $validarTelefono > 0 ){
            return response()->json(
                [
                    'message' => 'Upss! Al parecer ya existe un Cliente con el mismo número de teléfono. Por favor valida la información',
                    'status' => 409
                ],
                200
            );
        }

        DB::beginTransaction();
            $pkCliente = $datosInstalacionModificada['informacionPersonal']['pkCliente'];
            $this->clienteRepository->modificarDatosCliente( $pkCliente, $datosInstalacionModificada['informacionPersonal'] );
            $this->clienteRepository->modificarDatosDireccion( $pkCliente, $datosInstalacionModificada['direccionPersonal'] );
            $this->instalacionRepository->modificacionDetalleInstalacion( $datosInstalacionModificada['datosInstalacion']['pkInstalacion'], $datosInstalacionModificada['datosInstalacion'] );
        DB::commit();

        return response()->json(
            [
                'message' => 'Se modificó la instalación con éxito'
            ],
            200
        );
    }
}