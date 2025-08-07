document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('formProducto');

  formulario.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Obtener valores
    const datos = {
      codigo: document.getElementById('codigo').value.trim(),
      nombre: document.getElementById('nombre').value.trim(),
      bodega: document.getElementById('bodega').value,
      sucursal: document.getElementById('sucursal').value,
      moneda: document.getElementById('moneda').value,
      precio: document.getElementById('precio').value.trim(),
      materiales: Array.from(document.querySelectorAll('input[name="materiales[]"]:checked')).map(c => c.value),
      descripcion: document.getElementById('descripcion').value.trim()
    };

    // Validaciones sincronas
    const validaciones = [
      validarCampo(() => validarCodigo(datos.codigo)),
      validarCampo(() => validarNombre(datos.nombre)),
      validarCampo(() => validarBodega(datos.bodega)),
      validarCampo(() => validarSucursal(datos.sucursal)),
      validarCampo(() => validarMoneda(datos.moneda)),
      validarCampo(() => validarPrecio(datos.precio)),
      validarCampo(() => validarMateriales(datos.materiales)),
      validarCampo(() => validarDescripcion(datos.descripcion)),
    ];

    const errores = validaciones.filter(msg => msg !== null);

    if (errores.length > 0) {
      alert(errores.join('\n'));
      return;
    }

    // Validacion asincrona: codigo unico
    if (await verificarCodigoExistente(datos.codigo)) {
      alert("El código del producto ya está registrado.");
      return;
    }

    // Envio al servidor
    try {
      const response = await fetch('php/guardar_producto.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
      });

      const textoRespuesta = await response.text();
      console.log("Respuesta cruda del servidor:", textoRespuesta);

      const result = JSON.parse(textoRespuesta);

      if (result.success) {
        alert('Producto guardado correctamente.');
        formulario.reset();
        const sucursalSelect = document.getElementById('sucursal');
        sucursalSelect.innerHTML = '<option value=""></option>';
        sucursalSelect.disabled = true;
      } else {
        alert('Error al guardar el producto: ' + (result.error || 'Error desconocido.'));
      }

    } catch (error) {
      console.error("Error al comunicarse con el servidor:", error);
      alert('Error al comunicarse con el servidor.');
    }
  });

  // ---------- FUNCIONES DE VALIDACIÓN ----------

  const validarCampo = (fn) => {
    try {
      return fn();
    } catch {
      return "Error de validación desconocido.";
    }
  };

  const validarCodigo = (codigo) => {
    if (codigo === '') return "El código del producto no puede estar en blanco.";
    if (!/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{5,15}$/.test(codigo)) return "El código del producto debe contener letras y números (5-15 caracteres).";
    return null;
  };

  const validarNombre = (nombre) => {
    if (nombre === '') return "El nombre del producto no puede estar en blanco.";
    if (nombre.length < 2 || nombre.length > 50) return "El nombre del producto debe tener entre 2 y 50 caracteres.";
    return null;
  };

  const validarBodega = (bodega) => {
    return bodega === '' ? "Debe seleccionar una bodega." : null;
  };

  const validarSucursal = (sucursal) => {
    return sucursal === '' ? "Debe seleccionar una sucursal." : null;
  };

  const validarMoneda = (moneda) => {
    return moneda === '' ? "Debe seleccionar una moneda." : null;
  };

  const validarPrecio = (precio) => {
    if (precio === '') return "El precio del producto no puede estar en blanco.";
    if (!/^\d+(\.\d{1,2})?$/.test(precio)) return "El precio debe ser un número positivo con hasta dos decimales.";
    return null;
  };

  const validarMateriales = (materiales) => {
    return materiales.length < 2 ? "Debe seleccionar al menos dos materiales." : null;
  };

  const validarDescripcion = (descripcion) => {
    if (descripcion === '') return "La descripción del producto no puede estar en blanco.";
    if (descripcion.length < 10 || descripcion.length > 1000) return "La descripción debe tener entre 10 y 1000 caracteres.";
    return null;
  };

  // ---------- FUNCIONES DE APOYO ----------

  const verificarCodigoExistente = async (codigo) => {
    try {
      const response = await fetch(`php/verificar_codigo.php?codigo=${encodeURIComponent(codigo)}`);
      if (!response.ok) throw new Error("Error al verificar el código.");
      const data = await response.json();
      return data.existe === true;
    } catch (error) {
      console.error("Error al verificar el código:", error);
      alert("Error al verificar el código. Intenta nuevamente.");
      return true; // asume que existe para prevenir conflictos
    }
  };
});
