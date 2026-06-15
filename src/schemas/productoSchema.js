import * as yup from 'yup' 

export const productoSchema = yup.object({ 
  nombre: yup.string() 
    .required('El nombre es obligatorio') 
    .min(3, 'Mínimo 3 caracteres') 
    .max(100, 'Máximo 100 caracteres'), 

  precio: yup.number() 
    .typeError('Debe ser un número') 
    .required('El precio es obligatorio') 
    .positive('El precio debe ser mayor a cero'), 

  stock: yup.number() 
    .typeError('Debe ser un número entero') 
    .integer('Debe ser un número entero') 
    .min(0, 'El stock no puede ser negativo'), 

  descripcion: yup.string().max(500, 'Máximo 500 caracteres').optional(), 
}) 