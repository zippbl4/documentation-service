<?php

namespace App\Archive\Researcher\Strategies;

use App\Archive\Researcher\Contracts\BaseResearcherStrategy;

class ZipManualResearcherStrategy extends BaseResearcherStrategy
{
    public static function getName(): string
    {
        return 'zip_manual';
    }

    /**
     * @example ...
     *
     * @return array
     */
    public function getRawFiles(string $archive): array
    {
        $rawFiles = shell_exec(sprintf(
            // Объяснение:
            // unzip -l archive.zip: Показывает список файлов в архиве с их размерами, датой и временем.
            // awk 'NR>3 {print $NF}':
            // NR>3: Пропускает первые три строки, которые содержат заголовок.
            // {print $NF}: Печатает последний столбец (имя файла или путь).
            // head -n -2: Убирает последние две строки, которые содержат итоговую информацию (например, "N файлов в архиве").
            // Спасибо чат GPT!
            "unzip -l %s | awk 'NR>3 {print \$NF}' | head -n -2",
            $archive,
        ));

        $rawFiles = explode("\n", $rawFiles);
        return $rawFiles;
    }


}
