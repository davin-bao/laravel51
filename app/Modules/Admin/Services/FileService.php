<?php
namespace App\Modules\Admin\Services;

use App\Exceptions\NoticeMessageException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * Class FileService
 * @package App\Modules\Admin\Services
 *
 * @author chuanhangyu
 * @since 2016/7/29 9:00
 */
class FileService {

    // 头像大图宽度
    const AVATAR_WIDTH = 159;
    // 头像大图高度
    const AVATAR_HEIGHT =159;
    // 头像缩略图宽度
    const AVATAR_THUMB_WIDTH = 50;
    // 头像缩略图高度
    const AVATAR_THUMB_HEIGHT = 50;
    /**
     * 处理头像上传操作，包括更新
     * @param $avatar
     * @return mixed
     */
    public static function uploadAvatar($avatar) {
        $rules = [
            'id'=> 'required',
            'file' => 'required|mimes:png,gif,jpeg,jpg'
        ];
        $validator = Validator::make($avatar, $rules);

        if ($validator->fails()) {
            throw new NoticeMessageException($validator->messages()->first());
        }

        $photo = $avatar['file'];

        // 得到图片扩展后缀
        $extension = $photo->getClientOriginalExtension();
        // 得到图片在本地存储的唯一文件名
        $allowed_filename = self::createUniqueFilename( $extension, Config::get('upload.avatar_save_path'));
        // 保存图片，并压缩在159*159
        $uploadSuccess1 = self::saveImage( $photo, $allowed_filename, self::AVATAR_WIDTH, self::AVATAR_HEIGHT);
        // 保存图片缩略图
        $uploadSuccess2 = self::savaThumbImage( $photo, $allowed_filename, self::AVATAR_THUMB_WIDTH, self::AVATAR_THUMB_HEIGHT);

        // 存储失败，发送json请求
        if( !$uploadSuccess1 || !$uploadSuccess2 ) {
            return new NoticeMessageException('上传失败！');
        }

        // 存储成功，更新数据库staff表信息；更新失败，删除本地文件
        if (StaffService::updateAvatar([
            'id' => $avatar['id'],
            'avatar' => Config::get('upload.avatar_save_path'). $allowed_filename
        ])) {
            return true;
        } else {
            self::delete($allowed_filename);
            throw new NoticeMessageException('设置失败！');
        }

    }

    /**
     * 创建本地存储唯一文件名
     * @param $extension
     * @param $dir
     * @return string
     */
    static public function createUniqueFilename($extension, $dir )
    {
        // 随机得到32位不重复的文件名
        $filename = Str::random('32');
        while ( File::exists( $dir. $filename. '.'. $extension ) )
        {
            // Generate token for image
            $filename = Str::random('32');
        }

        return $filename. '.'. $extension;
    }

    /**
     * 保存图片，可选择压缩
     * @param $photo
     * @param $filename
     * @param null $thumbWidth
     * @param null $thumbHeight
     * @param bool $isRatio 是否按比例压缩
     * @return \Intervention\Image\Image
     */
    static private function saveImage($photo, $filename, $thumbWidth = null, $thumbHeight = null, $isRatio = false)
    {
        // 实例化intervention/image依赖
        $manager = new ImageManager();
        // 加载上传图片
        $image = $manager->make( $photo );
        // 压缩图片
        if ($thumbWidth || $thumbHeight) {
            if ($isRatio) {
                $image = $image->resize($thumbWidth, $thumbHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image = $image->resize($thumbWidth, $thumbHeight);
            }
        }
        // 保存图片
        $image = $image->save(Config::get('upload.avatar_save_path') . $filename );

        return $image;
    }

    /**
     * 保存图片缩略图
     * @param $photo
     * @param $filename
     * @param $thumbWidth
     * @param $thumbHeight
     * @param bool $isRatio 是否按比例压缩
     * @return \Intervention\Image\Image
     */
    static private function savaThumbImage($photo, $filename, $thumbWidth, $thumbHeight, $isRatio = false)
    {
        $manager = new ImageManager();
        if ($isRatio) {
            $image = $manager->make($photo)->resize($thumbWidth, $thumbHeight, function ($constraint) {
                $constraint->aspectRatio();
            })->save(Config::get('upload.avatar_thumb_save_path') . $filename);
        } else {
            $image = $manager->make($photo)->resize($thumbWidth, $thumbHeight)
                ->save(Config::get('upload.avatar_thumb_save_path') . $filename);
        }

        return $image;
    }

    /**
     * 删除本地文件
     * @param $filename
     * @return mixed
     */
    static public function delete($filename)
    {
        // 得到图片对于的存储文件夹，暂时只能删除头像
        $full_size_dir = Config::get('upload.avatar_save_path');
        $icon_size_dir = Config::get('upload.avatar_thumb_save_path');

        // 拼装图片资源路径
        $full_path1 = $full_size_dir . $filename;
        $full_path2 = $icon_size_dir . $filename;

        // 存在即删除
        if ( File::exists( $full_path1 ) )
        {
            File::delete( $full_path1 );
        }

        if ( File::exists( $full_path2 ) )
        {
            File::delete( $full_path2 );
        }

        return true;
    }

    /**
     * 得到用户头像
     * @param $id
     * @return mixed
     */
    static public function getStaffAvatar($id) {
        return StaffService::getAvatar($id);
    }
}
