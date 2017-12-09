<?php

namespace app\models;

use app\services\UploadUserPhotoService;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string  $email
 * @property integer $role
 * @property string  $password_hash
 * @property string  $name
 * @property integer $age
 * @property integer $gender
 * @property string  $photo_origin_path
 * @property string  $photo_small_path
 * @property string  $photo_preview_path
 *
 * @property string $password write-only password
 *
 * @property bool $isUser
 * @property bool $isAdmin
 */
class User extends ActiveRecord implements IdentityInterface
{
    const GENDER_FEMALE = 0;
    const GENDER_MALE   = 1;

    const MIN_AGE = 18;
    const MAX_AGE = 150;

    const ROLE_USER  = 1;
    const ROLE_ADMIN = 2;

    const MIN_PASSWORD_LENGTH = 6;
    const MAX_PASSWORD_LENGTH = 20;

    const PHOTO_MAX_SIDE_SIZE = 900;
    const PHOTO_PREVIEW_SIZE  = 100;

    /**
     * @return Token|string
     */
    public function getJWT()
    {
        $signer = new Sha256();

        return (string)Yii::$app->jwt->getBuilder()
            ->set('id', $this->id)
            ->sign($signer, Yii::$app->jwt->key)
            ->getToken();
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return null|static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $signer = new Sha256();

        $token = (new Parser())->parse((string)$token);

        if (!$token->verify($signer, Yii::$app->jwt->key)) {
            return null;
        }

        return static::findIdentity($token->getClaim('id'));
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public function fields()
    {
        return ['id', 'email', 'name', 'age', 'gender', 'photo_origin_path', 'photo_small_path', 'photo_preview_path'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'unique'],
            [['name', 'email'], 'required'],
            [['name', 'email', 'photo_origin_path', 'photo_small_path', 'photo_preview_path'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => [self::GENDER_FEMALE, self::GENDER_MALE]],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
            ['age', 'integer', 'min' => self::MIN_AGE, 'max' => self::MAX_AGE],
        ];
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getIsUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * @throws \Exception
     */
    public function getAuthKey()
    {
        throw new \Exception('User::getAuthKey() not implemented');
    }

    /**
     * @param string $authKey
     * @throws \Exception
     */
    public function validateAuthKey($authKey)
    {
        throw new \Exception('User::validateAuthKey() not implemented');
    }


    /**
     * @param $photo string in base64
     * @return bool
     */
    public function uploadPhoto($photo)
    {
        $this->photo_small_path = null;
        $this->photo_preview_path = null;

        $uploadService = new UploadUserPhotoService($photo);
        $this->photo_origin_path = $uploadService->getWebPath();
        return $uploadService->save();
    }
}