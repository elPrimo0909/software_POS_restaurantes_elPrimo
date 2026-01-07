-- SQLite schema inferred from application code
-- Run with: sqlite3 database.db < sqlite_schema.sql

PRAGMA foreign_keys = ON;

-- Users table used by login/registration
CREATE TABLE IF NOT EXISTS tbl_user (
  tbl_user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  first_name TEXT NOT NULL,
  last_name TEXT NOT NULL,
  contact_number TEXT,
  email TEXT UNIQUE,
  username TEXT UNIQUE NOT NULL,
  password TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_tbl_user_username ON tbl_user(username);
CREATE INDEX IF NOT EXISTS idx_tbl_user_email ON tbl_user(email);

-- Inventory / product list (frequently referenced as `lista7`)
CREATE TABLE IF NOT EXISTS lista7 (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  descripcion TEXT,      -- product description / name
  codigos TEXT,          -- alternative code / SKU
  codigo TEXT,           -- older code field seen in updates
  precio_compra REAL DEFAULT 0,
  precio_venta REAL DEFAULT 0,
  cantidad REAL DEFAULT 0,
  impuesto REAL DEFAULT 0,
  destacado INTEGER DEFAULT 0,
  imagen TEXT,
  logo TEXT
);

CREATE INDEX IF NOT EXISTS idx_lista7_descripcion ON lista7(descripcion);
CREATE INDEX IF NOT EXISTS idx_lista7_codigo ON lista7(codigo);

-- Customers (clients). Application uses `clientes2` and `clientes3` in several places.
CREATE TABLE IF NOT EXISTS clientes2 (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  empresa TEXT,          -- company / client name
  direccion TEXT,
  identificacion TEXT,   -- tax id / identification number
  telefono TEXT,
  email TEXT,
  ciudad TEXT
);

CREATE TABLE IF NOT EXISTS clientes3 (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  empresa TEXT,
  direccion TEXT,
  identificacion TEXT,
  telefono TEXT,
  email TEXT
);

-- Temporary client holder used by POS workflow
CREATE TABLE IF NOT EXISTS ostemporalclientes (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre TEXT,
  nit TEXT,
  direccion TEXT,
  email TEXT,
  telefono TEXT
);

-- Temporary order lines used across modules (generic temporary list)
CREATE TABLE IF NOT EXISTS ostemporal (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT,           -- product name or code
  come REAL,             -- quantity
  celu TEXT,             -- session id or reference
  num REAL,              -- price or numeric field
  estado TEXT,
  impuesto REAL DEFAULT 0
);

-- Temporary quotes / cart (used as `ostemporal_cotizaciones`)
CREATE TABLE IF NOT EXISTS ostemporal_cotizaciones (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT,
  come REAL,             -- quantity
  celu TEXT,             -- session id
  num REAL,              -- price
  estado TEXT,
  impuesto REAL DEFAULT 0,
  descuento REAL DEFAULT 0,
  mesa TEXT              -- optional table identifier (POS)
);

-- Temporary tables for mesas / tables in POS
CREATE TABLE IF NOT EXISTS ostemporal_mesas (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT
);

CREATE TABLE IF NOT EXISTS ostemporal_mesas2 (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT
);

-- Parking / payment temporary table
CREATE TABLE IF NOT EXISTS ostemporal_parqueadero (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT,
  num REAL
);

-- A small tmp table used by several ajax endpoints to store session cart items
CREATE TABLE IF NOT EXISTS tmp (
  id_tmp INTEGER PRIMARY KEY AUTOINCREMENT,
  id_producto INTEGER,   -- references lista7.id typically
  cantidad_tmp REAL,
  precio_tmp REAL,
  session_id TEXT
);

-- Generic helper table used by POS endpoints (production staging)
CREATE TABLE IF NOT EXISTS ostemporalproduccion (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  titulo TEXT,
  num REAL
);

-- Additional convenience indexes
CREATE INDEX IF NOT EXISTS idx_tmp_session ON tmp(session_id);
CREATE INDEX IF NOT EXISTS idx_ostemporal_celu ON ostemporal(celu);
CREATE INDEX IF NOT EXISTS idx_ostemporal_cotizaciones_celu ON ostemporal_cotizaciones(celu);

-- End of inferred schema
