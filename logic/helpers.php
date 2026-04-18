<?php
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('../index.php');
    }
}

function phonePrefix(string $stored): array
{
    $prefixes = ['+380','+48','+49','+44','+1','+33','+39','+34','+40','+36','+420','+421','+372','+371','+370'];
    foreach ($prefixes as $pfx) {
        if (str_starts_with($stored, $pfx)) {
            return [$pfx, substr($stored, strlen($pfx))];
        }
    }
    return ['+380', $stored];
}

function phonePrefixOptions(string $selected = '+380'): string
{
    $options = [
        '+380' => '🇺🇦 +380', '+48' => '🇵🇱 +48',  '+49' => '🇩🇪 +49',
        '+44'  => '🇬🇧 +44',  '+1'  => '🇺🇸 +1',   '+33' => '🇫🇷 +33',
        '+39'  => '🇮🇹 +39',  '+34' => '🇪🇸 +34',  '+40' => '🇷🇴 +40',
        '+36'  => '🇭🇺 +36',  '+420'=> '🇨🇿 +420', '+421'=> '🇸🇰 +421',
        '+372' => '🇪🇪 +372', '+371'=> '🇱🇻 +371', '+370'=> '🇱🇹 +370',
    ];

    $html = '';
    foreach ($options as $val => $label) {
        $sel   = $val === $selected ? ' selected' : '';
        $html .= '<option value="' . $val . '"' . $sel . '>' . $label . '</option>';
    }
    return $html;
}

function e(mixed $v): string
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}
