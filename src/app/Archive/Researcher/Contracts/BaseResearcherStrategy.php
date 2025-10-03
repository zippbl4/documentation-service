<?php

namespace App\Archive\Researcher\Contracts;

use App\Archive\Researcher\DTO\ProductDTO;

abstract class BaseResearcherStrategy
{
    public abstract function getRawFiles(string $archive): array;

    /**
     * @param string $archive
     * @param string $pathRegexPattern
     * @return false|ProductDTO[]
     */
    public function getProductInfo(string $archive, string $pathRegexPattern): false|array
    {
        $rawFiles = $this->getRawFiles($archive);

        $result = [];
        $lang = '';
        foreach ($rawFiles as $rawFile) {
            preg_match($pathRegexPattern, $rawFile, $matches);

            if (
                ! empty($matches['page'])
                && $matches['lang'] !== $lang
            ) {
                $lang = $matches['lang'];

                $result[] = new ProductDTO(
                    lang: $matches['lang'] ?? '',
                    product: $matches['product'] ?? '',
                    version: $matches['version'] ?? '',
                );
            }
        }

        if (empty($result)) {
            return false;
        }

        return $result;
    }

    public function getRootFolder(string $archive): false|string
    {
        $rawFiles = $this->getRawFiles($archive);

        $rootFolders = [];
        foreach ($rawFiles as $path) {
            $parts = explode('/', $path);
            if (! empty($parts[0])) {
                $rootFolders[] = $parts[0];
            }
        }

        $rootFolders = array_unique($rootFolders);
        $rootFolderExists = count($rootFolders) === 1;
        $rootFolder = current($rootFolders);

        return $rootFolderExists ? $rootFolder : false;
    }
}
