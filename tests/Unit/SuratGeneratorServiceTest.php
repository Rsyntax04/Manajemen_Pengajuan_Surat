<?php

namespace Tests\Unit;

use App\Services\SuratGeneratorService;
use PHPUnit\Framework\TestCase;

class SuratGeneratorServiceTest extends TestCase
{
    public function test_normalize_template_placeholders_converts_common_formats(): void
    {
        $service = new SuratGeneratorService();
        $method = new \ReflectionMethod($service, 'normalizeTemplatePlaceholders');
        $method->setAccessible(true);

        $content = '<w:t>{{nama_lengkap}}</w:t> and [[nomor_surat]] and ${tanggal_ttd}';
        $result = $method->invoke($service, $content);

        $this->assertStringContainsString('${nama_lengkap}', $result);
        $this->assertStringContainsString('${nomor_surat}', $result);
        $this->assertStringContainsString('${tanggal_ttd}', $result);
    }

    public function test_set_panitia_placeholders_uses_jabatan_nama_and_identitas(): void
    {
        $service = new SuratGeneratorService();
        $template = new class {
            public array $values = [];

            public function setValue($key, $value): void
            {
                $this->values[$key] = $value;
            }
        };

        $panitia = collect([
            (object) ['jabatan' => 'Ketua', 'nama' => 'Budi', 'identitas' => '12345'],
        ]);

        $method = new \ReflectionMethod($service, 'setPanitiaPlaceholders');
        $method->setAccessible(true);
        $method->invoke($service, $template, $panitia);

        $this->assertSame('Ketua', $template->values['jabatan']);
        $this->assertSame('Budi', $template->values['nama']);
        $this->assertSame('12345', $template->values['identitas']);
    }
}
