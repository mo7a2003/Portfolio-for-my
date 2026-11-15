import axios from 'axios'

// Resolve and normalize VITE_API_BASE_URL (ensuring no trailing slash issues)
const rawBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api'
export const API_BASE_URL = rawBaseUrl.replace(/\/+$/, '')

// Create configured Axios client instance
export const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 10000,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

// Request Interceptor: Attach Sanctum Bearer token & handle FormData headers
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    // If sending FormData, let browser set Content-Type with multipart boundary
    if (config.data instanceof FormData) {
      delete config.headers['Content-Type']
    }

    return config
  },
  (error) => {
    console.error('[apiClient] Request Configuration Error:', error)
    return Promise.reject(error)
  }
)

// Response Interceptor: Extract payload and provide structured error diagnostics
apiClient.interceptors.response.use(
  (response) => {
    return response.data
  },
  (error) => {
    const status = error.response ? error.response.status : null
    const requestUrl = error.config ? `${error.config.method?.toUpperCase()} ${error.config.baseURL || ''}${error.config.url || ''}` : 'Unknown URL'

    // Log detailed diagnostic info for debugging pending / failed requests
    if (error.code === 'ECONNABORTED') {
      console.error(`[apiClient] Request timed out (${requestUrl}):`, error.message)
    } else if (!error.response) {
      console.error(`[apiClient] Network / CORS Error (${requestUrl}) — Ensure Laravel backend is running at ${API_BASE_URL}:`, error.message)
    } else {
      console.error(`[apiClient] HTTP ${status} Error (${requestUrl}):`, error.response.data)
    }

    // Unauthorized - redirect to login if attempting protected admin access
    if (status === 401) {
      const isLoginRequest = error.config?.url?.includes('/admin/login')
      if (!isLoginRequest) {
        localStorage.removeItem('auth_token')
        if (window.location.pathname.startsWith('/admin') && window.location.pathname !== '/admin/login') {
          window.location.href = '/admin/login'
        }
      }
    }

    // Extract human-readable error message from Laravel response
    const data = error.response?.data
    let message = data?.message || error.message || 'An unexpected network error occurred.'

    // If Laravel returned 422 validation errors, append first validation message
    if (status === 422 && data?.errors) {
      const firstKey = Object.keys(data.errors)[0]
      if (firstKey && data.errors[firstKey].length) {
        message = data.errors[firstKey][0]
      }
    }

    const enhancedError = new Error(message)
    enhancedError.status = status
    enhancedError.response = error.response
    enhancedError.errors = data?.errors || null
    return Promise.reject(enhancedError)
  }
)

export default apiClient
