-- datos_iniciales.sql
INSERT INTO bodegas (nombre) VALUES ('Bodega 1'), ('Bodega 2'), ('Bodega 3');

INSERT INTO sucursales (bodega_id, nombre) VALUES 
  (1, 'Sucursal A1'),
  (1, 'Sucursal A2'),
  (2, 'Sucursal B1'),
  (3, 'Sucursal C1');

INSERT INTO monedas (nombre) VALUES ('DÓLAR'), ('PESO'), ('EURO');

INSERT INTO materiales (nombre) VALUES ('Madera'), ('Metal'), ('Plástico') , ('Vidrio') , ('Textil');
