<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Character;

class CharacterSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = storage_path('app/makemeahanzi/dictionary.txt');

        // Если файла нет, используем альтернативный путь
        if (!file_exists($filePath)) {
            $filePath = base_path('storage/makemeahanzi/dictionary.txt');
        }

        if (!file_exists($filePath)) {
            $this->command->error('Dictionary file not found! Run: git clone https://github.com/skishore/makemeahanzi storage/makemeahanzi');
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $total = count($lines);
        $imported = 0;
        $skipped = 0;

        $this->command->info("Processing {$total} characters...");

        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if (!$data) {
                $skipped++;
                continue;
            }

            $character = $data['character'] ?? null;
            if (!$character || mb_strlen($character) > 1) {
                $skipped++;
                continue;
            }

            // Обработка pinyin
            $pinyin = null;
            if (isset($data['pinyin'])) {
                if (is_array($data['pinyin']) && !empty($data['pinyin'])) {
                    $pinyin = $data['pinyin'];
                } elseif (is_string($data['pinyin']) && $data['pinyin'] !== '') {
                    $pinyin = [$data['pinyin']];
                }
            }

            // Получение количества черт
            $strokeCount = null;
            if (isset($data['strokes'])) {
                $strokeCount = (string) $data['strokes'];
            }

            // Получение этимологии
            $etymology = null;
            if (isset($data['etymology'])) {
                if (is_string($data['etymology'])) {
                    $etymology = $data['etymology'];
                } elseif (is_array($data['etymology'])) {
                    $etymology = $data['etymology']['hint'] ?? $data['etymology']['type'] ?? null;
                }
            }

            Character::updateOrCreate(
                ['character' => $character],
                [
                    'pinyin' => $pinyin,
                    'definition' => $data['definition'] ?? null,
                    'radical' => $data['radical'] ?? null,
                    'decomposition' => $data['decomposition'] ?? null,
                    'etymology' => $etymology,
                    'stroke_count' => $strokeCount,
                ]
            );

            $imported++;

            if ($imported % 1000 === 0) {
                $this->command->info("Imported {$imported} characters...");
            }
        }

        $this->command->info("✅ Import completed! Imported: {$imported}, Skipped: {$skipped}");
    }
}
