# MARCAN Figma Design Audit

Source file: `NEW-MARCAN-2--Copy-`
File key: `khZVAdpz6ZvY26Sqmo59D4`

## Pages and Frames

- Desktop page: `2045:235` / `DESKTOP_MARCAN`
- Mobile page: `2045:242` / `MOBILE_MARCAN`
- Assets page: `0:1` / `ASSETS`
- Desktop HOME frame: `8002:390`, `1512 x 5574`
- Mobile HOME frame: `8510:2529`, `402 x 5171`

## Header Nodes

Desktop header:
- Node: `8002:455`
- Size: `1512 x 67`
- Background: `#ffffff`
- Logo: `I8002:455;8002:585`, `110 x 22`, position `x=23 y=21`, fill `#4f4f4f`
- Menu label: `I8002:455;8002:586`, position `x=1428 y=19`, size `61 x 30`
- Menu typography: Inter Medium, `20px`, line-height `150%`, letter-spacing `1%`, color `#4f4f4f`
- Dropdown frame: `I8002:455;8002:587`, position `x=1321 y=64`, size `168 x 162`

Mobile header:
- Wrapper: `8523:2524`, `402 x 110`
- Header node: `9010:3144`, `402 x 54`, position `y=56` inside status wrapper
- Logo: `I9010:3144;9009:3117`, `100 x 20`, position `x=20 y=17`, fill `#4f4f4f`
- Menu group: `I9010:3144;9018:3643`, `94 x 34`, position `x=292 y=9`
- Menu typography: Inter Regular, `20px`, line-height `130%`, color `#4f4f4f`

## Hero Nodes for Next Approval Step

Desktop:
- HOME media/hero node: `8096:1801`
- Size: `1512 x 982`
- Base fill: `#eaeae8`
- Image child: `I8096:1801;8096:1156`, name `FACHADA DIAGONAL NOCTURNA_07_07_25 1`

Mobile:
- Hero section frame: `8523:2529`
- Size: `402 x 766`
- Layout: vertical, gap `25`
- Media frame: `8518:2862`, `402 x 580`
- Text/content frame: `8523:2530`, `402 x 161`

## Home Projects Block

Desktop departments:
- Section frame: `8300:2176`
- Left card: `8300:2171`
- Right card: `8300:2172`
- Card size: `990 x 824`
- Image size: `990 x 687`
- Data row size: `345 x 122`
- Section title: `8002:408`, `28px` Inter Light
- Section button: `8015:635`, label `Ver más departamentos`

Desktop offices:
- Section frame: `8300:2177`
- Left card: `8300:2173`
- Middle card: `8300:2174`
- Right card: `8300:2175`
- Section title: `8002:432`, `28px` Inter Light
- Section button: `8015:648`, label `Ver más oficinas`

Mobile departments:
- Section frame: `8523:2565`
- Card size: `315 x 593`
- Image size: `315 x 390`

Mobile offices:
- Section frame: `8549:2649`
- Card size: `315 x 593`
- Image size: `315 x 390`

Observed project assets:
- Llano Zapata 430: `4721aac4-6276-4ada-a8f4-6624a358831a`
- Costa de Lima: `6cf74827-e37b-4f43-9eac-f492992f2105`
- Time Aramburú: `53115681-5a32-474b-81e5-bac8271a616d`
- Time Angamos: `5debe8a6-c131-4c3f-a94b-08ce5c439331`
- Time Benavides: `f4650bb6-cdc3-48de-a331-d38b5358a6f9`

## Tokens Observed

- Primary yellow: `#ffcb05`
- Text gray: `#4f4f4f`
- Delivered section surface: `#f3f2f1`
- Hero image fallback: `#eaeae8`
- Header background: `#ffffff`
- Fonts: Inter and Bitter

## Footer Animation

Desktop footer node:
- Section node: `8002:391`
- Component node: `8002:536`
- Size: `1512 x 852`
- Background: `#ffcb05`
- Large brand vector: `9068:2987`, y `635`, height `125`
- Arrow vector: `9068:2985`, x `446`, y `572`, size `129 x 188`

Implementation note:
- Footer brand animation mirrors the preloader timing: brand moves in horizontally and the arrow rises into place after a delay.
- The SVGs assigned in the media library are Figma exports; their viewBoxes were corrected because the original downloaded SVGs contained the vector paths outside the visible bounds.

## Current Implementation Notes

- Header logo SVGs were exported from the actual Figma vector nodes, not recreated manually.
- Header CSS is mapped to the desktop and mobile coordinates above.
- The theme now expects SCF with ACF-compatible APIs and JSON sync.
- The previous manual property metabox implementation has been replaced by CPT + SCF field group registration.

## Header Visual Checklist

- [x] Desktop header height is 67px.
- [x] Desktop logo is 110 x 22 at 23px / 21px.
- [x] Desktop menu text is Inter Medium 20px at 23px from the right.
- [x] Mobile header height is 54px.
- [x] Mobile logo is 100 x 20 at 20px / 17px.
- [x] Mobile menu group is 94 x 34 at 16px from the right.
- [x] Departments and offices slider is driven by CPT `project` and ACF fields.
- [ ] Browser screenshot compared side-by-side against Figma desktop.
- [ ] Browser screenshot compared side-by-side against Figma mobile.
