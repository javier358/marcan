# MARCAN - Handoff para Continuar con IA

Fecha: 2026-05-12  
Proyecto: MARCAN WordPress local  
Alcance actual: HOME completo, pendiente validacion final pixel-perfect exhaustiva.

## Resumen Ejecutivo

El HOME ya fue implementado seccion por seccion desde Figma dentro del theme personalizado `marcan-theme`. Header, hero, secciones de departamentos/oficinas, proyectos entregados y footer estan construidos con CPT/SCF preparados para edicion desde WordPress.

La siguiente IA debe continuar con una validacion visual fina contra Figma, corregir cualquier diferencia de pixel-perfect y confirmar que todos los contenidos reales del HOME se pueden editar via SCF sin tocar codigo.

## Rutas Locales

- Raiz del sitio LocalWP:
  `C:\Users\USUARIO\Local Sites\marcan-web`
- WordPress public:
  `C:\Users\USUARIO\Local Sites\marcan-web\app\public`
- Theme activo / repo Git:
  `C:\Users\USUARIO\Local Sites\marcan-web\app\public\wp-content\themes\marcan-theme`
- Uploads / media library:
  `C:\Users\USUARIO\Local Sites\marcan-web\app\public\wp-content\uploads`

## URL Local

- Sitio:
  `http://marcan-web.local/`
- Tambien existe host:
  `http://www.marcan-web.local/`

Hosts detectados en Windows:

```txt
127.0.0.1 marcan-web.local
127.0.0.1 www.marcan-web.local
127.0.0.1 marcan-web2.local
127.0.0.1 www.marcan-web2.local
```

## Accesos Locales Conocidos

Base de datos LocalWP:

```txt
Host: 127.0.0.1
Puerto: 10011
Database: local
Usuario: root
Password: root
Charset recomendado: utf8mb4
```

Cliente MySQL usado:

```powershell
C:\Users\USUARIO\AppData\Roaming\Local\lightning-services\mysql-8.0.35+4\bin\win64\bin\mysql.exe
```

Comando:

```powershell
& 'C:\Users\USUARIO\AppData\Roaming\Local\lightning-services\mysql-8.0.35+4\bin\win64\bin\mysql.exe' --default-character-set=utf8mb4 -h 127.0.0.1 -P 10011 -u root -proot local
```

PHP LocalWP:

```powershell
C:\Users\USUARIO\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe
```

Comando de lint PHP:

```powershell
& 'C:\Users\USUARIO\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe' -l 'inc\acf.php'
```

## Tokens / Credenciales Externas

No hay tokens externos visibles en esta sesion y no se deben inventar ni escribir secretos falsos.

Figma se uso mediante MCP/plugin de Figma ya configurado en Codex. La siguiente IA debe usar la conexion Figma MCP disponible en el entorno. Si el plugin pide autenticacion, solicitar al usuario que reautorice Figma desde Codex.

WordPress admin no fue necesario para esta fase y no se conoce usuario/password admin desde esta sesion. Si la siguiente IA necesita entrar al dashboard, debe pedir el acceso al usuario o crear un admin local solo con aprobacion explicita.

## Git

Repositorio Git activo dentro del theme:

```txt
C:\Users\USUARIO\Local Sites\marcan-web\app\public\wp-content\themes\marcan-theme\.git
```

Rama actual:

```txt
feature/figma-wordpress-build
```

Ultimos commits conocidos:

```txt
5a3bed8 fix: offset footer arrow after logo
844bb32 fix: position footer arrow at logo end
b8bf4f6 fix: preserve footer arrow proportions
e9d4b1f fix: align footer brand with figma
d9d1543 feat: animate footer brand reveal
```

Estado al generar este handoff:

```txt
 M assets/css/theme.css
 M inc/acf.php
```

Cambios pendientes importantes:

- `assets/css/theme.css`: ajustes de alineacion del slider departamentos/oficinas para desktop y mobile.
- `inc/acf.php`: limpieza de labels SCF con acentos correctos y correccion de nombres internos para que sigan sin acentos.

Antes de seguir, ejecutar:

```powershell
git diff --check
git diff -- assets/css/theme.css inc/acf.php
& 'C:\Users\USUARIO\AppData\Roaming\Local\lightning-services\php-8.2.29+0\bin\win64\php.exe' -l 'inc\acf.php'
node --check assets\js\theme.js
```

Si todo esta correcto, hacer commit:

```powershell
git add assets/css/theme.css inc/acf.php docs/ai-continuation-handoff.md
git commit -m "fix: align home responsive and scf labels"
```

## Figma - Fuente de Verdad

Archivo:

```txt
https://www.figma.com/design/khZVAdpz6ZvY26Sqmo59D4/NEW-MARCAN-2--Copy-
```

Nodos clave:

```txt
Desktop HOME page: 2045:235
Mobile HOME page: 2045:242
Assets page: 0:1
Desktop HOME frame real: 8002:390, 1512 x 5574
Mobile HOME frame real: 8510:2529, 402 x 5171
Header desktop: 8002:455, 1512 x 67
Hero desktop/media: 8096:1801, 1512 x 982
Header mobile: 9010:3144, 402 x 54, dentro de wrapper 8523:2524
Hero mobile: 8523:2529, 402 x 766
Footer desktop: 8002:391, 1512 x 852
Slider propiedad referencia: 8139:2256
```

Medidas desktop extraidas:

```txt
HOME: 1512 x 5574
Hero: x0 y0 w1512 h982
Intro copy: x30 y1012 w591 h78
Intro title: x30 y1323 w591 h100
Intro button: x30 y1453 w239 h41
Departamentos title: x30 y1734
Departamentos slider: x0 y1804 w1482 h824
Oficinas title: x30 y2850
Oficinas slider: x0 y2920 w1482 h824
Proyectos entregados image: x0 y3972 w757 h750
Proyectos entregados copy: x757 y3972 w755 h750
Footer: x0 y4722 w1512 h852
```

Medidas mobile extraidas:

```txt
Figma mobile frame: 402 x 5171
Contenido web real sin home indicator iOS: 402 x 5117
Header wrapper: x0 y0 w402 h110
Header real: y56 h54
Hero: x0 y56 w402 h766
Departamentos title: x16 y1621
Departamentos slider: x0 y1721 w402 h593
Oficinas title: x16 y2439
Oficinas slider: x0 y2539 w402 h593
Proyectos entregados image: x0 y3258 w403 h500
Proyectos entregados copy: x0 y3758 w402 h500
Footer: x0 y4258 w402 h859
```

## Estado Visual Ya Verificado

Validaciones previas completadas:

- Desktop base `1512px`: estructura vertical coincidia con Figma dentro de subpixel:
  - hero `0,0 1512x982`
  - intro `y982 h752`
  - departamentos slider `y1803.59 h824`
  - oficinas slider `y2919.18 h824`
  - entregados `y3972.18 h750`
  - footer `y4722.18 h852`
- Mobile base `402px`: altura documento `5117`, equivalente al Figma `5171` menos el home indicator iOS de 54px.
- No habia overflow horizontal.
- Imagenes visibles cargaban al hacer scroll real.
- El footer dispara animacion `is-visible`.
- El menu viejo fue eliminado/neutralizado; el header nuevo no debe quedar desplegado por defecto.
- Slider de propiedades ya permite drag con mouse en desktop y no debe bloquear scroll vertical al pasar encima.

Validacion CDP final del 2026-05-12:

Desktop Figma `1512px`:

```txt
document height: 5574
overflow horizontal: 0
imagenes rotas: 0
mojibake visible: false
menu abierto por defecto: false
hero: y0 h982
intro: y982 h752
departamentos slider: y1803.59 h824
departamentos card: w990 h824
oficinas slider: y2919.19 h824
entregados: y3972.19 h750
footer: y4722.19 h852
```

Mobile Figma `402px`:

```txt
document height: 5117
overflow horizontal: 0
imagenes rotas: 0
mojibake visible: false
menu abierto por defecto: false
hero: y0 h766
intro: y766 h855
departamentos slider: y1721 h593
departamentos card: x16 w315 h593
oficinas slider: y2539 h593
entregados: y3258 h1000
footer: y4258 h859
```

Nota tecnica: las mediciones CDP restan el ancho visual de scrollbar en algunos anchos desktop, por eso ciertos `w` pueden verse como `1497` aunque el viewport sea `1512`. La altura vertical y posiciones clave coinciden con Figma.

Pendiente:

- Comparar capturas visuales contra Figma en:
  - 1366 laptop
  - 1920 monitor grande
  - 390 mobile estrecho

## SCF / ACF - Cobertura Esperada

Archivo principal:

```txt
inc/acf.php
```

Local JSON:

```txt
acf-json/
```

Grupos registrados por PHP:

- Global - Header:
  - logo desktop
  - logo mobile
  - texto MENU
  - color de fondo
  - color texto/logo
  - intensidad blur
  - fondo dropdown
- Global - Footer:
  - color fondo
  - color texto
  - titulos
  - logo/marca desktop/mobile
  - flechas desktop/mobile
  - redes sociales
  - miembros
  - direccion
  - telefonos
  - correo
  - texto legal
  - CTA
- Home - Hero Settings:
  - copy mobile
  - autoplay
  - intervalo
  - efecto
- Hero Slide:
  - imagen desktop
  - imagen mobile
  - label
  - link
  - duracion
  - transicion
- Home - Project Settings:
  - intro title
  - intro copy
  - intro button
  - titulos departamentos/oficinas
  - botones departamentos/oficinas
- Project Home Card:
  - seccion home
  - badge
  - ubicacion
  - precio
  - dormitorios
  - area
  - CTA
  - imagen desktop/mobile
  - crop X/Y/scale
- Property Data:
  - titulo comercial
  - subtitulo
  - precio
  - moneda
  - ubicacion
  - distrito
  - area
  - dormitorios
  - banos
  - estacionamientos
  - estado
  - descripcion
  - galeria
  - destacada
  - video
  - mapa
  - amenities
  - ficha tecnica
  - documentos
  - CTA/contacto
  - imagenes de detalle
  - brochure / WhatsApp
- Home - Proyectos entregados:
  - titulo
  - boton
  - imagen desktop
  - imagen mobile
  - colores de fondo/texto/boton

Regla importante:

Los `name` internos de SCF deben mantenerse sin acentos ni caracteres especiales. Los `label` visibles pueden tener acentos.

## CPT / Taxonomias

Archivo:

```txt
inc/cpt.php
```

CPT registrados:

- `property`
- `project`
- `hero_slide`

Taxonomias:

- `property_type`
- `district`

Nota: el viejo CPT/metabox `propiedad` puede existir en archivos heredados (`single-propiedad.php`, `archive-propiedad.php`) pero el flujo nuevo debe usar `property` y `project`.

## Contenido / Base de Datos Ya Corregida

Se corrigieron valores mojibake en meta de proyectos:

- `home_cta_label`: `Ver más`
- links serializados con title `Ver más`
- `home_area_text`: `m²`
- ubicacion: `Av. Andrés Aramburú 609 - San Isidro`

Comprobacion previa:

```txt
bodyTextHasQuestionReplacement: false
Textos visibles: Ver más, 80 m², Av. Andrés Aramburú 609 - San Isidro
```

## Archivos Clave

```txt
functions.php
inc/setup.php
inc/enqueue.php
inc/cpt.php
inc/acf.php
inc/helpers.php
front-page.php
header.php
footer.php
template-parts/header/
template-parts/home/
template-parts/footer/
assets/css/theme.css
assets/js/theme.js
assets/images/
acf-json/
docs/figma-design-audit.md
docs/deployment.md
```

## Checklist para la Siguiente IA

1. No avanzar a paginas internas antes de cerrar la validacion del HOME.
2. Revisar `git status` y entender los cambios pendientes.
3. Validar PHP/JS/diff:
   - `php -l inc\acf.php`
   - `node --check assets\js\theme.js`
   - `git diff --check`
4. Abrir `http://marcan-web.local/`.
5. Medir desktop 1512 contra Figma:
   - document height 5574
   - hero 982
   - sliders 824
   - delivered y3972
   - footer y4722 h852
6. Medir mobile 402 contra Figma:
   - document height 5117 esperado en web
   - hero 766 relativo
   - dept slider y1721 h593
   - office slider y2539 h593
   - delivered y3258 h1000
   - footer y4258 h859
7. Verificar:
   - sin overflow horizontal
   - sin imagenes rotas visibles
   - sliders arrastrables con mouse en desktop
   - scroll vertical no se detiene encima de sliders
   - menu no desplegado por defecto
   - dropdown header con blur uniforme
   - footer animacion de marca de derecha a izquierda
   - flecha del footer despues de la `n`, un poco separada
8. Verificar editabilidad SCF:
   - Header desde opciones SCF.
   - Footer desde opciones SCF.
   - Hero slides desde CPT `hero_slide`.
   - Cards de departamentos/oficinas desde CPT `project`.
   - Datos de detalle property desde CPT `property`.
9. Si todo pasa, commitear:
   - `fix: align home responsive and scf labels`
10. Si hay diferencias visuales, corregir solo lo necesario y volver a comparar contra Figma.

## Reglas de Continuacion

- Figma MCP es la fuente de verdad.
- No improvisar imagenes, colores, medidas ni animaciones.
- No usar Elementor.
- No usar Gutenberg como constructor visual.
- No hardcodear contenido que debe editar el usuario.
- Todas las imagenes reales deben vivir en la biblioteca de medios.
- Mantener commits pequenos y claros.
- No borrar archivos heredados sin revisar impacto.
- No exponer credenciales reales en Git remoto.
