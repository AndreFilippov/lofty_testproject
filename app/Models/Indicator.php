<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Indicator extends Model
{
    use HasFactory;

    const DEFAULT_TYPE = 'mix';
    const DEFAULT_LENGTH = 6;

    protected $fillable = ['code'];

    /**
     *
     *
     *
     * @param string $type
     * @param int $length
     * @return false|int|string
     */
    public function generate_code(string $type = self::DEFAULT_TYPE, int $length = self::DEFAULT_LENGTH){
        do{
            $code = self::generate($type, $length);
        }while(self::where('code', $code)->first());

        $this->code = $code;
        $this->save();

        return $code;
    }

    /**
     *
     * Генерация кода индикатора
     *
     * @param string $type
     * @param int $length
     * @return false|int|string
     */
    public static function generate(string $type = self::DEFAULT_TYPE, int $length = self::DEFAULT_LENGTH){
        $randomString = '';
        switch ($type){
            case 'string':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            case 'int':
                $int = 0;
                for($i = 0; $i < $length; $i++) {
                    $int .= mt_rand(0, 9);
                }
                return (int) $int;
            case 'guid':
                return (string) Str::uuid();
            case 'mix':
                return (string) Str::random($length);
            default:
                return false;
        }
    }
}
