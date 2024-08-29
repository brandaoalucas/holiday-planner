import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import App from './App.jsx'
import './index.css'

import { RouterProvider } from 'react-router-dom'
import route from './router.jsx'
import { ContextProvider } from './contexts/ContextProvider.jsx'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <ContextProvider>
      <RouterProvider router={route} />
    </ContextProvider>
  </StrictMode>,
)
