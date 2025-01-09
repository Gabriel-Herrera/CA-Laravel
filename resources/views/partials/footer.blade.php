<footer class="bg-dark text-white mt-4">
    <div class="container py-4">
        <div class="row">
            <div class="col-md-6">
                <h5>Mi Tienda</h5>
                <p>Tu destino para compras en línea.</p>
            </div>
            <div class="col-md-3">
                <h5>Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white">Inicio</a></li>
                    <li><a href="#" class="text-white">Sobre Nosotros</a></li>
                    <li><a href="#" class="text-white">Contacto</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Síguenos</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Facebook</a></li>
                    <li><a href="#" class="text-white">Twitter</a></li>
                    <li><a href="#" class="text-white">Instagram</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center py-3">
        <p>&copy; {{ date('Y') }} Laf Store. Todos los derechos reservados.</p>
    </div>
</footer>