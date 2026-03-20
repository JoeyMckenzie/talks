#!/usr/bin/env bash
set -uo pipefail

cd "$(dirname "$0")"

pass=0
fail=0
warn=0

check() {
    local label="$1"
    shift
    if "$@" > /dev/null 2>&1; then
        echo "  ✓ $label"
        ((pass++))
    else
        echo "  ✗ $label"
        ((fail++))
    fi
}

installed() {
    local tool="$1"
    if which "$tool" > /dev/null 2>&1; then
        echo "  ✓ $tool ($(which "$tool"))"
        ((pass++))
    else
        echo "  ✗ $tool — NOT INSTALLED"
        ((fail++))
    fi
}

echo "=== Executable slide demos ==="
echo ""

check "cat docs/demos/legacy-config.php"       cat docs/demos/legacy-config.php
check "bat docs/demos/legacy-config.php"        bat docs/demos/legacy-config.php
check "ls -la docs/demos"                       ls -la docs/demos
check "eza -la --icons --git docs/demos"        eza -la --icons --git docs/demos
check "eza --tree --level=2 --icons --git docs" eza --tree --level=2 --icons --git docs/
check "fd -H .env docs/demos"                   fd -H .env docs/demos
check "rg --hidden STRIPE_KEY docs/demos"       rg --hidden STRIPE_KEY docs/demos
check "rg TODO docs/demos"                      rg "TODO" docs/demos
check "cat docs/demos/talk-layout.kdl"          cat docs/demos/talk-layout.kdl
check "cat docs/demos/api-flow.hurl"            cat docs/demos/api-flow.hurl
check "glow docs/demos/sample-readme.md"        glow docs/demos/sample-readme.md
check "task list"                               task list

# delta returns exit 1 from --no-index, so test separately
echo -n "  "
if git diff --no-index docs/demos/old-api.php docs/demos/new-api.php 2>&1 | delta --side-by-side > /dev/null 2>&1; then
    echo "✓ delta side-by-side"
    ((pass++))
else
    # exit code 1 is expected when files differ — check delta actually ran
    if git diff --no-index docs/demos/old-api.php docs/demos/new-api.php 2>&1 | delta --side-by-side 2>&1 | head -1 | grep -q "Δ"; then
        echo "✓ delta side-by-side (exit 1 expected, output OK)"
        ((pass++))
    else
        echo "✗ delta side-by-side"
        ((fail++))
    fi
fi

# hurl hits the network
echo -n "  "
if timeout 15 hurl --very-verbose docs/demos/api-flow.hurl > /dev/null 2>&1; then
    echo "✓ hurl --very-verbose docs/demos/api-flow.hurl"
    ((pass++))
else
    echo "✗ hurl --very-verbose docs/demos/api-flow.hurl (network issue?)"
    ((fail++))
fi

echo ""
echo "=== Tool installs (all tools on reference slide) ==="
echo ""
for tool in bat eza yazi fd rg fzf delta lazygit atuin thefuck zellij btop harlequin hurl glow task presenterm; do
    installed "$tool"
done

echo ""
echo "=== Demo support files ==="
echo ""
for f in docs/demos/legacy-config.php docs/demos/.env.development docs/demos/.env.staging docs/demos/.env.production docs/demos/old-api.php docs/demos/new-api.php docs/demos/api-flow.hurl docs/demos/sample-readme.md docs/demos/talk-layout.kdl docs/demos/todos.py; do
    if [ -f "$f" ]; then
        echo "  ✓ $f"
        ((pass++))
    else
        echo "  ✗ $f — MISSING"
        ((fail++))
    fi
done

echo ""
echo "=== Summary ==="
echo "  Pass: $pass"
echo "  Fail: $fail"
[ "$fail" -eq 0 ] && echo "  All good! 🎉" || echo "  ⚠ Fix failures before the talk!"
