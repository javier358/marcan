# Plan de reemplazo de contenido desde Drive

Fuente local del cliente: `C:\Users\USUARIO\Downloads\drive-download-20260528T224512Z-3-001`

Regla base: cargar todo dato editable por SCF/ACF, post meta, opciones o medios de WordPress. No hardcodear textos, precios, fechas, recorridos, CTAs, brochures ni imagenes de contenido. El theme solo debe resolver presentacion, fallbacks seguros y comportamiento.

## 1. Auditoria y normalizacion

1. Extraer texto de los 8 documentos Word y convertirlo a una matriz por pagina, seccion, campo SCF esperado y estado.
2. Normalizar acentos y caracteres corruptos del `.docx` exportado: precision, mas, diseno, estandar, solida, areas, banos, ninos, clinicas, etc.
3. Marcar como pendiente de cliente todo placeholder visible:
   - `S/ xxx` en TIME Angamos dentro de `5. Landing_ Oficinas.docx`.
   - `(info de tipologias disponibles)` en landings de detalle.
   - `Texto breve sobre el proyecto`, `Concepto` vacio, `Detalles que marcan` vacio y frases genericas en proyectos iconicos.
4. Resolver inconsistencias entre documentos antes de cargar:
   - En home, la tercera card de oficinas dice `TIME Aramburu` pero trae direccion y precio de Benavides. Debe cargarse como `TIME Benavides`.
   - TIME Angamos no trae precio en el bloque Home, pero el detalle de oficinas indica `S/ 367,000`; usar ese dato solo si el cliente confirma que aplica tambien al home.
   - Costa de Lima y Llano Zapata tienen precios distintos a los actuales locales; reemplazar por los documentos del cliente.

## 2. Mapa de paginas y campos

### Home

Pagina `Inicio`, campos SCF:

| Seccion | Campo | Valor cliente |
| --- | --- | --- |
| Intro proyectos | `home_intro_copy` | `Desarrollamos arquitectura de precision impulsando el desarrollo urbano. Cada proyecto es curado al detalle por nuestro equipo de mas de 15 especialistas, en alianza con las principales firmas de diseno y arquitectura del pais.` |
| Intro proyectos | `home_intro_title` | `Hacemos las cosas diferente.` |
| Intro proyectos | `home_intro_button_label` | `Descubre mas sobre nosotros` |
| Departamentos | `home_departments_title` | `Departamentos en venta` |
| Oficinas | `home_offices_title` | `Oficinas boutique en venta` |
| Entregados | `home_delivered_title` | `Nuestra trayectoria habla por nosotros` |
| Entregados | `home_delivered_button_label` | `Explora proyectos entregados` |

Cards de `property`, pestaña `Tarjeta Home`:

| Inmueble | Campos home |
| --- | --- |
| Llano Zapata 430 | `home_title=Llano Zapata 430`, `home_badge_label=En Construccion`, `home_location=Llano Zapata 430 - Miraflores`, `home_price=S/ 965,000`, `home_area_text=80 m2`, `home_bedrooms_text=1, 3 y 4 dormitorios` |
| Costa de Lima | `home_title=Costa de Lima`, `home_badge_label=En Construccion`, `home_location=Av. 28 de Julio 320 - Miraflores`, `home_price=S/ 795,000`, `home_area_text=79 m2`, `home_bedrooms_text=1 y 2 dormitorios` |
| TIME Aramburu | `home_title=TIME Aramburu`, `home_badge_label=En Construccion`, `home_location=Av. Aramburu 609 - San Isidro`, `home_price=S/ 367,000`, `home_area_text=25 m2`, sin dormitorios |
| TIME Angamos | `home_title=TIME Angamos`, `home_badge_label=En Construccion`, `home_location=Av. Angamos Oeste 500 - Miraflores`, `home_price=S/ 367,000` pendiente de confirmacion por inconsistencia documental, `home_area_text=25 m2`, sin dormitorios |
| TIME Benavides | `home_title=TIME Benavides`, `home_badge_label=Pre Venta`, `home_location=Av. Alfredo Benavides 2088 - Miraflores`, `home_price=S/ 391,380`, `home_area_text=28 m2`, sin dormitorios |

Imagenes Home a convertir a WebP y cargar despues:

| Archivo Drive | Destino SCF |
| --- | --- |
| `1. Home/1. Carrusel/IMG_Costa de Lima.png` | CPT `hero_slide` de Costa de Lima, imagen desktop/mobile segun crop final |
| `1. Home/1. Carrusel/IMG_Llano Zapata 430.png` | CPT `hero_slide` de Llano Zapata 430 |
| `1. Home/1. Carrusel/IMG_TIME Angamos.png` | CPT `hero_slide` de TIME Angamos |
| `1. Home/1. Carrusel/IMG_TIME Aramburu.png` | CPT `hero_slide` de TIME Aramburu |
| `1. Home/1. Carrusel/IMG_TIME Benavides.png` | CPT `hero_slide` de TIME Benavides |
| `1. Home/2. Oficinas & Depas en venta/IMG_D&P_Costa de Lima.png` | `home_desktop_image` / `home_mobile_image` de Costa de Lima |
| `1. Home/2. Oficinas & Depas en venta/IMG_D&P_Llano Zapata 430.png` | `home_desktop_image` / `home_mobile_image` de Llano Zapata 430 |
| `1. Home/2. Oficinas & Depas en venta/IMG_D&P_TIME Angamos.png` | `home_desktop_image` / `home_mobile_image` de TIME Angamos |
| `1. Home/2. Oficinas & Depas en venta/IMG_D&P_TIME Aramburu.png` | `home_desktop_image` / `home_mobile_image` de TIME Aramburu |
| `1. Home/2. Oficinas & Depas en venta/IMG_D&P_TIME Benavides.png` | `home_desktop_image` / `home_mobile_image` de TIME Benavides |
| `1. Home/3. Proy entregados/IMG_PE_AVA 159.png` | Pagina Inicio: `home_delivered_image_desktop` y version mobile si aplica |

### Quienes somos

Pagina `Quienes somos`, campos SCF de contenido about:

- Hero/copy principal: `Redefinimos el estandar del sector...`
- Bloque `Por que elegir Marcan`: 4 razones editables.
- Proyectos iconicos: Schreiber, Ava 159, Vive Vichayito, Tandem, TIME Surco, Qualis, POD La Mar.
- Respaldo post-entrega: titulo `La entrega del proyecto es el inicio de nuestro respaldo.` y texto de administracion de junta.
- Imagenes: `2. Quienes somos/IMG_HB_AVA 459.png`, `IMG_TIME Surco.png`, carpeta `2. Proy iconicos`, carpeta `4. Nuestro equipo`.

### Departamentos listado

Pagina `Departamentos`, template `page-departamentos.php`:

- `listing_title=Departamentos en venta`.
- `listing_intro=Arquitectura de autor y amplitud real...`.
- `listing_search_copy=Departamentos para tu estilo de vida o inversion desde S/ 795,000.`
- Cards deben venir de los inmuebles Llano Zapata 430 y Costa de Lima, no de texto hardcodeado.
- Imagenes: `3. Departamentos/IMG_Banner Header.png`, `IMG_Costa de Lima.png`, `IMG_Llano Zapata 430.png`.

### Detalle departamentos

CPT `property`:

- Llano Zapata 430: identidad, fecha `Octubre 2026`, precio `S/ 965,000`, area `80 m2`, dormitorios `1, 3 y 4 dormitorios`, concepto, recorridos Kuula, ubicacion cercana, areas comunes/departamentos, cita de Ruben Calvo y bloque fachada.
- Costa de Lima: fecha `Diciembre 2027`, precio `S/ 795,000`, area `79 m2`, dormitorios `1 y 2 dormitorios`, concepto, recorridos Kuula, barrio, areas comunes/departamentos, cita de Ruben Calvo y bloque fachada.
- Imagenes: carpetas `4. Landings_ depas en venta/Costa de Lima` y `Llano Zapata 430`, mapeadas a `detalle_hero_desktop`, `detalle_imagen_ancha`, `areas_comunes`, `areas_internas`, `galeria`.

### Oficinas listado

Pagina `Oficinas`, template `page-oficinas.php`:

- `listing_title=Oficinas boutique en venta`.
- Intro principal y razones de inversion desde `5. Landing_ Oficinas.docx`.
- Cards de TIME Aramburu, TIME Angamos y TIME Benavides.
- Pendiente: confirmar precio de TIME Angamos en listado general porque el documento trae `S/ xxx`.
- Imagenes: `5. Oficinas boutique/IMG_Banner Header.png`, cards `IMG_TIME Angamos.png`, `IMG_TIME Aramburu.png`, `IMG_TIME Benavides.png`.

### Detalle oficinas

CPT `property`:

- TIME Aramburu: fecha `Febrero 2027`, precio `S/ 367,000`, area `25 m2`, razones, tours, entorno, areas comunes, cita Rodrigo Martinez, bloque fachada.
- TIME Angamos: fecha `Diciembre 2027`, precio `S/ 367,000`, area `25 m2`, razones, tours, entorno, areas comunes, cita Rodrigo Martinez, bloque arquitectura de origen.
- TIME Benavides: fecha `Junio 2028`, precio `S/ 391,380`, area `28 m2`, razones, tours, entorno, areas comunes, cita Rodrigo Martinez, bloque arquitectura de triple frente.
- Imagenes: carpetas `6. Landing_ oficinas en venta/TIME Aramburu`, `TIME Angamos`, `TIME Benavides` cuando esten presentes.

### Proyectos iconicos

Documento `7. Landing_ Ver proyectos iconicos.docx` no esta listo para carga completa. Contiene placeholders y campos vacios. Accion:

- Cargar solo nombre y ubicacion si el cliente aprueba.
- Mantener bloques de concepto/cita/detalles ocultos si no hay contenido final.
- Imagenes: carpeta `2. Quienes somos/2. Proy iconicos` y home entregados.

### Contactanos

Documento `9. Landing_ Contactanos.docx`:

- Campos de formulario son labels fijos de interfaz.
- Datos editables globales: telefono, correo, direccion, horario, link Waze/Maps.
- Revisar diferencia actual: local muestra `Oficinas: (01) 711 9400` y horarios extendidos; documento cliente no incluye telefono de oficinas y usa `Lun a Vier 9:00am - 6:00pm`.

## 3. Orden de ejecucion seguro

1. Crear backup de BD/local antes de cambios masivos.
2. Actualizar SCF schema faltante antes de cargar datos.
3. Cargar HOME completo primero, solo textos y metadatos; imagenes se dejan mapeadas hasta conversion WebP.
4. Validar HOME desktop/mobile en `http://marcan-web.local/`.
5. Cuando el usuario lo ordene, continuar con `Quienes somos`.
6. Luego listados `Departamentos` y `Oficinas`.
7. Luego detalles de inmuebles, uno por uno.
8. Al final, proyectos iconicos y contacto.

## 4. Validacion por fase

- PHP sin errores de sintaxis cuando haya PHP disponible.
- Render local `200`.
- Busqueda en HTML para confirmar textos y precios esperados.
- Confirmar que datos salen de SCF/post meta/opciones, no de hardcode.
- Confirmar que no aparece `lorem`, `S/ xxx`, `Texto breve sobre el proyecto`, ni placeholders visibles.
- Comparar desktop/mobile visualmente contra Figma cuando se tenga link exacto con `node-id`.
