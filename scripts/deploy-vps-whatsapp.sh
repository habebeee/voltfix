#!/usr/bin/env bash
# Deploy WhatsApp (Fonnte) + notifikasi admin ke VPS Voltfix
# Usage: ./scripts/deploy-vps-whatsapp.sh
# Env opsional: VPS_HOST, VPS_USER, VPS_DIR, FONNTE_TOKEN

set -euo pipefail

VPS_HOST="${VPS_HOST:-2.27.165.51}"
VPS_USER="${VPS_USER:-root}"
VPS_DIR="${VPS_DIR:-~/apps/voltfix}"
FONNTE_TOKEN="${FONNTE_TOKEN:?Set FONNTE_TOKEN (export FONNTE_TOKEN=...)}"
FONNTE_URL="${FONNTE_URL:-https://api.fonnte.com/send}"

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SSH_TARGET="${VPS_USER}@${VPS_HOST}"
REMOTE="${SSH_TARGET}:${VPS_DIR}"

echo "==> Deploy ke ${SSH_TARGET}:${VPS_DIR}"

# Deteksi path Laravel (src/ atau root)
REMOTE_SRC=$(ssh "${SSH_TARGET}" "if [ -f ${VPS_DIR}/src/artisan ]; then echo ${VPS_DIR}/src; elif [ -f ${VPS_DIR}/artisan ]; then echo ${VPS_DIR}; else echo MISSING; fi")

if [ "${REMOTE_SRC}" = "MISSING" ]; then
  echo "ERROR: artisan tidak ditemukan di ${VPS_DIR} atau ${VPS_DIR}/src"
  exit 1
fi

echo "==> Laravel path: ${REMOTE_SRC}"

# Sync file yang berubah
echo "==> Upload kode WhatsApp..."
rsync -avz \
  "${ROOT}/src/app/Services/WhatsAppService.php" \
  "${SSH_TARGET}:${REMOTE_SRC}/app/Services/WhatsAppService.php"

rsync -avz \
  "${ROOT}/src/app/Filament/Admin/Resources/TicketResource.php" \
  "${SSH_TARGET}:${REMOTE_SRC}/app/Filament/Admin/Resources/TicketResource.php"

rsync -avz \
  "${ROOT}/src/app/Filament/Admin/Resources/TicketResource/Pages/ViewTicket.php" \
  "${SSH_TARGET}:${REMOTE_SRC}/app/Filament/Admin/Resources/TicketResource/Pages/ViewTicket.php"

# Update .env Fonnte (tambah jika belum ada, update jika sudah)
echo "==> Konfigurasi FONNTE di .env..."
ssh "${SSH_TARGET}" bash <<REMOTE_SCRIPT
set -e
ENV_FILE="${REMOTE_SRC}/.env"
touch "\$ENV_FILE"

if grep -q '^FONNTE_URL=' "\$ENV_FILE"; then
  sed -i 's|^FONNTE_URL=.*|FONNTE_URL=${FONNTE_URL}|' "\$ENV_FILE"
else
  printf '\n# WhatsApp (Fonnte)\nFONNTE_URL=${FONNTE_URL}\n' >> "\$ENV_FILE"
fi

if grep -q '^FONNTE_TOKEN=' "\$ENV_FILE"; then
  sed -i 's|^FONNTE_TOKEN=.*|FONNTE_TOKEN=${FONNTE_TOKEN}|' "\$ENV_FILE"
else
  echo 'FONNTE_TOKEN=${FONNTE_TOKEN}' >> "\$ENV_FILE"
fi

echo "FONNTE vars:"
grep '^FONNTE_' "\$ENV_FILE" | sed 's/TOKEN=.*/TOKEN=***/'
REMOTE_SCRIPT

# Clear cache & verify
echo "==> Clear config cache..."
ssh "${SSH_TARGET}" "cd ${REMOTE_SRC} && php artisan config:clear && php artisan config:cache"

echo "==> Verifikasi token..."
ssh "${SSH_TARGET}" "cd ${REMOTE_SRC} && php -r \"
require 'vendor/autoload.php';
\\\$app = require 'bootstrap/app.php';
\\\$app->make('Illuminate\\\\Contracts\\\\Console\\\\Kernel')->bootstrap();
echo config('services.fonnte.token') !== '' ? 'TOKEN_OK' : 'TOKEN_EMPTY';
\""

echo ""
echo "✅ Deploy selesai!"
echo "   Uji: buat tiket baru → admin setujui → cek WhatsApp pelanggan"
