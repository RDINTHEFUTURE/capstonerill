# TODO - Fix Faktur Pajak Border Alignment

- [ ] Read and identify current Faktur Pajak table markup/CSS sources (preview + pdf).
- [ ] Refactor `resources/views/invoices/preview.blade.php` Faktur Pajak layout:
  - [ ] Replace separate items-table + summary-table with a single unified grid table.
  - [ ] Enforce fixed column count per row; remove unnecessary colspan/rowspan.
  - [ ] Ensure `border-collapse: collapse`, single-source border drawing, and consistent widths.
  - [ ] Remove/avoid fixed “blank-row-height” hacks that cause row-height mismatch.
- [ ] Refactor `resources/views/invoices/pdf.blade.php` to match the same Faktur Pajak HTML/CSS structure as preview.
- [ ] Audit and align CSS rules in `public/css/invoice-preview.css` (and `invoice-pdf.css` if used).
- [ ] Test rendering in browser preview, print preview, and generated PDF; compare border intersections.
- [ ] Produce before/after summary and explain which HTML/CSS issues caused asymmetrical borders.

