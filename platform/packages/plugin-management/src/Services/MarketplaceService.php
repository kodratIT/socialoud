<?php

namespace Botble\PluginManagement\Services;

use Botble\Base\Supports\Core;
use Botble\Base\Supports\Zipper;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class MarketplaceService
{
    protected string $url;

    protected ?string $token;

    protected string $publishedPath;

    protected string $productId;

    protected string $licenseUrl;

    protected string $licenseApiKey;

    public function __construct(?string $url = null, ?string $token = null)
    {
        $coreData = Core::make()->getCoreFileData();

        $this->url = $url ?? ($coreData['marketplaceUrl'] ?? 'https://marketplace.botble.com');

        $this->token = $token ?? ($coreData['marketplaceToken'] ?? '');

        $this->publishedPath = storage_path('app/marketplace');

        $this->productId = $coreData['productId'] ?? '';

        $this->licenseUrl = $coreData['apiUrl'] ?? 'https://license.botble.com';

        $this->licenseApiKey = $coreData['apiKey'] ?? '';
    }

    public function callApi(string $method, string $path, array $request = []): JsonResponse|Response
    {
        abort_unless(config('packages.plugin-management.general.enable_marketplace_feature'), 404);

        $request = array_merge($request, [
            'product_id' => $this->productId,
            'site_url' => rtrim(url('')),
            'core_version' => get_core_version(),
            'license_url' => $this->licenseUrl ?: 'https://license.botble.com',
            'license_api_key' => $this->licenseApiKey ?: 'CAF4B17F6D3F656125F9',
        ]);

        $response = $this->request()->{$method}($this->url . $path, $request);

        if ($response->forbidden()) {
            return response()->json([
                'error' => true,
                'message' => $response->json('message') ?: trans('packages/plugin-management::marketplace.could_not_connect'),
            ], 403);
        }

        if ($response->failed()) {
            throw new Exception($response->json('message') ?: trans('packages/plugin-management::marketplace.could_not_connect'));
        }

        return $response;
    }

    protected function request(): PendingRequest
    {
        return Http::asJson()
            ->withHeaders([
                'Authorization' => 'Token ' . $this->token,
            ])
            ->acceptJson()
            ->withoutVerifying()
            ->connectTimeout(100)
            ->timeout(300);
    }

    public function beginInstall(string $id, string $name, ?PluginService $pluginService = null): bool|JsonResponse
    {
        $requestData = [];

        // Add plugin purchase code if available
        if ($pluginService) {
            $pluginPurchaseCode = $pluginService->getPluginPurchaseCode($name);
            if ($pluginPurchaseCode) {
                $requestData['plugin_purchase_code'] = $pluginPurchaseCode;
            }
        }

        $data = $this->callApi(
            'post',
            '/products/' . $id . '/download',
            $requestData
        );

        if ($data->getStatusCode() != 200) {
            $content = json_decode($data->getContent(), true);

            throw new Exception(Arr::get($content, 'message') ?: $data);
        }

        $storageTempPath = $this->publishedPath . '/' . $id;

        File::ensureDirectoryExists($storageTempPath, 0775);

        $destination = $storageTempPath . '/' . $name . '.zip';

        File::cleanDirectory($storageTempPath);

        File::put($destination, $data);

        $this->extractFile($storageTempPath, $name);
        $this->copyToPath($storageTempPath, plugin_path($name));

        return true;
    }

    protected function extractFile(string $pathTo, string $name): string
    {
        $destination = $pathTo . '/' . $name . '.zip';

        $zipper = new Zipper();

        if (! $zipper->extract($destination, $pathTo)) {
            throw new Exception(trans('packages/plugin-management::marketplace.unzip_failed'));
        }

        File::delete($destination);

        return $pathTo;
    }

    protected function copyToPath(string $fromPath, string $destinationPath): string
    {
        File::ensureDirectoryExists($destinationPath, 0775);

        if (File::isDirectory($fromPath)) {
            File::copyDirectory($fromPath, $destinationPath);
            File::deleteDirectory($fromPath);
        }

        return $destinationPath;
    }
}
