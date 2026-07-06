# Upload File Endpoint

---

### 📝 Column Definitions (Migration)

| Column Name    | Data Type            | Additional Attributes           | Description                                                      | Example Value                                                           |
| :------------- | :------------------- | :------------------------------ | :--------------------------------------------------------------- | :---------------------------------------------------------------------- |
| **id_file**    | `BigInteger`         | `Primary Key`, `Auto Increment` | Unique identifier for the file record.                           | `1`                                                                     |
| **name**       | `String`             | -                               | Unique file name stored on the server (prefixed with timestamp). | `1719878400_financial_report.pdf`                                       |
| **path**       | `String`             | -                               | Absolute URL to access the file publicly.                        | `http://localhost:8000/storage/uploads/1719878400_financial_report.pdf` |
| **type**       | `String`             | -                               | Official _MIME Type_ of the file.                                | `application/pdf`                                                       |
| **size**       | `Integer` / `String` | -                               | Formatted, human-readable file size.                             | `2.4 MB`                                                                |
| **created_at** | `Timestamp`          | `Nullable`                      | Timestamp when the file was uploaded.                            | `2026-07-05 16:00:00`                                                   |
| **updated_at** | `Timestamp`          | `Nullable`                      | Timestamp when the file record was last updated.                 | `2026-07-05 16:00:00`                                                   |
| **deleted_at** | `Timestamp`          | `Nullable` (_Soft Deletes_)     | Timestamp when the file was logically deleted (not permanent).   | `null`                                                                  |

> ⚠️ **Important Note on the `size` Column:** > In your original draft, you used `$table->integer('size')`. However, inside the `uploadFile()` method, you are passing `$formattedSize` from `$this->formatSize($rawSize)`, which typically returns a string (e.g., `"2.4 MB"` or `"512 KB"`). To store formatted text like this without causing _SQL errors (Incorrect integer value)_, make sure to change the migration column type to **`$table->string('size')`**.

---

---

## ⚙️ File Upload Workflow (Flowchart)

This flowchart visualizes how a physical file is processed and stored in both the file system and the database:

1. **Request Intake**: The file is received through the `UploadFileRequest $file` object.
2. **File Renaming**: The file is renamed using the format `time() . '_' . $file->getClientOriginalName()` to prevent duplicate filenames on the server.
3. **Physical Storage**: The physical file is saved into the public disk storage directory (`storage/app/public/temp/`).
4. **Data Formatting**: The raw file size (in bytes) is converted into a human-readable format via `$this->formatSize()`.
5. **Database Persistence**: The `UploadFile` model instantiates a new record, maps the public asset URL (`asset('storage/' . $path)`), and saves the entry to the database.

---

# 📄 Dokumentasi Laravel Scheduler (Auto Clean Temporary Files)

Dokumentasi ini berisi panduan perintah (_commands_) untuk mengelola, mengecek, dan mengaktifkan fitur pembersihan file sementara secara otomatis pada project Laravel.

---

## 1. Perintah Pengujian (Manual)

Sebelum menjalankan sistem otomatis, Anda dapat memastikan bahwa logika penghapusan file di dalam kode `CleanUpTemporaryFiles` berfungsi dengan baik menggunakan perintah berikut:

```bash
php artisan app:clean-temp-files
```

- **Kegunaan:** Mengeksekusi penghapusan file di folder `storage/app/public/temp` yang usianya sudah lebih dari 24 jam saat itu juga.
- **Output jika berhasil:** Menampilkan daftar file yang dihanguskan (misal: `Deleted: temp/171999_document.pdf`).

---

## 2. Perintah Pengecekan Jadwal

Untuk melihat apakah perintah cleanup sudah terdaftar dalam sistem antrean scheduler Laravel beserta informasi waktu eksekusi selanjutnya (_Next Run_):

```bash
php artisan schedule:list
```

---

## 3. Mengaktifkan Scheduler (Cron Job)

Perintah `schedule:work` di atas hanya berjalan selama terminal masih aktif. Untuk memastikan pembersihan berjalan otomatis setiap menit di server produksi (tanpa perlu membuka terminal), Anda harus mendaftarkan _cron job_ ke sistem operasi server (Cron Tab).

### Langkah 1: Edit Cron Tab

Jalankan perintah berikut di terminal:

```bash
crontab -e
```

### Langkah 2: Tambahkan Baris Perintah

Gunakan format crontab untuk menjalankan perintah artisan setiap menit (atau sesuai kebutuhan):

```crontab
* * * * * cd /path/to/your/laravel/project && php artisan schedule:run >> /dev/null 2>&1
```

**Penjelasan:**

- `* * * * *`: Menjalankan perintah setiap **1 menit**.
- `cd /path/to/your/laravel/project`: Pindah ke direktori utama project Laravel Anda.
- `php artisan schedule:run`: Menjalankan semua task yang terdaftar di `app/Console/Kernel.php`.
- `>> /dev/null 2>&1`: Mengalihkan output (termasuk error) ke "null" agar tidak membanjiri log email server (opsional).

> ⚠️ **Penting:** Ganti `/path/to/your/laravel/project` dengan lokasi fisik folder project Anda di server (misal: `/home/rizky/lp-programming`).
