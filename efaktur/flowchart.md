flowchart TD
  A[User opens app
(/)] --> B[HomeController@index
redirect to invoices.index]

  B --> C[Invoices list
GET /invoices]
  C -->|Click Detail| D[InvoiceController@show
GET /invoices/{id}]
  C -->|Click Edit| E[InvoiceController@edit
GET /invoices/{id}/edit]
  C -->|Click + Buat Invoice| F[InvoiceController@create
GET /invoices/create]
  C -->|Pagination / refresh| C

  F --> G[Invoice form (create view)]
  G --> H[POST /invoices (store)]
  H --> I{Validate request
(required: nomor unique, tanggal, items[]
+ seller/buyer fields)}
  I -->|Invalid| G
  I -->|Valid| J[Compute each item subtotal
subtotal=max((harga*qty)-diskon,0)
Sum total]
  J --> K[Build QR payload
base64(JSON with nomor, tanggal,
penjual, pembeli, total, currency)]
  K --> L[Create Invoice record
store qr_payload]
  L --> M[Create Invoice items
invoice->items()->create()]
  M --> N[Redirect to Invoice detail
route('invoices.show')]

  E --> O[Invoice form (edit view)]
  O --> P[PUT /invoices/{id} (update)]
  P --> Q{Validate request
(nomor unique except current)}
  Q -->|Invalid| O
  Q -->|Valid| R[Recompute total + item subtotals]
  R --> S[Build new QR payload]
  S --> T[Update Invoice fields
replace qr_payload]
  T --> U[Refresh items
invoice->items()->delete();
then create rows]
  U --> V[Redirect to Invoice detail]

  D --> W[Invoice detail actions]
  W -->|Preview Faktur| X[GET /invoices/{id}/preview
InvoicePdfController@preview]
  X --> X1[Render preview view
invoices.preview]

  W -->|Unduh PDF| Y[GET /invoices/{id}/pdf
InvoicePdfController@show]
  Y --> Y1[Load invoice + items]
  Y1 --> Y2[Generate QR as PNG -> base64]
  Y2 --> Y3[Render PDF via dompdf
invoices.cetakfaktur]
  Y3 --> Y4[Download faktur-{nomor}.pdf]

  W -->|Hapus| Z[DELETE /invoices/{id}
InvoiceController@destroy]
  Z --> Z1[Delete invoice]
  Z1 --> B

  W -->|QR Image| ZA[GET /invoices/{id}/qr
InvoiceController@qr]
  ZA --> ZA1[Ensure qr_payload exists
(build if missing)]
  ZA1 --> ZA2[Set QR data = route('invoices.pdf', {id})]
  ZA2 --> ZA3[Generate QR PNG -> return image/png]
