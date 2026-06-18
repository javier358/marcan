# Instrucciones Criticas Del Repo Marcan Theme

## Git Es La Fuente De Verdad

- Este directorio es el repo de codigo del theme Marcan: `javier358/marcan`.
- Antes de modificar cualquier archivo, ejecutar `git fetch --all --prune` y `git pull --ff-only` en `main`.
- Si aparecen cambios locales sin commitear que contradicen GitHub, GitHub gana salvo instruccion explicita del usuario.
- No trabajar desde backups, zips, archivos viejos ni cambios no trackeados si el usuario no los pidio expresamente.
- Flujo de entrega: el agente implementa los cambios pedidos en local, el usuario los valida en `http://marcan-web.local/`, y solo despues de confirmacion explicita se commitea y se sube a `main` para que se deploye.

## WordPress Solo Para Informacion

- WordPress local, staging o produccion solo se usa para editar informacion administrable: paginas, posts, campos SCF/ACF, opciones, medios, menus y datos de contenido.
- No editar codigo desde WordPress: no usar editor de theme/plugin, snippets, CSS adicional, templates, PHP, JS o CSS en produccion/staging.
- Todo cambio de codigo se hace en este repo local, se valida en `http://marcan-web.local/`, se commitea y se sube a `main` para deploy.

## Ruta Del Proyecto

- Entorno LocalWP: `C:\Users\USUARIO\Local Sites\marcan-web`.
- Repo real del theme: `C:\Users\USUARIO\Local Sites\marcan-web\app\public\wp-content\themes\marcan-theme`.
