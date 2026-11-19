# SomaliLingua — local development build

SomaliLingua is a PHP/MySQL learning platform intended to run locally with XAMPP. The interface is built with separate HTML/PHP templates, CSS stylesheets and vanilla JavaScript. No framework or package manager is required.

## 1. Requirements

- Windows with XAMPP installed
- Apache and MySQL running from the XAMPP Control Panel
- PHP 8.1+ recommended (PDO MySQL and Fileinfo extensions enabled)
- A modern browser

## 2. Install on XAMPP

1. Extract the `SomaliLingua` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin/`.
4. Choose **Import**, select `SomaliLingua/database/somalingua.sql`, and run the import. The SQL file creates the `somalingua` database and starter content.
5. Import `SomaliLingua/database/lesson-content-update.sql` to add the expanded learning path. This update can also be imported into an existing installation without deleting accounts or progress.
6. Open `config/config.php` and check `BASE_URL`. It is set to `/SomaliLingua` for the folder name used in this package.
7. Open `config/database.php`. The defaults are host `127.0.0.1`, database `somalingua`, user `root`, and a blank password. Change these if your MySQL setup is different.
8. Visit `http://localhost/SomaliLingua/`.

## 3. Create an administrator account

1. Register a normal account using the website.
2. In phpMyAdmin, select the `somalingua` database and open the SQL tab.
3. Run this query, replacing the email with the account you registered:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

4. Sign out and sign in again. The Admin link will appear in the navigation.

## 4. Included functionality

- Responsive public homepage and navigation
- Student registration, login, logout and profile name update
- Password hashing with PHP `password_hash()` / `password_verify()`
- Session-based authentication and role checks
- PDO database access and prepared statements
- Alphabet explorer with optional audio playback
- Published lessons and lesson completion tracking
- Vocabulary cards and Somali–English dictionary search
- Multiple-choice quizzes scored on the server and saved to quiz history
- Admin dashboard, lesson management, alphabet/audio management, vocabulary management and quiz-question management
- CSRF tokens on state-changing forms and progress requests
- Audio upload validation (MP3, WAV, OGG and M4A; 10 MB maximum)

## 5. Where to add your recordings

The admin pages allow you to upload recordings for alphabet letters and vocabulary. Audio is stored under `assets/audio/alphabet/` or `assets/audio/vocabulary/`. The browser will show an audio control only when a recording has been uploaded.

## 6. Important project notes

- The starter database contains sample learning text and vocabulary. Review Somali spelling, dialect choices, translations and example sentences with fluent speakers before presenting the content as a teaching authority.
- The alphabet module uses 26 base letters (21 consonants and five vowels); `kh`, `dh` and `sh` are digraph letters. Long vowels are represented by doubled vowels, not separate base letters.
- This is a local academic/demo build. Before public hosting, configure HTTPS, stronger database credentials, backups, upload scanning, rate limiting, and production error logging.
- No admin user is hard-coded into the database. Promote your own registered account using the SQL statement above.

## 7. Troubleshooting

**Database connection failed:** Ensure MySQL is running, the database was imported, and credentials in `config/database.php` match your XAMPP setup.

**404 / broken links:** Confirm the folder is exactly `C:\xampp\htdocs\SomaliLingua\` and `BASE_URL` is `/SomaliLingua`.

**Audio upload does not work:** Confirm PHP's `file_uploads` setting is enabled, the audio folders are writable, and the file is below 10 MB. The PHP Fileinfo extension is used to validate file type.

**Apache cannot start:** Another application may be using port 80. Check the XAMPP Control Panel logs or configure Apache to use another port, then use that port in the browser URL.
