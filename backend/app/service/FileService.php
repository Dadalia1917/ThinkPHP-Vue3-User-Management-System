<?php

namespace app\service;

/**
 * 文件服务类 - 简化版本，替代 think-filesystem
 */
class FileService
{
    /**
     * 上传目录
     */
    protected $uploadPath = 'uploads/';

    /**
     * 允许的文件类型
     */
    protected $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];

    /**
     * 文件上传
     */
    public function upload($file, $dir = 'common')
    {
        if (!$file || !$file->isValid()) {
            return ['status' => false, 'msg' => '文件上传失败'];
        }

        $uploadDir = $this->uploadPath . $dir . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower($file->extension());
        if (!in_array($ext, $this->allowedTypes)) {
            return ['status' => false, 'msg' => '文件类型不允许'];
        }

        $filename = date('YmdHis') . '_' . uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;

        if ($file->move($uploadDir, $filename)) {
            return [
                'status' => true,
                'msg' => '上传成功',
                'data' => [
                    'filename' => $filename,
                    'filepath' => $filepath,
                    'url' => '/' . $filepath
                ]
            ];
        }

        return ['status' => false, 'msg' => '文件保存失败'];
    }

    /**
     * 删除文件
     */
    public function delete($filepath)
    {
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
