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
