<?php

namespace Modules\Access\Models;

use App\Models\EntityModel;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Access extends EntityModel
{
    use PresentableTrait;
    use SoftDeletes;

    /**
     * @var string
     */
    protected $presenter = 'Modules\Access\Presenters\AccessPresenter';

    /**
     * @var array
     */
    protected $fillable = ["name","host","username","password","notes","client_id"];

    /*
     * @var array
     */
    protected $guarded = ["username","password"];
    
    /**
     * @var string
     */
    protected $table = 'access';

    public function getEntityType()
    {
        return 'access';
    }

    /**
     * @return mixed
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }


    /**
     * Returns an encrypted & utf8-encoded
     */
    public static function encrypt($pure_string, $encryption_key)
    {
        if(strlen($encryption_key) > 32){
            $encryption_key = substr($encryption_key,0,32);
        }
        $iv_size = mcrypt_get_iv_size(
            MCRYPT_RIJNDAEL_128,
            MCRYPT_MODE_CBC
        );
        $iv = mcrypt_create_iv($iv_size);
        return base64_encode(
            $iv . mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                $encryption_key,
                $pure_string,
                MCRYPT_MODE_CBC,
                $iv
            )
        );
    }

    /**
     * Returns decrypted original string
     */
    public static function decrypt($encrypted_string, $encryption_key)
    {
            if($encrypted_string == ""){return ;}

            if (strlen($encryption_key) > 32) {
                $encryption_key = substr($encryption_key, 0, 32);
            }
            $iv_size = mcrypt_get_iv_size(
                MCRYPT_RIJNDAEL_128,
                MCRYPT_MODE_CBC
            );

            $encrypted_string = base64_decode($encrypted_string);
            $iv = substr(
                $encrypted_string,
                0,
                $iv_size
            );
            $cipher = substr(
                $encrypted_string,
                $iv_size
            );
            return trim(
                mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128,
                    $encryption_key,
                    $cipher,
                    MCRYPT_MODE_CBC,
                    $iv
                )
            );

    }
}
