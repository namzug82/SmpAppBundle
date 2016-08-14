<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

final class S3Bucket
{
	private $client;
	private $bucket;
	private $container;

	public function __construct(Container $container, $config)
	{
		$this->client = S3Client::factory([
			'key' => $config['key'],
			'secret' => $config['secret']
		]);

		$this->bucket = $config['bucket'];
		$this->container = $container;
	}

	public function upload($file, $baseUrl)
	{
		try {
			$fileName = $file->getClientOriginalName();
			
			$upload = $this->client->putObject(array(
			    'Bucket'     => $this->bucket,
			    'Key'        => $fileName,
			    'Body'   => fopen($baseUrl, 'r+')
			));

			// We can poll the object until it is accessible
			$this->client->waitUntil('ObjectExists', array(
			    'Bucket' => $this->bucket,
			    'Key'    => $fileName
			));

			return $upload->get('ObjectURL');

		} catch (S3Exception $e) {
			echo $e->getMessage(), ' ', $e->getAwsErrorCode();
		    die("There was an error uploading that file.");
		}
	}
}
