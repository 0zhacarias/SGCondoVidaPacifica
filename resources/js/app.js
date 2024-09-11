/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;

import Vue from "vue";
import vuetify from "./vuetify";
import { App, plugin, createInertiaApp, Link } from "@inertiajs/inertia-vue";
import "./config/msg";
import { format, formatDistance } from "date-fns";
import { pt } from "date-fns/locale";
import Vuelidate from "vuelidate";
import Chartkick from "vue-chartkick";
import Highcharts from "highcharts";
import Chart from "chart.js";

Vue.use(Chartkick, { adapter: Highcharts });
/* Vue.use(Chartkick.use(Chart)); */
Vue.use(Vuelidate);

Vue.component("inertia-link", Link);
Vue.use(plugin);

Vue.filter("formatDateTime", (value) => {
    return format(new Date(value), "dd - LLLL - yyyy HH:mm:ss", {
        locale: pt,
    });
});
Vue.filter("formatDate", (value) => {
    return format(new Date(value), "dd - LLLL - yyyy", {
        locale: pt,
    });
});

createInertiaApp({
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props, plugin }) {
        Vue.use(plugin);

        new Vue({
            vuetify,
            render: (h) => h(App, props),
        }).$mount(el);
    },
});
