import { mount, flushPromises } from '@vue/test-utils' 
import { vi } from 'vitest' 
import axios from 'axios' 
import ProductosList from '@/views/ProductosList.vue' 

vi.mock('axios') 

it('carga y muestra productos desde la API', async () => { 
  axios.get.mockResolvedValue({ 
    data: { data: [ 
      { id: 1, nombre: 'Mouse', precio: 25.00 }, 
    ]}, 
  }) 

  const wrapper = mount(ProductosList) 
  await flushPromises() 

  expect(axios.get).toHaveBeenCalledWith('/api/productos') 
  expect(wrapper.text()).toContain('Mouse') 
})