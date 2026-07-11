#!/bin/bash
set -euo pipefail

PROJECT_NAME="${1:-}"
if [ -z "$PROJECT_NAME" ]; then
  echo "❌ Please provide a project name: ./start_mac.sh myproject"
  exit 1
fi

DOMAIN="${PROJECT_NAME}.test"
ROOT_DIR="$HOME/perkuliahan/$PROJECT_NAME"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TEMPLATE_DIR="$SCRIPT_DIR/template"
ZSHRC_FILE="$HOME/.zshrc"
DB_DIR="$ROOT_DIR/db/conf.d"
NGINX_DIR="$ROOT_DIR/nginx"
NGINX_SSL="$NGINX_DIR/ssl"
PHP_DIR="$ROOT_DIR/php"
SRC_DIR="$ROOT_DIR/src"

mkdir -p "$DB_DIR" "$NGINX_SSL" "$PHP_DIR" "$SRC_DIR"

# --- Function to check and install CLI tool if missing ---
check_and_install() {
  local cmd="$1"
  local install_cmd="$2"
  local label="$3"

  if ! command -v "$cmd" &>/dev/null; then
    echo "🔧 Installing $label..."
    eval "$install_cmd"
  else
    echo "✅ $label already installed."
  fi
}

# --- Prerequisite Tools ---
echo "🔍 Checking prerequisites..."
check_and_install brew "/bin/bash -c \"$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)\"" "Homebrew"
check_and_install docker "brew install --cask docker" "Docker Desktop"
check_and_install docker-compose "brew install docker-compose" "Docker Compose"
check_and_install mkcert "brew install mkcert" "mkcert"
check_and_install zsh "brew install zsh" "zsh"

if [ "$SHELL" != "/bin/zsh" ]; then
  echo "🔄 Changing default shell to zsh..."
  chsh -s /bin/zsh
fi

# --- Oh My Zsh ---
if [ ! -d "$HOME/.oh-my-zsh" ]; then
  echo "💅 Installing Oh My Zsh..."
  sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended
else
  echo "✅ Oh My Zsh already installed."
fi

# --- Meslo Font for Powerlevel10k ---
FONT_DIR="$HOME/Library/Fonts"
MESLO_URL="https://github.com/romkatv/powerlevel10k-media/raw/master"
MESLO_FONT_INSTALLED=$(ls "$FONT_DIR" | grep -i "MesloLGS NF Regular.ttf" || true)

if [ -z "$MESLO_FONT_INSTALLED" ]; then
  echo "🔤 Installing MesloLGS NF fonts..."
  for font in "MesloLGS NF Regular.ttf" "MesloLGS NF Bold.ttf" "MesloLGS NF Italic.ttf" "MesloLGS NF Bold Italic.ttf"; do
    curl -fsSL "$MESLO_URL/$(echo $font | sed 's/ /%20/g')" -o "$FONT_DIR/$font"
  done
fi

if ! fc-list | grep -qi "MesloLGS NF"; then
  brew install --cask font-meslo-lg-nerd-font
fi

# --- Powerlevel10k and Plugins ---
P10K_DIR="${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k"
[ ! -d "$P10K_DIR" ] && git clone --depth=1 https://github.com/romkatv/powerlevel10k.git "$P10K_DIR"
sed -i '' 's/^ZSH_THEME=.*/ZSH_THEME="powerlevel10k\/powerlevel10k"/' "$ZSHRC_FILE"

# --- Plugins ---
for plugin in zsh-autosuggestions zsh-syntax-highlighting; do
  PLUGIN_DIR="${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/plugins/$plugin"
  [ ! -d "$PLUGIN_DIR" ] && git clone https://github.com/zsh-users/$plugin "$PLUGIN_DIR"
done

if ! grep -q "zsh-autosuggestions" "$ZSHRC_FILE"; then
  sed -i '' '/^plugins=(/ s/)/ zsh-autosuggestions zsh-syntax-highlighting)/' "$ZSHRC_FILE" || echo "📝 Please manually add plugins to $ZSHRC_FILE"
fi

# --- Aliases and Functions ---
sed -i '' '/# === START ===/,/# === END ===/d' "$ZSHRC_FILE"
# === Add Aliases/Functions to .zshrc ===
echo "🔗 Updating functions and aliases in $ZSHRC_FILE..."

cat <<'EOF' >> "$ZSHRC_FILE"
# === START ===
unalias dcr 2>/dev/null
dcr() {
  local NAME="$1"
  if [ -z "$NAME" ]; then
    echo "❌ Usage: dcr <ModelName>"
    return 1
  fi

  local CONTAINER=$(docker ps --filter "name=_php" --format "{{.Names}}" | head -n 1)
  if [ -z "$CONTAINER" ]; then
    echo "❌ No PHP container found."
    return 1
  fi

  local NAME_SNAKE=$(echo "$NAME" | sed -E 's/([a-z])([A-Z])/\1_\2/g' | tr '[:upper:]' '[:lower:]')
  local NAME_PLURAL="${NAME_SNAKE}s"

  echo "🗑 Removing $NAME files from container: $CONTAINER"
  docker exec "$CONTAINER" bash -c "rm -f app/Models/$NAME.php"
  docker exec "$CONTAINER" bash -c "rm -f app/Http/Controllers/${NAME}Controller.php"
  docker exec "$CONTAINER" bash -c "rm -f database/seeders/${NAME}Seeder.php"
  docker exec "$CONTAINER" bash -c "find database/migrations -type f -name 'create_${NAME_PLURAL}_table.php' -delete"
  docker exec "$CONTAINER" bash -c "rm -rf app/Filament/Admin/Resources/${NAME}*"
  echo "✅ Done Remove: $NAME"
}
unalias dcm 2>/dev/null
dcm() {
  if [ -z "$1" ]; then
    echo "❌ Usage: dcm <ModelName>"
    return 1
  fi
  local CONTAINER=$(docker ps --filter "name=_php" --format "{{.Names}}" | head -n 1)
  if [ -z "$CONTAINER" ]; then
    echo "❌ PHP container not found."
    return 1
  fi
  local NAME="$1"
  docker exec -it "$CONTAINER" art make:model "$NAME" -msc
  docker exec -it "$CONTAINER" art make:filament-resource "$NAME" --generate
  echo "✅ $NAME scaffolded with Filament."
}
unalias dcv 2>/dev/null
dcv() {
  if [ -z "$1" ]; then
    echo "❌ Usage: dcm <ModelName>"
    return 1
  fi
  local CONTAINER=$(docker ps --filter "name=_php" --format "{{.Names}}" | head -n 1)
  if [ -z "$CONTAINER" ]; then
    echo "❌ PHP container not found."
    return 1
  fi
  local NAME="$1"
  docker exec -it "$CONTAINER" art make:filament-resource "$NAME" --generate
  echo "✅ $NAME resource with Filament."
}
unalias dcp 2>/dev/null
dcp() {
  if [ $# -eq 0 ]; then
    echo "❌ Usage: dcp your commit message"
    return 1
  fi
  if ! git diff --quiet || ! git diff --cached --quiet; then
    echo "⚠ Warning: You have uncommitted changes."
  fi
  git add .
  git commit -m "$*"
  git push -u origin main
  echo "✅ Changes pushed to origin/main."
}
unalias dcd 2>/dev/null
dcd() {
  PROJECT=$(docker ps --format "{{.Names}}" | grep php | cut -d'_' -f1)
  if [ -n "$PROJECT" ]; then
    echo "🔻 Stopping containers for $PROJECT..."
    docker compose -p "$PROJECT" down
  else
    echo "❌ Could not detect project name."
  fi
}
unalias dcu 2>/dev/null
alias dcu='docker compose up -d'
unalias dci 2>/dev/null
alias dci='docker exec -it $(docker ps --filter "name=_php" --format "{{.Names}}" | head -n 1) art project:init'
unalias dca 2>/dev/null
alias dca='docker exec -it $(docker ps --filter "name=_php" --format "{{.Names}}" | head -n 1) art'
# === END ===
EOF


# --- Copy Template Files ---
cp "$TEMPLATE_DIR/db/my.cnf" "$DB_DIR/"
cp "$TEMPLATE_DIR/nginx/Dockerfile" "$NGINX_DIR/"
cp "$TEMPLATE_DIR/php/"* "$PHP_DIR/"
cp -a "$TEMPLATE_DIR/src/." "$SRC_DIR/" || true

# --- Generate SSL Cert ---
CERT_SOURCE_CRT="./${PROJECT_NAME}.pem"
CERT_SOURCE_KEY="./${PROJECT_NAME}-key.pem"
CERT_DEST_CRT="$NGINX_SSL/${DOMAIN}.crt"
CERT_DEST_KEY="$NGINX_SSL/${DOMAIN}.key"

if [[ ! -f "$CERT_SOURCE_CRT" || ! -f "$CERT_SOURCE_KEY" ]]; then
  mkcert -cert-file "$CERT_SOURCE_CRT" -key-file "$CERT_SOURCE_KEY" "$DOMAIN"
fi
cp "$CERT_SOURCE_CRT" "$CERT_DEST_CRT"
cp "$CERT_SOURCE_KEY" "$CERT_DEST_KEY"
rm -f "$CERT_SOURCE_CRT" "$CERT_SOURCE_KEY"

# --- Nginx & Entrypoint ---
sed -e "s|{{PROJECT_NAME}}|$PROJECT_NAME|g" -e "s|{{DOMAIN}}|$DOMAIN|g" "$TEMPLATE_DIR/php/docker-entrypoint.sh.template" > "$PHP_DIR/docker-entrypoint.sh"
chmod +x "$PHP_DIR/docker-entrypoint.sh"
sed -e "s|{{PROJECT_NAME}}|$PROJECT_NAME|g" -e "s|{{DOMAIN}}|$DOMAIN|g" "$TEMPLATE_DIR/nginx/default.conf.template" > "$NGINX_DIR/default.conf"

# --- .env and .gitignore ---
cat <<EOF > "$ROOT_DIR/.env"
COMPOSE_PROJECT_NAME=${PROJECT_NAME}
REPOSITORY_NAME=${PROJECT_NAME}
IMAGE_TAG=latest
COMPOSE_BAKE=true
APP_NAME="${PROJECT_NAME}"
APP_URL="https://${DOMAIN}"
ASSET_URL="https://${DOMAIN}"
EOF

cat <<EOF > "$ROOT_DIR/.gitignore"
db/data/*
/db/data/
../db/data/*
EOF

# --- Docker Compose ---
cat <<EOF > "$ROOT_DIR/docker-compose.yml"
services:
  php:
    build:
      context: ./php
    container_name: ${PROJECT_NAME}_php
    volumes:
      - ./src:/var/www/html
    environment:
      - PROJECT_NAME=${PROJECT_NAME}
    depends_on:
      - db
    healthcheck:
      test: ["CMD", "composer", "--version"]
      interval: 30s
      timeout: 10s
      retries: 3

  nginx:
    build:
      context: ./nginx
    container_name: ${PROJECT_NAME}_nginx
    ports:
      - "443:443"
      - "80:80"
    volumes:
      - ./src:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./nginx/ssl:/etc/nginx/ssl:ro
    depends_on:
      - php
    healthcheck:
      test: ["CMD-SHELL", "curl --silent --show-error --fail --insecure https://${DOMAIN}/up || exit 1"]
      interval: 30s
      timeout: 10s
      retries: 3

  db:
    image: mariadb:10.11
    container_name: ${PROJECT_NAME}_db
    ports:
      - "13306:3306"
    environment:
      MYSQL_DATABASE: $PROJECT_NAME
      MYSQL_ROOT_PASSWORD: p455w0rd
    volumes:
      - ./db/conf.d:/etc/mysql/conf.d
      - ./db/data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5
EOF

# --- Hosts Entry ---
grep -q "$DOMAIN" /etc/hosts || echo "127.0.0.1 $DOMAIN" | sudo tee -a /etc/hosts >/dev/null



echo "✅ Project ready at https://${DOMAIN}"
read -p "🚀 Start Docker Compose now? (y/n): " run_now
if [[ "$run_now" =~ ^[Yy]$ ]]; then
  cd "$ROOT_DIR" && docker-compose up -d --build

  # --- Wait for Health ---
  echo "⏳ Waiting for containers to be healthy..."
  wait_for_health() {
    # Use the project name we already know
    local project_name="${PROJECT_NAME}"

    if [ -z "$project_name" ]; then
      echo "❌ Could not determine project name for health check. Ensure containers are starting."
      exit 1
    fi

    while true; do
      local all_healthy=true
      # Get all container names associated with the detected project name
      local containers=$(docker ps --filter "name=${project_name}_" --format "{{.Names}}")

      if [ -z "$containers" ]; then
        echo "❌ No containers found for project '$project_name'. Exiting..."
        exit 1
      fi

      for container in $containers; do
        local health_status=$(docker inspect --format='{{.State.Health.Status}}' "$container" 2>/dev/null || echo "not_set")
        local running_status=$(docker inspect --format='{{.State.Running}}' "$container" 2>/dev/null || echo "false")

        if [ "$running_status" != "true" ]; then
          echo "❌ Container $container is not running. Exiting..."
          exit 1
        fi

        if [ "$health_status" != "healthy" ]; then
          all_healthy=false
          break # Found an unhealthy container, no need to check others in this iteration
        fi
      done

      if [ "$all_healthy" = "true" ]; then
        echo "✅ All containers are healthy!"
        echo "💻 Opening in VS Code..."
        code "$ROOT_DIR"
        return # All healthy, exit the function
      else
        echo "⌛ Waiting for containers to be healthy..."
        sleep 5
      fi
    done
  }

  wait_for_health
fi

# === GitHub Repo Creation ===
echo "🌐 Creating GitHub repository..."
if [ ! -f "$SCRIPT_DIR/.github-user" ]; then
  read -p "👤 Enter your GitHub username: " GITHUB_USER
  echo "$GITHUB_USER" > "$SCRIPT_DIR/.github-user"
else
  GITHUB_USER=$(<"$SCRIPT_DIR/.github-user")
fi

if [ ! -f "$SCRIPT_DIR/.github-token" ]; then
  read -s -p "🔑 Enter your GitHub token: " GITHUB_TOKEN
  echo
  echo "$GITHUB_TOKEN" > "$SCRIPT_DIR/.github-token"
  chmod 600 "$SCRIPT_DIR/.github-token"
else
  GITHUB_TOKEN=$(<"$SCRIPT_DIR/.github-token")
fi

REPO_NAME="${PROJECT_NAME}-$(date +%Y)"
API_URL="https://api.github.com/user/repos"
REPO_PAYLOAD=$(cat <<EOF
{
  "name": "$REPO_NAME",
  "private": false
}
EOF
)

RESPONSE=$(curl -s -w "\n%{http_code}" -u "$GITHUB_USER:$GITHUB_TOKEN" \
  -X POST "$API_URL" \
  -H "Accept: application/vnd.github+json" \
  -d "$REPO_PAYLOAD")

# --- Fix for "head: illegal line count -- -1" on macOS (BSD head) ---
# Use sed to remove the last line (which is the HTTP status code added by curl -w)
BODY=$(echo "$RESPONSE" | sed '$d')
STATUS=$(echo "$RESPONSE" | tail -n 1) # Get only the last line (HTTP status code)
# --- End Fix ---


if [ "$STATUS" = "201" ]; then
  echo "✅ GitHub repository '$REPO_NAME' created."
  GITHUB_SSH="git@github.com:$GITHUB_USER/$REPO_NAME.git"
elif [ "$STATUS" = "422" ]; then
  echo "⚠ Repo exists or invalid. Proceeding..."
  GITHUB_SSH="git@github.com:$GITHUB_USER/$REPO_NAME.git"
else
  echo "❌ GitHub API failed: $BODY"
  GITHUB_SSH=""
fi

if [ -n "$GITHUB_SSH" ]; then
  echo "🔧 Initializing Git..."
  cd "$ROOT_DIR"
  git init
  git remote remove origin 2>/dev/null || true
  git remote add origin "$GITHUB_SSH"
  git add .
  git commit -m "🔥 fresh from the oven" 2>/dev/null || echo "ℹ️ No changes to commit."
  git branch -M main
  
  # Pull first to handle existing remote content
  if git fetch origin main 2>/dev/null; then
    git pull origin main --allow-unrelated-histories --no-edit || true
  fi
  
  git push -u origin main && echo "✅ Project pushed to GitHub." || echo "⚠ Failed to push."
fi

# === Launch VS Code ===
echo "🧠 Opening in VS Code..."
if command -v code &>/dev/null; then
  code "$ROOT_DIR"
else
  echo "⚠️ VS Code command not found. Please install it or run 'Shell Command: Install code command in PATH' from VS Code."
  echo "📁 Project created at: $ROOT_DIR"
fi
