# MARCAN - Handoff Quienes Somos

Fecha: 2026-05-12

Estado:
- Pagina `Quienes Somos` implementada en `page-quienes-somos.php`.
- Slug local en la BD: `quienes-somos`, page ID `64`.
- Sin commits ni cambios Git por pedido del usuario.
- Figma sigue siendo la fuente de verdad:
  - Desktop: `8002:456`, frame `1512 x 5684`.
  - Mobile: `8669:2646`, frame `402 x 5765`.
  - Mobile web medido sin home indicator iOS: `402 x 5711`.

## Archivos creados o tocados

- `functions.php`
- `inc/about.php`
- `page-quienes-somos.php`
- `template-parts/about/about-hero.php`
- `template-parts/about/about-reasons.php`
- `template-parts/about/about-iconic-projects.php`
- `template-parts/about/about-promise-awards-team.php`
- `assets/css/theme.css`
- `assets/js/theme.js`
- `docs/about-page-handoff.md`

## SCF / ACF

Options page registrada:
- Titulo: `MARCAN Quienes Somos`
- Slug: `marcan-about-settings`
- Field group: `group_marcan_about_page`

Campos registrados:
- `about_hero_intro`
- `about_hero_image_desktop`
- `about_hero_image_mobile`
- `about_reasons_title`
- `about_reasons_items`
- `about_iconic_title`
- `about_iconic_projects`
- `about_timeline_arrow`
- `about_promise_image_desktop`
- `about_promise_image_mobile`
- `about_promise_title`
- `about_promise_text`
- `about_awards_title`
- `about_awards`
- `about_team_title`
- `about_team_members`

Los valores fueron cargados como opciones SCF/ACF en BD, simulando carga de usuario. El codigo ya no depende de URLs temporales de Figma.

## Media Library

Assets subidos a `wp-content/uploads/2026/05` e insertados en la biblioteca de medios:

- `65`: hero desktop
- `66`: hero mobile
- `67`: timeline arrow SVG
- `68`: timeline Ava
- `69`: timeline Shcreiber
- `70`: timeline Vichayito
- `71`: timeline POD
- `72`: timeline Tandem
- `73`: timeline Time Surco
- `74`: timeline Qualis
- `75`: promise desktop
- `76`: promise mobile
- `77`: award LADI
- `78`: award Architizer
- `79`: team 1
- `80`: team 2
- `81`: team 3
- `82`: team 4
- `83`: team 5

## Validacion contra Figma

Nodo revisado adicionalmente:
- `9175:5279` Linea de tiempo desktop, componente `1452 x 829`.
- Ajuste aplicado: desktop usa step `804px`, tarjeta `780 x 690`, primer card en `x30`, segundo en `x834`, tercero en `x1638`.
- Ajuste aplicado: mobile usa step `335px`, tarjeta `315 x 445`, primer card en `x16`, segundo en `x351`, tercero en `x686`.
- Se agrego linea horizontal/ticks de timeline con CSS para respetar `Conjunto de Lineas`.
- Se corrigieron los botones prev/next del partial a entidades HTML `&larr;` y `&rarr;` para evitar mojibake.

Desktop `1512px`:
- `document height`: `5684`
- `overflow horizontal`: `0`
- `broken images`: `0`
- `figma src`: `0`
- `upload src`: `60`
- `bad encoding chars`: `0`
- `hero`: `y0 h1200`
- `reasons`: `y1200 h555`
- `iconic`: `y1755 h1205`
- `timeline`: `y1981 h829`
- `timeline cards`: `x30/x834/x1638`, `w780 h690`
- `facts`: `y2960 h1872`
- `promise card`: `y3145 h522`
- `footer`: `y4832 h852`

Mobile `402px`:
- `document height`: `5711` sin home indicator iOS
- `overflow horizontal`: `0`
- `broken images`: `0`
- `figma src`: `0`
- `upload src`: `60`
- `bad encoding chars`: `0`
- `hero`: `y0 h1090`
- `reasons`: `y1090 h480`
- `iconic`: `y1570 h793`
- `timeline`: `y1703 h560`
- `timeline cards`: `x16/x351/x686`, `w315 h445`
- `facts`: `y2363 h2489`
- `promise card`: `y2686 h520`
- `footer`: `y4852 h859`

## Checks ejecutados

- `php -l inc/about.php`
- `php -l page-quienes-somos.php`
- `php -l template-parts/about/about-hero.php`
- `php -l template-parts/about/about-iconic-projects.php`
- `php -l template-parts/about/about-promise-awards-team.php`
- `node --check assets/js/theme.js`
- `git diff --check`
- `rg "figma.com/api/mcp|figma.com/design" inc template-parts assets/css/theme.css assets/js/theme.js page-quienes-somos.php`

Resultado:
- Sin errores de sintaxis PHP.
- Sin errores JS.
- Sin trailing whitespace.
- Sin URLs de Figma en codigo de la pagina.
- `git diff --check` solo mostro avisos CRLF esperados de Windows.

## Notas para siguiente agente

- No usar Git todavia.
- Mantener todo editable desde SCF.
- El PHP CLI local no carga `mysqli`; para tocar BD se uso el `mysql.exe` de LocalWP.
- Si se hace QA visual de 5.5, comparar contra Figma screenshots y no contra aproximaciones.
- Si se requiere `pixel-perfect` mas fino, priorizar tipografia/recortes visuales sobre refactors.
