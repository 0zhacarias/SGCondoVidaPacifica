import Vue from 'vue'
import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader
//import '@fortawesome/fontawesome-free/css/all.css' 
import Vuetify from 'vuetify'
import { Icon } from '@iconify/vue2';
import VuetifyMask from "vuetify-mask";  
Vue.use(VuetifyMask);  


Vue.use(Vuetify)

export default new Vuetify({
    icons: {
        iconfont: 'md',
        iconfont: 'fa',
        iconfont:'mdi'
    },
    theme: {
        
        themes: {
            light: {
                primary: '191654',//'#2b5876',//2b5876
                secondary: '#696969',
                accent: '#8c9eff',
                error: '#b71c1c',
            },
        },
    },
    components: {
		Icon,
	},
})