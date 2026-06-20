import './bootstrap';

import Alpine from 'alpinejs';

import './bootstrap'; // Esto ya debería estar en tu archivo

// 1. Importamos la librería que acabamos de instalar
import DOMPurify from 'dompurify';

// 2. La asignamos al objeto window para poder usarla en cualquier vista Blade o script
window.DOMPurify = DOMPurify;

window.Alpine = Alpine;

Alpine.start();
