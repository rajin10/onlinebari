# Google Sheets order logging (Apps Script webhook)

Successful orders (with fraud analytics) are POSTed to a Google Apps Script Web
App that appends a row to your sheet. No Google OAuth, service account, or extra
Composer package is required — the script runs as the sheet owner.

The integration **no-ops** until a webhook URL is configured, so nothing breaks
before setup.

## 1. Create the sheet + script

1. Create a Google Sheet (e.g. **Onlinebari Orders**).
2. `Extensions → Apps Script`, delete the boilerplate, paste the script below.
3. Set `SECRET` to a long random string (must match `GOOGLE_SHEETS_SECRET`).

```javascript
const SHEET_NAME = 'Orders';
const SECRET = 'CHANGE-ME-to-a-long-random-string';

const HEADERS = [
  'Timestamp', 'Order ID', 'Invoice', 'Full Name', 'Phone', 'Address',
  'Products', 'Total Price', 'Payment Method',
  'Total Orders', 'Successful Orders', 'Pending Orders', 'Cancelled Orders',
  'Success Rate (%)', 'Risk Level',
];

function doPost(e) {
  try {
    const body = JSON.parse(e.postData.contents);

    if (SECRET && body.secret !== SECRET) {
      return json({ ok: false, error: 'unauthorized' });
    }

    const ss = SpreadsheetApp.getActiveSpreadsheet();
    let sheet = ss.getSheetByName(SHEET_NAME);
    if (!sheet) { sheet = ss.insertSheet(SHEET_NAME); }
    if (sheet.getLastRow() === 0) { sheet.appendRow(HEADERS); }

    // De-dupe on Order ID (column B) so retries never create duplicates.
    const ids = sheet.getRange(2, 2, Math.max(sheet.getLastRow() - 1, 0), 1).getValues();
    for (let i = 0; i < ids.length; i++) {
      if (String(ids[i][0]) === String(body.order_id)) {
        return json({ ok: true, duplicate: true });
      }
    }

    sheet.appendRow([
      body.timestamp || new Date(),
      body.order_id, body.invoice, body.full_name, "'" + (body.phone || ''),
      body.address, body.products, body.total_price, body.payment_method,
      body.total_orders, body.success_orders, body.pending_orders, body.cancelled_orders,
      body.success_rate, body.risk_level,
    ]);

    return json({ ok: true });
  } catch (err) {
    return json({ ok: false, error: String(err) });
  }
}

function json(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
```

## 2. Deploy

1. `Deploy → New deployment → Web app`.
2. **Execute as:** Me. **Who has access:** Anyone.
3. Copy the `/exec` Web App URL.

## 3. Configure the app

Either add to `.env`:

```
GOOGLE_SHEETS_WEBHOOK_URL=https://script.google.com/macros/s/XXXX/exec
GOOGLE_SHEETS_SECRET=the-same-long-random-string
```

…or set the DB setting `GOOGLE_SHEETS_WEBHOOK_URL` (the DB value wins, so you can
change it without a redeploy). The secret is read from config only.

Run `php artisan config:clear` after editing `.env`.

## Notes

- Logging runs **after the HTTP response** (`dispatchAfterResponse`), so checkout
  is never slowed down or blocked by Google.
- Failures are logged to `storage/logs` only — they can never break an order.
- Fraud columns (Total/Successful/Pending/Cancelled/Success Rate/Risk Level) come
  from the courier-history check that runs at the same time.
