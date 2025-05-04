<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace creocoder\flysystem;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use yii\base\InvalidConfigException;

/**
 * GoogleDriveFilesystem
 *
 * Flysystem component for Google Drive using a service account.
 *
 * Example usage:
 *
 * 'components' => [
 *     'fsGoogleDrive' => [
 *         'class' => 'creocoder\flysystem\GoogleDriveFilesystem',
 *         'authConfig' => '/path/to/credentials.json',
 *         'folderId' => 'your-google-drive-folder-id',
 *     ],
 * ],
 */
class GoogleDriveFilesystem extends Filesystem {
    /**
     * @var string Path to the credentials.json file for the Google service account
     */
    public $authConfig;

    /**
     * @var string|null ID of the Google Drive folder (null for root directory)
     */
    public $folderId;

    /**
     * Initializes the component and validates configuration.
     *
     * @throws InvalidConfigException if required properties are not set
     */
    public function init() {
        if ($this->authConfig === null) {
            throw new InvalidConfigException('The "authConfig" property must be set.');
        }

        parent::init();
    }

    /**
     * Prepares and returns the Google Drive adapter for Flysystem.
     *
     * @return GoogleDriveAdapter
     */
    protected function prepareAdapter() {
        $client = new GoogleClient();
        $client->setAuthConfig($this->authConfig);
        $client->addScope(GoogleDriveService::DRIVE);

        $service = new GoogleDriveService($client);

        return new GoogleDriveAdapter($service, $this->folderId);
    }
}
