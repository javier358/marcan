# Cómo conectar WordPress, Git y Codex

## Opción recomendada: versionar solo el theme

No subas todo WordPress a Git. Lo correcto es versionar solo:

- `wp-content/themes/marcan-theme`
- plugins propios, si existen
- documentación del proyecto

La base de datos, uploads y credenciales no deben ir al repo.

## Paso 1: Instalar WordPress

1. Crea una instalación WordPress local o en tu hosting.
2. Verifica que puedas entrar al panel: `tudominio.com/wp-admin`.
3. Instala WordPress 6.4 o superior.
4. Usa PHP 8.0 o superior.

## Paso 2: Copiar el theme

1. Copia la carpeta `marcan-theme`.
2. Pégala en:

```text
wp-content/themes/marcan-theme
```

3. Entra al panel de WordPress.
4. Ve a `Apariencia > Temas`.
5. Activa `Marcan`.

## Paso 3: Refrescar enlaces permanentes

1. Ve a `Ajustes > Enlaces permanentes`.
2. No necesitas cambiar nada.
3. Presiona `Guardar cambios`.

Esto activa correctamente la URL `/propiedades/`.

## Paso 4: Crear el repo Git

Desde la carpeta del theme:

```bash
cd wp-content/themes/marcan-theme
git init
git add .
git commit -m "Initial Marcan WordPress theme"
```

## Paso 5: Crear un repo remoto

En GitHub, GitLab o Bitbucket:

1. Crea un nuevo repositorio vacío.
2. Copia la URL del repo, por ejemplo:

```text
https://github.com/tu-usuario/marcan-theme.git
```

3. Conecta tu theme local:

```bash
git branch -M main
git remote add origin https://github.com/tu-usuario/marcan-theme.git
git push -u origin main
```

## Paso 6: Pasarme el proyecto

Pásame una de estas opciones:

- URL pública del repo.
- URL privada más acceso autorizado.
- Archivo `.zip` del theme.
- Carpeta local abierta en Codex.

Si el repo es privado, asegúrate de que Codex tenga acceso o instala el conector de GitHub cuando te lo pida.

## Paso 7: Flujo de trabajo conmigo

Cuando me pases el repo, puedo trabajar así:

```bash
git checkout -b figma-home
```

Luego implemento cambios, verifico, y dejo commits listos:

```bash
git add .
git commit -m "Build Marcan home from Figma"
git push origin figma-home
```

Después puedes revisar el Pull Request antes de publicar.

## Qué información adicional necesito del Figma

El link recibido apunta al frame `Intro B01`. Para completar toda la web igual al Figma necesito los links o node IDs de:

- Home completa.
- Header/menu final.
- Listado de propiedades.
- Ficha de propiedad.
- Contacto.
- Footer.
- Versiones mobile, si existen.
- Animaciones esperadas: entrada, scroll, hover, sliders, transiciones.

Con eso convierto cada frame en una plantilla o sección PHP administrable.
