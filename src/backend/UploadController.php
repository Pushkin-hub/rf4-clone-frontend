<?php
require_once 'utils/UploadHandler.php';
require_once 'utils/Response.php';

class UploadController {
    public function uploadImage() {
        $user = AuthMiddleware::authenticate();
        
        if (!isset($_FILES['file'])) {
            Response::error('No file provided');
        }
        
        $uploadHandler = new UploadHandler();
        $result = $uploadHandler->handleImageUpload($_FILES['file'], $user['user_id']);
        
        if ($result['success']) {
            Response::success([
                'file_url' => $result['file_url'],
                'file_path' => $result['file_path'],
                'file_name' => $result['file_name']
            ], 'File uploaded successfully');
        } else {
            Response::error($result['error']);
        }
    }

    public function uploadDocument() {
        $user = AuthMiddleware::authenticate();
        
        if (!isset($_FILES['file'])) {
            Response::error('No file provided');
        }
        
        $uploadHandler = new UploadHandler();
        $result = $uploadHandler->handleDocumentUpload($_FILES['file'], $user['user_id']);
        
        if ($result['success']) {
            Response::success([
                'file_url' => $result['file_url'],
                'file_path' => $result['file_path'],
                'file_name' => $result['file_name']
            ], 'Document uploaded successfully');
        } else {
            Response::error($result['error']);
        }
    }

    public function deleteFile($filename) {
        $user = AuthMiddleware::authenticate();
        
        $uploadHandler = new UploadHandler();
        $result = $uploadHandler->deleteFile($filename, $user['user_id']);
        
        if ($result['success']) {
            Response::success(null, 'File deleted successfully');
        } else {
            Response::error($result['error']);
        }
    }

    public function getTempFile($filename) {
        $uploadHandler = new UploadHandler();
        $filePath = $uploadHandler->getTempFilePath($filename);
        
        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            Response::error('File not found', 404);
        }
    }
}
