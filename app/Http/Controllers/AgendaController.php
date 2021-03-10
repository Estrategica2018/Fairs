<?php

namespace App\Http\Controllers;

use App\Models\Agendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
     /**
     * @OA\Get(
     *  path="/api/meetings",

     *  operationId="listar reuniones",

     *  summary="Obtener la lista de reuniones",


     *  @OA\Response(response="200",

     *    description="Validation Response",

     *  )

     * )

     */
    public function list (Request $request) {
        $validator = Validator::make($request->all(), [
            'fair_id' => '',
            'pavilion_id' => '',
            'stand_id' => '',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();
    
        if(isset($data['fair_id'])) {
          $meetings = Agendas::with('invited_speakers.speaker.user')->where('fair_id',$data['fair_id'])->get();
        }
        else if(isset($data['pavilion_id'])) {
          $meetings = Agendas::whereHas('room', function ($query) use ($data) {
              $query->where('pavilion_id','=',$data['pavilion_id']);
           })->get();
        }
        else if(isset($data['stand_id'])) {
          $meetings = Agendas::with('invited_speakers')->get();
        }
        return [
            'success' => 201,
            'data' => $meetings,
        ];

    }
}
