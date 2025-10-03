<?php

namespace App\ContentEngine\Diff;

use App\ContentEngine\Contracts\DiffInterface;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class DiffSebastianWrapper implements DiffInterface
{
    public function diff(string $from, string $to): string
    {
        $diff = (new Differ(new UnifiedDiffOutputBuilder()))->diff($from, $to);

        $doc = new \DOMDocument();
        @$doc->loadHTML(
            // обеспечивает корректную обработку UTF-8
            mb_convert_encoding($diff, 'HTML-ENTITIES', 'UTF-8'),
            // предотвращают добавление автоматических тегов (<html>, <body>)
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        $doc->encoding = 'utf-8';
        return sprintf('<pre>%s</pre>', $doc->saveHTML());
    }
}
