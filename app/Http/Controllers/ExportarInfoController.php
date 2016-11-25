<?php

namespace Wupos\Http\Controllers;

use App\Http\Requests;
use App\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExportarInfoController extends Controller {

	/**
	 * Exporta a Excel XLS
	 *
	 * @return Response
	 */
	public function export($ext='xlsx')
	{


        Excel::create('CertificadosWU', function($excel) {
            $excel->sheet('Certs', function($sheet) {

				$columnas = [
				//'CERT_id',
				'CERT_codigo',
				'CERT_equipo',
				//'AGEN_id',
				'AGEN_codigo',
				'AGEN_nombre',
				//'AGEN_descripcion',
				'AGEN_cuentawu',
				'AGEN_activa',
				//'REGI_id',
				'REGI_codigo',
				'REGI_nombre',
				'CERT_creadopor',
				'CERT_fechacreado',
				'CERT_modificadopor',
				'CERT_fechamodificado',
				];

				//Se obtienen todos los registros.
				$certificados = \Wupos\Certificado::orderBy('CERT_id')
								->join('AGENCIAS', 'AGENCIAS.AGEN_id', '=', 'CERTIFICADOS.AGEN_id')
								->join('REGIONALES', 'REGIONALES.REGI_id', '=', 'AGENCIAS.REGI_id')
								->get($columnas);
		        $sheet->fromArray($certificados->toArray());
				$sheet->freezeFirstRow();
				$sheet->setAutoFilter();

	        });
    	})->export($ext);

		Session::flash('message', '¡Datos exportados exitosamente!');
    	return redirect()->refresh()->with('error_code', 1)->send();
	}


}