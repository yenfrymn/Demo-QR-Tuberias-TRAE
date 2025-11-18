import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({ token: '', role: 'viewer' as 'admin'|'editor'|'viewer' }),
  actions: {
    setToken(t: string) { this.token = t },
    setRole(r: 'admin'|'editor'|'viewer') { this.role = r },
    clear() { this.token = ''; this.role = 'viewer' },
  },
})