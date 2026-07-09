---
version: 1.0.0
name: Little Latte Cafe Visual System
description: A sophisticated, print-inspired design system using a neutral "latte" palette, stark charcoal typography, and geometric diamond motifs.
colors:
  background: "#efeee6"
  foreground: "#252525"
  muted: "rgba(37, 37, 37, 0.7)"
  border: "rgba(37, 37, 37, 0.2)"
  accent: "#efeee6"
typography:
  display:
    family: "Sans-Serif"
    weight: "300 (Light)"
    case: "uppercase"
    tracking: "-0.02em (tight)"
  heading:
    family: "Sans-Serif"
    weight: "300 (Light)"
    case: "uppercase"
    tracking: "-0.02em"
  body:
    family: "Sans-Serif"
    size: "0.875rem (14px)"
    lineHeight: "1.625"
  label:
    family: "Sans-Serif"
    size: "0.75rem (12px)"
    case: "uppercase"
    tracking: "tight"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "32px"
  xl: "64px"
  container: "112rem"
rounded:
  none: "0px"
  full: "999px"
  sm: "4px"
components:
  nav:
    style: "minimalist-inline"
    alignment: "centered-links"
    spacing: "gap-9"
  hero:
    layout: "grid-three-column-header"
    imageStyle: "object-cover-darkened"
  buttons:
    primary: "border-outline-rect"
    secondary: "circle-action-icon"
  cards:
    product: "vertical-stack-no-border"
    stat: "large-display-number"
  tables:
    menu: "border-bottom-row-only"
motion:
  hover: "opacity-fade-transition"
  icon: "translate-tr-on-hover"
---
## Overview
This design system mimics the feel of a premium lifestyle magazine. It uses extreme whitespace, massive typography, and custom geometric textures to establish a sense of modern craft and local soul.

## Colors
The palette is intentionally limited to a two-tone interaction between a warm off-white (`#efeee6`) and a deep charcoal (`#252525`). This high contrast ensures maximum legibility and an editorial aesthetic. Transparency is used exclusively for borders and secondary body text.

## Typography
Typography is the primary visual driver. All headings must be Light weight (300) and Uppercase. The system relies on tight letter-spacing and tight line-heights for a "compacted" professional look. Display text often uses large viewport-based sizing (e.g., `16vw`) or explicit scales from `4xl` to `7xl`.

## Spacing
A rigid grid system is used with specific paddings of `5`, `8`, or `14` units on the main container. Vertical section spacing is generous, typically `py-16` to `py-24` (64px to 96px), to create a slow-scrolling "boutique" experience.

## Layout
The system uses asymmetrical CSS Grids. Major sections utilize specific ratios like `1fr 1.15fr 12rem` or `1.2fr 1fr`. Layering is achieved via absolute positioning of "stroke text" that overlaps background images, creating a three-dimensional depth without using shadows.

## Elevation & Depth
Depth is created through photographic treatments and text overlays rather than shadows. Images are frequently desaturated and darkened (`brightness-[0.62]`) to allow light-colored stroke-text or white UI elements to sit on top without losing legibility.

## Shapes
- **Hard Edges**: Most images and layout containers have 0px radius.
- **Circular Actions**: Primary call-to-actions (Order Now, Instagram) are perfect circles with thin 1px borders.
- **Diamonds**: 45-degree rotated squares are used as a recurring geometric motif and brand pattern.

## Components
- **Circular CTA**: A `size-32` border-only circle containing an icon and centered text.
- **Menu Rows**: Horizontal grid rows with a `0.5` opacity bottom border and two-column distribution (Item name left, Description/Price right).
- **Pattern Grids**: Small grids of `size-7` or `size-16` squares (solid charcoal or background-colored) used as section breaks or decorative anchors.

## Motion
Interactions are subtle and purposeful. Icon buttons transition using `group-hover` to move up and right. Hover states on links utilize simple opacity shifts (0.6) to indicate interactivity without disrupting the minimalist aesthetic.

## Do's and Don'ts
- **Do**: Use heavy tracking-tight on all uppercase text.
- **Do**: Ensure all images have high contrast and slightly reduced saturation.
- **Don't**: Use drop shadows or gradients.
- **Don't**: Introduce a third primary color; stick to the Latte and Charcoal pairing.

## Accessibility
- Maintain a high contrast ratio between `#252525` and `#efeee6`.
- All functional icons (Lucide) must include `aria-label` attributes on their parent buttons.
- Use `antialiased` font-smoothing to ensure light-weight text remains legible on dark backgrounds.