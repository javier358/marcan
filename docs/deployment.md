# Deployment Notes

## Flujo de deploy automatico (produccion y staging)

Solo se versiona y despliega el **theme** (`marcan-theme`). La base de datos y
`wp-content/uploads` (contenido e imagenes del cliente) **nunca** se tocan: viven fuera del repo.
Regla de alcance: **solo estructura y CSS** (PHP de templates, CSS/JS, `acf-json`, `assets/images`).

Pipeline:

1. Editar en local (`C:\Users\USUARIO\Local Sites\marcan-web`, theme `marcan-theme`).
2. Si se tocaron partials CSS, recompilar: `node build-css.js build`.
3. Validar: `php -l` de los `.php` tocados, `node --check assets/js/theme.js`, `git diff --check`.
4. `git add` + `git commit` (mensajes pequenos y claros).
5. `git push origin main`.
6. GitHub Actions (`.github/workflows/deploy-all.yml`) entra por SSH a ambos cPanel y hace
   `git fetch origin main && git checkout -f -B main origin/main` dentro de la carpeta del theme en
   vivo → produccion `https://marcan.com.pe/` y staging `https://new.marcan.com.pe/` quedan actualizados. (El `checkout -f -B main`
   auto-corrige la rama del server si quedo en otra.)

### Infra del deploy

- **Produccion cPanel:** usuario `marcan`, SSH `207.244.240.169:55222` (dominio principal `marcan.com.pe`).
- **Repo produccion = carpeta del theme en vivo:** `/home/marcan/public_html/wp-content/themes/marcan-theme`
  (cPanel Git Version Control, branch checked-out = `main`).
- **Staging cPanel:** usuario `newmarcancom`, SSH `new.marcan.com.pe:55222`.
- **Repo staging = carpeta del theme en vivo:** `/home/newmarcancom/public_html/wp-content/themes/marcan-theme`
  (cPanel Git Version Control, branch checked-out = `main`).
- **Secret de GitHub:** `SSH_PRIVATE_KEY` (clave privada dedicada; la publica va a
  `~/.ssh/authorized_keys` de los usuarios `marcan` y `newmarcancom`).
- El workflow tambien se puede disparar a mano desde GitHub → Actions → "Run workflow"
  (`workflow_dispatch`).

### Rollback

- Opcion A: `git revert <commit>` en local + push a `main` (re-deploya el estado corregido).
- Opcion B: desde cPanel → Git Version Control → Pull or Deploy, checkout de un commit anterior.
- El `reset --hard` del deploy solo afecta archivos versionados del theme; uploads y DB quedan intactos.

### Reglas operativas

- **No editar archivos del theme directamente en el server** (file manager/SSH). El deploy hace
  `reset --hard`, asi que cualquier cambio manual en el server se pierde en el siguiente push.
- Nunca commitear: `wp-content/uploads`, dumps de DB, credenciales, `wp-config.php`, capturas QA
  (`docs/*.png`) ni scripts puntuales (`tools/`). Ya estan en `.gitignore`.

## Requirements

- WordPress 6.4 or newer.
- PHP 8.1 or newer recommended.
- Theme: `marcan-theme`.
- Required plugin: SCF installed and active.

## Theme Install

1. Deploy `wp-content/themes/marcan-theme`.
2. Activate `marcan-theme` in WordPress.
3. Visit Permalinks once to flush rewrite rules after CPT registration.

## SCF JSON

- JSON path: `wp-content/themes/marcan-theme/acf-json`.
- SCF should load field groups from this folder through the ACF-compatible JSON filters.
- After editing fields in admin, sync or save JSON back into the same folder and commit the changed JSON.

## CPT

CPT are registered by code in `inc/cpt.php`:
- `property`
- `project`
- `hero_slide`

Taxonomies:
- `property_type`
- `district`

Do not recreate these with a visual CPT plugin.

## Database and URLs

- Migrate database with a standard WordPress migration tool or WP-CLI export/import.
- Run search-replace for local/staging/production URLs after import.
- Keep uploads outside Git and sync them separately.

## Assets

- Figma-exported theme assets live in `assets/images`.
- Do not replace Figma-exported images with stock or placeholder assets.
- Heavy uploads remain in `wp-content/uploads` and are not versioned.

## ACF Groups

- `Hero de inicio`
- `Slide del hero`
- `Home - Proyectos`
- `Tarjeta home de proyecto`

## Git

Repository scope is the theme folder only:
`wp-content/themes/marcan-theme`

Do not commit:
- `wp-content/uploads`
- database exports
- credentials
- `wp-config.php`
- LocalWP logs or temporary files
