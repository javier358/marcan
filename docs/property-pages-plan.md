# MARCAN - Plan Departamentos y Oficinas

Fecha: 2026-05-12

Estado:
- No usar Git todavia.
- El archivo Figma nuevo `8ZTeasI8qqjoqP8lRqEtcp` sigue sin acceso via MCP.
- Los mismos nodos si fueron leidos desde el archivo anterior accesible `khZVAdpz6ZvY26Sqmo59D4`.

## Nodos Figma revisados

- Departamentos general: `8015:1190`, frame `1512 x 3320`.
- Single departamento: `8028:669`, frame `1512 x 12070`.
- Oficinas general: `8015:1380`, frame `1512 x 4316`.
- Single oficina: `8082:1475`, frame `1512 x 11558`.

## Correccion rapida de submenu

Problema detectado:
- En desktop el header mide `67px`, pero el dropdown arrancaba en `top:64px`.
- Eso podia generar un solape visual entre header y submenu.
- Tambien faltaban reglas explicitas para `li`, alineacion y overflow de links.

Ajuste aplicado:
- `.marcan-primary-nav` desktop ahora empieza en `top:67px` y mide `159px`.
- `.marcan-menu-list li` tiene `height:30px`, sin margin/padding.
- `.marcan-menu-list a` queda alineado a la derecha, sin wrap y con ellipsis.

Pendiente:
- El menu de WP local solo tiene dos items: `Pagina de ejemplo` y `Quienes Somos`.
- Para validar pixel-perfect del submenu real hay que cargar el menu final:
  - Quienes somos
  - Departamentos
  - Oficinas
  - Blog
  - Contactanos

## Arquitectura recomendada

Usar un solo CPT tecnico `property` para departamentos y oficinas.

Razon:
- Home, paginas generales y singles comparten el mismo modelo visual.
- Evita duplicar campos, templates y consultas.
- Permite que cada inmueble alimente:
  - carousel de departamentos del home
  - carousel de oficinas del home
  - pagina `/departamentos`
  - pagina `/oficinas`
  - `single-property.php`
  - modulos de "otras opciones"

Para que el admin sea claro para el usuario final:
- Mantener CPT `property`.
- Renombrar labels a `Departamentos y Oficinas` o `Inmuebles`.
- Agregar taxonomia/campo obligatorio `tipo_inmueble`:
  - `departamento`
  - `oficina`
- Opcional: crear submenu admin filtrado para "Departamentos" y "Oficinas", ambos apuntando al mismo CPT con query filtrada.

Si el usuario exige CPTs separados, alternativa:
- `department`
- `office`
- Pero requeriria duplicar/abstraer queries y singles. No recomendado para este proyecto.

## Modelo de datos SCF / ACF

### Identidad comercial

- `tipo_inmueble`: departamento/oficina
- `titulo_comercial`
- `subtitulo`
- `slug`
- `estado_comercial`: en obra, entrega inmediata, preventa, vendido, alquilado
- `destacado_home`: true/false
- `mostrar_en_listado`: true/false
- `orden_listado`

### Ubicacion

- `direccion`
- `distrito`
- `ciudad`
- `latitud`
- `longitud`
- `mapa_embed`
- `lugares_interes`: repeater con nombre, categoria, distancia, columna

### Precio y resumen

- `moneda`
- `precio_desde`
- `precio_hasta`
- `area_desde`
- `area_hasta`
- `dormitorios_desde`
- `dormitorios_hasta`
- `banos_desde`
- `banos_hasta`
- `estacionamientos`
- `lavatorio` solo oficinas si aplica
- `fecha_entrega`
- `texto_resumen_listado`
- `texto_cta_listado`

### Assets para listados/home

- `imagen_card_desktop`
- `imagen_card_mobile`
- `imagen_card_hover`
- `imagen_hero_general`
- `iconos_specs`: dormitorios, area, banos si se quiere editable
- `arrow_hover` global reutilizable si no viene del theme

### Single property

- `hero_desktop`
- `hero_mobile`
- `headline_single`
- `descripcion_intro`
- `concepto_titulo`
- `concepto_texto`
- `concepto_imagen`
- `autor_nombre`
- `autor_cargo`
- `autor_foto`
- `recorrido_virtual_url`
- `recorrido_virtual_embed`
- `galeria_principal`
- `areas_comunes`: galeria/repeater
- `areas_internas`: galeria/repeater
- `quote_titulo`
- `quote_texto`
- `quote_autor`
- `arquitectura_titulo`
- `arquitectura_texto`
- `arquitecto_nombre`
- `arquitecto_cargo`
- `arquitecto_foto`
- `arquitectura_imagen`
- `brochure`
- `whatsapp`
- `cta_cotizar`
- `cta_brochure`

### Unidades / pricing table

Usar repeater `unidades` dentro de cada property:
- `codigo`
- `piso`
- `habitaciones`
- `banos`
- `lavatorio`
- `area_m2`
- `precio`
- `moneda`
- `estado`
- `plano`
- `tour_url`

Esto alimenta:
- tabla de precios del single
- filtros por habitaciones/banos/area/precio
- min/max automaticos para pagina general

## Templates necesarios

- `archive-property.php`: puede redirigir o servir fallback.
- `page-departamentos.php`: listado filtrado `tipo_inmueble=departamento`.
- `page-oficinas.php`: listado filtrado `tipo_inmueble=oficina`.
- `single-property.php`: template unico, cambia textos/filters segun `tipo_inmueble`.
- `template-parts/properties/property-card.php`
- `template-parts/properties/property-listing-hero.php`
- `template-parts/properties/property-listing-row.php`
- `template-parts/properties/property-single-hero.php`
- `template-parts/properties/property-pricing-table.php`
- `template-parts/properties/property-map.php`
- `template-parts/properties/property-gallery-carousel.php`
- `template-parts/properties/property-related.php`

## Reglas por pagina Figma

### Departamentos general `8015:1190`

Desktop:
- Frame `1512 x 3320`.
- Hero `1512 x 1075`.
- Imagen hero `1512 x 606`.
- Titulo `Departamentos en venta` en `x30 y646`.
- Copy `x30 y716 w591 h120`.
- Texto de busqueda `x30 y933`.
- Listado inicia `y1105`.
- Cada item usa media `1073 x 651` + panel info `369 x 651`.
- Departamentos alternan layout:
  - media izquierda `x30`, info derecha `x1113`
  - dos cards en Figma: y `1105` y `1786`.

### Oficinas general `8015:1380`

Desktop:
- Frame `1512 x 4316`.
- Hero `1512 x 1390`.
- Imagen hero `1512 x 606`.
- Tiene bloque "Por que invertir en oficinas" en `x30 y932 w1452 h191`.
- Listado inicia `y1420`.
- Cada item usa info izquierda `369 x 651` + media derecha `1073 x 651`.
- Tres cards en Figma: y `1420`, `2101`, `2782`.

### Single departamento `8028:669`

Desktop:
- Frame `1512 x 12070`.
- Hero image `1512 x 788`, empieza `y32`.
- Barra cotizar fija/superior: `1512 x 216`.
- Contenido principal empieza `y753`.
- Imagen principal interna `x30 y715 w1452 h745`.
- Tabla/filtros de unidades desde `y3422` / `y4226`.
- Mapa `x30.5 y1109 w1451 h844` dentro de seccion interna.
- Carruseles: areas comunes e internas.
- CTA y otras opciones antes de footer.

### Single oficina `8082:1475`

Desktop:
- Frame `1512 x 11558`.
- Estructura casi igual al single departamento.
- Filtros cambian:
  - Lavatorio
  - Banos
  - Area
  - Precio
- Tabla de precios menor: `478px` alto en el bloque inspeccionado.
- Related: tres oficinas en `Frame 98278`, cards `477.33 x 472`.

## Consultas dinamicas

Home:
- Departamentos: `post_type=property`, `tipo_inmueble=departamento`, `destacado_home=true`, ordenar por `orden_listado`.
- Oficinas: igual con `tipo_inmueble=oficina`.

Paginas generales:
- Query principal por tipo.
- Mostrar solo `mostrar_en_listado=true`.
- Si no hay posts, mostrar estado vacio editable.

Single:
- Tomar `tipo_inmueble` para:
  - labels de filtros
  - related items
  - CTA final
  - texto de "otros departamentos/oficinas"

## Orden de implementacion sugerido

1. Resolver acceso MCP al archivo nuevo o confirmar que se trabaja con el archivo anterior.
2. Normalizar CPT `property` y taxonomia `tipo_inmueble`.
3. Crear/ajustar SCF de property con grupos por tabs.
4. Migrar home carousels para leer solo `property`.
5. Implementar `page-departamentos.php`.
6. Implementar `page-oficinas.php`.
7. Implementar `single-property.php`.
8. Cargar minimo 2 departamentos y 3 oficinas desde Media Library/SCF para QA.
9. Validar desktop/mobile contra Figma por seccion.

## Riesgos

- El archivo Figma nuevo no esta accesible via MCP. Hay que corregir permisos antes de implementar pixel-perfect definitivo.
- Actualmente hay campos con mojibake en algunos defaults PHP. Los datos SCF reales pueden ocultarlo, pero conviene limpiar antes de produccion.
- PHP CLI local no carga `mysqli`; para poblar BD se debe usar `mysql.exe` de LocalWP o instalar/activar extension mysqli en CLI.

## Handoff de ejecucion

Fecha de actualizacion: 2026-05-12

Estado actual:
- No se hicieron commits por pedido del usuario.
- Se crearon las paginas locales:
  - `/departamentos/`, page ID `91`.
  - `/oficinas/`, page ID `92`.
- Se migraron los posts previos de `project` a `property` para usar un unico modelo dinamico:
  - `22` Llano Zapata 430, `tipo_inmueble=departamento`.
  - `23` Costa de Lima, `tipo_inmueble=departamento`.
  - `25` Time: Angamos, `tipo_inmueble=oficina`.
  - `26` Time: Benavides, `tipo_inmueble=oficina`.
  - `31` Time: Aramburu, `tipo_inmueble=oficina`.
- Se eliminaron las rewrite rules antiguas para que WordPress regenere `/propiedades/{slug}/` apuntando al CPT `property`.
- Se mantuvieron las imagenes existentes de la biblioteca de medios como valores SCF/meta; no se dejaron URLs de Figma hardcodeadas para estas paginas.

Archivos del theme creados o tocados:
- `inc/cpt.php`
- `inc/acf.php`
- `inc/helpers.php`
- `template-parts/home/home-projects.php`
- `page-departamentos.php`
- `page-oficinas.php`
- `single-property.php`
- `template-parts/properties/property-card-listing.php`
- `template-parts/properties/property-listing-page.php`
- `assets/css/theme.css`
- `assets/js/theme.js`
- `template-parts/header/site-header.php`
- `docs/property-pages-plan.md`

Archivo fuera del theme tocado:
- `wp-content/plugins/marcan-preloader/marcan-preloader.php`
- Motivo: el preloader dejaba `body.ml-loading` activo cuando GSAP no terminaba de cargar en QA headless. Se agrego fallback independiente para retirar el overlay a los `6500ms` y fallback especifico si `window.gsap` no existe.

SCF / ACF preparado:
- El grupo de inmueble se movio conceptualmente a `property`.
- Campos clave registrados por PHP:
  - `tipo_inmueble`
  - `mostrar_en_listado`
  - `destacado_home`
  - `orden_listado`
  - `listado_hero_imagen`
  - `listado_intro_titulo`
  - `listado_intro_texto`
  - `concepto_titulo`
  - `concepto_texto`
  - `recorrido_virtual`
  - `areas_comunes`
  - `areas_internas`
  - `frase_proyecto`
  - `autor_frase`
  - repeater `unidades`
- Los campos heredados de cards del home siguen activos y ahora alimentan home/listados desde `property`.

Validacion local ejecutada:
- PHP lint OK:
  - `inc/cpt.php`
  - `inc/helpers.php`
  - `inc/acf.php`
  - `page-departamentos.php`
  - `page-oficinas.php`
  - `single-property.php`
  - `template-parts/properties/property-card-listing.php`
  - `template-parts/properties/property-listing-page.php`
  - `template-parts/home/home-projects.php`
  - `template-parts/header/site-header.php`
  - `wp-content/plugins/marcan-preloader/marcan-preloader.php`
- JS check OK:
  - `node --check assets/js/theme.js`
- `git diff --check` OK, solo avisos CRLF propios de Windows.

QA visual Playwright:
- Desktop `1512px` y mobile `402px`.
- URLs validadas:
  - `http://marcan-web.local/departamentos/`
  - `http://marcan-web.local/oficinas/`
  - `http://marcan-web.local/propiedades/llano-zapata-430/`
  - `http://marcan-web.local/propiedades/time-aramburu/`
- Resultado tecnico:
  - HTTP `200`.
  - `overflowX=0`.
  - `broken images=0`.
  - Preloader removido correctamente.
  - Singles resuelven con `single-property.php`.
  - `/departamentos/` muestra 2 cards dinamicas.
  - `/oficinas/` muestra 3 cards dinamicas.
- Capturas guardadas:
  - `docs/qa-clean-departamentos-desktop.png`
  - `docs/qa-clean-departamentos-mobile.png`
  - `docs/qa-clean-oficinas-desktop.png`
  - `docs/qa-clean-oficinas-mobile.png`
  - `docs/qa-clean-single-depto-desktop.png`
  - `docs/qa-clean-single-depto-mobile.png`
  - `docs/qa-clean-single-office-desktop.png`
  - `docs/qa-clean-single-office-mobile.png`

Medidas QA principales:
- Header desktop: `1512 x 67`.
- Header mobile: `402 x 54`.
- Departamentos desktop:
  - hero `1512 x 1075`.
  - listado inicia en `y1075`.
  - primera card `x30 y1105 w1452 h651`.
- Oficinas desktop:
  - hero `1512 x 1390`.
  - listado inicia en `y1390`.
  - 3 cards dinamicas.
- Single desktop:
  - hero `1512 x 820`.
  - sticky quote `1512 x 216`.
- Mobile:
  - archivos sin overflow, hero `402 x 860`.
  - singles con hero `402 x 560`.

Dropdown QA:
- Desktop:
  - `aria-expanded=true` al click.
  - fondo header/dropdown uniforme `rgba(255,255,255,0.74)`.
  - dropdown `1512 x 162`, top `64px`.
  - items: Quienes somos, Departamentos, Oficinas, Blog, Contactanos.
- Mobile:
  - dropdown `402 x 162`, top `54px`.
  - items alineados a la derecha como en referencia.

Pendientes para la validacion 5.5:
- El Figma nuevo `8ZTeasI8qqjoqP8lRqEtcp` sigue sin acceso via MCP; para pixel-perfect final hay que reabrir permisos o validar contra capturas manuales.
- Las paginas generales y singles ya son funcionales y dinamicas, pero la comparacion al milimetro debe hacerse con capturas Figma de cada seccion, no solo con metadata MCP.
- Revisar tipografias exactas y textos finales cuando el cliente defina copy definitivo.
- Limpiar mojibake remanente en labels/defaults antiguos antes de produccion.
