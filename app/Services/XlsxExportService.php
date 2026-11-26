<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class XlsxExportService
{
    /**
     * Menghasilkan file Excel (.xlsx) asli dengan styling header, border, dan lebar kolom otomatis.
     *
     * @param  list<string>  $headers
     * @param  list<list<string|int|float|null>>  $rows
     * @param  array<string, string>  $metadata
     * @param  list<string|int|float|null>|null  $totalRow
     */
    public function download(
        string $filename,
        string $sheetTitle,
        string $reportTitle,
        array $metadata,
        array $headers,
        array $rows,
        ?array $totalRow = null,
    ): StreamedResponse {
        $allRows = [];
        $rowStyles = [];

        $allRows[] = ['PT BUANA SEJAHTERA — BUANA GROUP'];
        $rowStyles[] = 'title';

        $allRows[] = [strtoupper($reportTitle)];
        $rowStyles[] = 'subtitle';

        foreach ($metadata as $label => $value) {
            $allRows[] = [$label, $value];
            $rowStyles[] = 'meta';
        }

        $allRows[] = ['Dicetak Pada', now()->format('d/m/Y H:i').' WIB'];
        $rowStyles[] = 'meta';

        $allRows[] = [];
        $rowStyles[] = 'blank';

        $allRows[] = $headers;
        $rowStyles[] = 'header';

        foreach ($rows as $row) {
            $allRows[] = $row;
            $rowStyles[] = 'data';
        }

        if ($totalRow !== null) {
            $allRows[] = $totalRow;
            $rowStyles[] = 'total';
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        if ($tmpFile === false) {
            throw new RuntimeException('Gagal membuat file sementara untuk export Excel.');
        }

        $this->buildWorkbook($tmpFile, $sheetTitle, $allRows, $rowStyles, count($headers));

        return response()->streamDownload(function () use ($tmpFile): void {
            readfile($tmpFile);
            @unlink($tmpFile);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @param  list<list<string|int|float|null>>  $allRows
     * @param  list<string>  $rowStyles
     */
    private function buildWorkbook(string $path, string $sheetTitle, array $allRows, array $rowStyles, int $colCount): void
    {
        $zip = new ZipArchive;
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Gagal membuat arsip XLSX.');
        }

        $safeSheetTitle = mb_substr(preg_replace('/[\\\\\/\?\*\[\]:]/', '', $sheetTitle) ?: 'Laporan', 0, 31);

        $zip->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $zip->addFromString('_rels/.rels', $this->relsXml());
        $zip->addFromString('xl/workbook.xml', $this->workbookXml($safeSheetTitle));
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelsXml());
        $zip->addFromString('xl/styles.xml', $this->stylesXml());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->sheetXml($allRows, $rowStyles, $colCount));

        $zip->close();
    }

    /**
     * @param  list<list<string|int|float|null>>  $allRows
     * @param  list<string>  $rowStyles
     */
    private function sheetXml(array $allRows, array $rowStyles, int $colCount): string
    {
        $maxCols = max($colCount, 2);
        $colWidths = array_fill(0, $maxCols, 12);

        foreach ($allRows as $idx => $row) {
            if (($rowStyles[$idx] ?? '') === 'title' || ($rowStyles[$idx] ?? '') === 'subtitle') {
                continue;
            }
            foreach ($row as $colIdx => $val) {
                $len = mb_strlen((string) ($val ?? '')) + 4;
                if ($len > ($colWidths[$colIdx] ?? 12)) {
                    $colWidths[$colIdx] = min($len, 45);
                }
            }
        }

        $colsXml = '<cols>';
        foreach ($colWidths as $idx => $width) {
            $colNum = $idx + 1;
            $colsXml .= "<col min=\"{$colNum}\" max=\"{$colNum}\" width=\"{$width}\" customWidth=\"1\"/>";
        }
        $colsXml .= '</cols>';

        $rowsXml = '';
        foreach ($allRows as $rowIndex => $row) {
            $r = $rowIndex + 1;
            $style = $rowStyles[$rowIndex] ?? 'data';
            $heightAttr = $style === 'header' ? ' ht="24" customHeight="1"' : ($style === 'title' ? ' ht="22" customHeight="1"' : '');
            $rowsXml .= "<row r=\"{$r}\"{$heightAttr}>";

            $cellsCount = $style === 'header' || $style === 'data' || $style === 'total' ? $colCount : count($row);
            for ($c = 0; $c < $cellsCount; $c++) {
                $val = $row[$c] ?? '';
                $ref = $this->colLetter($c + 1).$r;
                $styleIdx = $this->resolveStyleIndex($style, $val);

                if (is_int($val) || is_float($val)) {
                    $rowsXml .= "<c r=\"{$ref}\" s=\"{$styleIdx}\"><v>{$val}</v></c>";
                } else {
                    $escaped = htmlspecialchars((string) $val, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                    $rowsXml .= "<c r=\"{$ref}\" t=\"inlineStr\" s=\"{$styleIdx}\"><is><t>{$escaped}</t></is></c>";
                }
            }

            $rowsXml .= '</row>';
        }

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .$colsXml
            .'<sheetData>'.$rowsXml.'</sheetData>'
            .'</worksheet>';
    }

    private function resolveStyleIndex(string $rowStyle, mixed $val): int
    {
        return match ($rowStyle) {
            'title' => 1,
            'subtitle' => 2,
            'meta' => 3,
            'header' => 4,
            'total' => is_int($val) || is_float($val) ? 7 : 6,
            'data' => is_int($val) || is_float($val) ? 8 : 5,
            default => 0,
        };
    }

    private function colLetter(int $col): string
    {
        $letter = '';
        while ($col > 0) {
            $mod = ($col - 1) % 26;
            $letter = chr(65 + $mod).$letter;
            $col = (int) (($col - $mod) / 26);
        }

        return $letter;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';
    }

    private function relsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';
    }

    private function workbookXml(string $sheetTitle): string
    {
        $escaped = htmlspecialchars($sheetTitle, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="'.$escaped.'" sheetId="1" r:id="rId1"/></sheets>'
            .'</workbook>';
    }

    private function workbookRelsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<numFmts count="1"><numFmt numFmtId="164" formatCode="#,##0"/></numFmts>'
            .'<fonts count="4">'
            .'<font><sz val="11"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="14"/><color rgb="FF065F46"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            .'<font><b/><sz val="11"/><color rgb="FF111827"/><name val="Calibri"/></font>'
            .'</fonts>'
            .'<fills count="4">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF059669"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFECFDF5"/></patternFill></fill>'
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border>'
            .'<left style="thin"><color rgb="FFD1D5DB"/></left>'
            .'<right style="thin"><color rgb="FFD1D5DB"/></right>'
            .'<top style="thin"><color rgb="FFD1D5DB"/></top>'
            .'<bottom style="thin"><color rgb="FFD1D5DB"/></bottom>'
            .'</border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs count="9">'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="1" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            .'<xf numFmtId="0" fontId="2" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1"/>'
            .'<xf numFmtId="0" fontId="3" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="3" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>'
            .'<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>'
            .'</cellXfs>'
            .'</styleSheet>';
    }
}
