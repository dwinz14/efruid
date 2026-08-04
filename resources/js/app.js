import './bootstrap';

import Alpine from 'alpinejs';
import typewriter from "./components/typewriter";

window.Alpine = Alpine;

Alpine.data("typewriter", typewriter);

Alpine.start();
