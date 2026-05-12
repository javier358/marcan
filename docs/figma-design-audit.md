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

## Tokens Observed

- Primary yellow: `#ffcb05`
- Text gray: `#4f4f4f`
- Delivered section surface: `#f3f2f1`
- Hero image fallback: `#eaeae8`
- Header background: `#ffffff`
- Fonts: Inter and Bitter

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
- [ ] Browser screenshot compared side-by-side against Figma desktop.
- [ ] Browser screenshot compared side-by-side against Figma mobile.
