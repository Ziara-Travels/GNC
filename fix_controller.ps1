$lines = Get-Content "c:\xampp\htdocs\GNC\app\Http\Controllers\OrderFlightController.php"
$newLines = @()
$skipMode = $false

for ($i = 0; $i -lt $lines.Length; $i++) {
    if ($lines[$i] -match "^\s*'branch_code'") {
        $skipMode = $true
        continue
    }
    if ($skipMode -and $lines[$i] -match "^\s*private function detectCardType") {
        $skipMode = $false
        $newLines += ""
    }
    if (-not $skipMode) {
        $newLines += $lines[$i]
    }
}

$newLines | Set-Content "c:\xampp\htdocs\GNC\app\Http\Controllers\OrderFlightController.php"
Write-Host "File cleaned successfully"
