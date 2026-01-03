-- create_db.sql
PRAGMA foreign_keys = ON;

-- Table to store staff/users
CREATE TABLE IF NOT EXISTS tbl_staff (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    nama TEXT DEFAULT NULL,
    role TEXT NOT NULL DEFAULT 'user',     -- e.g. admin, admin_ict, user
    password_status TEXT NOT NULL DEFAULT 'not_changed', -- or 'changed'
    created_at TEXT DEFAULT (datetime('now')),
    updated_at TEXT DEFAULT (datetime('now'))
);

-- Example inserts (replace hashed password later)
INSERT INTO tbl_staff (username, password, nama, role, password_status) VALUES
('admin', 'plain_or_hashed_password_here', 'Administrator', 'admin', 'changed'),
('staff1', 'plain_or_hashed_password_here', 'Siti binti Ahmad', 'user', 'not_changed');
