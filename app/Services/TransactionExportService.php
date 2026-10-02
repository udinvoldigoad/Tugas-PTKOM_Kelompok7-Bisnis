<?php

namespace App\Services;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RuntimeException;
use ZipArchive;

class TransactionExportService
{
    /** @param Collection<int, Transaksi> $transactions */
    public function createXlsx(Collection $transactions): string
    {
        $path = tempnam(sys_get_temp_dir(), 'riwayat-');

        if ($path === false) {
            throw new RuntimeException('File export sementara tidak dapat dibuat.');
        }

        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('File Excel tidak dapat dibuat.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->rootRelationships());
        $zip->addFromString('xl/workbook.xml', $this->workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationships());
        $zip->addFromString('xl/styles.xml', $this->styles());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheet($transactions));
        $zip->close();

        return $path;
    }

    /** @param Collection<int, Transaksi> $transactions */
    private function worksheet(Collection $transactions): string
    {
        $rows = [['No. Struk', 'Tanggal', 'Kasir', 'Metode Pembayaran', 'Subtotal', 'PPN 10%', 'Total']];

        foreach ($transactions as $transaction) {
            $subtotal = (int) $transaction->detailTransaksi->sum('subtotal');
            $rows[] = [
                sprintf('TRX-%03d', $transaction->id),
                Carbon::parse($transaction->tanggal)->format('d-m-Y H:i'),
                $transaction->user->name,
                strtoupper($transaction->metode_pembayaran ?? '-'),
                $subtotal,
                max(0, (int) $transaction->total_harga - $subtotal),
                (int) $transaction->total_harga,
            ];
        }

        $sheetRows = '';

        foreach ($rows as $rowIndex => $row) {
            $cells = '';

            foreach ($row as $columnIndex => $value) {
                $reference = $this->columnName($columnIndex + 1).($rowIndex + 1);

                if (is_int($value)) {
                    $cells .= sprintf('<c r="%s" s="2"><v>%d</v></c>', $reference, $value);
                } else {
                    $style = $rowIndex === 0 ? 1 : 0;
                    $cells .= sprintf('<c r="%s" t="inlineStr" s="%d"><is><t>%s</t></is></c>', $reference, $style, $this->xml($value));
                }
            }

            $sheetRows .= sprintf('<row r="%d">%s</row>', $rowIndex + 1, $cells);
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><cols><col min="1" max="1" width="14" customWidth="1"/><col min="2" max="4" width="22" customWidth="1"/><col min="5" max="7" width="16" customWidth="1"/></cols>'
            .'<sheetData>'.$sheetRows.'</sheetData><autoFilter ref="A1:G'.count($rows).'"/></worksheet>';
    }

    private function columnName(int $number): string
    {
        $name = '';

        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)).$name;
            $number = intdiv($number, 26);
        }

        return $name;
    }

    private function xml(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>';
    }

    private function rootRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
    }

    private function workbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Riwayat Transaksi" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private function workbookRelationships(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>';
    }

    private function styles(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><numFmts count="1"><numFmt numFmtId="164" formatCode="&quot;Rp &quot;#,##0"/></numFmts><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills><borders count="1"><border/></borders><cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs><cellXfs count="3"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/><xf numFmtId="164" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/></cellXfs></styleSheet>';
    }
}
