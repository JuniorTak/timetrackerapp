import './bootstrap';

import moment from "moment";
import "moment-timezone";
import Chart from 'chart.js/auto';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Make Moment and Chart globally available
window.moment = moment;
window.Chart = Chart;

// Set moment timezone same as Laravel
moment.tz.setDefault("UTC");
