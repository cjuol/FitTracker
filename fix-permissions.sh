#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TARGET_DIR="${PROJECT_ROOT}/development"

if [[ ! -d "${TARGET_DIR}" ]]; then
  echo "❌ No existe ${TARGET_DIR}" >&2
  exit 1
fi

USER_NAME="${SUDO_USER:-${USER}}"
GROUP_NAME="$(id -gn "${USER_NAME}")"

echo "🔧 Arreglando permisos en: ${TARGET_DIR}"
echo "👤 Usuario objetivo: ${USER_NAME}:${GROUP_NAME}"

sudo chown -R "${USER_NAME}:${GROUP_NAME}" "${TARGET_DIR}"

# Permisos seguros para desarrollo
find "${TARGET_DIR}" -type d -exec chmod 2775 {} \;
find "${TARGET_DIR}" -type f -exec chmod 664 {} \;

# Mantener binarios/script ejecutables típicos
if [[ -d "${TARGET_DIR}/bin" ]]; then
  find "${TARGET_DIR}/bin" -type f -exec chmod 775 {} \;
fi

chmod +x "${PROJECT_ROOT}/fix-permissions.sh"

echo "✅ Permisos corregidos"
ls -ld "${TARGET_DIR}"
