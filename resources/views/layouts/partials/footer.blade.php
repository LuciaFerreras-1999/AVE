<footer id="footer" style="background-color: rgba(38, 70, 83, 0.9); color: #ecfffb; padding: 2rem 1.5rem; margin-top: 2.5rem; margin-left: 220px;">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">

        <!-- Contacto -->
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #f4a261;">Contacto</h3>
            <p>Email: 
                <a href="mailto:admin@aveweb.com" style="color: #ecfffb; text-decoration: none;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'">
                    admin@aveweb.com
                </a>
            </p>
            <p>Teléfono: 
                <a href="tel:+34123456789" style="color: #ecfffb; text-decoration: none;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'">
                    +34 123 456 789
                </a>
            </p>
        </div>

        <!-- Redes sociales -->
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #f4a261;">Síguenos</h3>
            <div style="display: flex; gap: 24px; margin-top: 0.5rem;">
                <a href="https://facebook.com" target="_blank" style="color: #ecfffb;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com" target="_blank" style="color: #ecfffb;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'"><i class="fab fa-instagram"></i></a>
                <a href="https://twitter.com" target="_blank" style="color: #ecfffb;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <!-- Legal -->
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem; color: #f4a261;">Legal</h3>
            <p>&copy; {{ date('Y') }} AVE. Todos los derechos reservados.</p>
            <p>
                <a href="#" onclick="mostrarModal('privacidad'); return false;" style="color: #ecfffb; text-decoration: none;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'">
                    Política de privacidad
                </a>
            </p>
            <p>
                <a href="#" onclick="mostrarModal('terminos'); return false;" style="color: #ecfffb; text-decoration: none;" onmouseover="this.style.color='#f4a261'" onmouseout="this.style.color='#ecfffb'">
                    Términos y condiciones
                </a>
            </p>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0, 0, 0, 0.6); text-align: center; padding-top: 100px; z-index: 9999;">
        <div style="background: white; color: black; padding: 20px; width: 80%; max-width: 600px; margin: auto; border-radius: 10px; position: relative;">
            <span onclick="cerrarModal()" style="position: absolute; top: 10px; right: 15px; cursor: pointer; font-size: 1.25rem;">❌</span>
            <div id="modal-content" style="text-align: left;"></div>
        </div>
    </div>

    <!-- Script -->
    <script>
        function mostrarModal(tipo) {
            const contenido = {
                privacidad: `
                    <h3 style="color: #264653;">Política de privacidad</h3>
                    <p>En AVE valoramos tu privacidad. Los datos personales que proporciones (nombre, correo electrónico, prendas publicadas, etc.) se utilizan únicamente para el funcionamiento de la plataforma y no serán compartidos con terceros sin tu consentimiento.</p>
                    <p>Solo el personal autorizado puede acceder a la información sensible y se han implementado medidas de seguridad como sesiones cifradas, autenticación y roles de usuario.</p>
                    <p>Puedes solicitar la eliminación de tu cuenta y datos personales en cualquier momento.</p>
                `,
                terminos: `
                    <h3 style="color: #264653;">Términos y condiciones</h3>
                    <p>El uso de AVE implica la aceptación de estas condiciones:</p>
                    <ul>
                        <li>Los usuarios deben publicar prendas reales y propias.</li>
                        <li>No está permitido el contenido ofensivo o fraudulento.</li>
                        <li>El equipo de administración se reserva el derecho a suspender cuentas en caso de abusos, reportes o violaciones de normas.</li>
                        <li>AVE no se responsabiliza por transacciones realizadas fuera de la plataforma.</li>
                    </ul>
                    <p>El objetivo es crear una comunidad segura, ecológica y colaborativa.</p>
                `
            };
            document.getElementById("modal-content").innerHTML = contenido[tipo];
            document.getElementById("modal").style.display = "block";
        }

        function cerrarModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>
</footer>
