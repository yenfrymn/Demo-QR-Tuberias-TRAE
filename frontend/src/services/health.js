import axios from 'axios'

const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000'

export const healthService = {
  async checkHealth() {
    try {
      const response = await axios.get(`${API_URL}/api/health`)
      return response.data
    } catch (error) {
      return {
        status: 'error',
        message: 'Unable to connect to backend',
        timestamp: new Date().toISOString(),
        error: error.message
      }
    }
  },

  async checkFrontendHealth() {
    return {
      status: 'healthy',
      message: 'Frontend application is running',
      timestamp: new Date().toISOString(),
      environment: import.meta.env.MODE,
      version: '1.0.0'
    }
  }
}