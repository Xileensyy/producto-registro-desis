-- Tabla bodega
CREATE TABLE bodegas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla sucursales
CREATE TABLE sucursales (
    id SERIAL PRIMARY KEY,
    bodega_id INTEGER REFERENCES bodegas(id) ON DELETE CASCADE,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla moneda
CREATE TABLE monedas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla materiales
CREATE TABLE materiales (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla productos
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,

    codigo_producto VARCHAR(15) NOT NULL UNIQUE,
    nombre_producto VARCHAR(50) NOT NULL,
    bodega_id INTEGER NOT NULL REFERENCES bodegas(id),
    sucursal_id INTEGER NOT NULL REFERENCES sucursales(id),
    moneda_id INTEGER NOT NULL REFERENCES monedas(id),
    precio NUMERIC(10,2) NOT NULL CHECK (precio > 0),
    descripcion TEXT NOT NULL CHECK (char_length(descripcion) BETWEEN 10 AND 1000)
);

-- Relacion N:N entre productos y materiales
CREATE TABLE producto_material (
    producto_id INTEGER REFERENCES productos(id) ON DELETE CASCADE,
    material_id INTEGER REFERENCES materiales(id) ON DELETE CASCADE,
    PRIMARY KEY (producto_id, material_id)
);
