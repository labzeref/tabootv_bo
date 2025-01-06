// Vuetify
import '@mdi/font/css/materialdesignicons.css'

import 'vuetify/styles'
import {createVuetify} from 'vuetify'

export default createVuetify({
    icons :{
      iconfont: 'mdi'
    },

    theme: {
        defaultTheme: 'dark',
        themes: {
            dark: {
                colors: {
                    primary: '#ff3a44',
                    primaryDark: '#ab0013',
                    secondary: '#9f9f9f',
                    accent: '#807e81',
                    dark: '#101010',
                    success: '#0eda53',
                    warning: '#ffbd00',
                },
            },
        },
    },
    defaults: {
        VTextField: {
            class: 'theme__input',
            variant: 'plan'
        },
        VSelect: {
            class: 'theme__select',
            variant: 'plan'
        },
        VAutocomplete: {
            class: 'theme__autocomplete',
            variant: 'plan'
        },
        VCombobox: {
            class: 'theme__combobox',
            variant: 'plan'
        },
        VBtn: {
            style: 'letterSpacing: normal'
        },
        VTabs: {
            class: 'ChoosePlan',
        },
        VFileInput: {
            class: 'theme__file',
        },
        VListItem:{
            class: 'theme__list-item',
        }
    },
})
