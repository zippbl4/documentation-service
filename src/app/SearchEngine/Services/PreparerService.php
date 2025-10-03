<?php

namespace App\SearchEngine\Services;

class PreparerService
{
    public function prepareIndex(string $html): array
    {
        $result = [];

        if (preg_match("/<title[^>]*>(.+)<\/title>/Uis", $html, $title)) {
            $result["title"] = $this->removeHtmlTags($title[1]);
        } else {
            $result["title"] = "";
        }

        if (preg_match("/<body[^>]*>(.+)<\/body>/Uis", $html, $body)) {
            $result["body"] = $this->removeHtmlTags($body[1]);
        } else {
            $result["body"] = $html;
        }

        return $result;
    }

    private function removeHtmlTags($html): string
    {
        return preg_replace('/<[^>]+>/', '', $html);
    }
}
