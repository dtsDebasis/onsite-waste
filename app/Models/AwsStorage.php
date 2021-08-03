<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws;


class AwsStorage extends Model
{
    use HasFactory;

    public function uploadFile($filepath='', $key='') {
        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));

        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);
        $source = fopen($filepath, 'rb');
        $uploader = new Aws\S3\ObjectUploader(
            $s3,
            env("AWS_BUCKET"),
            $key,
            $source
        );
        do {
            try {
                $result = $uploader->upload();
                if ($result["@metadata"]["statusCode"] == '200') {
                    return $result["ObjectURL"];
                }
            } catch (MultipartUploadException $e) {
                rewind($source);
                $uploader = new MultipartUploader($s3Client, $source, [
                    'state' => $e->getState(),
                ]);
            }
        } while (!isset($result));
        
        fclose($source);
    }
    public function createFolder($key='') {
        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);
        return $s3->putObject(array( 
            'Bucket' => env("AWS_BUCKET"),
            'Key'    => $key,
            'Body'   => "",
        ));
    }

    public function deleteobj($key=''){
        $credentials = new Aws\Credentials\Credentials(env("AWS_ACCESS_KEY_ID"), env("AWS_SECRET_ACCESS_KEY"));
        $s3 = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => $credentials
        ]);
        $result = $s3->deleteObject([
            'Bucket' => env("AWS_BUCKET"),
            'Key' => $key,
        ]);
        return $result;
    }
}