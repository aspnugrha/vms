<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kartu Akses</title>
  <style>
    body {
      font-family: "Inter", Arial, sans-serif;
      background: #f3f4f6;
      padding: 40px;
      display: flex;
      justify-content: center;
    }

    .card-container {
      background: #ffffff;
      width: 420px;
      border-radius: 16px;
      padding: 24px;
      display: flex;
      gap: 24px;
      box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.08);
      border: 1px solid #e5e7eb;
    }

    /* QR Box */
    .qr-box {
      width: 160px;
      height: 160px;
      border-radius: 12px;
      background: #f9fafb;
      display: flex;
      justify-content: center;
      align-items: center;
      border: 1px solid #e5e7eb;
    }

    /* Data Sisi Kanan */
    .info {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: 6px;
    }

    .info h2 {
      margin: 0;
      font-size: 20px;
      font-weight: 600;
      color: #111827;
    }

    .info .label {
      font-size: 12px;
      color: #6b7280;
      margin-bottom: -2px;
    }

    .info .value {
      font-size: 14px;
      color: #111827;
      margin-bottom: 8px;
    }

    .joined {
      font-size: 12px;
      color: #6b7280;
      margin-top: 4px;
      border-top: 1px solid #e5e7eb;
      padding-top: 8px;
    }
  </style>

  <!-- QR Code Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

  <div class="card-container">
    <div class="qr-box" id="qrcode"></div>

    <div class="info">
      <h2>{{ $data->name }}</h2>

      <div>
        <div class="label">Email</div>
        <div class="value">{{ $data->email }}</div>
      </div>

      <div>
        <div class="label">Phone</div>
        <div class="value">{{ $data->phone_number }}</div>
      </div>

      <div class="joined">Bergabung sejak: {{ date('d F Y', strtotime($data->created_at)) }}</div>
    </div>
  </div>

  <script>
    // Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
      text: "{{ $data->code }}",
      width: 150,
      height: 150,
    });

    window.print()
  </script>

</body>
</html>
