# MARCAN handoff: siguiente seccion HOME + header/footer globales

Estado actual: header, hero y sliders de departamentos/oficinas ya existen en `marcan-theme`. No avanzar a paginas internas. La siguiente ejecucion debe implementar solo el siguiente bloque del HOME y preparar header/footer como componentes globales editables.

## Fuente Figma

- Desktop HOME: `8002:390`, 1512 x 5574.
- Mobile HOME: `8510:2529`, 402 x 5171.
- Siguiente seccion desktop: `Proyectos entregados`.
  - Imagen: `9088:2365`, x 0, y 3972, w 757, h 750.
  - Panel: `9088:2362`, x 757, y 3972, w 755, h 750.
  - Asset imagen desktop: `https://www.figma.com/api/mcp/asset/67e01082-f4df-4ad9-b801-92a21c4fdad1`.
- Siguiente seccion mobile:
  - Imagen: `9185:5344`, x 0, y 3258, w 403, h 500.
  - Panel: `8549:2780`, x 0, y 3758, w 402, h 500.
  - Asset imagen mobile: `https://www.figma.com/api/mcp/asset/eb5df3a3-63d8-4b2c-a481-e8e2a982b318`.
- Footer desktop: `8002:391`, x 0, y 4722, w 1512, h 852.
- Footer mobile: `8636:2585`, x 0, y 4258, w 402, h 859.

## Proyectos Entregados

Desktop:
- Layout 2 columnas sin gap: izquierda imagen 757 x 750, derecha panel 755 x 750.
- Panel background: `#f3f2f1`.
- Titulo: `Nuestros proyectos entregados hablan por nosotros`.
- Titulo desktop: Bitter Light, 45px, line-height 1.1, letter-spacing 0.45px, color `#4f4f4f`.
- Titulo x 31, y 31 dentro del panel, width 591, height 150.
- Boton x 31, y 229, w 192, h 33; background `#4f4f4f`; texto Inter Regular 12px, color `#fbfafa`.

Mobile:
- Imagen 403 x 500 arriba.
- Panel 402 x 500 abajo, background `#f3f2f1`.
- Panel usa flex center, padding 100px 20px.
- Contenido width 362, gap 30, centrado.
- Titulo Bitter Regular 32px, color `#4f4f4f`, centrado.
- Boton 192 x 33, centrado.

Implementacion:
- Crear `template-parts/home/home-delivered-projects.php`.
- Incluirlo en `front-page.php` despues de `home-projects.php`.
- Importar las dos imagenes a Media Library y guardarlas en opciones/campos, no hardcodear URLs Figma.
- ACF recomendado para front page:
  - `home_delivered_title`
  - `home_delivered_button_label`
  - `home_delivered_button`
  - `home_delivered_image_desktop`
  - `home_delivered_image_mobile`
  - `home_delivered_background_color`
  - `home_delivered_text_color`
  - `home_delivered_button_background`
  - `home_delivered_button_text_color`

## Header global editable

El header ya renderiza en `template-parts/header/site-header.php` y se usa en todo el sitio via `header.php`.

Falta formalizar ACF/opciones globales:
- Crear options page si ACF/SCF lo permite:
  - `acf_add_options_page(array('page_title' => 'MARCAN Global', 'menu_title' => 'MARCAN Global', 'menu_slug' => 'marcan-global-settings'))`.
  - Si SCF no soporta options page, usar una pagina normal `Configuracion global` o `theme_mod` como fallback.
- Grupo `group_marcan_global_header`:
  - `header_logo_desktop` image id.
  - `header_logo_mobile` image id.
  - `header_menu_label` text, default `MENU`.
  - `header_background_color` color, default glass `rgba(234,234,232,0.72)`.
  - `header_text_color` color, default `#4f4f4f`.
  - `header_blur_amount` number, default `18`.
  - `header_dropdown_background_color` color, same as header.
  - `header_dropdown_links` repeater/link list if not using WP menu.
- Preferir WP nav menu para enlaces; ACF solo para label, logos y colores.
- Mantener medidas Figma:
  - Desktop header 1512 x 67, logo 110 x 22 at x 23 y 21, menu at right.
  - Mobile header 402 x 54, logo 100 x 20 at x 20 y 17, menu at x 292 y 9.

## Footer global editable

Actualmente `footer.php` esta vacio salvo `wp_footer()`. Debe crear template global:
- `template-parts/footer/site-footer.php`.
- Llamar desde `footer.php` antes de `wp_footer()`.

Desktop Figma:
- Footer 1512 x 852.
- Background `#ffcb05`.
- Text color `#4f4f4f`.
- Columna izquierda x 30 y 30 w 149:
  - Proyectos actuales
  - Departamentos
  - Oficinas
  - Boton `Ver Proyectos` x 30 y 186, h 46 aprox.
- Columna centro x calc(50% + 12px) y 30 w 168:
  - Quienes somos
  - Proyectos iconicos
  - Blog
  - Contactanos
- Columna derecha x 83.33% y 30 w 168:
  - Direccion
  - Contact Center / Oficinas
  - Email
  - Social icons at y 210, 22 x 22, gap 22.
  - Logos miembro at y 308.
- Marca/flecha inferiores:
  - Arrow `9068:2985`, x calc(25% + 68px), y 572, w 129, h 188.
  - Marcan vertical `9068:2987`, x calc(8.33% + 27px), y 635, w 10, h 125.
  - Legal x 57 y 790.

Mobile Figma:
- Footer 402 x 859.
- Padding x 16, top 56.
- Layout vertical gap 48.
- Social icons 25 x 25, gap 16.
- Member logos 88 x 26, 55 x 26, 44 x 26.
- Bottom brand horizontal: Marcan 242.242 x 37.676 + Arrow 39.003 x 56.78.
- Legal text 14px, can wrap into 2 lines.

Footer assets from Figma MCP:
- Desktop member logos/social/brand/arrow:
  - `638869eb-3d08-472b-8ab7-4c1b3b206e0a`, `0f48bb00-f325-45d5-ba0b-dec748c04b13`, `3a25f8a8-4310-4950-8fc0-5570b169515e`
  - `0678821e-3683-4a3d-91c6-0e1e34f3266d`, `d372e63a-34e3-4465-9212-196f470f5c64`, `0ea62792-1ca4-4a19-a788-c106385f561f`, `ed5dba99-5cb5-47e4-beb0-18ceb209343c`
  - `93ede8fb-61c1-4b17-b98c-fa79ffc8f7aa`, `eccde06c-d5ce-42e0-bc91-0c8409ffbe8c`
- Mobile footer assets:
  - `5f1e07e8-186f-4954-8120-b8bde61baae4`, `dca67d43-d24b-4bc2-87a0-7923df0eaa63`, `35ee202c-4cc9-42cc-a38f-e6e1fec93dd2`
  - `01cde3c6-43e6-429c-8112-c2125c7925df`, `794993e0-6680-4813-8956-c3291848728f`, `c858b09b-639a-44e1-8638-797d890dce2f`, `bbac508d-c682-4cb0-b917-7e759d0a17b0`
  - `2949852c-562d-436c-b792-fd2e9567fee9`, `59f0acb7-c08a-4320-8065-e6010b41f853`

ACF global footer recomendado:
- `footer_background_color`, default `#ffcb05`.
- `footer_text_color`, default `#4f4f4f`.
- `footer_projects_title`, `footer_projects_links` repeater/link, `footer_projects_button`.
- `footer_company_links` repeater/link.
- `footer_address`, `footer_phone_lines`, `footer_email`.
- `footer_social_links` repeater: label, url, icon image.
- `footer_member_logos` repeater: label, url, logo desktop, logo mobile, width/height optional.
- `footer_brand_logo_desktop`, `footer_brand_logo_mobile`.
- `footer_arrow_desktop`, `footer_arrow_mobile`.
- `footer_legal_text`.

## CPT/ACF decision

Header and footer should not be CPTs. They are global site settings. Use ACF options page or a single settings page. CPTs are for repeatable content entities:
- Keep `project` for home project cards and project detail.
- Keep `property` for real estate detail pages.
- Add `delivered_project` CPT only if later the delivered-projects section becomes a slider/list of delivered projects. For the current Figma node it is a static CTA block with one image, so ACF page fields are enough.

## Ejecucion sugerida para GPT-5.4 mini

1. Check `git status`.
2. Import delivered-projects desktop/mobile images to Media Library using WordPress bootstrap and update front-page ACF/meta/options.
3. Add ACF fields in `inc/acf.php` and matching JSON in `acf-json/`.
4. Add `template-parts/home/home-delivered-projects.php`.
5. Include it in `front-page.php` after `home-projects.php`.
6. Add CSS matching desktop/mobile measurements exactly.
7. Create footer global template and ACF global fields, but do not improvise beyond Figma.
8. Import footer assets to Media Library and store IDs in options/fields.
9. Update `footer.php` to render `template-parts/footer/site-footer.php`.
10. Validate:
    - Desktop 1512px.
    - Large desktop 1920px.
    - Mobile 402px.
    - Header/footer appear on non-home templates too.
11. Commit in two commits:
    - `feat: add delivered projects home section`
    - `feat: add editable global footer settings`

## Riesgos

- Some labels in existing ACF/PHP have mojibake. Avoid spreading it; use ASCII field labels if needed, or fix encoding in a separate focused commit.
- Do not hardcode Figma asset URLs. They expire. Import to Media Library first.
- Do not use Elementor/Gutenberg builder.
- Do not continue to internal pages until HOME and global footer are approved.
