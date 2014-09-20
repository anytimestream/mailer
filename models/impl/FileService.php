<?php

use Aws\S3\S3Client;

class FileService {

    public static function GetSize($path) {
        if (file_exists($path)) {
            $info = stat($path);
            return $info['size'];
        }
        return 0;
    }

    public static function Remove($path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public static function UploadToS3($file, $key) {
        try {
            error_reporting(0);
            if ($_FILES[$file]['error'] == 0) {
                $client = S3Client::factory(array('key' => AWS_ACCESS_KEY_ID, 'secret' => AWS_SECRET_ACCESS_KEY));

                $contents = file_get_contents($_FILES[$file]['tmp_name']);

                $client->putObject(array(
                    'Bucket' => 'cdn.mailer.apps.iportalworks.com',
                    'Key' => $key,
                    'Body' => $contents,
                    'StorageClass' => 'REDUCED_REDUNDANCY',
                    'ACL' => 'public-read'
                ));
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function DeleteFromS3($key) {
        try {
            error_reporting(0);
            $client = S3Client::factory(array('key' => AWS_ACCESS_KEY_ID, 'secret' => AWS_SECRET_ACCESS_KEY));

            $client->deleteObject("cdn.mailer.apps.iportalworks.com", $key);
            
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

    public static function Upload($file, $path) {
        try {
            if ($_FILES[$file]['error'] == 0) {
                if (move_uploaded_file($_FILES[$file]['tmp_name'], $path)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            ExceptionManager::RaiseException($e);
        }
    }

}

?>
