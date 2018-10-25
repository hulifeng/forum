<?php
namespace App\Handlers;

use Image;

class ImageUploaderHandler
{
    // 只允许以下后缀名的图片上传
    protected $allowed_ext = ['png', 'jpeg', 'jpg', 'gif'];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // 获取文件后缀名，如果上传的后缀名不在允许的后缀名里面，终止操作
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 构建目录
        $folder_name = "uploads/images/$folder" .  '/' . date("Ym/d", time());

        // 上传路径
        $upload_path = public_path() . '/' . $folder_name;

        // 文件名 1_1493521050_7BVc9v9ujP.png
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // 将文件上传到我们的目标目录
        $file->move($upload_path, $filename);

        if ($max_width && $extension != 'gif') {
            // 此类中封装的函数，用语裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 实例化，传输的物理路径
        $image = Image::make($file_path);

        // 进行大小的调整
        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止截图时尺寸放大
            $constraint->upsize();
        });

        // 对图片进行存储
        $image->save();
    }
}