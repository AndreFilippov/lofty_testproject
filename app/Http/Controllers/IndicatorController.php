<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndicatorRequest;
use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;

class IndicatorController extends Controller
{
    /**
     *
     * Генерация нового индикатора
     *
     * @param IndicatorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(IndicatorRequest $request){
        $type = $request->input('type') ?: Indicator::DEFAULT_TYPE;
        $length = ((int) $request->input('length')) ?: Indicator::DEFAULT_LENGTH;

        if($type == 'int' && $length > 19){
            return self::json_answer(['error' => 'Максимальное количество символов для типа int не может быть больше 19.'], 400);
        }

        try{
            $indicator = new Indicator;
            $indicator->generate_code($type, $length);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return self::json_answer(['error' => 'При создании индикатора возникла ошибка.'], 500);
        }


        if(!$indicator->id){
            return self::json_answer(['error' => 'При создании индикатора возникла ошибка.'], 500);
        }

        return self::json_answer(['data' => ['code' => $indicator->code, 'id' => $indicator->id]]);
    }


    /**
     *
     * Получение индикатора по ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request){
        $id = $request->route()->parameter('id');

        $id = (int) $id;

        if(!$id){
            return self::json_answer(['error' => 'Укажите ID в виде int.'], 400);
        }

        $indicator = Indicator::find($id);

        if($indicator){
            return self::json_answer(['data' => ['code' => $indicator->code, 'id' => $indicator->id]]);
        }

        return self::json_answer(['error' => 'Индикатор не найден.'], 404);
    }

    /**
     *
     * @param array $object
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public static function json_answer(array $object, int $status = 200){
        $object = array_merge(['status' => $status], $object);
        return response()->json($object, 200);
    }
}
