<?php
/*******************************************************************************
* FPDF - Free PDF generation class                                             *
* Version: 1.86                                                                *
* License: FPDF License (Permissive)                                           *
*******************************************************************************/

class FPDF {
    protected int $page = 0;
    protected int $n = 2;
    protected array $offsets = [];
    protected string $buffer = '';
    protected array $pages = [];
    protected int $state = 0;
    protected bool $compress = true;
    protected float $k;
    protected string $DefOrientation = 'P';
    protected string $CurOrientation = 'P';
    protected array $StdPageSizes = [];
    protected array $DefPageSize = [];
    protected array $CurPageSize = [];
    protected float $wPt, $hPt;
    protected float $w, $h;
    protected float $lMargin, $tMargin, $rMargin, $bMargin;
    protected float $x, $y;
    protected float $lasth = 0;
    protected float $LineWidth;
    protected string $fontpath = '';
    protected array $CoreFonts = [];
    protected array $fonts = [];
    protected array $FontFiles = [];
    protected array $encodings = [];
    protected array $cmaps = [];
    protected string $FontFamily = '';
    protected string $FontStyle = '';
    protected bool $underline = false;
    protected array $CurrentFont = [];
    protected float $FontSizePt = 12;
    protected float $FontSize = 12;
    protected string $DrawColor = '0 G';
    protected string $FillColor = '0 g';
    protected string $TextColor = '0 g';
    protected bool $ColorFlag = false;
    protected bool $WithAlpha = false;
    protected float $ws = 0;
    protected array $images = [];
    protected array $PageLinks = [];
    protected array $links = [];
    protected bool $AutoPageBreak = true;
    protected float $PageBreakTrigger;
    protected bool $InHeader = false;
    protected bool $InFooter = false;
    protected bool $AliasNbPages = false;
    protected string $alias = '{nb}';
    protected string $ZoomMode = 'fullwidth';
    protected string $LayoutMode = 'continuous';
    protected string $metadata = '';

    public function __construct(string $orientation = 'P', string $unit = 'mm', $size = 'A4') {
        $this->CoreFonts = ['courier', 'helvetica', 'times', 'symbol', 'zapfdingbats'];
        if ($unit === 'pt') $this->k = 1;
        elseif ($unit === 'mm') $this->k = 72 / 25.4;
        elseif ($unit === 'cm') $this->k = 72 / 2.54;
        elseif ($unit === 'in') $this->k = 72;
        else $this->Error('Unidad de medida incorrecta: ' . $unit);

        $this->StdPageSizes = [
            'a3' => [841.89, 1190.55],
            'a4' => [595.28, 841.89],
            'a5' => [420.94, 595.28],
            'letter' => [612, 792],
            'legal' => [612, 1008]
        ];

        $size = $this->_getpagesize($size);
        $this->DefPageSize = $size;
        $this->CurPageSize = $size;

        $orientation = strtolower($orientation);
        if ($orientation === 'p' || $orientation === 'portrait') {
            $this->DefOrientation = 'P';
            $this->w = $size[0];
            $this->h = $size[1];
        } elseif ($orientation === 'l' || $orientation === 'landscape') {
            $this->DefOrientation = 'L';
            $this->w = $size[1];
            $this->h = $size[0];
        } else {
            $this->Error('Orientación incorrecta: ' . $orientation);
        }

        $this->CurOrientation = $this->DefOrientation;
        $this->wPt = $this->w * $this->k;
        $this->hPt = $this->h * $this->k;

        $margin = 28.35 / $this->k;
        $this->SetMargins($margin, $margin);
        $this->LineWidth = 0.567 / $this->k;
        $this->SetAutoPageBreak(true, 2 * $margin);
        $this->SetDisplayMode('default');
        $this->compress = function_exists('gzcompress');
    }

    public function SetMargins(float $left, float $top, ?float $right = null): void {
        $this->lMargin = $left;
        $this->tMargin = $top;
        if ($right === null) $right = $left;
        $this->rMargin = $right;
    }

    public function SetAutoPageBreak(bool $auto, float $margin = 0): void {
        $this->AutoPageBreak = $auto;
        $this->bMargin = $margin;
        $this->PageBreakTrigger = $this->h - $margin;
    }

    public function SetDisplayMode(string $zoom, string $layout = 'continuous'): void {
        $this->ZoomMode = $zoom;
        $this->LayoutMode = $layout;
    }

    public function AddPage(string $orientation = '', $size = '', float $rotation = 0): void {
        if ($this->state === 0) $this->Open();
        $family = $this->FontFamily;
        $style = $this->FontStyle . ($this->underline ? 'U' : '');
        $fontsize = $this->FontSizePt;
        $lw = $this->LineWidth;
        $dc = $this->DrawColor;
        $fc = $this->FillColor;
        $tc = $this->TextColor;
        $cf = $this->ColorFlag;

        if ($this->page > 0) {
            $this->InFooter = true;
            $this->Footer();
            $this->InFooter = false;
            $this->_endpage();
        }

        $this->_beginpage($orientation, $size, $rotation);
        $this->_out('2 J');
        $this->LineWidth = $lw;
        $this->_out(sprintf('%.2F w', $lw * $this->k));

        if ($family) $this->SetFont($family, $style, $fontsize);
        $this->DrawColor = $dc;
        if ($dc !== '0 G') $this->_out($dc);
        $this->FillColor = $fc;
        if ($fc !== '0 g') $this->_out($fc);
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;

        $this->InHeader = true;
        $this->Header();
        $this->InHeader = false;

        if ($this->LineWidth !== $lw) {
            $this->LineWidth = $lw;
            $this->_out(sprintf('%.2F w', $lw * $this->k));
        }
        if ($family) $this->SetFont($family, $style, $fontsize);
        if ($this->DrawColor !== $dc) {
            $this->DrawColor = $dc;
            $this->_out($dc);
        }
        if ($this->FillColor !== $fc) {
            $this->FillColor = $fc;
            $this->_out($fc);
        }
        $this->TextColor = $tc;
        $this->ColorFlag = $cf;
    }

    public function Header(): void {}
    public function Footer(): void {}

    public function PageNo(): int {
        return $this->page;
    }

    public function SetDrawColor(int $r, ?int $g = null, ?int $b = null): void {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null)
            $this->DrawColor = sprintf('%.3F G', $r / 255);
        else
            $this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
        if ($this->page > 0) $this->_out($this->DrawColor);
    }

    public function SetFillColor(int $r, ?int $g = null, ?int $b = null): void {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null)
            $this->FillColor = sprintf('%.3F g', $r / 255);
        else
            $this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        $this->ColorFlag = ($this->FillColor !== '0 g');
        if ($this->page > 0) $this->_out($this->FillColor);
    }

    public function SetTextColor(int $r, ?int $g = null, ?int $b = null): void {
        if (($r === 0 && $g === 0 && $b === 0) || $g === null)
            $this->TextColor = sprintf('%.3F g', $r / 255);
        else
            $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        $this->ColorFlag = ($this->FillColor !== '0 g');
    }

    public function SetFont(string $family, string $style = '', ?float $size = 0): void {
        $family = strtolower($family);
        if ($family === '') $family = $this->FontFamily;
        if ($family === 'arial') $family = 'helvetica';
        elseif ($family === 'symbol' || $family === 'zapfdingbats') $style = '';
        $style = strtoupper($style);

        if ($size === 0 || $size === null) $size = $this->FontSizePt;
        $this->FontSizePt = $size;
        $this->FontSize = $size / $this->k;
        $this->FontFamily = $family;
        $this->FontStyle = $style;
        $fontkey = $family . $style;

        if (!isset($this->fonts[$fontkey])) {
            $this->fonts[$fontkey] = ['i' => count($this->fonts) + 1, 'type' => 'core', 'name' => $this->_getfontname($family, $style)];
        }
        $this->CurrentFont = &$this->fonts[$fontkey];
        if ($this->page > 0) {
            $this->_out(sprintf('BT /F%d %.2F Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
        }
    }

    protected function _getfontname(string $family, string $style): string {
        $f = ucfirst($family);
        if ($style === 'B') return $f . '-Bold';
        if ($style === 'I') return $f . '-Oblique';
        if ($style === 'BI' || $style === 'IB') return $f . '-BoldOblique';
        return $f;
    }

    public function SetFontSize(float $size): void {
        $this->SetFont($this->FontFamily, $this->FontStyle, $size);
    }

    public function Line(float $x1, float $y1, float $x2, float $y2): void {
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F l S', $x1 * $this->k, ($this->h - $y1) * $this->k, $x2 * $this->k, ($this->h - $y2) * $this->k));
    }

    public function Rect(float $x, float $y, float $w, float $h, string $style = ''): void {
        $op = 'S';
        if ($style === 'F') $op = 'f';
        elseif ($style === 'FD' || $style === 'DF') $op = 'B';
        $this->_out(sprintf('%.2F %.2F %.2F %.2F re %s', $x * $this->k, ($this->h - $y) * $this->k, $w * $this->k, -$h * $this->k, $op));
    }

    public function SetXY(float $x, float $y): void {
        $this->SetY($y);
        $this->SetX($x);
    }

    public function SetX(float $x): void {
        if ($x >= 0) $this->x = $x;
        else $this->x = $this->w + $x;
    }

    public function SetY(float $y, bool $resetX = true): void {
        if ($resetX) $this->x = $this->lMargin;
        if ($y >= 0) $this->y = $y;
        else $this->y = $this->h + $y;
    }

    public function GetX(): float { return $this->x; }
    public function GetY(): float { return $this->y; }

    public function Cell(float $w, float $h = 0, string $txt = '', $border = 0, int $ln = 0, string $align = '', bool $fill = false, string $link = ''): void {
        $k = $this->k;
        if ($this->y + $h > $this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak()) {
            $x = $this->x;
            $ws = $this->ws;
            if ($ws > 0) {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->AddPage($this->CurOrientation, $this->CurPageSize);
            $this->x = $x;
            if ($ws > 0) {
                $this->ws = $ws;
                $this->_out(sprintf('%.3F Tw', $ws * $k));
            }
        }
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $s = '';
        if ($fill || $border == 1) {
            $op = ($fill ? ($border == 1 ? 'B' : 'f') : 'S');
            $s = sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y) * $k, $w * $k, -$h * $k, $op);
        }
        if ($border && is_string($border)) {
            $x = $this->x;
            $y = $this->y;
            if (strpos($border, 'L') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, $x * $k, ($this->h - ($y + $h)) * $k);
            if (strpos($border, 'T') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - $y) * $k);
            if (strpos($border, 'R') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', ($x + $w) * $k, ($this->h - $y) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
            if (strpos($border, 'B') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x * $k, ($this->h - ($y + $h)) * $k, ($x + $w) * $k, ($this->h - ($y + $h)) * $k);
        }
        if ($txt !== '') {
            if ($align === 'R') $dx = $w - $this->rMargin - $this->GetStringWidth($txt);
            elseif ($align === 'C') $dx = ($w - $this->GetStringWidth($txt)) / 2;
            else $dx = 1.5;
            if ($this->ColorFlag) $s .= 'q ' . $this->TextColor . ' ';
            $txt2 = str_replace(')', '\\)', str_replace('(', '\\(', str_replace('\\', '\\\\', $txt)));
            $s .= sprintf('BT %.2F %.2F Td (%s) Tj ET', ($this->x + $dx) * $k, ($this->h - ($this->y + 0.5 * $h + 0.3 * $this->FontSize)) * $k, $txt2);
            if ($this->ColorFlag) $s .= ' Q';
        }
        if ($s) $this->_out($s);
        $this->lasth = $h;
        if ($ln > 0) {
            $this->y += $h;
            if ($ln == 1) $this->x = $this->lMargin;
        } else {
            $this->x += $w;
        }
    }

    public function MultiCell(float $w, float $h, string $txt, $border = 0, string $align = 'J', bool $fill = false): void {
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $lines = explode("\n", $txt);
        foreach ($lines as $line) {
            $this->Cell($w, $h, trim($line), $border, 1, $align, $fill);
        }
    }

    public function Ln(?float $h = null): void {
        $this->x = $this->lMargin;
        if ($h === null) $this->y += $this->lasth;
        else $this->y += $h;
    }

    public function GetStringWidth(string $s): float {
        // Estimación aproximada para Helvetica
        return strlen($s) * $this->FontSize * 0.55;
    }

    public function AcceptPageBreak(): bool {
        return $this->AutoPageBreak;
    }

    public function Open(): void {
        $this->state = 1;
    }

    public function Output(string $dest = '', string $name = '', bool $isUTF8 = false): string {
        if ($this->state < 3) $this->Close();
        $dest = strtoupper($dest);
        if ($dest === '') {
            $dest = 'I';
        }
        if ($dest === 'I' || $dest === 'D') {
            if (php_sapi_name() !== 'cli') {
                header('Content-Type: application/pdf');
                header('Content-Disposition: ' . ($dest === 'D' ? 'attachment' : 'inline') . '; filename="' . $name . '"');
                header('Cache-Control: private, max-age=0, must-revalidate');
                header('Pragma: public');
            }
            echo $this->buffer;
            return '';
        }
        return $this->buffer;
    }

    public function Close(): void {
        if ($this->state === 3) return;
        if ($this->page === 0) $this->AddPage();
        $this->InFooter = true;
        $this->Footer();
        $this->InFooter = false;
        $this->_endpage();
        $this->_enddoc();
    }

    protected function _beginpage(string $orientation, $size, float $rotation): void {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->state = 2;
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
    }

    protected function _endpage(): void {
        $this->state = 1;
    }

    protected function _getpagesize($size): array {
        if (is_string($size)) {
            $size = strtolower($size);
            if (!isset($this->StdPageSizes[$size])) $this->Error('Tamaño de página desconocido: ' . $size);
            $a = $this->StdPageSizes[$size];
            return [$a[0] / $this->k, $a[1] / $this->k];
        }
        return [$size[0], $size[1]];
    }

    protected function _out(string $s): void {
        if ($this->state === 2) $this->pages[$this->page] .= $s . "\n";
        else $this->buffer .= $s . "\n";
    }

    protected function _putheader(): void {
        $this->_out('%PDF-1.4');
    }

    protected function _puttrailer(): void {
        $this->_out('/Size ' . ($this->n + 1));
        $this->_out('/Root 1 0 R');
        $this->_out('/Info 2 0 R');
    }

    protected function _enddoc(): void {
        $this->_putheader();
        $this->_putpages();
        $this->_putresources();
        // Info
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/Producer (OrientaUneti Pure PHP Engine)');
        $this->_out('/CreationDate (D:' . @date('YmdHis') . ')');
        $this->_out('>>');
        $this->_out('endobj');
        // Catalog
        $this->_newobj(1);
        $this->_out('<<');
        $this->_out('/Type /Catalog');
        $this->_out('/Pages 3 0 R');
        $this->_out('>>');
        $this->_out('endobj');
        // Cross-ref
        $offset = strlen($this->buffer);
        $this->_out('xref');
        $this->_out('0 ' . ($this->n + 1));
        $this->_out('0000000000 65535 f ');
        for ($i = 1; $i <= $this->n; $i++) {
            $this->_out(sprintf('%010d 00000 n ', $this->offsets[$i]));
        }
        $this->_out('trailer');
        $this->_out('<<');
        $this->_puttrailer();
        $this->_out('>>');
        $this->_out('startxref');
        $this->_out((string)$offset);
        $this->_out('%%EOF');
        $this->state = 3;
    }

    protected function _newobj(?int $n = null): void {
        if ($n === null) $n = ++$this->n;
        $this->offsets[$n] = strlen($this->buffer);
        $this->_out($n . ' 0 obj');
    }

    protected function _putpages(): void {
        $nb = $this->page;
        for ($n = 1; $n <= $nb; $n++) {
            $this->_newobj();
            $this->_out('<<');
            $this->_out('/Type /Page');
            $this->_out('/Parent 3 0 R');
            $this->_out('/Resources 4 0 R');
            $this->_out('/Contents ' . ($this->n + 1) . ' 0 R');
            $this->_out('>>');
            $this->_out('endobj');
            $p = $this->pages[$n];
            $this->_newobj();
            $this->_out('<<');
            $this->_out('/Length ' . strlen($p));
            $this->_out('>>');
            $this->_out('stream');
            $this->_out($p);
            $this->_out('endstream');
            $this->_out('endobj');
        }
        // Pages root
        $this->_newobj(3);
        $this->_out('<<');
        $this->_out('/Type /Pages');
        $kids = '/Kids [';
        for ($n = 1; $n <= $nb; $n++) $kids .= (4 + 2 * ($n - 1)) . ' 0 R ';
        $this->_out($kids . ']');
        $this->_out('/Count ' . $nb);
        $this->_out(sprintf('/MediaBox [0 0 %.2F %.2F]', $this->wPt, $this->hPt));
        $this->_out('>>');
        $this->_out('endobj');
    }

    protected function _putresources(): void {
        $this->_putfonts();
        $this->_newobj(4);
        $this->_out('<<');
        $this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
        $this->_out('/Font <<');
        foreach ($this->fonts as $font) {
            $this->_out('/F' . $font['i'] . ' ' . $font['n'] . ' 0 R');
        }
        $this->_out('>>');
        $this->_out('>>');
        $this->_out('endobj');
    }

    protected function _putfonts(): void {
        foreach ($this->fonts as &$font) {
            $this->_newobj();
            $font['n'] = $this->n;
            $this->_out('<<');
            $this->_out('/Type /Font');
            $this->_out('/Subtype /Type1');
            $this->_out('/BaseFont /' . $font['name']);
            $this->_out('/Encoding /WinAnsiEncoding');
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    protected function Error(string $msg): void {
        throw new RuntimeException('FPDF error: ' . $msg);
    }
}
