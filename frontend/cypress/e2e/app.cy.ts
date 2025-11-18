describe('App E2E', () => {
  it('loads home and navigates to login', () => {
    cy.visit('/')
    cy.contains('Sistema de Tuberías QR')
    cy.contains('Login').click()
    cy.contains('Iniciar sesión')
  })
})