# Plan de correcciones finales Marcan

Fuente de verdad: `https://github.com/javier358/marcan`, rama `feature/figma-wordpress-build`.

Reglas de trabajo:

- Trabajar sobre el theme existente, con cambios quirurgicos.
- No rehacer estructura general si no es necesario.
- Mantener compatibilidad WordPress, PHP 8+, ACF / Secure Custom Fields.
- No tocar WordPress core, uploads, `wp-config.php` ni base de datos.
- No instalar librerias externas salvo necesidad estricta.
- No dejar contenido visible comercial hardcodeado como fallback en PHP/JS/CSS.
- Si un campo ACF editable queda vacio, no mostrar texto por defecto.
- Si una seccion queda sin contenido suficiente, ocultarla de forma segura.
- Escapar salidas con `esc_html`, `esc_attr`, `esc_url` o `wp_kses_post` segun corresponda.
- Sanitizar valores de estilo antes de imprimirlos.
- Cada fase debe terminar con commit y push. Pausar luego para validacion del usuario antes de continuar.

## Estado de fases

- [x] Fase 0 - Crear este plan de continuidad.
- [x] Fase 1 - Auditoria inicial y base global de colores/tipografia/fallbacks.
- [x] Fase 2 - Departamentos/Oficinas, modal de contacto y sticky single property.
- [x] Fase 3 - Mapa/cercania y CTA de single property sin hardcodes.
- [x] Fase 4 - ACF de tamanos por contexto, galeria y otros proyectos.
- [ ] Fase 5 - Revision global, pruebas y documentacion final.

## Fase 1 - Auditoria inicial y base global

Objetivo:

- Ubicar plantillas, helpers, ACF JSON, CSS y JS relacionados con contenido visible.
- Normalizar textos grises a `#4f4f4f` sin tocar blancos, amarillos, fondos ni marca.
- Alinear font-family/pesos evidentes con la base del home.
- Detectar y registrar fallbacks visibles hardcodeados.

Archivos probables:

- `assets/css/theme.css`
- `assets/css/partials/*.css`
- `inc/helpers.php`
- `inc/acf.php`
- `template-parts/**/*.php`
- `single-property.php`
- `single-propiedad.php`
- `page-departamentos.php`
- `page-oficinas.php`

Validacion:

- `git diff --check`
- Busquedas de grises frecuentes: `#838382`, `#777`, `#666`, `#555`, `rgba(... gray ...)`.
- Busquedas de fallbacks: `?: '`, `?: "`, `?? '`, `?? "`, `get_field`, `get_post_meta`.

## Fase 2 - Departamentos/Oficinas, modal y sticky

Objetivo:

- Eliminar del frontend el bloque repetido inferior de Departamentos y Oficinas.
- Dejar de usar ACF duplicado para ese texto si existe, sin borrar datos destructivamente.
- Ajustar modal "Conversemos" para laptop/desktop con `max-height`, respiro y scroll interno.
- Reducir padding/altura visual de la barra sticky superior en single property.

Validacion:

- Departamentos y Oficinas sin texto repetido debajo del intro.
- Modal visible en laptop aproximado 1366x768, con scroll interno si hace falta.
- Sticky de propiedad mas delgado, sin romper responsive.

## Fase 3 - Mapa/cercania y CTA single property

Objetivo:

- Eliminar fallbacks hardcodeados de mapa/cercania.
- Ocultar titulos, textos o listas vacias cuando ACF no tenga contenido.
- Hacer editables desde ACF los CTA de single property:
  - CTA oscuro.
  - CTA blanco/opciones.
  - Texto de boton.
  - Enlace o accion de boton cuando aplique.
- Mantener logica actual de modal/contacto.

Validacion:

- Al borrar ACF de mapa/cercania no aparecen textos por defecto.
- Al borrar campos CTA no aparecen textos quemados.
- Secciones CTA se ocultan si quedan sin contenido suficiente.

## Fase 4 - ACF tamanos por contexto, galeria y otros proyectos

Objetivo:

- Agregar campos ACF de tamanos por contexto reutilizando contenido existente:
  - `home_card_title_font_size`
  - `home_card_meta_font_size`
  - `listing_card_title_font_size`
  - `listing_card_meta_font_size`
  - `single_property_title_font_size`
  - `single_property_meta_font_size`
  - `related_project_title_font_size`
  - `related_project_meta_font_size`
  - `gallery_menu_font_size`
- Adaptar nombres si el patron real del theme ya usa otros prefijos.
- Aplicar tamanos con variables CSS inline sanitizadas o clases seguras.
- Dejar tamano CSS actual si el campo esta vacio.
- Eliminar/reemplazar hardcodes de "otros proyectos".

Validacion:

- Galeria single property permite editar tamano del menu lateral desde ACF.
- Cards home/listados/single/relacionados conservan contenido editable.
- Cambios de tamano afectan solo su contexto.

### Fase 4

- Completada pendiente de validacion visual del cliente.
- Se agrego `gallery_menu_font_size` para editar el tamano del menu lateral de galeria en single property.
- Se normalizaron familias en single property y se mantuvo el peso de textos editoriales/rich text alineado al home (`300`): concepto, mapa, galeria lightbox y arquitectura. Titulos y elementos destacados conservan su peso propio.
- El titulo de relacionados/otros proyectos dejo de usar fallback visible en template; ahora se siembra una sola vez como ACF global editable (`ui_property_related_dept` / `ui_property_related_office`) y si se borra no se renderiza.
- El titulo de relacionados queda conectado al control de tamano ACF global generado para ese campo.

## Fase 5 - Revision global y cierre

Objetivo:

- Revisar PHP/JS/CSS por contenido visible hardcodeado restante.
- Revisar que no existan notices por campos vacios.
- Documentar:
  - Archivos modificados.
  - Campos ACF nuevos.
  - Fallbacks eliminados.
  - Pruebas realizadas y pendientes.

Validacion tecnica:

- `git diff --check`
- `php -l` en PHP modificados si PHP esta disponible.
- Build CSS si aplica: revisar `package.json` y usar el script existente.
- `git status --short --branch` limpio luego de commit/push.

### Fase 5

- En progreso.
- Corregido: `.marcan-property-intro` ahora declara `font-weight: 300`, alineado al peso de los textos rich del home.
- Corregido: `template-parts/properties/property-listing-page.php` ya no usa fallbacks visibles para `listing_title`, `listing_intro`, `listing_reasons_title` ni las razones de inversion de oficinas; si ACF queda vacio, el bloque no se renderiza.
- Agregada migracion idempotente `marcan_seed_listing_editable_copy` para sembrar esos textos anteriores en las paginas `departamentos` y `oficinas` una sola vez, sin reponerlos si el cliente los borra despues.
- En progreso: se agregan pestañas `Cards` en Inicio, Departamentos y Oficinas para controlar por pagina el tamano de textos de las cards. Si el campo de pagina queda vacio, se conserva el tamano configurado en cada proyecto.
- Revisado: los campos ACF de Arquitectura del proyecto si se usan en `single-property.php`; no afectan las cards de departamentos/oficinas porque pertenecen a la ficha del inmueble.
- Ajustado: las imagenes especificas de card (`home_desktop_image` y `home_mobile_image`) se movieron a Ficha tecnica del proyecto para quedar junto a los datos que alimentan single/cards, manteniendo las mismas keys para no perder contenido.
- Corregido: los CTAs del single ahora tienen override por inmueble en la pestaña `CTAs single` (`single_cta_*`, `single_price_label`, `single_tours_title`) y fallback solo a campos globales editables; si ambos quedan vacios, no se renderiza el CTA.
- Corregido: se retiraron fallbacks visibles hardcodeados restantes del single para botones de unidad, mapa, brochure, cotizar, compartir, etiqueta de precio y titulo de recorridos. Los textos antiguos se siembran como ACF global editable por migracion.
- Pendiente detectado para siguiente bloque: `inc/blog.php` mantiene defaults visibles en contenido/CTA del blog; revisar si entra en alcance antes del cierre final.

## Registro de avance

### Fase 0

- Creado este documento para continuidad.
- Commit y push completados: `b6fbc0b`.

### Fase 1

- Completada.
- Auditoria inicial encontro grises `#838382` en textos de cards, unidades, mapa, blog y responsive.
- No se tocaron fondos ni SVGs con `#838382` porque no son texto.
- Se eliminaron fallbacks visibles legacy `Consultar` y `Disponible`.
- Se alineo la cabecera de precios de unidades a `Inter`, consistente con el resto del bloque.
- `npm run build:css` ejecutado correctamente.
- `git diff --check` ejecutado correctamente.
- `php -l` pendiente: PHP no esta disponible en PATH local.

### Fase 2

- Parcialmente completada.
- Revertido el cambio que eliminaba el contenedor/campos `listing_search_title` y `listing_search_copy`; el usuario indico que deben quedar 3 cajas y va a precisar cual eliminar.
- Se elimino `listing_search_title`; se conserva solo `listing_search_copy` como tercera caja y se renombro su label a "Texto de opciones del listado".
- Se mantiene aplicado el ajuste del modal de contacto para laptop/desktop con `max-height`, respiro lateral/vertical y scroll interno del formulario.
- Se mantiene aplicado el ajuste de alto visual de `.marcan-property-sticky-quote` en single property.

### Fase 3

- Completada.
- Se eliminaron fallbacks visibles de mapa: `ubicacion_titulo`, `lugares_cercanos_titulo`, URLs Google/Waze generadas automaticamente y listas vacias.
- La seccion de mapa solo renderiza contenido si hay datos reales visibles o enlaces ACF configurados.
- Se agregaron campos ACF `unidades_titulo_intro` y `unidades_titulo_detalle` para reemplazar el texto hardcodeado de la seccion de unidades/opciones.
- Se quitaron fallbacks visibles de botones de cotizar/mapa usados en single property; si el campo global esta vacio no se muestra el boton.
- Se quitaron fallbacks visibles de frase/autor del proyecto y del bloque previo a relacionados.
- Se agrego una migracion idempotente que siembra esos textos antiguos en ACF una sola vez, solo cuando el campo aun no existe; si el cliente los borra despues, no se reponen.
