<?php

namespace Totp;

class TotpException extends \FuelException {}

class Totp
{
	/**
	 * Default config
	 * @var array
	 */
	protected static $_defaults = array();

	/**
	* Driver config
	* @var array
	*/
	protected $config = array();

	/**
	 * Init
	 */
	public static function _init()
	{
		\Config::load('totp', true);
	}

	/**
	 * Totp driver forge.
	 *
	 * @param	array			$config		Config array
	 * @return  Totp
	 */
	public static function forge($config = array())
	{
		$config = \Arr::merge(static::$_defaults, \Config::get('totp', array()), $config);

		$class = new static($config);

		return $class;
	}

	/**
	* Driver constructor
	*
	* @param array $config driver config
	*/
	public function __construct(array $config = array())
	{
		$this->config = $config;
	}

	/**
	* Get a config setting.
	*
	* @param string $key the config key
	* @param mixed  $default the default value
	* @return mixed the config setting value
	*/
	public function get_config($key, $default = null)
	{
		return \Arr::get($this->config, $key, $default);
	}

	/**
	* Set a config setting.
	*
	* @param string $key the config key
	* @param mixed $value the new config value
	* @return object $this for chaining
	*/
	public function set_config($key, $value)
	{
		\Arr::set($this->config, $key, $value);

		return $this;
	}
        /** 
         * 
         * @return token for QRCode and App
         */
	public static function generateToken()
	{
		$token = GoogleAuthenticator::generateRandom();
		return $token;
	}

        /**
         * 
         * @param string $email_users the users email or other (id), example: (website)some@email.ex
         * @param string $token Can be null if you wouldn't know token 
         * @return link for googlechart
         */
	public static function generateQrCode($email_users, $token = null)
	{
		if($token == NULL){
                    $token = self::generateToken();
		}
		$qrCode = GoogleAuthenticator::getQrCodeUrl('totp', $email_users, $token);
		return $qrCode;
	}

        /**
         * 
         * @param string $email_users the users email or other (id), example: (website)some@email.ex
         * @param string $token Can be null if you wouldn't know token 
         * @return link for secret key (useless)
         */
	public static function generateKeyUri($email_users, $token = null)
	{
		if($token == NULL){
                    $token = self::generateToken();
		}
		$keyUri = GoogleAuthenticator::getKeyUri('totp', $email_users, $token);
		return $keyUri;
	}

        /**
         * 
         * @param string $email_users the users email or other (id), example: (website)some@email.ex
         * @param string $token type generate by app (example: 123456)
         * @return 1, 2 or 3
         */
	public static function checkToken($email_users, $token)
	{
		$otp = new Otp();
		$key = preg_replace('/[^0-9]/', '', $token);
		// Standard is 6 for keys, but can be changed with setDigits on $otp
		if(strlen($key) == 6){
                    $query = \Fuel\Core\DB::select('uniq_key')
                                            ->from('totp_users')
                                            ->where('email_users', '=', $email_users)
                                            ->execute();
                    $uniq_key = $query[0]['uniq_key'];
			// Remember that the secret is a base32 string that needs decoding
			// to use it here!
			if($otp->checkTotp(Base32::decode($uniq_key), $key)){
				return '1'; // return 1 for valid token
				// Add here something that makes note of this key and will not allow
				// the use of it, for this user for the next 2 minutes. This way you
				// prevent a replay attack. Otherwise your OTP is missing one of the
				// key features it can bring in security to your application!
			}
			else{
				return '0'; // return 0 for no valid token
			}
			
		}
		else{
			return '2'; // return 2 for wrong size
		}
	}
        
        /**
         * 
         * @param int $id_users the ID users for update (comming soon)
         * @param string $email_users the users email or other (id), example: (website)some@email.ex
         * @param string $token Can be null if you wouldn't know token 
         * @return string
         */
        public static function addUser($id_users, $email_users, $token = NULL){
            if($token == NULL){
		$token = self::generateToken();
            }
            $select = \Fuel\Core\DB::select('email_users')
                                    ->from('totp_users')
                                    ->where('email_users', '=', $email_users)
                                    ->execute();
            $num_rows = count($select);
            if($num_rows == '0'){
                $query = \Fuel\Core\DB::insert('totp_users')
                            ->columns(array(
                                'id_users', 'email_users', 'uniq_key'
                            ))
                            ->values(array(
                                $id_users, $email_users, $token
                            ))
                            ->execute();
                return $token;
            }
            else{
                return '0'; //email already exist
            }
        }
        /*
        public static function deleteUser($users){
            
        }
        
        public static function updateUser($users){
            
        }*/
}
