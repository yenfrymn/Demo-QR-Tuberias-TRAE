import { describe, it, expect } from 'vitest'
import api from './api'

describe('api interceptors', () => {
  it('sets Authorization when token present', async () => {
    localStorage.setItem('token', 'TEST')
    const req = await api.get('/api/health', { validateStatus: () => true })
    expect(req.config.headers?.Authorization).toBe('Bearer TEST')
  })
})
